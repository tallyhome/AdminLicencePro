<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
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

        // Vérifier si la licence est valide (utiliser les settings mis à jour par le contrôleur)
        $isValid = Setting::get('license_valid', false);
        $licenseKey = env('INSTALLATION_LICENSE_KEY', '');

        // Si aucune clé n'est configurée ou si la licence n'est pas valide
        if (empty($licenseKey) || !$isValid) {
            return redirect()->route('admin.settings.license')
                ->with('error', 'Veuillez configurer et valider votre clé de licence pour accéder à l\'administration.');
        }

        return $next($request);
    }
} 