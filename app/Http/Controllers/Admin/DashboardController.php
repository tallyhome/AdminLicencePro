<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SerialKey;
use App\Models\Project;
use App\Models\LicenceHistory;
use App\Models\Setting;
use App\Services\LicenceService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    /**
     * Service de licence
     *
     * @var LicenceService
     */
    protected $licenceService;

    /**
     * Constructeur du contrôleur
     *
     * @param LicenceService $licenceService
     */
    public function __construct(LicenceService $licenceService)
    {
        $this->licenceService = $licenceService;
    }

    /**
     * Afficher le tableau de bord avec les statistiques.
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index(Request $request)
    {
        // Vérification périodique de la licence d'installation
        $redirect = $this->checkLicensePeriodically();
        if ($redirect) {
            return $redirect;
        }
        
        // Vérifier si la licence est valide avant de continuer
        $licenseValid = session('license_check_result', false);
        if (!$licenseValid && !request()->is('admin/settings/license')) {
            return redirect()->route('admin.settings.license')
                ->with('error', 'Votre licence d\'installation n\'est pas valide ou n\'est pas configurée. Veuillez configurer une licence valide pour continuer à utiliser le système.');
        }
        
        // Récupérer les paramètres de pagination
        $perPage = $request->input('per_page', 10);
        $validPerPage = in_array($perPage, [10, 25, 50, 100, 500, 1000]) ? $perPage : 10;

        // Statistiques générales
        $stats = [
            'total_keys' => SerialKey::count(),
            'active_keys' => SerialKey::where('status', 'active')->count(),
            'suspended_keys' => SerialKey::where('status', 'suspended')->count(),
            'revoked_keys' => SerialKey::where('status', 'revoked')->count(),
            'used_keys' => SerialKey::where('status', 'active')
                ->whereNotNull('domain')
                ->whereNotNull('ip_address')
                ->count(),
            'total_projects' => Project::count(),
        ];

        // Clés récentes avec pagination
        $recentKeys = SerialKey::with('project')
            ->orderBy('created_at', 'desc')
            ->paginate($validPerPage);

        // Historique des actions récentes
        $recentActions = LicenceHistory::with(['serialKey', 'user'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Statistiques d'utilisation par projet
        $projectStats = Project::withCount([
            'serialKeys', 
            'serialKeys as active_keys_count' => function ($query) {
                $query->where('status', 'active');
            },
            'serialKeys as used_keys_count' => function ($query) {
                $query->where('status', 'active')
                      ->where(function($q) {
                          $q->whereNotNull('domain')
                            ->orWhereNotNull('ip_address');
                      });
            },
            'serialKeys as available_keys_count' => function ($query) {
                $query->where('status', 'active')
                      ->whereNull('domain')
                      ->whereNull('ip_address');
            }
        ])->get();
        
        // Ajouter l'indicateur de faible disponibilité
        foreach ($projectStats as $project) {
            $project->is_running_low = ($project->serialKeys_count > 0) 
                ? ($project->available_keys_count / $project->serialKeys_count) < 0.1 
                : false;
        }

        // Statistiques d'utilisation par jour (30 derniers jours)
        // Utiliser la date actuelle (27 mai 2025)
        $currentDate = Carbon::createFromDate(2025, 5, 27);
        $startDate = $currentDate->copy()->subDays(30);
        
        // Récupérer les données d'utilisation à partir de l'historique
        $rawUsageStats = LicenceHistory::where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');
        
        // Créer un tableau complet de 30 jours avec des valeurs à zéro pour les jours sans données
        $usageStatsArray = [];
        for ($i = 0; $i <= 30; $i++) {
            $date = $startDate->copy()->addDays($i)->format('Y-m-d');
            $usageStatsArray[] = [
                'date' => $date,
                'count' => $rawUsageStats->has($date) ? $rawUsageStats[$date]->count : 0
            ];
        }
        
        // Convertir le tableau en collection Laravel pour pouvoir utiliser la méthode pluck()
        $usageStats = collect($usageStatsArray);

        return view('admin.dashboard', compact(
            'stats',
            'recentKeys',
            'recentActions',
            'projectStats',
            'usageStats',
            'validPerPage'
        ));
    }

    /**
     * Générer des statistiques pour l'API.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiStats()
    {
        $stats = [
            'total_keys' => SerialKey::count(),
            'active_keys' => SerialKey::where('status', 'active')->count(),
            'suspended_keys' => SerialKey::where('status', 'suspended')->count(),
            'revoked_keys' => SerialKey::where('status', 'revoked')->count(),
            'total_projects' => Project::count(),
            'usage_last_30_days' => LicenceHistory::where('created_at', '>=', Carbon::now()->subDays(30))->count(),
        ];

        return response()->json($stats);
    }
    
    /**
     * Vérifie périodiquement la validité de la licence
     *
     * @return \Illuminate\Http\RedirectResponse|null
     */
    protected function checkLicensePeriodically()
    {
        // Note: La vérification de licence est maintenant toujours effectuée,
        // même en environnement local, pour assurer la sécurité
        
        // Initialiser le compteur de visites si nécessaire
        if (!session()->has('dashboard_visit_count')) {
            session(['dashboard_visit_count' => 0]);
        }
        
        // Incrémenter le compteur
        $visitCount = session('dashboard_visit_count') + 1;
        session(['dashboard_visit_count' => $visitCount]);
        
        // Récupérer la fréquence de vérification (par défaut: 1 fois sur 5)
        $checkFrequency = Setting::get('license_check_frequency', 5);
        
        // Déterminer si une vérification est nécessaire
        $shouldCheck = ($visitCount % $checkFrequency === 0);
        
        // Vérifier si le résultat est déjà en session
        if (session()->has('license_check_result') && !$shouldCheck) {
            $licenseValid = session('license_check_result');
        } else {
            try {
                // Effectuer la vérification de licence
                $licenseValid = $this->licenceService->verifyInstallationLicense();
                
                // Stocker le résultat en session
                session(['license_check_result' => $licenseValid]);
                
                // Mettre à jour le paramètre de dernière vérification
                Setting::set('last_license_check', now()->toDateTimeString());
                Setting::set('license_valid', $licenseValid);
            } catch (\Exception $e) {
                // En cas d'erreur, logger et considérer comme valide en environnement local
                Log::error('Erreur lors de la vérification de licence: ' . $e->getMessage());
                $licenseValid = env('APP_ENV') === 'local';
                session(['license_check_result' => $licenseValid]);
            }
        }
        
        // Marquer comme vérifié pour cette session
        $sessionKey = 'license_check_session_' . session()->getId();
        session()->put($sessionKey, true);
        
        // Ajouter le résultat à la vue
        view()->share('licenseValid', $licenseValid);
        
        // Si la licence n'est pas valide, rediriger vers la page de licence
        if (!$licenseValid) {
            // Vérifier si nous sommes déjà sur la page de licence pour éviter une redirection en boucle
            if (!request()->is('admin/settings/license')) {
                return redirect()->route('admin.settings.license')
                    ->with('error', 'Votre licence d\'installation n\'est pas valide ou n\'est pas configurée. Veuillez configurer une licence valide pour continuer à utiliser le système.');
            }
        }
        
        return null;
    }
}