<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // Si l'utilisateur est authentifié en tant qu'admin, redirigez-le vers le tableau de bord admin
                if ($guard === 'admin') {
                    return redirect()->route('admin.dashboard');
                }
                // Si l'utilisateur est authentifié en tant que client, redirigez-le vers le tableau de bord client
                if ($guard === 'client') {
                    return redirect()->route('client.dashboard');
                }
                // Pour tout autre type d'authentification, redirigez vers la page d'accueil
                return redirect('/');
            }
        }

        return $next($request);
    }
}
