<?php

namespace App\Http\Middleware;

use App\Services\LicenceService;
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
            // Note: La vérification de licence est maintenant toujours effectuée,
            // même en environnement local, pour assurer la sécurité
            
            // Récupérer la clé de licence
            $licenseKey = env('INSTALLATION_LICENSE_KEY');
            
            // Vérifier si une clé de licence est configurée
            if (empty($licenseKey)) {
                Log::warning('Tentative d\'accès sans clé de licence configurée: ' . $request->path());
                return redirect()->route('admin.settings.license')
                    ->with('error', 'Aucune clé de licence n\'est configurée. Veuillez configurer une licence valide pour continuer à utiliser le système.');
            }
            
            // Vérifier si c'est une nouvelle clé de licence (différente de celle en cache)
            $lastVerifiedKey = \Illuminate\Support\Facades\Cache::get('last_verified_license_key');
            $isNewKey = ($lastVerifiedKey !== $licenseKey);
            
            // Forcer la vérification si c'est une nouvelle clé
            $cacheKey = 'license_verification_' . md5($licenseKey);
            
            // Si c'est une nouvelle clé, vider immédiatement le cache
            if ($isNewKey) {
                \Illuminate\Support\Facades\Cache::forget($cacheKey);
                \Illuminate\Support\Facades\Cache::forget('last_verified_license_key');
                Log::info('Cache de licence vidé pour nouvelle clé dans le middleware', [
                    'old_key' => $lastVerifiedKey,
                    'new_key' => $licenseKey
                ]);
            }
            
            // FORCER UNE NOUVELLE VÉRIFICATION - CACHE DÉSACTIVÉ TEMPORAIREMENT
            $cachedResult = null; // Force une nouvelle vérification
            
            // Vérifier si nous devons faire une nouvelle vérification API
            // 1. Si aucun résultat en cache
            // 2. Si c'est une nouvelle clé
            // 3. Si la dernière vérification date de plus de 8 heures
            $lastCheckKey = 'last_license_check_' . md5($licenseKey);
            $lastCheck = 0; // Force une nouvelle vérification
            $currentTime = time();
            $eightHoursInSeconds = 8 * 60 * 60;
            
            if (true) { // Force toujours une nouvelle vérification
                // Vérifier directement avec l'API
                $domain = $request->getHost();
                $ipAddress = ''; // IP non utilisée maintenant, mais gardée pour compatibilité
                
                Log::info('Validation de licence (domaine uniquement)', ['domain' => $domain]);
                
                // Valider avec le domaine uniquement
                $result = $this->licenceService->validateSerialKey($licenseKey, $domain, $ipAddress);
                $isValid = $result['valid'] === true;
                
                // Mettre à jour le timestamp de la dernière vérification
                \Illuminate\Support\Facades\Cache::put($lastCheckKey, $currentTime, 60 * 24 * 7); // 7 jours
            } else {
                // Utiliser le résultat en cache
                $isValid = $cachedResult;
                $result = ['valid' => $isValid, 'message' => 'Résultat en cache', 'data' => []]; 
            }
            
            // Journaliser uniquement les échecs ou les changements de statut
            if (!$isValid) {
                // Journaliser les échecs de validation de licence
                Log::warning('Licence invalide dans le middleware', [
                    'message' => $result['message'] ?? 'Aucun détail disponible',
                    'path' => $request->path()
                ]);
            } elseif (!$cachedResult && $isValid) {
                // Journaliser uniquement la première validation réussie ou après un changement de statut
                Log::info('Licence validée avec succès', [
                    'path' => $request->path()
                ]);
            }
            
            // Stocker le résultat en cache (24 heures)
            \Illuminate\Support\Facades\Cache::put($cacheKey, $isValid, 60 * 24);
            
            // Sauvegarder la clé vérifiée pour détecter les changements futurs
            \Illuminate\Support\Facades\Cache::put('last_verified_license_key', $licenseKey, 60 * 24 * 7); // 7 jours
            
            // Si la licence n'est pas valide, rediriger vers la page de licence
            if (!$isValid) {
                Log::warning('Licence invalide détectée, redirection vers la page de licence');
                
                // Éviter la redirection en boucle si on est déjà sur la page de licence
                if (!$request->is('admin/settings/license*')) {
                    return redirect()->route('admin.settings.license')
                        ->with('error', 'Votre licence d\'installation n\'est pas valide. Veuillez configurer une licence valide pour continuer.');
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
