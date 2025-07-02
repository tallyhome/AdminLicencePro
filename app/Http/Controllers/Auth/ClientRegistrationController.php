<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Plan;
use App\Models\Tenant;
use App\Models\Subscription;
use App\Services\TenantService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Str;

class ClientRegistrationController extends Controller
{
    protected $tenantService;

    public function __construct(TenantService $tenantService)
    {
        $this->tenantService = $tenantService;
        // Le middleware sera géré au niveau des routes
    }

    /**
     * Afficher le formulaire d'inscription
     */
    public function showRegistrationForm()
    {
        $plans = Plan::active()->orderBy('price')->get();
        return view('auth.client-register', compact('plans'));
    }

    /**
     * Traiter l'inscription du client
     */
    public function register(Request $request)
    {
        // Log des données reçues pour debug
        \Log::info('Tentative d\'inscription client', [
            'data' => $request->all(),
            'url' => $request->url(),
            'method' => $request->method()
        ]);
        
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:clients'],
            'password' => ['required', 'confirmed', 'min:8'],
            'company_name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'country' => ['required', 'string', 'max:100'],
            'plan_id' => ['required', 'exists:plans,id'],
            'terms_accepted' => ['required', 'accepted'],
        ]);

        try {
            DB::beginTransaction();

            // Récupérer le plan sélectionné
            $plan = Plan::findOrFail($request->plan_id);

            // Créer le tenant
            $tenant = $this->createTenant($request, $plan);

            // Créer le client
            $client = $this->createClient($request, $tenant);

            // Créer l'abonnement (période d'essai si disponible)
            $subscription = $this->createSubscription($tenant, $plan);

            // Configurer le tenant initial
            $this->tenantService->setupInitialTenantData($tenant, $client);

            DB::commit();

            // Connecter automatiquement le client
            auth('client')->login($client);

            // Déclencher l'événement d'inscription
            event(new Registered($client));

            // Envoyer email de bienvenue
            $this->sendWelcomeEmail($client, $tenant, $plan);

            return redirect()->route('client.dashboard')
                ->with('success', 'Inscription réussie ! Bienvenue !');

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Log de l'erreur pour debug
            \Log::error('Erreur lors de l\'inscription client', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => $request->except('password', 'password_confirmation')
            ]);
            
            return back()
                ->withInput($request->except('password', 'password_confirmation'))
                ->with('error', 'Erreur lors de l\'inscription : ' . $e->getMessage());
        }
    }

    /**
     * Créer un nouveau tenant
     */
    protected function createTenant(Request $request, Plan $plan): Tenant
    {
        $domain = $this->generateTenantDomain($request->company_name);
        
        return Tenant::create([
            'name' => $request->company_name,
            'domain' => $domain,
            'database' => config('database.default'), // Utilise la même DB avec tenant_id
            'status' => Tenant::STATUS_ACTIVE,
            'subscription_status' => $plan->hasTrial() ? Tenant::SUBSCRIPTION_TRIAL : Tenant::SUBSCRIPTION_ACTIVE,
            'subscription_ends_at' => $plan->hasTrial() ? null : now()->addMonth(),
            'trial_ends_at' => $plan->hasTrial() ? now()->addDays($plan->trial_days) : null,
            'settings' => [
                'timezone' => 'Europe/Paris',
                'locale' => app()->getLocale(),
                'currency' => 'EUR',
                'date_format' => 'd/m/Y',
                'time_format' => 'H:i',
            ]
        ]);
    }

    /**
     * Créer un nouveau client
     */
    protected function createClient(Request $request, Tenant $tenant): Client
    {
        return Client::create([
            'name' => $request->name,
            'email' => $request->email,
            'email_verified_at' => now(), // Auto-vérifié pour simplifier
            'password' => Hash::make($request->password),
            'company_name' => $request->company_name,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'postal_code' => $request->postal_code,
            'country' => $request->country,
            'tenant_id' => $tenant->id,
            'status' => Client::STATUS_ACTIVE,
        ]);
    }

    /**
     * Créer un nouvel abonnement
     */
    protected function createSubscription(Tenant $tenant, Plan $plan): Subscription
    {
        $startsAt = $plan->hasTrial() ? now()->addDays($plan->trial_days) : now();
        $endsAt = $plan->hasTrial() ? null : now()->addMonth();
        
        return Subscription::create([
            'tenant_id' => $tenant->id,
            'plan_id' => $plan->id,
            'status' => $plan->hasTrial() ? 'trial' : 'active',
            'trial_ends_at' => $plan->hasTrial() ? now()->addDays($plan->trial_days) : null,
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
            'renewal_price' => $plan->price,
            'billing_cycle' => $plan->billing_cycle,
            'auto_renew' => true,
        ]);
    }

    /**
     * Générer un domaine unique pour le tenant
     */
    protected function generateTenantDomain(string $companyName): string
    {
        $slug = Str::slug($companyName);
        $domain = $slug;
        $counter = 1;

        while (Tenant::where('domain', $domain)->exists()) {
            $domain = $slug . '-' . $counter;
            $counter++;
        }

        return $domain;
    }

    /**
     * Envoyer l'email de bienvenue
     */
    protected function sendWelcomeEmail(Client $client, Tenant $tenant, Plan $plan): void
    {
        try {
            Mail::send('emails.client.welcome', [
                'client' => $client,
                'tenant' => $tenant,
                'plan' => $plan,
                'loginUrl' => route('client.login.form')
            ], function ($message) use ($client) {
                $message->to($client->email, $client->name)
                        ->subject('Bienvenue sur ' . config('app.name'));
            });
        } catch (\Exception $e) {
            // Log l'erreur mais ne pas faire échouer l'inscription
            \Log::error('Failed to send welcome email: ' . $e->getMessage());
        }
    }

    /**
     * Vérifier la disponibilité d'un domaine
     */
    public function checkDomainAvailability(Request $request)
    {
        $request->validate([
            'domain' => 'required|string|max:100'
        ]);

        $domain = Str::slug($request->domain);
        $available = !Tenant::where('domain', $domain)->exists();

        return response()->json([
            'available' => $available,
            'suggested_domain' => $available ? $domain : $this->generateTenantDomain($request->domain)
        ]);
    }
} 