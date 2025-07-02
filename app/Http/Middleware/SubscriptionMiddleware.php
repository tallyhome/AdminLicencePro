<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SubscriptionMiddleware
{
    /**
     * Vérifier que l'abonnement du client est actif
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $client = Auth::guard('client')->user();
        
        if (!$client) {
            return redirect()->route('client.login.form')
                ->with('error', 'Veuillez vous connecter pour accéder à cette page.');
        }

        $tenant = $client->tenant;
        
        if (!$tenant) {
            return redirect()->route('client.login.form')
                ->with('error', 'Compte non configuré correctement. Contactez le support.');
        }

        // Vérifier le statut du tenant
        if ($tenant->status !== 'active') {
            return redirect()->route('client.dashboard')
                ->with('error', 'Votre compte est suspendu. Contactez le support pour plus d\'informations.');
        }

        $subscription = $tenant->subscriptions()->first();
        
        if (!$subscription) {
            return redirect()->route('subscription.plans')
                ->with('error', 'Aucun abonnement actif. Choisissez un plan pour continuer.');
        }

        // Vérifier le statut de l'abonnement
        switch ($subscription->status) {
            case 'trial':
                // Vérifier si la période d'essai est encore valide
                if ($subscription->trial_ends_at && now()->gt($subscription->trial_ends_at)) {
                    // Période d'essai expirée
                    $subscription->update(['status' => 'expired']);
                    return redirect()->route('subscription.plans')
                        ->with('error', 'Votre période d\'essai a expiré. Choisissez un plan pour continuer.');
                }
                break;

            case 'active':
                // Vérifier si l'abonnement a expiré
                if ($subscription->ends_at && now()->gt($subscription->ends_at)) {
                    $subscription->update(['status' => 'expired']);
                    return redirect()->route('subscription.plans')
                        ->with('error', 'Votre abonnement a expiré. Renouvelez votre plan pour continuer.');
                }
                break;

            case 'canceled':
                // L'abonnement est annulé mais peut encore être actif jusqu'à la date de fin
                if ($subscription->ends_at && now()->gt($subscription->ends_at)) {
                    return redirect()->route('subscription.plans')
                        ->with('error', 'Votre abonnement annulé a expiré. Choisissez un nouveau plan pour continuer.');
                }
                break;

            case 'expired':
                return redirect()->route('subscription.plans')
                    ->with('error', 'Votre abonnement a expiré. Choisissez un plan pour continuer.');

            default:
                return redirect()->route('subscription.plans')
                    ->with('error', 'Statut d\'abonnement invalide. Contactez le support.');
        }

        return $next($request);
    }
} 