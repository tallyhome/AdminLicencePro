<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Services\TenantService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    protected $tenantService;

    public function __construct(TenantService $tenantService)
    {
        $this->tenantService = $tenantService;
    }

    /**
     * Afficher le tableau de bord client complet
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        try {
            $client = Auth::guard('client')->user();
            
            if (!$client) {
                return redirect()->route('client.login.form');
            }
            
            $tenant = $client->tenant;
            
            if (!$tenant) {
                return $this->returnFallbackDashboard($client, 'Aucun tenant associé à votre compte.');
            }
            
            // Récupérer l'abonnement actuel
            $subscription = $tenant->subscriptions()->with('plan')->latest()->first();
            
            // Récupérer les statistiques d'utilisation réelles
            $usageStats = $this->getUsageStatistics($tenant, $subscription);
            
            // Récupérer les notifications importantes
            $notifications = $this->getImportantNotifications($tenant, $subscription);
            
            // Récupérer les données des graphiques
            $chartsData = $this->getChartsData($tenant, $request->get('period', 30));
            
            // Récupérer l'activité récente
            $recentActivity = $this->getRecentActivity($tenant);
            
            // Récupérer les projets récents
            $recentProjects = $tenant->projects()
                ->with('serialKeys')
                ->orderBy('created_at', 'desc')
                ->take(3)
                ->get();
            
            // Récupérer les licences récentes
            $recentLicenses = $tenant->serialKeys()
                ->with('project')
                ->orderBy('created_at', 'desc')
                ->take(3)
                ->get();

            return view('client.dashboard', compact(
                'client',
                'tenant', 
                'subscription',
                'usageStats',
                'notifications',
                'chartsData',
                'recentActivity',
                'recentProjects',
                'recentLicenses'
            ));
            
        } catch (\Exception $e) {
            \Log::error('Dashboard error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return $this->returnFallbackDashboard($client ?? null, 'Une erreur technique est survenue. Veuillez réessayer plus tard.');
        }
    }

    /**
     * Retourner un dashboard de fallback en cas d'erreur
     */
    private function returnFallbackDashboard($client, $errorMessage)
    {
        return view('client.dashboard-simple', [
            'client' => $client,
            'tenant' => $client ? $client->tenant : null,
            'subscription' => null,
            'usageStats' => [
                'projects' => [
                    'count' => 0, 
                    'limit' => 10, 
                    'percentage' => 0, 
                    'status' => 'success',
                    'text' => '0 sur 10 projets'
                ],
                'licenses' => [
                    'count' => 0, 
                    'limit' => 100, 
                    'percentage' => 0, 
                    'status' => 'success',
                    'text' => '0 sur 100 licences'
                ],
                'active_licenses' => [
                    'count' => 0,
                    'text' => 'Licences actives'
                ],
                'total_activations' => [
                    'count' => 0,
                    'text' => 'Activations totales'
                ]
            ],
            'notifications' => [],
            'chartsData' => [
                'labels' => [], 
                'data' => [],
                'datasets' => []
            ],
            'recentActivity' => [],
            'recentProjects' => collect(),
            'recentLicenses' => collect(),
            'error' => $errorMessage
        ]);
    }

    /**
     * Obtenir les statistiques d'utilisation
     */
    private function getUsageStatistics($tenant, $subscription)
    {
        $plan = $subscription ? $subscription->plan : null;
        
        // Compter les projets et licences réels
        $projectCount = $tenant->projects()->count();
        $licenseCount = $tenant->serialKeys()->count();
        $activeLicenseCount = $tenant->serialKeys()->where('serial_keys.status', 'active')->count();
        $totalActivations = $tenant->serialKeys()->sum('activation_count');
        
        // Calculer les limites et pourcentages
        $projectLimit = $plan ? ($plan->max_projects === -1 ? 'Illimité' : $plan->max_projects) : 0;
        $licenseLimit = $plan ? ($plan->max_licenses === -1 ? 'Illimité' : $plan->max_licenses) : 0;
        
        $projectPercentage = $projectLimit !== 'Illimité' ? round(($projectCount / $projectLimit) * 100) : 0;
        $licensePercentage = $licenseLimit !== 'Illimité' ? round(($licenseCount / $licenseLimit) * 100) : 0;
        
        return [
            'projects' => [
                'count' => $projectCount,
                'limit' => $projectLimit,
                'percentage' => $projectPercentage,
                'status' => $projectPercentage < 80 ? 'success' : ($projectPercentage < 90 ? 'warning' : 'danger'),
                'text' => $projectLimit === 'Illimité' ? 'Projets illimités' : $projectCount . ' sur ' . $projectLimit . ' projets'
            ],
            'licenses' => [
                'count' => $licenseCount,
                'limit' => $licenseLimit,
                'percentage' => $licensePercentage,
                'status' => $licensePercentage < 80 ? 'success' : ($licensePercentage < 90 ? 'warning' : 'danger'),
                'text' => $licenseLimit === 'Illimité' ? 'Licences illimitées' : $licenseCount . ' sur ' . $licenseLimit . ' licences'
            ],
            'active_licenses' => [
                'count' => $activeLicenseCount,
                'text' => 'Licences actives'
            ],
            'total_activations' => [
                'count' => $totalActivations,
                'text' => 'Activations totales'
            ]
        ];
    }

    /**
     * Obtenir les notifications importantes
     */
    private function getImportantNotifications($tenant, $subscription)
    {
        $notifications = [];
        
        // Vérifier les licences qui expirent bientôt
        $expiringLicenses = $tenant->serialKeys()
            ->where('serial_keys.status', 'active')
            ->where('serial_keys.expires_at', '<=', now()->addDays(30))
            ->where('serial_keys.expires_at', '>', now())
            ->count();
            
        if ($expiringLicenses > 0) {
            $notifications[] = [
                'type' => 'warning',
                'icon' => 'fas fa-exclamation-triangle',
                'title' => $expiringLicenses . ' licence(s) vont expirer dans les 30 prochains jours',
                'message' => 'Pensez à renouveler vos licences avant leur expiration.',
                'action' => [
                    'text' => 'Voir les licences',
                    'url' => route('client.licenses.index', ['status' => 'expiring'])
                ]
            ];
        }
        
        // Vérifier les limites d'utilisation
        if ($subscription && $subscription->plan) {
            $projectCount = $tenant->projects()->count();
            $licenseCount = $tenant->serialKeys()->count();
            
            if ($subscription->plan->max_projects !== -1 && $projectCount >= $subscription->plan->max_projects * 0.9) {
                $notifications[] = [
                    'type' => 'danger',
                    'icon' => 'fas fa-exclamation-circle',
                    'title' => 'Limite de projets presque atteinte',
                    'message' => 'Vous approchez de la limite de projets de votre plan.',
                    'action' => [
                        'text' => 'Changer de plan',
                        'url' => route('client.subscription.plans')
                    ]
                ];
            }
            
            if ($subscription->plan->max_licenses !== -1 && $licenseCount >= $subscription->plan->max_licenses * 0.9) {
                $notifications[] = [
                    'type' => 'danger',
                    'icon' => 'fas fa-exclamation-circle',
                    'title' => 'Limite de licences presque atteinte',
                    'message' => 'Vous approchez de la limite de licences de votre plan.',
                    'action' => [
                        'text' => 'Changer de plan',
                        'url' => route('client.subscription.plans')
                    ]
                ];
            }
        }
        
        // Vérifier les licences expirées
        $expiredLicenses = $tenant->serialKeys()
            ->where('serial_keys.expires_at', '<', now())
            ->count();
            
        if ($expiredLicenses > 0) {
            $notifications[] = [
                'type' => 'danger',
                'icon' => 'fas fa-times-circle',
                'title' => $expiredLicenses . ' licence(s) expirée(s)',
                'message' => 'Certaines de vos licences ont expiré et ne sont plus utilisables.',
                'action' => [
                    'text' => 'Voir les licences',
                    'url' => route('client.licenses.index', ['status' => 'expired'])
                ]
            ];
        }
        
        return $notifications;
    }

    /**
     * Obtenir les données des graphiques
     */
    private function getChartsData($tenant, $period = 30)
    {
        $endDate = now();
        $startDate = now()->subDays($period);
        
        // Récupérer les activations par jour
        $activations = DB::table('serial_key_activations')
            ->join('serial_keys', 'serial_key_activations.serial_key_id', '=', 'serial_keys.id')
            ->where('serial_keys.tenant_id', $tenant->id)
            ->whereBetween('serial_key_activations.created_at', [$startDate, $endDate])
            ->groupBy(DB::raw('DATE(serial_key_activations.created_at)'))
            ->select(
                DB::raw('DATE(serial_key_activations.created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->get();
            
        // Créer un tableau avec toutes les dates de la période
        $dates = collect();
        for ($date = $startDate->copy(); $date <= $endDate; $date->addDay()) {
            $dates->put($date->format('Y-m-d'), 0);
        }
        
        // Remplir avec les données réelles
        foreach ($activations as $activation) {
            $dates->put($activation->date, $activation->count);
        }
        
        // Formater les données pour le graphique
        return [
            'labels' => $dates->keys()->map(function($date) {
                return Carbon::parse($date)->format('d/m');
            })->values(),
            'datasets' => [
                [
                    'label' => 'Activations',
                    'data' => $dates->values(),
                    'borderColor' => '#4e73df',
                    'backgroundColor' => 'rgba(78, 115, 223, 0.1)',
                    'borderWidth' => 2,
                    'fill' => true,
                    'tension' => 0.4
                ]
            ]
        ];
    }

    /**
     * Obtenir l'activité récente
     */
    private function getRecentActivity($tenant)
    {
        $activity = collect();
        
        // Récupérer les derniers projets créés
        $tenant->projects()
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get()
            ->each(function($project) use ($activity) {
                $activity->push([
                    'type' => 'project',
                    'icon' => 'fas fa-folder',
                    'color' => 'primary',
                    'title' => $project->name,
                    'description' => 'Nouveau projet créé',
                    'date' => $project->created_at,
                    'url' => route('client.projects.show', $project)
                ]);
            });
            
        // Récupérer les dernières licences créées
        $tenant->serialKeys()
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get()
            ->each(function($license) use ($activity) {
                $activity->push([
                    'type' => 'license',
                    'icon' => 'fas fa-key',
                    'color' => 'success',
                    'title' => $license->serial_key,
                    'description' => 'Nouvelle licence générée',
                    'date' => $license->created_at,
                    'url' => route('client.licenses.show', $license)
                ]);
            });
            
        // Récupérer les dernières activations
        DB::table('serial_key_activations')
            ->join('serial_keys', 'serial_key_activations.serial_key_id', '=', 'serial_keys.id')
            ->where('serial_keys.tenant_id', $tenant->id)
            ->orderBy('serial_key_activations.created_at', 'desc')
            ->take(3)
            ->get()
            ->each(function($activation) use ($activity) {
                $activity->push([
                    'type' => 'activation',
                    'icon' => 'fas fa-bolt',
                    'color' => 'warning',
                    'title' => 'Activation de licence',
                    'description' => 'Depuis ' . $activation->ip_address,
                    'date' => Carbon::parse($activation->created_at),
                    'url' => route('client.licenses.show', $activation->serial_key_id)
                ]);
            });
            
        // Trier par date et prendre les 5 plus récents
        return $activity->sortByDesc('date')->take(5)->values();
    }

    /**
     * Calculer l'espace de stockage utilisé (simulation)
     */
    private function calculateStorageUsed($tenant)
    {
        try {
            // Simulation basée sur le nombre de licences et projets
            $projectsCount = $tenant->projects()->count();
            $licensesCount = $tenant->serialKeys()->count();
            
            // Estimation : 1MB par projet + 0.1MB par licence
            $estimatedMB = ($projectsCount * 1) + ($licensesCount * 0.1);
            
            return [
                'used_mb' => round($estimatedMB, 2),
                'used_formatted' => $this->formatBytes($estimatedMB * 1024 * 1024),
                'limit_mb' => 1000, // 1GB par défaut
                'percentage' => min(100, round(($estimatedMB / 1000) * 100, 1))
            ];
        } catch (\Exception $e) {
            \Log::warning('Error calculating storage: ' . $e->getMessage());
            return [
                'used_mb' => 0,
                'used_formatted' => '0 B',
                'limit_mb' => 1000,
                'percentage' => 0
            ];
        }
    }

    /**
     * Formater les bytes en unité lisible
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }

    /**
     * Endpoint pour les données du graphique
     */
    public function chartData(Request $request)
    {
        $period = $request->get('period', 30);
        $tenant = Auth::guard('client')->user()->tenant;
        
        return response()->json($this->getChartsData($tenant, $period));
    }
} 