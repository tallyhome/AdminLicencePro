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
            
            // DONNÉES DE DÉMONSTRATION pour voir le dashboard complet
            $usageStats = [
                'projects' => [
                    'count' => 3,
                    'limit' => 10,
                    'percentage' => 30,
                    'status' => 'success'
                ],
                'licenses' => [
                    'count' => 15,
                    'limit' => 100,
                    'percentage' => 15,
                    'status' => 'success'
                ],
                'active_licenses' => 12,
                'total_activations' => 28,
                'storage_used' => [
                    'used_mb' => 45.7,
                    'used_formatted' => '45.7 MB',
                    'limit_mb' => 1000,
                    'percentage' => 4.6
                ]
            ];
            
            // Notifications de démonstration
            $notifications = [
                [
                    'type' => 'info',
                    'icon' => 'fas fa-key',
                    'title' => 'Licences expirant bientôt',
                    'message' => '2 licence(s) vont expirer dans les 30 prochains jours.',
                    'action' => [
                        'text' => 'Voir les licences',
                        'url' => route('client.licenses.index')
                    ]
                ],
                [
                    'type' => 'success',
                    'icon' => 'fas fa-chart-line',
                    'title' => 'Performance excellente',
                    'message' => 'Vos licences fonctionnent parfaitement ce mois-ci.',
                    'action' => null
                ]
            ];
            
            // Données de graphiques de démonstration
            $chartsData = [
                'labels' => ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun'],
                'data' => [2, 5, 3, 8, 4, 6],
                'licenses_over_time' => collect([
                    '2024-01-01' => 2,
                    '2024-02-01' => 5,
                    '2024-03-01' => 3,
                    '2024-04-01' => 8,
                    '2024-05-01' => 4,
                    '2024-06-01' => 6
                ]),
                'licenses_by_status' => collect([
                    'active' => 12,
                    'inactive' => 3
                ]),
                'activations_over_time' => collect([
                    '2024-01-01' => 4,
                    '2024-02-01' => 8,
                    '2024-03-01' => 6,
                    '2024-04-01' => 12,
                    '2024-05-01' => 7,
                    '2024-06-01' => 9
                ]),
                'period' => 30
            ];
            
            // Activité récente de démonstration
            $recentActivity = collect([
                [
                    'type' => 'project_created',
                    'icon' => 'fas fa-folder-plus text-primary',
                    'title' => 'Nouveau projet créé',
                    'description' => 'Application Mobile',
                    'date' => now()->subDays(2),
                    'url' => '#'
                ],
                [
                    'type' => 'license_created',
                    'icon' => 'fas fa-key text-success',
                    'title' => 'Nouvelle licence générée',
                    'description' => 'Site Web E-commerce',
                    'date' => now()->subDays(1),
                    'url' => '#'
                ],
                [
                    'type' => 'activation',
                    'icon' => 'fas fa-play text-info',
                    'title' => 'Licence activée',
                    'description' => 'API REST - example.com',
                    'date' => now()->subHours(3),
                    'url' => '#'
                ]
            ]);
            
            // Projets récents de démonstration
            $recentProjects = collect([
                (object)[
                    'id' => 1,
                    'name' => 'Application Mobile',
                    'description' => 'App iOS/Android',
                    'status' => 'active',
                    'created_at' => now()->subDays(5),
                    'serialKeys' => collect([1, 2, 3]) // Simule 3 licences
                ],
                (object)[
                    'id' => 2,
                    'name' => 'Site Web E-commerce',
                    'description' => 'Boutique en ligne',
                    'status' => 'active',
                    'created_at' => now()->subDays(10),
                    'serialKeys' => collect([1, 2, 3, 4, 5]) // Simule 5 licences
                ],
                (object)[
                    'id' => 3,
                    'name' => 'API REST',
                    'description' => 'Services web',
                    'status' => 'active',
                    'created_at' => now()->subDays(15),
                    'serialKeys' => collect([1, 2, 3, 4, 5, 6, 7]) // Simule 7 licences
                ]
            ]);
                
            // Licences récentes de démonstration
            $recentLicenses = collect([
                (object)[
                    'id' => 1,
                    'serial_key' => 'DEMO-1234-ABCD-5678',
                    'status' => 'active',
                    'expires_at' => now()->addDays(90),
                    'created_at' => now()->subDays(2),
                    'project' => (object)['name' => 'Application Mobile']
                ],
                (object)[
                    'id' => 2,
                    'serial_key' => 'DEMO-5678-EFGH-9012',
                    'status' => 'active',
                    'expires_at' => now()->addDays(60),
                    'created_at' => now()->subDays(1),
                    'project' => (object)['name' => 'Site Web E-commerce']
                ],
                (object)[
                    'id' => 3,
                    'serial_key' => 'DEMO-9012-IJKL-3456',
                    'status' => 'active',
                    'expires_at' => now()->addDays(120),
                    'created_at' => now()->subHours(5),
                    'project' => (object)['name' => 'API REST']
                ]
            ]);

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
            
            return $this->returnFallbackDashboard($client ?? null, 'Une erreur est survenue lors du chargement du dashboard.');
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
                'projects' => ['count' => 0, 'limit' => 10, 'percentage' => 0, 'status' => 'success'],
                'licenses' => ['count' => 0, 'limit' => 100, 'percentage' => 0, 'status' => 'success'],
                'active_licenses' => 0,
                'total_activations' => 0,
                'storage_used' => '0 B'
            ],
            'notifications' => [],
            'chartsData' => ['labels' => [], 'data' => []],
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
        try {
            $plan = $subscription ? $subscription->plan : null;
            
            // Compter les ressources utilisées
            $projectsCount = $tenant->projects()->count();
            $licensesCount = $tenant->serialKeys()->count();
            $activeLicensesCount = $tenant->serialKeys()->where('status', 'active')->count();
            $totalActivations = $tenant->serialKeys()->sum('current_activations') ?? 0;
            
            // Limites du plan
            $projectsLimit = $plan ? $plan->max_projects : 0;
            $licensesLimit = $plan ? $plan->max_licenses : 0;
            
            return [
                'projects' => [
                    'count' => $projectsCount,
                    'limit' => $projectsLimit,
                    'percentage' => $projectsLimit > 0 ? min(100, round(($projectsCount / $projectsLimit) * 100)) : 0,
                    'status' => $projectsLimit > 0 && $projectsCount >= $projectsLimit ? 'danger' : 'success'
                ],
                'licenses' => [
                    'count' => $licensesCount,
                    'limit' => $licensesLimit,
                    'percentage' => $licensesLimit > 0 ? min(100, round(($licensesCount / $licensesLimit) * 100)) : 0,
                    'status' => $licensesLimit > 0 && $licensesCount >= $licensesLimit ? 'danger' : 'success'
                ],
                'active_licenses' => $activeLicensesCount,
                'total_activations' => $totalActivations,
                'storage_used' => $this->calculateStorageUsed($tenant),
            ];
        } catch (\Exception $e) {
            \Log::warning('Error getting usage statistics: ' . $e->getMessage());
            return [
                'projects' => ['count' => 0, 'limit' => 0, 'percentage' => 0, 'status' => 'success'],
                'licenses' => ['count' => 0, 'limit' => 0, 'percentage' => 0, 'status' => 'success'],
                'active_licenses' => 0,
                'total_activations' => 0,
                'storage_used' => '0 B',
            ];
        }
    }

    /**
     * Obtenir les notifications importantes
     */
    private function getImportantNotifications($tenant, $subscription)
    {
        try {
            $notifications = [];
            
            // Vérifier l'expiration de l'abonnement
            if ($subscription && $subscription->ends_at) {
                $daysLeft = now()->diffInDays($subscription->ends_at, false);
                
                if ($daysLeft <= 7 && $daysLeft > 0) {
                    $notifications[] = [
                        'type' => 'warning',
                        'icon' => 'fas fa-exclamation-triangle',
                        'title' => 'Abonnement expire bientôt',
                        'message' => "Votre abonnement expire dans {$daysLeft} jour(s). Renouvelez-le pour continuer à utiliser nos services.",
                        'action' => [
                            'text' => 'Renouveler',
                            'url' => '#'
                        ]
                    ];
                } elseif ($daysLeft <= 0) {
                    $notifications[] = [
                        'type' => 'danger',
                        'icon' => 'fas fa-times-circle',
                        'title' => 'Abonnement expiré',
                        'message' => 'Votre abonnement a expiré. Renouvelez-le immédiatement pour restaurer l\'accès.',
                        'action' => [
                            'text' => 'Renouveler maintenant',
                            'url' => '#'
                        ]
                    ];
                }
            }
            
            // Vérifier les limites du plan (version sécurisée)
            try {
                $usageStats = $this->tenantService->checkTenantLimits($tenant);
                
                if (!$usageStats['within_limits']) {
                    $notifications[] = [
                        'type' => 'warning',
                        'icon' => 'fas fa-chart-line',
                        'title' => 'Limites du plan atteintes',
                        'message' => 'Vous avez atteint les limites de votre plan actuel. Passez à un plan supérieur pour continuer.',
                        'action' => [
                            'text' => 'Voir les plans',
                            'url' => '#'
                        ]
                    ];
                }
            } catch (\Exception $e) {
                \Log::warning('Error checking tenant limits: ' . $e->getMessage());
            }
            
            // Vérifier les licences qui vont expirer
            try {
                $expiringLicenses = $tenant->serialKeys()
                    ->where('expires_at', '<=', now()->addDays(30))
                    ->where('expires_at', '>', now())
                    ->count();
                    
                if ($expiringLicenses > 0) {
                    $notifications[] = [
                        'type' => 'info',
                        'icon' => 'fas fa-key',
                        'title' => 'Licences expirant bientôt',
                        'message' => "{$expiringLicenses} licence(s) vont expirer dans les 30 prochains jours.",
                        'action' => [
                            'text' => 'Voir les licences',
                            'url' => route('client.licenses.index')
                        ]
                    ];
                }
            } catch (\Exception $e) {
                \Log::warning('Error checking expiring licenses: ' . $e->getMessage());
            }
            
            return $notifications;
        } catch (\Exception $e) {
            \Log::warning('Error getting notifications: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtenir les données pour les graphiques
     */
    private function getChartsData($tenant, $period = 30)
    {
        try {
            $startDate = now()->subDays($period);
            
            // Évolution des licences créées
            $licensesOverTime = $tenant->serialKeys()
                ->where('created_at', '>=', $startDate)
                ->select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('COUNT(*) as count')
                )
                ->groupBy('date')
                ->orderBy('date')
                ->get()
                ->mapWithKeys(function ($item) {
                    return [$item->date => $item->count];
                });

            // Répartition par statut des licences
            $licensesByStatus = $tenant->serialKeys()
                ->select('status', DB::raw('COUNT(*) as count'))
                ->groupBy('status')
                ->get()
                ->mapWithKeys(function ($item) {
                    return [$item->status => $item->count];
                });

            // Activations par jour
            $activationsOverTime = $tenant->serialKeys()
                ->where('last_activation_at', '>=', $startDate)
                ->select(
                    DB::raw('DATE(last_activation_at) as date'),
                    DB::raw('SUM(current_activations) as activations')
                )
                ->groupBy('date')
                ->orderBy('date')
                ->get()
                ->mapWithKeys(function ($item) {
                    return [$item->date => $item->activations ?? 0];
                });

            return [
                'licenses_over_time' => $licensesOverTime,
                'licenses_by_status' => $licensesByStatus,
                'activations_over_time' => $activationsOverTime,
                'period' => $period
            ];
        } catch (\Exception $e) {
            \Log::warning('Error getting charts data: ' . $e->getMessage());
            return [
                'licenses_over_time' => collect(),
                'licenses_by_status' => collect(),
                'activations_over_time' => collect(),
                'period' => $period
            ];
        }
    }

    /**
     * Obtenir l'activité récente
     */
    private function getRecentActivity($tenant)
    {
        try {
            $activities = collect();
            
            // Projets récents
            $recentProjects = $tenant->projects()
                ->latest()
                ->take(3)
                ->get()
                ->map(function ($project) {
                    return [
                        'type' => 'project_created',
                        'icon' => 'fas fa-folder-plus text-primary',
                        'title' => 'Nouveau projet créé',
                        'description' => $project->name,
                        'date' => $project->created_at,
                        'url' => route('client.projects.show', $project)
                    ];
                });
                
            // Licences récentes
            $recentLicenses = $tenant->serialKeys()
                ->latest()
                ->take(3)
                ->get()
                ->map(function ($license) {
                    return [
                        'type' => 'license_created',
                        'icon' => 'fas fa-key text-success',
                        'title' => 'Nouvelle licence générée',
                        'description' => $license->project->name ?? 'Projet inconnu',
                        'date' => $license->created_at,
                        'url' => route('client.licenses.show', $license)
                    ];
                });
                
            return $activities
                ->merge($recentProjects)
                ->merge($recentLicenses)
                ->sortByDesc('date')
                ->take(10);
        } catch (\Exception $e) {
            \Log::warning('Error getting recent activity: ' . $e->getMessage());
            return collect();
        }
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
     * API pour les données des graphiques
     */
    public function chartData(Request $request)
    {
        try {
            $client = Auth::guard('client')->user();
            $tenant = $client ? $client->tenant : null;
            
            if (!$tenant) {
                return response()->json(['error' => 'Tenant not found'], 404);
            }
            
            $period = $request->get('period', 30);
            $chartsData = $this->getChartsData($tenant, $period);
            
            return response()->json($chartsData);
            
        } catch (\Exception $e) {
            \Log::error('Chart data error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
} 