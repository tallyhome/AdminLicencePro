<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Helpers\FrontendHelper;
use Symfony\Component\HttpFoundation\Response;

class MaintenanceMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Vérifier si le mode maintenance est activé
        if (FrontendHelper::isMaintenanceMode()) {
            // Permettre l'accès à l'administration
            if ($request->is('admin*')) {
                return $next($request);
            }
            
            // Permettre l'accès aux APIs (pour les licences)
            if ($request->is('api*')) {
                return $next($request);
            }
            
            // Afficher la page de maintenance pour le reste
            return response()->view('frontend.maintenance', [
                'message' => FrontendHelper::get('maintenance_message', 'Site en maintenance, revenez bientôt !')
            ], 503);
        }

        return $next($request);
    }
} 