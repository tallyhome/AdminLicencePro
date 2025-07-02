<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ClientAuthController extends Controller
{
    public function __construct()
    {
        // Le middleware sera géré au niveau des routes
    }

    /**
     * Afficher le formulaire de connexion client
     */
    public function showLoginForm()
    {
        return view('auth.client-login');
    }

    /**
     * Traiter la connexion client
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Vérifier les informations d'identification
        $client = Client::where('email', $request->email)->first();

        if (!$client || !Hash::check($request->password, $client->password)) {
            throw ValidationException::withMessages([
                'email' => ['Les informations d\'identification ne correspondent pas.'],
            ]);
        }

        // Vérifier le statut du client
        if ($client->status !== Client::STATUS_ACTIVE) {
            throw ValidationException::withMessages([
                'email' => ['Votre compte est désactivé.'],
            ]);
        }

        // Vérifier le statut du tenant
        if ($client->tenant && $client->tenant->status !== 'active') {
            throw ValidationException::withMessages([
                'email' => ['Votre compte est suspendu.'],
            ]);
        }

        // Connecter le client
        Auth::guard('client')->login($client, $request->boolean('remember'));

        $request->session()->regenerate();

        return redirect()->intended(route('client.dashboard'))
            ->with('success', 'Connexion réussie !');
    }

    /**
     * Déconnecter le client
     */
    public function logout(Request $request)
    {
        Auth::guard('client')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('client.login.form')
            ->with('success', 'Déconnexion réussie !');
    }

    /**
     * Afficher le formulaire de demande de réinitialisation de mot de passe
     */
    public function showForgotPasswordForm()
    {
        return view('auth.client-forgot-password');
    }

    /**
     * Envoyer le lien de réinitialisation de mot de passe
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $client = Client::where('email', $request->email)->first();

        if (!$client) {
            // Pour des raisons de sécurité, ne pas révéler si l'email existe
            return back()->with('status', 'Un lien de réinitialisation a été envoyé à votre adresse email.');
        }

        // Ici vous pourriez implémenter l'envoi d'email avec un token
        // Pour l'instant, on retourne juste un message de succès
        return back()->with('status', 'Un lien de réinitialisation a été envoyé à votre adresse email.');
    }
} 