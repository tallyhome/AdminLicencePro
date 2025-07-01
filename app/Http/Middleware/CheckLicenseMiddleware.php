<?php

namespace App\Http\Middleware;

use App\Services\LicenceService;
use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

class CheckLicenseMiddleware
{
    protected $licenceService;

    public function __construct(LicenceService $licenceService)
    {
        $this->licenceService = $licenceService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Routes exemptées de la vérification de licence
        $exemptedRoutes = [
            'admin/login',
            'admin/login/*',
            'admin/direct-login',
            'admin/logout',
            'admin/password/*',
            'admin/2fa/*',
            'admin/settings/license',
            'admin/settings/license/*'
        ];
        
        // Vérifier si la route actuelle est exemptée
        foreach ($exemptedRoutes as $pattern) {
            if ($request->is($pattern)) {
                return $next($request);
            }
        }
        
        // Si ce n'est pas une route admin, laisser passer
        if (!$request->is('admin') && !$request->is('admin/*')) {
            return $next($request);
        }

        try {
            // Note: Vérification de licence simple et sécurisée
            // Si licence valide → Accès autorisé
            // Si licence invalide → Redirection vers page de licence
            
            // Récupérer la clé de licence
            $licenseKey = env('INSTALLATION_LICENSE_KEY', '');
            
            // Vérifier si une clé de licence est configurée
            if (empty($licenseKey)) {
                return redirect()->route('admin.settings.license')
                    ->with('error', 'Aucune clé de licence n\'est configurée. Veuillez configurer une licence valide pour continuer à utiliser le système.');
            }
            
            // Vérifier si la licence est valide
            $isValid = $this->licenceService->verifyInstallationLicense(true);
            
            if (!$isValid) {
                if (!$request->is('admin/settings/license*')) {
                    return redirect()->route('admin.settings.license')
                        ->with('error', 'Votre licence n\'est pas valide. Veuillez configurer une licence valide.');
                }
            }
        } catch (\Exception $e) {
            Log::error('Erreur lors de la vérification de licence dans le middleware', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'path' => $request->path()
            ]);
            
            // Éviter la redirection en boucle si on est déjà sur la page de licence
            if (!$request->is('admin/settings/license*')) {
                return redirect()->route('admin.settings.license')
                    ->with('error', 'Erreur lors de la vérification de licence: ' . $e->getMessage());
            }
        }

        return $next($request);
    }
}
