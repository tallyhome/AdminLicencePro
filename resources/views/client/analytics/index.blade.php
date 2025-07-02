@extends('layouts.client')

@section('title', 'Analytics')

@section('content')
<div class="container-fluid">
    <!-- En-tête de page -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Analytics</h1>
        <div class="d-none d-sm-inline-block">
            <button class="btn btn-sm btn-primary shadow-sm" onclick="exportData('csv')">
                <i class="fas fa-download fa-sm text-white-50"></i> Export CSV
            </button>
            <button class="btn btn-sm btn-secondary shadow-sm ml-2" onclick="exportData('json')">
                <i class="fas fa-download fa-sm text-white-50"></i> Export JSON
            </button>
        </div>
    </div>

    <!-- Cartes de statistiques -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Licences Actives</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['active_licenses'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-key fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Validations Ce Mois</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['validations_this_month']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Projets Actifs</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['active_projects'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-folder fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Taux de Réussite</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['success_rate'], 1) }}%</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-percentage fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphiques -->
    <div class="row">
        <!-- Graphique des validations -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Validations de Licences</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown" 
                           aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in">
                            <div class="dropdown-header">Période:</div>
                            <a class="dropdown-item" href="#" onclick="updateChart('7days'); return false;">7 derniers jours</a>
                            <a class="dropdown-item" href="#" onclick="updateChart('30days'); return false;">30 derniers jours</a>
                            <a class="dropdown-item" href="#" onclick="updateChart('90days'); return false;">90 derniers jours</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="validationsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Graphique des projets -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Répartition par Projet</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="projectsChart"></canvas>
                    </div>
                    <div class="mt-4 text-center small">
                        @foreach($chartData['projects'] as $project)
                            <span class="mr-2">
                                <i class="fas fa-circle" style="color: {{ $project['color'] }}"></i> {{ $project['name'] }}
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau des activités récentes -->
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Activités Récentes</h6>
                </div>
                <div class="card-body">
                    @if(count($recentActivity) > 0)
                        @foreach($recentActivity as $activity)
                            <div class="d-flex align-items-center mb-3">
                                <div class="mr-3">
                                    @switch($activity['type'])
                                        @case('license_created')
                                            <i class="fas fa-plus-circle text-success"></i>
                                            @break
                                        @case('license_validated')
                                            <i class="fas fa-check-circle text-primary"></i>
                                            @break
                                        @case('license_expired')
                                            <i class="fas fa-exclamation-circle text-warning"></i>
                                            @break
                                        @default
                                            <i class="fas fa-info-circle text-info"></i>
                                    @endswitch
                                </div>
                                <div class="flex-grow-1">
                                    <div class="small text-gray-500">{{ $activity['date'] }}</div>
                                    <div>{{ $activity['description'] }}</div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-clock fa-2x text-gray-300 mb-2"></i>
                            <p class="text-gray-500">Aucune activité récente</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Top licences -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Licences les Plus Utilisées</h6>
                </div>
                <div class="card-body">
                    @if(count($topLicenses) > 0)
                        @foreach($topLicenses as $license)
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div>
                                    <div class="font-weight-bold">{{ $license['name'] }}</div>
                                    <div class="small text-gray-500">{{ $license['project'] }}</div>
                                </div>
                                <div class="text-right">
                                    <div class="font-weight-bold text-primary">{{ number_format($license['validations']) }}</div>
                                    <div class="small text-gray-500">validations</div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-chart-bar fa-2x text-gray-300 mb-2"></i>
                            <p class="text-gray-500">Aucune donnée disponible</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Configuration des graphiques
const validationsCtx = document.getElementById('validationsChart').getContext('2d');
const validationsChart = new Chart(validationsCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode($chartData['validations']['labels'] ?? []) !!},
        datasets: [{
            label: 'Validations',
            data: {!! json_encode($chartData['validations']['data'] ?? []) !!},
            borderColor: '#4e73df',
            backgroundColor: 'rgba(78, 115, 223, 0.1)',
            borderWidth: 2,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true
            }
        },
        plugins: {
            legend: {
                display: true
            }
        }
    }
});

// Graphique des projets avec gestion des données vides
const projectsData = {!! json_encode($chartData['projects'] ?? []) !!};
const projectsCtx = document.getElementById('projectsChart').getContext('2d');

let projectsChart;
if (projectsData.length > 0) {
    projectsChart = new Chart(projectsCtx, {
        type: 'doughnut',
        data: {
            labels: projectsData.map(p => p.name),
            datasets: [{
                data: projectsData.map(p => p.value),
                backgroundColor: projectsData.map(p => p.color)
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
} else {
    // Graphique vide avec message
    projectsChart = new Chart(projectsCtx, {
        type: 'doughnut',
        data: {
            labels: ['Aucune donnée'],
            datasets: [{
                data: [1],
                backgroundColor: ['#e3e6f0'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    enabled: false
                }
            },
            elements: {
                arc: {
                    borderWidth: 0
                }
            }
        },
        plugins: [{
            beforeDraw: function(chart) {
                const ctx = chart.ctx;
                const width = chart.width;
                const height = chart.height;
                
                ctx.restore();
                ctx.font = '16px Arial';
                ctx.fillStyle = '#858796';
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                ctx.fillText('Aucune donnée disponible', width / 2, height / 2);
                ctx.save();
            }
        }]
    });
}

// Fonctions d'export
function exportData(format) {
    const url = `{{ route('client.analytics.export') }}?format=${format}`;
    window.location.href = url;
}

function updateChart(period) {
    // Mise à jour AJAX du graphique
    fetch(`{{ route('client.analytics.chart-data') }}?period=${period}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                validationsChart.data.labels = data.labels;
                validationsChart.data.datasets[0].data = data.data;
                validationsChart.update();
            } else {
                console.error('Erreur lors de la mise à jour du graphique:', data.message);
            }
        })
        .catch(error => {
            console.error('Erreur de réseau:', error);
        });
}

// Initialisation Bootstrap dropdowns
$(document).ready(function() {
    $('.dropdown-toggle').dropdown();
});
</script>
@endpush
@endsection 