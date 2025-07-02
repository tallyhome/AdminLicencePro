<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\SerialKey;
use App\Services\TenantService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OnboardingController extends Controller
{
    protected $tenantService;

    public function __construct(TenantService $tenantService)
    {
        $this->middleware('auth:client');
        $this->tenantService = $tenantService;
    }

    /**
     * Page de bienvenue après inscription
     */
    public function welcome()
    {
        $client = Auth::guard('client')->user();
        $tenant = $client->tenant;
        $subscription = $tenant->subscriptions()->first();
        $plan = $subscription ? $subscription->plan : null;

        return view('client.onboarding.welcome', compact('client', 'tenant', 'subscription', 'plan'));
    }

    /**
     * Étape 1: Configuration du profil
     */
    public function setupProfile()
    {
        $client = Auth::guard('client')->user();
        return view('client.onboarding.setup-profile', compact('client'));
    }

    /**
     * Sauvegarder la configuration du profil
     */
    public function storeProfile(Request $request)
    {
        $request->validate([
            'company_website' => ['nullable', 'url'],
            'company_logo' => ['nullable', 'image', 'max:2048'],
            'timezone' => ['required', 'string'],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        $client = Auth::guard('client')->user();
        $tenant = $client->tenant;

        // Mettre à jour les informations du client
        $client->update([
            'phone' => $request->phone,
        ]);

        // Mettre à jour les paramètres du tenant
        $settings = $tenant->settings ?? [];
        $settings['company_website'] = $request->company_website;
        $settings['timezone'] = $request->timezone;

        // Gérer l'upload du logo
        if ($request->hasFile('company_logo')) {
            $logoPath = $request->file('company_logo')->store('logos', 'public');
            $settings['company_logo'] = $logoPath;
        }

        $tenant->settings = $settings;
        $tenant->save();

        return redirect()->route('client.onboarding.create-project')
            ->with('success', t('onboarding.profile_updated'));
    }

    /**
     * Étape 2: Créer le premier projet
     */
    public function createProject()
    {
        $client = Auth::guard('client')->user();
        $tenant = $client->tenant;
        
        return view('client.onboarding.create-project', compact('client', 'tenant'));
    }

    /**
     * Sauvegarder le premier projet
     */
    public function storeProject(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'version' => ['nullable', 'string', 'max:50'],
        ]);

        $client = Auth::guard('client')->user();
        $tenant = $client->tenant;

        // Vérifier les limites du plan
        $limits = $this->tenantService->checkTenantLimits($tenant);
        if (!$limits['limits']['projects']['within_limit']) {
            return back()->with('error', t('onboarding.project_limit_reached'));
        }

        // Créer le projet
        $project = Project::create([
            'name' => $request->name,
            'description' => $request->description,
            'version' => $request->version ?? '1.0.0',
            'tenant_id' => $tenant->id,
            'status' => 'active',
        ]);

        return redirect()->route('client.onboarding.create-license')
            ->with('success', t('onboarding.project_created'))
            ->with('project_id', $project->id);
    }

    /**
     * Étape 3: Créer la première licence
     */
    public function createLicense()
    {
        $client = Auth::guard('client')->user();
        $tenant = $client->tenant;
        $projects = $tenant->projects;
        
        $projectId = session('project_id');
        $selectedProject = $projectId ? $projects->find($projectId) : $projects->first();

        return view('client.onboarding.create-license', compact('client', 'tenant', 'projects', 'selectedProject'));
    }

    /**
     * Sauvegarder la première licence
     */
    public function storeLicense(Request $request)
    {
        $request->validate([
            'project_id' => ['required', 'exists:projects,id'],
            'licence_type' => ['required', 'string', 'in:trial,standard,premium'],
            'max_activations' => ['nullable', 'integer', 'min:1'],
            'expires_at' => ['nullable', 'date', 'after:today'],
        ]);

        $client = Auth::guard('client')->user();
        $tenant = $client->tenant;

        // Vérifier que le projet appartient au tenant
        $project = Project::where('id', $request->project_id)
                         ->where('tenant_id', $tenant->id)
                         ->firstOrFail();

        // Vérifier les limites du plan
        $limits = $this->tenantService->checkTenantLimits($tenant);
        if (!$limits['limits']['licenses']['within_limit']) {
            return back()->with('error', t('onboarding.license_limit_reached'));
        }

        // Générer une clé série unique
        $serialKey = $this->generateSerialKey();

        // Créer la licence
        SerialKey::create([
            'serial_key' => $serialKey,
            'project_id' => $project->id,
            'tenant_id' => $tenant->id,
            'licence_type' => $request->licence_type,
            'max_activations' => $request->max_activations ?? 1,
            'current_activations' => 0,
            'expires_at' => $request->expires_at,
            'status' => 'active',
            'notes' => 'Première licence créée lors de l\'onboarding'
        ]);

        return redirect()->route('client.onboarding.complete')
            ->with('success', t('onboarding.license_created'))
            ->with('serial_key', $serialKey);
    }

    /**
     * Finalisation de l'onboarding
     */
    public function complete()
    {
        $client = Auth::guard('client')->user();
        $tenant = $client->tenant;
        $serialKey = session('serial_key');
        
        // Marquer l'onboarding comme terminé
        $settings = $tenant->settings ?? [];
        $settings['onboarding_completed'] = true;
        $settings['onboarding_completed_at'] = now();
        $tenant->settings = $settings;
        $tenant->save();

        return view('client.onboarding.complete', compact('client', 'tenant', 'serialKey'));
    }

    /**
     * Passer l'onboarding (optionnel)
     */
    public function skip()
    {
        $client = Auth::guard('client')->user();
        $tenant = $client->tenant;
        
        // Marquer l'onboarding comme ignoré
        $settings = $tenant->settings ?? [];
        $settings['onboarding_completed'] = true;
        $settings['onboarding_skipped'] = true;
        $settings['onboarding_completed_at'] = now();
        $tenant->settings = $settings;
        $tenant->save();

        return redirect()->route('client.dashboard')
            ->with('info', t('onboarding.skipped'));
    }

    /**
     * Générer une clé série unique
     */
    protected function generateSerialKey(): string
    {
        do {
            $key = strtoupper(substr(md5(uniqid(rand(), true)), 0, 4) . '-' .
                             substr(md5(uniqid(rand(), true)), 0, 4) . '-' .
                             substr(md5(uniqid(rand(), true)), 0, 4) . '-' .
                             substr(md5(uniqid(rand(), true)), 0, 4));
        } while (SerialKey::where('serial_key', $key)->exists());

        return $key;
    }
} 