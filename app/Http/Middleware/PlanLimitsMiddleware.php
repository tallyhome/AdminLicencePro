<?php

namespace App\Http\Middleware;

use App\Services\TenantService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PlanLimitsMiddleware
{
    protected $tenantService;

    public function __construct(TenantService $tenantService)
    {
        $this->tenantService = $tenantService;
    }

    /**
     * Vérifier les limites du plan avant d'autoriser certaines actions
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $resource  Le type de ressource à vérifier (projects, licenses, clients)
     */
    public function handle(Request $request, Closure $next, string $resource = null): Response
    {
        $client = Auth::guard('client')->user();
        
        if (!$client || !$client->tenant) {
            return redirect()->route('client.login.form');
        }

        $tenant = $client->tenant;
        
        // Si aucune ressource spécifiée, on détermine automatiquement
        if (!$resource) {
            $resource = $this->determineResourceFromRoute($request);
        }

        // Vérifier les limites seulement pour les actions de création
        if ($this->isCreationRequest($request)) {
            $limits = $this->tenantService->checkTenantLimits($tenant);
            
            if (!$limits['within_limits'] && isset($limits['limits'][$resource])) {
                $limit = $limits['limits'][$resource];
                
                if (!$limit['within_limit']) {
                    $plan = $limits['plan'];
                    $resourceName = $this->getResourceDisplayName($resource);
                    
                    // Pour les requêtes JSON (API), retourner une réponse JSON
                    if ($request->expectsJson()) {
                        return response()->json([
                            'error' => 'Limite du plan atteinte',
                            'message' => "Vous avez atteint la limite de votre plan pour les {$resourceName} ({$limit['current']}/{$limit['max']}).",
                            'current_plan' => $plan ? $plan->name : 'Inconnu',
                            'upgrade_required' => true
                        ], 403);
                    }
                    
                    // Pour les requêtes web, rediriger avec message d'erreur
                    $upgradeUrl = route('subscription.plans');
                    
                    return redirect()->back()
                        ->with('error', "Vous avez atteint la limite de votre plan pour les {$resourceName} ({$limit['current']}/{$limit['max']}). <a href='{$upgradeUrl}' class='alert-link'>Mettre à niveau votre plan</a> pour continuer.")
                        ->with('upgrade_required', true);
                }
            }
        }

        return $next($request);
    }

    /**
     * Déterminer le type de ressource basé sur la route
     */
    protected function determineResourceFromRoute(Request $request): string
    {
        $routeName = $request->route()->getName();
        
        if (strpos($routeName, 'project') !== false) {
            return 'projects';
        }
        
        if (strpos($routeName, 'license') !== false || strpos($routeName, 'serial') !== false) {
            return 'licenses';
        }
        
        if (strpos($routeName, 'client') !== false) {
            return 'clients';
        }
        
        return 'projects'; // Par défaut
    }

    /**
     * Vérifier si c'est une requête de création
     */
    protected function isCreationRequest(Request $request): bool
    {
        $method = $request->method();
        $routeName = $request->route()->getName();
        
        // Vérifier par méthode HTTP
        if ($method === 'POST') {
            return true;
        }
        
        // Vérifier par nom de route
        if (strpos($routeName, 'store') !== false || 
            strpos($routeName, 'create') !== false) {
            return true;
        }
        
        return false;
    }

    /**
     * Obtenir le nom d'affichage de la ressource
     */
    protected function getResourceDisplayName(string $resource): string
    {
        $names = [
            'projects' => 'projets',
            'licenses' => 'licences',
            'clients' => 'clients'
        ];
        
        return $names[$resource] ?? $resource;
    }

    /**
     * Middleware statique pour vérifier spécifiquement les projets
     */
    public static function forProjects()
    {
        return function (Request $request, Closure $next) {
            return app(self::class)->handle($request, $next, 'projects');
        };
    }

    /**
     * Middleware statique pour vérifier spécifiquement les licences
     */
    public static function forLicenses()
    {
        return function (Request $request, Closure $next) {
            return app(self::class)->handle($request, $next, 'licenses');
        };
    }

    /**
     * Middleware statique pour vérifier spécifiquement les clients
     */
    public static function forClients()
    {
        return function (Request $request, Closure $next) {
            return app(self::class)->handle($request, $next, 'clients');
        };
    }
} 