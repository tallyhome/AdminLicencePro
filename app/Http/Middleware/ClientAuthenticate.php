<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class ClientAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Log pour le débogage
        Log::debug('ClientAuthenticate middleware - Checking authentication');
        Log::debug('Current guard: ' . Auth::getDefaultDriver());
        
        // Vérification directe de la session
        if (Session::has('client_id')) {
            Log::debug('Client authenticated via session, proceeding with request');
            return $next($request);
        }
        
        // Vérification via Auth facade
        if (Auth::guard('client')->check()) {
            Log::debug('Client authenticated via guard, proceeding with request');
            // Stocker l'ID du client en session pour les futures requêtes
            Session::put('client_id', Auth::guard('client')->id());
            return $next($request);
        }

        Log::debug('Client not authenticated, redirecting to login');
        
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Non authentifié.'], 401);
        }

        return redirect()->guest(route('client.login.form'));
    }
} 