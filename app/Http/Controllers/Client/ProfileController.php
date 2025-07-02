<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Services\TenantService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    protected $tenantService;

    public function __construct(TenantService $tenantService)
    {
        $this->middleware('auth:client');
        $this->middleware('subscription');
        $this->tenantService = $tenantService;
    }

    /**
     * Afficher le profil du client
     */
    public function show()
    {
        $client = Auth::guard('client')->user();
        $tenant = $client->tenant;

        // Statistiques du compte
        $accountStats = [
            'projects_count' => $tenant->projects()->count(),
            'licenses_count' => $tenant->serialKeys()->count(),
            'api_keys_count' => $tenant->apiKeys()->count(),
            'subscription_days_left' => $tenant->subscription_ends_at ? now()->diffInDays($tenant->subscription_ends_at, false) : null,
        ];

        // Limites du plan
        $planLimits = $this->tenantService->checkTenantLimits($tenant);

        return view('client.profile.show', compact('client', 'tenant', 'accountStats', 'planLimits'));
    }

    /**
     * Afficher le formulaire d'édition du profil
     */
    public function edit()
    {
        $client = Auth::guard('client')->user();
        $tenant = $client->tenant;

        return view('client.profile.edit', compact('client', 'tenant'));
    }

    /**
     * Mettre à jour le profil
     */
    public function update(Request $request)
    {
        $client = Auth::guard('client')->user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('clients')->ignore($client->id)],
            'company_name' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'country' => ['nullable', 'string', 'max:100'],
        ]);

        $client->update($request->only([
            'name', 'email', 'company_name', 'phone', 
            'address', 'city', 'state', 'postal_code', 'country'
        ]));

        return redirect()->route('client.profile.show')
            ->with('success', 'Profil mis à jour avec succès !');
    }

    /**
     * Afficher le formulaire de changement de mot de passe
     */
    public function changePassword()
    {
        $client = Auth::guard('client')->user();
        return view('client.profile.change-password', compact('client'));
    }

    /**
     * Mettre à jour le mot de passe
     */
    public function updatePassword(Request $request)
    {
        $client = Auth::guard('client')->user();

        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if (!Hash::check($request->current_password, $client->password)) {
            return back()->withErrors(['current_password' => 'Le mot de passe actuel est incorrect.']);
        }

        $client->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->route('client.profile.show')
            ->with('success', 'Mot de passe mis à jour avec succès !');
    }

    /**
     * Afficher les paramètres du compte
     */
    public function settings()
    {
        $client = Auth::guard('client')->user();
        $tenant = $client->tenant;

        return view('client.profile.settings', compact('client', 'tenant'));
    }

    /**
     * Mettre à jour les paramètres du compte
     */
    public function updateSettings(Request $request)
    {
        $client = Auth::guard('client')->user();
        $tenant = $client->tenant;

        $request->validate([
            'timezone' => ['nullable', 'string', 'max:50'],
            'language' => ['nullable', 'string', 'in:fr,en,es,de,it,nl,pt,ru,zh,ja'],
            'notifications_email' => ['nullable', 'boolean'],
            'notifications_sms' => ['nullable', 'boolean'],
            'two_factor_enabled' => ['nullable', 'boolean'],
        ]);

        // Mettre à jour les paramètres du client
        $client->update([
            'timezone' => $request->timezone,
            'language' => $request->language,
            'notifications_email' => $request->boolean('notifications_email'),
            'notifications_sms' => $request->boolean('notifications_sms'),
            'two_factor_enabled' => $request->boolean('two_factor_enabled'),
        ]);

        // Mettre à jour les paramètres du tenant si nécessaire
        if ($request->has('tenant_settings')) {
            $tenantSettings = $tenant->settings ?? [];
            $tenantSettings = array_merge($tenantSettings, $request->tenant_settings);
            $tenant->update(['settings' => $tenantSettings]);
        }

        return redirect()->route('client.profile.settings')
            ->with('success', 'Paramètres mis à jour avec succès !');
    }

    /**
     * Afficher les préférences de notification
     */
    public function notifications()
    {
        $client = Auth::guard('client')->user();
        return view('client.profile.notifications', compact('client'));
    }

    /**
     * Mettre à jour les préférences de notification
     */
    public function updateNotifications(Request $request)
    {
        $client = Auth::guard('client')->user();

        $request->validate([
            'notifications' => ['array'],
            'notifications.*' => ['boolean'],
            'email_frequency' => ['required', 'in:immediate,daily,weekly'],
            'sms_enabled' => ['boolean'],
        ]);

        $notificationSettings = [
            'license_expiry' => $request->boolean('notifications.license_expiry', true),
            'license_activation' => $request->boolean('notifications.license_activation', true),
            'subscription_renewal' => $request->boolean('notifications.subscription_renewal', true),
            'security_alerts' => $request->boolean('notifications.security_alerts', true),
            'api_usage' => $request->boolean('notifications.api_usage', false),
            'support_updates' => $request->boolean('notifications.support_updates', true),
        ];

        $client->update([
            'notification_settings' => $notificationSettings,
            'email_frequency' => $request->email_frequency,
            'sms_enabled' => $request->boolean('sms_enabled'),
        ]);

        return redirect()->route('client.profile.notifications')
            ->with('success', 'Préférences de notification mises à jour !');
    }

    /**
     * Afficher les paramètres de sécurité
     */
    public function security()
    {
        $client = Auth::guard('client')->user();
        
        // Historique des connexions (simulé)
        $loginHistory = [
            [
                'ip_address' => '192.168.1.1',
                'location' => 'Paris, France',
                'device' => 'Chrome sur Windows',
                'timestamp' => now()->subHours(2),
                'status' => 'success'
            ],
            [
                'ip_address' => '10.0.0.1',
                'location' => 'Lyon, France',
                'device' => 'Safari sur iPhone',
                'timestamp' => now()->subDays(1),
                'status' => 'success'
            ],
        ];

        return view('client.profile.security', compact('client', 'loginHistory'));
    }

    /**
     * Activer l'authentification à deux facteurs
     */
    public function enableTwoFactor(Request $request)
    {
        $client = Auth::guard('client')->user();

        $request->validate([
            'two_factor_code' => ['required', 'string', 'size:6'],
        ]);

        // Ici on vérifierait le code 2FA
        // Pour l'exemple, on simule une validation réussie
        if ($request->two_factor_code === '123456') {
            $client->update(['two_factor_enabled' => true]);
            return redirect()->route('client.profile.security')
                ->with('success', 'Authentification à deux facteurs activée !');
        }

        return back()->withErrors(['two_factor_code' => 'Code incorrect.']);
    }

    /**
     * Désactiver l'authentification à deux facteurs
     */
    public function disableTwoFactor(Request $request)
    {
        $client = Auth::guard('client')->user();

        $request->validate([
            'current_password' => ['required', 'string'],
        ]);

        if (!Hash::check($request->current_password, $client->password)) {
            return back()->withErrors(['current_password' => 'Mot de passe incorrect.']);
        }

        $client->update(['two_factor_enabled' => false]);

        return redirect()->route('client.profile.security')
            ->with('success', 'Authentification à deux facteurs désactivée.');
    }

    /**
     * Afficher les paramètres de branding (premium)
     */
    public function branding()
    {
        $client = Auth::guard('client')->user();
        $tenant = $client->tenant;

        // Vérifier si le plan permet le branding personnalisé
        $planLimits = $this->tenantService->checkTenantLimits($tenant);
        $canCustomizeBranding = $planLimits['plan']['features']['custom_branding'] ?? false;

        if (!$canCustomizeBranding) {
            return redirect()->route('client.subscription.index')
                ->with('error', 'Le branding personnalisé est disponible uniquement avec les plans Premium et Enterprise.');
        }

        return view('client.profile.branding', compact('client', 'tenant', 'planLimits'));
    }

    /**
     * Mettre à jour le branding
     */
    public function updateBranding(Request $request)
    {
        $client = Auth::guard('client')->user();
        $tenant = $client->tenant;

        // Vérifier les permissions
        $planLimits = $this->tenantService->checkTenantLimits($tenant);
        $canCustomizeBranding = $planLimits['plan']['features']['custom_branding'] ?? false;

        if (!$canCustomizeBranding) {
            return redirect()->route('client.subscription.index')
                ->with('error', 'Le branding personnalisé nécessite un plan supérieur.');
        }

        $request->validate([
            'company_logo' => ['nullable', 'image', 'max:2048'],
            'primary_color' => ['nullable', 'string', 'regex:/^#[0-9A-F]{6}$/i'],
            'secondary_color' => ['nullable', 'string', 'regex:/^#[0-9A-F]{6}$/i'],
            'custom_domain' => ['nullable', 'string', 'max:255'],
            'email_signature' => ['nullable', 'string', 'max:1000'],
        ]);

        $brandingSettings = $tenant->settings['branding'] ?? [];
        
        // Gérer l'upload du logo
        if ($request->hasFile('company_logo')) {
            $logoPath = $request->file('company_logo')->store('branding', 'public');
            $brandingSettings['logo'] = $logoPath;
        }

        $brandingSettings = array_merge($brandingSettings, [
            'primary_color' => $request->primary_color,
            'secondary_color' => $request->secondary_color,
            'custom_domain' => $request->custom_domain,
            'email_signature' => $request->email_signature,
        ]);

        $tenantSettings = $tenant->settings ?? [];
        $tenantSettings['branding'] = $brandingSettings;
        $tenant->update(['settings' => $tenantSettings]);

        return redirect()->route('client.profile.branding')
            ->with('success', 'Branding mis à jour avec succès !');
    }

    /**
     * Exporter les données du compte
     */
    public function exportData()
    {
        $client = Auth::guard('client')->user();
        $tenant = $client->tenant;

        $data = [
            'profile' => $client->toArray(),
            'tenant' => $tenant->toArray(),
            'projects' => $tenant->projects()->with('serialKeys')->get()->toArray(),
            'licenses' => $tenant->serialKeys()->with('project')->get()->toArray(),
            'api_keys' => $tenant->apiKeys()->with('project')->get()->toArray(),
            'exported_at' => now()->toISOString(),
        ];

        return response()->json($data);
    }

    /**
     * Supprimer le compte
     */
    public function deleteAccount(Request $request)
    {
        $client = Auth::guard('client')->user();

        $request->validate([
            'confirmation' => ['required', 'string', 'in:DELETE'],
            'current_password' => ['required', 'string'],
        ]);

        if (!Hash::check($request->current_password, $client->password)) {
            return back()->withErrors(['current_password' => 'Mot de passe incorrect.']);
        }

        // Supprimer le compte et toutes les données associées
        $client->delete();

        Auth::guard('client')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('client.login')
            ->with('success', 'Votre compte a été supprimé avec succès.');
    }
} 