<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{

    /**
     * Afficher la page des paramètres
     */
    public function index()
    {
        $client = Auth::guard('client')->user();
        $tenant = $client->tenant;

        // Informations sur l'abonnement
        $subscription = $tenant ? $tenant->subscriptions()->with('plan')->latest()->first() : null;

        // Statistiques d'utilisation
        $usageStats = [
            'storage_used' => $this->calculateStorageUsed($tenant),
            'api_calls_this_month' => $this->getApiCallsThisMonth($tenant),
            'last_login' => $client->last_login_at,
            'account_created' => $client->created_at,
        ];

        return view('client.settings.index', compact('client', 'tenant', 'subscription', 'usageStats'));
    }

    /**
     * Afficher le formulaire de profil
     */
    public function profile()
    {
        $client = Auth::guard('client')->user();
        $tenant = $client->tenant;

        return view('client.settings.profile', compact('client', 'tenant'));
    }

    /**
     * Mettre à jour le profil
     */
    public function updateProfile(Request $request)
    {
        $client = Auth::guard('client')->user();

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('clients', 'email')->ignore($client->id)
            ],
            'phone' => 'nullable|string|max:20',
            'timezone' => 'nullable|string|max:50',
            'language' => 'nullable|string|max:10',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $client->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'timezone' => $request->timezone,
                'language' => $request->language,
            ]);

            return redirect()->back()
                ->with('success', 'Profil mis à jour avec succès !');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la mise à jour : ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Afficher le formulaire de changement de mot de passe
     */
    public function password()
    {
        return view('client.settings.password');
    }

    /**
     * Mettre à jour le mot de passe
     */
    public function updatePassword(Request $request)
    {
        $client = Auth::guard('client')->user();

        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }

        // Vérifier le mot de passe actuel
        if (!Hash::check($request->current_password, $client->password)) {
            return redirect()->back()
                ->withErrors(['current_password' => 'Le mot de passe actuel est incorrect.']);
        }

        try {
            $client->update([
                'password' => Hash::make($request->password)
            ]);

            return redirect()->back()
                ->with('success', 'Mot de passe mis à jour avec succès !');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la mise à jour : ' . $e->getMessage());
        }
    }

    /**
     * Afficher les paramètres de sécurité
     */
    public function security()
    {
        $client = Auth::guard('client')->user();
        $tenant = $client->tenant;

        // Sessions actives
        $activeSessions = $tenant->sessions()
            ->where('last_activity', '>=', now()->subHours(24))
            ->orderBy('last_activity', 'desc')
            ->get();

        return view('client.settings.security', compact('client', 'tenant', 'activeSessions'));
    }

    /**
     * Révoquer une session
     */
    public function revokeSession(Request $request)
    {
        $request->validate([
            'session_id' => 'required|string'
        ]);

        $client = Auth::guard('client')->user();
        $tenant = $client->tenant;

        $session = $tenant->sessions()->where('id', $request->session_id)->first();
        
        if ($session) {
            $session->delete();
            return back()->with('success', 'Session révoquée avec succès !');
        }

        return back()->with('error', 'Session introuvable.');
    }

    /**
     * Afficher les paramètres de notifications
     */
    public function notifications()
    {
        $client = Auth::guard('client')->user();
        $tenant = $client->tenant;

        return view('client.settings.notifications', compact('client', 'tenant'));
    }

    /**
     * Mettre à jour les préférences de notifications
     */
    public function updateNotifications(Request $request)
    {
        $request->validate([
            'email_notifications' => 'boolean',
            'license_expiry_alerts' => 'boolean',
            'subscription_alerts' => 'boolean',
            'security_alerts' => 'boolean',
            'marketing_emails' => 'boolean',
        ]);

        $client = Auth::guard('client')->user();
        $tenant = $client->tenant;

        $tenant->update([
            'settings' => array_merge($tenant->settings ?? [], [
                'notifications' => [
                    'email_notifications' => $request->boolean('email_notifications'),
                    'license_expiry_alerts' => $request->boolean('license_expiry_alerts'),
                    'subscription_alerts' => $request->boolean('subscription_alerts'),
                    'security_alerts' => $request->boolean('security_alerts'),
                    'marketing_emails' => $request->boolean('marketing_emails'),
                ]
            ])
        ]);

        return redirect()->route('client.settings.notifications')
            ->with('success', 'Préférences de notifications mises à jour !');
    }

    /**
     * Afficher les paramètres de branding (premium)
     */
    public function branding()
    {
        $client = Auth::guard('client')->user();
        $tenant = $client->tenant;

        // Vérifier si le plan permet le branding personnalisé
        $subscription = $tenant->subscriptions()->with('plan')->first();
        $canCustomizeBranding = $subscription && $subscription->plan->features->contains('name', 'custom_branding');

        if (!$canCustomizeBranding) {
            return redirect()->route('client.settings.index')
                ->with('error', 'Le branding personnalisé n\'est disponible que dans les plans premium.');
        }

        return view('client.settings.branding', compact('client', 'tenant'));
    }

    /**
     * Mettre à jour le branding
     */
    public function updateBranding(Request $request)
    {
        $client = Auth::guard('client')->user();
        $tenant = $client->tenant;

        // Vérifier si le plan permet le branding personnalisé
        $subscription = $tenant->subscriptions()->with('plan')->first();
        $canCustomizeBranding = $subscription && $subscription->plan->features->contains('name', 'custom_branding');

        if (!$canCustomizeBranding) {
            return back()->with('error', 'Le branding personnalisé n\'est disponible que dans les plans premium.');
        }

        $request->validate([
            'company_name' => 'nullable|string|max:255',
            'primary_color' => 'nullable|string|regex:/^#[0-9A-F]{6}$/i',
            'secondary_color' => 'nullable|string|regex:/^#[0-9A-F]{6}$/i',
            'logo' => 'nullable|image|mimes:jpeg,png,svg|max:2048',
            'favicon' => 'nullable|image|mimes:ico,png|max:1024',
        ]);

        $brandingData = [
            'company_name' => $request->company_name,
            'primary_color' => $request->primary_color,
            'secondary_color' => $request->secondary_color,
        ];

        // Gérer l'upload du logo
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('branding/' . $tenant->id, 'public');
            $brandingData['logo'] = $logoPath;
        }

        // Gérer l'upload du favicon
        if ($request->hasFile('favicon')) {
            $faviconPath = $request->file('favicon')->store('branding/' . $tenant->id, 'public');
            $brandingData['favicon'] = $faviconPath;
        }

        $tenant->update([
            'settings' => array_merge($tenant->settings ?? [], [
                'branding' => $brandingData
            ])
        ]);

        return redirect()->route('client.settings.branding')
            ->with('success', 'Branding mis à jour avec succès !');
    }

    /**
     * Afficher les paramètres d'intégration API
     */
    public function api()
    {
        $client = Auth::guard('client')->user();
        $tenant = $client->tenant;

        $apiKeys = $tenant->apiKeys()->orderBy('created_at', 'desc')->get();

        return view('client.settings.api', compact('client', 'tenant', 'apiKeys'));
    }

    /**
     * Créer une nouvelle clé API
     */
    public function createApiKey(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'permissions' => 'array',
        ]);

        $client = Auth::guard('client')->user();
        $tenant = $client->tenant;

        $apiKey = $tenant->apiKeys()->create([
            'name' => $request->name,
            'key' => \Illuminate\Support\Str::random(64),
            'permissions' => $request->permissions ?? [],
            'last_used_at' => null,
        ]);

        return redirect()->route('client.settings.api')
            ->with('success', 'Clé API créée avec succès !')
            ->with('new_api_key', $apiKey->key);
    }

    /**
     * Supprimer une clé API
     */
    public function deleteApiKey(Request $request)
    {
        $request->validate([
            'api_key_id' => 'required|exists:api_keys,id'
        ]);

        $client = Auth::guard('client')->user();
        $tenant = $client->tenant;

        $apiKey = $tenant->apiKeys()->findOrFail($request->api_key_id);
        $apiKey->delete();

        return redirect()->route('client.settings.api')
            ->with('success', 'Clé API supprimée avec succès !');
    }

    /**
     * Afficher les paramètres de facturation
     */
    public function billing()
    {
        $client = Auth::guard('client')->user();
        $tenant = $client->tenant;

        $subscription = $tenant ? $tenant->subscriptions()->with('plan')->first() : null;
        $invoices = $tenant ? $tenant->invoices()->orderBy('created_at', 'desc')->limit(10)->get() : collect();

        // Données simulées pour la démo
        $paymentMethod = null;
        $nextInvoice = null;

        return view('client.billing.index', compact('client', 'tenant', 'subscription', 'invoices', 'paymentMethod', 'nextInvoice'));
    }

    /**
     * Afficher toutes les factures
     */
    public function invoices()
    {
        $client = Auth::guard('client')->user();
        $tenant = $client->tenant;

        $invoices = $tenant ? $tenant->invoices()->orderBy('created_at', 'desc')->paginate(20) : collect();

        return view('client.billing.invoices', compact('client', 'tenant', 'invoices'));
    }

    /**
     * Afficher la page de gestion de l'abonnement
     */
    public function subscription()
    {
        $client = Auth::guard('client')->user();
        $tenant = $client->tenant;

        $subscription = $tenant ? $tenant->subscriptions()->with('plan')->first() : null;
        $plans = \App\Models\Plan::where('is_visible', true)->orderBy('price')->get();

        return view('client.billing.subscription', compact('client', 'tenant', 'subscription', 'plans'));
    }

    /**
     * Annuler l'abonnement
     */
    public function cancelSubscription(Request $request)
    {
        $client = Auth::guard('client')->user();
        $tenant = $client->tenant;

        if (!$tenant) {
            return redirect()->back()->with('error', 'Aucun tenant associé.');
        }

        $subscription = $tenant->subscriptions()->where('status', 'active')->first();

        if (!$subscription) {
            return redirect()->back()->with('error', 'Aucun abonnement actif à annuler.');
        }

        try {
            $subscription->update([
                'status' => 'cancelled',
                'ends_at' => now()->endOfMonth()
            ]);

            return redirect()->back()->with('success', 'Votre abonnement sera annulé à la fin de la période de facturation.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de l\'annulation : ' . $e->getMessage());
        }
    }

    /**
     * Mettre à niveau l'abonnement
     */
    public function upgradeSubscription(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:plans,id'
        ]);

        $client = Auth::guard('client')->user();
        $tenant = $client->tenant;

        if (!$tenant) {
            return redirect()->back()->with('error', 'Aucun tenant associé.');
        }

        $newPlan = \App\Models\Plan::findOrFail($request->plan_id);
        $currentSubscription = $tenant->subscriptions()->where('status', 'active')->first();

        if ($currentSubscription && $currentSubscription->plan_id == $newPlan->id) {
            return redirect()->back()->with('error', 'Vous êtes déjà sur ce plan.');
        }

        try {
            // Désactiver l'ancien abonnement
            if ($currentSubscription) {
                $currentSubscription->update(['status' => 'cancelled']);
            }

            // Créer le nouvel abonnement
            $tenant->subscriptions()->create([
                'plan_id' => $newPlan->id,
                'status' => 'active',
                'starts_at' => now(),
                'ends_at' => now()->addMonth(),
                'trial_ends_at' => null
            ]);

            return redirect()->back()->with('success', 'Votre abonnement a été mis à jour avec succès !');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de la mise à niveau : ' . $e->getMessage());
        }
    }

    /**
     * Télécharger une facture
     */
    public function downloadInvoice($invoiceId)
    {
        $client = Auth::guard('client')->user();
        $tenant = $client->tenant;

        $invoice = $tenant->invoices()->findOrFail($invoiceId);

        // Générer le PDF de la facture
        $pdf = \PDF::loadView('client.invoices.pdf', compact('invoice', 'tenant'));

        return $pdf->download('facture-' . $invoice->number . '.pdf');
    }

    /**
     * Supprimer le compte
     */
    public function deleteAccount(Request $request)
    {
        $client = Auth::guard('client')->user();

        $validator = Validator::make($request->all(), [
            'password' => 'required',
            'confirmation' => 'required|in:DELETE',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }

        // Vérifier le mot de passe
        if (!Hash::check($request->password, $client->password)) {
            return redirect()->back()
                ->withErrors(['password' => 'Le mot de passe est incorrect.']);
        }

        try {
            $tenant = $client->tenant;

            // Supprimer l'avatar s'il existe
            if ($client->avatar && Storage::disk('public')->exists($client->avatar)) {
                Storage::disk('public')->delete($client->avatar);
            }

            // Supprimer le client (les relations seront gérées par les contraintes de clés étrangères)
            $client->delete();

            // Si c'était le seul client du tenant, supprimer le tenant aussi
            if ($tenant && $tenant->clients()->count() === 0) {
                $tenant->delete();
            }

            // Déconnecter et rediriger
            Auth::guard('client')->logout();

            return redirect()->route('frontend.index')
                ->with('success', 'Votre compte a été supprimé avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la suppression : ' . $e->getMessage());
        }
    }

    /**
     * Calculer l'espace de stockage utilisé
     */
    private function calculateStorageUsed($tenant)
    {
        if (!$tenant) {
            return [
                'used_mb' => 0,
                'used_formatted' => '0 B',
                'limit_mb' => 50, // 50MB par défaut
                'percentage' => 0
            ];
        }

        // Obtenir la limite de stockage du plan actuel
        $subscription = $tenant->subscriptions()->with('plan')->where('status', 'active')->first();
        $storageLimit = $subscription && $subscription->plan ? $subscription->plan->storage_limit_mb : 50; // 50MB par défaut

        // Simulation basée sur le nombre de projets et licences
        $projectsCount = $tenant->projects()->count() ?? 0;
        $licensesCount = $tenant->serialKeys()->count() ?? 0;

        // Estimation : 1MB par projet + 0.1MB par licence
        $estimatedMB = ($projectsCount * 1) + ($licensesCount * 0.1);

        return [
            'used_mb' => round($estimatedMB, 2),
            'used_formatted' => $this->formatBytes($estimatedMB * 1024 * 1024),
            'limit_mb' => $storageLimit,
            'percentage' => min(100, round(($estimatedMB / $storageLimit) * 100, 1))
        ];
    }

    /**
     * Obtenir le nombre d'appels API ce mois
     */
    private function getApiCallsThisMonth($tenant)
    {
        // Simulation - à remplacer par de vraies données d'API
        return rand(100, 5000);
    }

    /**
     * Formater les bytes en unité lisible
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }
} 