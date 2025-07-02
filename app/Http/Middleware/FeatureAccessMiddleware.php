<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class FeatureAccessMiddleware
{
    /**
     * Contrôler l'accès aux fonctionnalités basé sur le plan et les permissions
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $feature  La fonctionnalité requise
     * @param  string|null  $permission  La permission optionnelle requise
     */
    public function handle(Request $request, Closure $next, string $feature, string $permission = null): Response
    {
        $client = Auth::guard('client')->user();
        
        if (!$client || !$client->tenant) {
            return redirect()->route('client.login.form')
                ->with('error', 'Accès non autorisé.');
        }

        $tenant = $client->tenant;
        $subscription = $tenant->subscriptions()->with('plan')->first();
        
        // Vérifier si le plan a accès à cette fonctionnalité
        if (!$this->hasFeatureAccess($subscription, $feature)) {
            $featureName = $this->getFeatureDisplayName($feature);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Fonctionnalité non disponible',
                    'message' => "La fonctionnalité '{$featureName}' n'est pas disponible dans votre plan actuel.",
                    'feature_required' => $feature,
                    'upgrade_required' => true
                ], 403);
            }
            
            $upgradeUrl = route('subscription.plans');
            return redirect()->back()
                ->with('error', "La fonctionnalité '{$featureName}' n'est pas disponible dans votre plan actuel. <a href='{$upgradeUrl}' class='alert-link'>Mettre à niveau</a> pour y accéder.")
                ->with('feature_blocked', $feature);
        }

        // Vérifier les permissions utilisateur si spécifiées
        if ($permission && !$client->hasPermission($permission)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Permission insuffisante',
                    'message' => 'Vous n\'avez pas la permission d\'accéder à cette fonctionnalité.',
                    'permission_required' => $permission
                ], 403);
            }
            
            return redirect()->back()
                ->with('error', 'Vous n\'avez pas la permission d\'accéder à cette fonctionnalité. Contactez l\'administrateur de votre compte.');
        }

        return $next($request);
    }

    /**
     * Vérifier si le plan a accès à une fonctionnalité
     */
    protected function hasFeatureAccess($subscription, string $feature): bool
    {
        if (!$subscription || !$subscription->plan) {
            return false;
        }

        $plan = $subscription->plan;
        $features = $plan->features ?? [];
        
        // Mapping des fonctionnalités avec les features du plan
        $featureMapping = [
            'api_access' => ['API access', 'api_access', 'API'],
            'priority_support' => ['Priority support', 'priority_support', 'Support prioritaire'],
            'custom_branding' => ['Custom branding', 'custom_branding', 'Branding personnalisé'],
            'advanced_analytics' => ['Advanced analytics', 'advanced_analytics', 'Analytiques avancées'],
            'bulk_operations' => ['Bulk operations', 'bulk_operations', 'Opérations en lot'],
            'export_data' => ['Export data', 'export', 'Exportation'],
            'webhooks' => ['Webhooks', 'webhook', 'Notifications webhook'],
            'white_label' => ['White label', 'white_label', 'Marque blanche'],
            'sso' => ['SSO', 'single_sign_on', 'Single Sign-On'],
            'advanced_permissions' => ['Advanced permissions', 'advanced_permissions', 'Permissions avancées'],
        ];

        // Vérifier si la fonctionnalité est dans les features du plan
        if (isset($featureMapping[$feature])) {
            $possibleNames = $featureMapping[$feature];
            
            foreach ($possibleNames as $name) {
                if (in_array($name, $features)) {
                    return true;
                }
            }
        }

        // Fonctionnalités de base disponibles pour tous les plans payants
        $basicFeatures = [
            'basic_support',
            'license_management',
            'project_management',
            'client_management'
        ];

        if (in_array($feature, $basicFeatures)) {
            return !$plan->isFree(); // Disponible pour tous les plans payants
        }

        // Fonctionnalités premium (seulement pour les plans entreprise)
        $premiumFeatures = [
            'unlimited_projects',
            'unlimited_licenses',
            'custom_branding',
            'white_label',
            'sso'
        ];

        if (in_array($feature, $premiumFeatures)) {
            return $plan->slug === 'enterprise' || $plan->price >= 49.99;
        }

        return false;
    }

    /**
     * Obtenir le nom d'affichage de la fonctionnalité
     */
    protected function getFeatureDisplayName(string $feature): string
    {
        $names = [
            'api_access' => 'Accès API',
            'priority_support' => 'Support prioritaire',
            'custom_branding' => 'Branding personnalisé',
            'advanced_analytics' => 'Analytiques avancées',
            'bulk_operations' => 'Opérations en lot',
            'export_data' => 'Exportation de données',
            'webhooks' => 'Webhooks',
            'white_label' => 'Marque blanche',
            'sso' => 'Single Sign-On',
            'advanced_permissions' => 'Permissions avancées',
            'unlimited_projects' => 'Projets illimités',
            'unlimited_licenses' => 'Licences illimitées'
        ];

        return $names[$feature] ?? ucfirst(str_replace('_', ' ', $feature));
    }

    /**
     * Middleware statique pour vérifier l'accès API
     */
    public static function requiresApiAccess()
    {
        return function (Request $request, Closure $next) {
            return app(self::class)->handle($request, $next, 'api_access');
        };
    }

    /**
     * Middleware statique pour vérifier l'accès aux analytiques avancées
     */
    public static function requiresAdvancedAnalytics()
    {
        return function (Request $request, Closure $next) {
            return app(self::class)->handle($request, $next, 'advanced_analytics');
        };
    }

    /**
     * Middleware statique pour vérifier l'accès au branding personnalisé
     */
    public static function requiresCustomBranding()
    {
        return function (Request $request, Closure $next) {
            return app(self::class)->handle($request, $next, 'custom_branding');
        };
    }

    /**
     * Middleware statique pour vérifier l'accès aux opérations en lot
     */
    public static function requiresBulkOperations()
    {
        return function (Request $request, Closure $next) {
            return app(self::class)->handle($request, $next, 'bulk_operations');
        };
    }

    /**
     * Middleware statique pour vérifier l'accès à l'exportation
     */
    public static function requiresExportAccess()
    {
        return function (Request $request, Closure $next) {
            return app(self::class)->handle($request, $next, 'export_data');
        };
    }
} 