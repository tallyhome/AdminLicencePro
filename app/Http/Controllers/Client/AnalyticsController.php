<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\SerialKey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    /**
     * Afficher le tableau de bord analytics
     */
    public function index(Request $request)
    {
        $client = Auth::guard('client')->user();
        $tenant = $client->tenant;

        $period = $request->get('period', 30); // Par défaut 30 jours

        // Statistiques générales
        $generalStats = $this->getGeneralStats($tenant);

        // Données pour les graphiques
        $chartsData = $this->getChartsData($tenant, $period);

        // Top projets par activations
        $topProjects = $this->getTopProjects($tenant);

        // Évolution des activations
        $activationsTrend = $this->getActivationsTrend($tenant, $period);

        // Répartition par type de licence
        $licenseTypeDistribution = $this->getLicenseTypeDistribution($tenant);

        // Licences expirant bientôt
        $expiringLicenses = $this->getExpiringLicenses($tenant);

        // Données pour les graphiques Chart.js
        $chartData = [
            'validations' => [
                'labels' => array_keys($chartsData['licenses_over_time']->toArray()),
                'data' => array_values($chartsData['licenses_over_time']->toArray())
            ],
            'projects' => $topProjects->take(5)->map(function($project) {
                return [
                    'name' => $project['name'],
                    'value' => $project['total_activations'],
                    'color' => '#' . substr(md5($project['name']), 0, 6)
                ];
            })->toArray()
        ];

        // Activité récente
        $recentActivity = $this->getRecentActivity($tenant);

        // Top licences
        $topLicenses = $this->getTopLicenses($tenant);

        // Renommer les variables pour correspondre à la vue
        $stats = [
            'active_licenses' => $generalStats['active_licenses'],
            'validations_this_month' => $generalStats['activations_this_month'],
            'active_projects' => $generalStats['total_projects'],
            'success_rate' => 95.5 // Valeur simulée
        ];

        return view('client.analytics.index', compact(
            'stats',
            'chartData',
            'recentActivity',
            'topLicenses',
            'period',
            'client',
            'tenant'
        ));
    }

    /**
     * API pour récupérer des données analytics
     */
    public function getData(Request $request)
    {
        $client = Auth::guard('client')->user();
        $period = $request->get('period', 30);
        $type = $request->get('type', 'licenses');

        try {
            switch ($type) {
                case 'licenses':
                    $data = $this->getLicensesData($period);
                    break;
                case 'activations':
                    $data = $this->getActivationsData($period);
                    break;
                case 'projects':
                    $data = $this->getProjectsData($period);
                    break;
                default:
                    $data = [];
            }

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des données : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API pour récupérer les données des graphiques
     */
    public function getChartData(Request $request)
    {
        $client = Auth::guard('client')->user();
        $tenant = $client->tenant;
        $period = $request->get('period', '30days');

        try {
            // Convertir la période en nombre de jours
            $days = match($period) {
                '7days' => 7,
                '30days' => 30,
                '90days' => 90,
                default => 30
            };

            $chartsData = $this->getChartsData($tenant, $days);

            return response()->json([
                'success' => true,
                'labels' => array_keys($chartsData['licenses_over_time']->toArray()),
                'data' => array_values($chartsData['licenses_over_time']->toArray())
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des données : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Exporter les données analytics
     */
    public function export(Request $request)
    {
        $client = Auth::guard('client')->user();
        $format = $request->get('format', 'csv');
        $period = $request->get('period', 30);

        try {
            $data = $this->getExportData($period);

            switch ($format) {
                case 'json':
                    return $this->exportJson($data);
                case 'csv':
                default:
                    return $this->exportCsv($data);
            }
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de l\'export : ' . $e->getMessage());
        }
    }

    /**
     * Obtenir les statistiques générales
     */
    private function getGeneralStats($tenant)
    {
        return [
            'total_projects' => $tenant->projects()->count(),
            'total_licenses' => $tenant->serialKeys()->count(),
            'active_licenses' => $tenant->serialKeys()->where('serial_keys.status', 'active')->count(),
            'total_activations' => $tenant->serialKeys()->sum('current_activations') ?? 0,
            'licenses_this_month' => $tenant->serialKeys()
                ->whereMonth('serial_keys.created_at', now()->month)
                ->whereYear('serial_keys.created_at', now()->year)
                ->count(),
            'activations_this_month' => $tenant->serialKeys()
                ->whereMonth('serial_keys.last_activation_at', now()->month)
                ->whereYear('serial_keys.last_activation_at', now()->year)
                ->sum('current_activations') ?? 0,
            'expiring_soon' => $tenant->serialKeys()
                ->where('serial_keys.expires_at', '<=', now()->addDays(30))
                ->where('serial_keys.expires_at', '>', now())
                ->count(),
            'expired_licenses' => $tenant->serialKeys()
                ->where('serial_keys.expires_at', '<', now())
                ->count(),
        ];
    }

    /**
     * Obtenir les données pour les graphiques
     */
    private function getChartsData($tenant, $period)
    {
        $startDate = now()->subDays($period);

        // Évolution des licences créées
        $licensesOverTime = $tenant->serialKeys()
            ->where('serial_keys.created_at', '>=', $startDate)
            ->select(
                DB::raw('DATE(serial_keys.created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->mapWithKeys(function ($item) {
                return [Carbon::parse($item->date)->format('d/m') => $item->count];
            });

        // Si pas de données, créer des données vides pour la période
        if ($licensesOverTime->isEmpty()) {
            $licensesOverTime = collect();
            for ($i = $period; $i >= 0; $i--) {
                $date = now()->subDays($i)->format('d/m');
                $licensesOverTime[$date] = 0;
            }
        }

        // Évolution des activations
        $activationsOverTime = $tenant->serialKeys()
            ->where('serial_keys.last_activation_at', '>=', $startDate)
            ->whereNotNull('serial_keys.last_activation_at')
            ->select(
                DB::raw('DATE(serial_keys.last_activation_at) as date'),
                DB::raw('SUM(current_activations) as activations')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->mapWithKeys(function ($item) {
                return [Carbon::parse($item->date)->format('d/m') => $item->activations ?? 0];
            });

        // Répartition par statut
        $statusDistribution = $tenant->serialKeys()
            ->select('serial_keys.status', DB::raw('COUNT(*) as count'))
            ->groupBy('serial_keys.status')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->status => $item->count];
            });

        return [
            'licenses_over_time' => $licensesOverTime,
            'activations_over_time' => $activationsOverTime,
            'status_distribution' => $statusDistribution,
            'period' => $period
        ];
    }

    /**
     * Obtenir le top des projets par activations
     */
    private function getTopProjects($tenant)
    {
        return $tenant->projects()
            ->withCount(['serialKeys as total_licenses'])
            ->withSum('serialKeys as total_activations', 'current_activations')
            ->orderByDesc('total_activations')
            ->limit(10)
            ->get()
            ->map(function ($project) {
                return [
                    'name' => $project->name,
                    'total_licenses' => $project->total_licenses ?? 0,
                    'total_activations' => $project->total_activations ?? 0,
                    'status' => $project->status,
                ];
            });
    }

    /**
     * Obtenir la tendance des activations
     */
    private function getActivationsTrend($tenant, $period)
    {
        $currentPeriod = $tenant->serialKeys()
            ->where('serial_keys.last_activation_at', '>=', now()->subDays($period))
            ->sum('current_activations') ?? 0;

        $previousPeriod = $tenant->serialKeys()
            ->where('serial_keys.last_activation_at', '>=', now()->subDays($period * 2))
            ->where('serial_keys.last_activation_at', '<', now()->subDays($period))
            ->sum('current_activations') ?? 0;

        $percentage = $previousPeriod > 0 
            ? round((($currentPeriod - $previousPeriod) / $previousPeriod) * 100, 1)
            : 0;

        return [
            'current' => $currentPeriod,
            'previous' => $previousPeriod,
            'percentage' => $percentage,
            'trend' => $percentage >= 0 ? 'up' : 'down'
        ];
    }

    /**
     * Obtenir la répartition par type de licence
     */
    private function getLicenseTypeDistribution($tenant)
    {
        return $tenant->serialKeys()
            ->select('serial_keys.licence_type', DB::raw('COUNT(*) as count'))
            ->groupBy('serial_keys.licence_type')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->licence_type => $item->count];
            });
    }

    /**
     * Obtenir les licences expirant bientôt
     */
    private function getExpiringLicenses($tenant)
    {
        return $tenant->serialKeys()
            ->with('project')
            ->where('serial_keys.expires_at', '<=', now()->addDays(30))
            ->where('serial_keys.expires_at', '>', now())
            ->orderBy('serial_keys.expires_at')
            ->limit(10)
            ->get()
            ->map(function ($license) {
                return [
                    'serial_key' => $license->serial_key,
                    'project_name' => $license->project->name ?? 'N/A',
                    'expires_at' => $license->expires_at,
                    'days_left' => now()->diffInDays($license->expires_at, false),
                    'status' => $license->status,
                ];
            });
    }

    /**
     * Obtenir les données pour les licences
     */
    private function getLicensesData($period)
    {
        $client = Auth::guard('client')->user();
        $tenant = $client->tenant;
        $startDate = now()->subDays($period);

        return $tenant->serialKeys()
            ->where('serial_keys.created_at', '>=', $startDate)
            ->select(
                DB::raw('DATE(serial_keys.created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->date => $item->count];
            });
    }

    /**
     * Obtenir les données pour les activations
     */
    private function getActivationsData($period)
    {
        $client = Auth::guard('client')->user();
        $tenant = $client->tenant;
        $startDate = now()->subDays($period);

        return $tenant->serialKeys()
            ->where('serial_keys.last_activation_at', '>=', $startDate)
            ->whereNotNull('serial_keys.last_activation_at')
            ->select(
                DB::raw('DATE(serial_keys.last_activation_at) as date'),
                DB::raw('SUM(current_activations) as activations')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->date => $item->activations ?? 0];
            });
    }

    /**
     * Obtenir les données pour les projets
     */
    private function getProjectsData($period)
    {
        $client = Auth::guard('client')->user();
        $tenant = $client->tenant;
        $startDate = now()->subDays($period);

        return $tenant->projects()
            ->where('projects.created_at', '>=', $startDate)
            ->select(
                DB::raw('DATE(projects.created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->date => $item->count];
            });
    }

    /**
     * Obtenir les données pour l'export
     */
    private function getExportData($period)
    {
        $client = Auth::guard('client')->user();
        $tenant = $client->tenant;
        $startDate = now()->subDays($period);

        return [
            'general_stats' => $this->getGeneralStats($tenant),
            'licenses' => $tenant->serialKeys()
                ->with('project')
                ->where('serial_keys.created_at', '>=', $startDate)
                ->get()
                ->map(function ($license) {
                    return [
                        'serial_key' => $license->serial_key,
                        'project' => $license->project->name ?? 'N/A',
                        'type' => $license->licence_type ?? 'Standard',
                        'status' => $license->status,
                        'max_activations' => $license->max_activations ?? 0,
                        'current_activations' => $license->current_activations ?? 0,
                        'created_at' => $license->created_at->format('d/m/Y H:i'),
                        'expires_at' => $license->expires_at ? $license->expires_at->format('d/m/Y H:i') : 'Jamais',
                    ];
                }),
            'projects' => $tenant->projects()
                ->withCount('serialKeys')
                ->get()
                ->map(function ($project) {
                    return [
                        'name' => $project->name,
                        'status' => $project->status,
                        'licenses_count' => $project->serial_keys_count,
                        'created_at' => $project->created_at->format('d/m/Y H:i'),
                    ];
                }),
        ];
    }

    /**
     * Exporter en JSON
     */
    private function exportJson($data)
    {
        $filename = 'analytics_export_' . date('Y-m-d_H-i-s') . '.json';

        return response()->json($data)
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Exporter en CSV
     */
    private function exportCsv($data)
    {
        $filename = 'analytics_export_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');

            // Statistiques générales
            fputcsv($file, ['=== STATISTIQUES GÉNÉRALES ===']);
            foreach ($data['general_stats'] as $key => $value) {
                fputcsv($file, [ucfirst(str_replace('_', ' ', $key)), $value]);
            }

            fputcsv($file, []); // Ligne vide

            // Licences
            fputcsv($file, ['=== LICENCES ===']);
            fputcsv($file, [
                'Clé', 'Projet', 'Type', 'Statut', 'Max Activations', 
                'Activations actuelles', 'Créée le', 'Expire le'
            ]);

            foreach ($data['licenses'] as $license) {
                fputcsv($file, [
                    $license['serial_key'],
                    $license['project'],
                    $license['type'],
                    $license['status'],
                    $license['max_activations'],
                    $license['current_activations'],
                    $license['created_at'],
                    $license['expires_at']
                ]);
            }

            fputcsv($file, []); // Ligne vide

            // Projets
            fputcsv($file, ['=== PROJETS ===']);
            fputcsv($file, ['Nom', 'Statut', 'Nb Licences', 'Créé le']);

            foreach ($data['projects'] as $project) {
                fputcsv($file, [
                    $project['name'],
                    $project['status'],
                    $project['licenses_count'],
                    $project['created_at']
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Obtenir l'activité récente
     */
    private function getRecentActivity($tenant)
    {
        // Simuler des activités récentes
        return collect([
            [
                'type' => 'license_created',
                'description' => 'Nouvelle licence créée',
                'date' => now()->subHours(2)->format('d/m/Y H:i')
            ],
            [
                'type' => 'license_validated',
                'description' => 'Licence validée',
                'date' => now()->subHours(5)->format('d/m/Y H:i')
            ]
        ]);
    }

    /**
     * Obtenir les top licences
     */
    private function getTopLicenses($tenant)
    {
        return $tenant->serialKeys()
            ->with('project')
            ->where('current_activations', '>', 0)
            ->orderBy('current_activations', 'desc')
            ->limit(5)
            ->get()
            ->map(function($license) {
                return [
                    'name' => $license->name ?? $license->serial_key,
                    'project' => $license->project->name ?? 'N/A',
                    'validations' => $license->current_activations ?? 0
                ];
            });
    }
} 