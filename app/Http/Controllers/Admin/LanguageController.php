<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\TranslationService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cookie;

class LanguageController extends Controller
{
    protected $translationService;

    public function __construct(TranslationService $translationService)
    {
        $this->translationService = $translationService;
    }

    /**
     * Change la langue de l'interface d'administration
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setLanguage(Request $request)
    {
        $locale = $request->input('locale');
        $availableLocales = config('app.available_locales', ['en', 'fr', 'es', 'de', 'it', 'pt', 'nl', 'ru', 'zh', 'ja', 'ar', 'tr']);
        
        if (!in_array($locale, $availableLocales)) {
            return redirect()->back()->with('error', 'Langue non supportée');
        }

        try {
            // Utiliser le TranslationService pour définir la locale
            $success = $this->translationService->setLocale($locale);
            
            if (!$success) {
                return redirect()->back()->with('error', 'Erreur lors du changement de langue');
            }
            
            // Créer un cookie durable (30 jours)
            Cookie::queue('locale', $locale, 60 * 24 * 30);
            
            // Log pour le débogage
            Log::debug('Langue changée vers: ' . $locale . ' avec TranslationService');
            
            // Forcer la régénération de la session pour éviter les problèmes de cache
            $request->session()->regenerate();
            
            // Obtenir le message de succès dans la nouvelle langue
            $successMessage = $this->translationService->translate('common.language_changed_successfully');
            
            // Vérifier si l'URL précédente est disponible
            $previousUrl = url()->previous();
            $baseUrl = url('/');
            
            // Si l'URL précédente n'est pas disponible ou pointe vers la même page
            // rediriger vers la page d'accueil admin pour éviter les boucles
            if (empty($previousUrl) || $previousUrl === $request->fullUrl()) {
                return redirect('/admin')->with('success', $successMessage);
            }
            
            // Rediriger vers la page précédente
            return redirect()->back()->with('success', $successMessage);
        } catch (\Exception $e) {
            Log::error('Erreur lors du changement de langue: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Une erreur est survenue lors du changement de langue');
        }
    }
}