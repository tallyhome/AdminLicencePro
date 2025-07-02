<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        // Si la requête attend une réponse JSON, ne pas rediriger
        if ($request->expectsJson()) {
            return null;
        }
        
        // Si c'est une route admin, rediriger vers la page de connexion admin
        if ($request->is('admin*')) {
            return route('admin.login.form');
        }
        
        // Si c'est une route client, rediriger vers la page de connexion client
        if ($request->is('client*')) {
            return route('client.login.form');
        }
        
        // Pour les routes frontend, ne jamais rediriger
        return null;
    }
}