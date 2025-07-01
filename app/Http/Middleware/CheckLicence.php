<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Setting;
use App\Services\LicenceService;

class CheckLicence
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
        // Ne pas vérifier la licence pour certaines routes
        $excludedRoutes = [
            'admin/settings/license*',
            'admin/login*',
            'admin/logout*',
            'api/*',
            'install/*'
        ];

        foreach ($excludedRoutes as $pattern) {
            if ($request->is($pattern)) {
                return $next($request);
            }
        }

        // Ne vérifier que pour les routes admin
        if (!$request->is('admin*')) {
            return $next($request);
        }

        // Récupérer la clé de licence
        $licenseKey = env('INSTALLATION_LICENSE_KEY', '');
        
        // Vérifier si une clé de licence est configurée
        if (empty($licenseKey)) {
            return redirect()->route('admin.settings.license')
                ->with('error', 'Aucune clé de licence n\'est configurée. Veuillez configurer une licence valide pour accéder à l\'administration.');
        }

        // Vérifier si la licence est valide
        $isValid = $this->licenceService->verifyInstallationLicense(true);
        
        if (!$isValid) {
            return redirect()->route('admin.settings.license')
                ->with('error', 'Votre licence n\'est pas valide. Veuillez configurer une licence valide.');
        }
        
        return $next($request);
    }
} 