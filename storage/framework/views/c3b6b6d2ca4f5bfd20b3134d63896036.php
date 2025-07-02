

<?php $__env->startSection('title', 'Analytics'); ?>

<?php $__env->startSection('content'); ?>
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
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($stats['active_licenses']); ?></div>
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
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e(number_format($stats['validations_this_month'])); ?></div>
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
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($stats['active_projects']); ?></div>
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
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e(number_format($stats['success_rate'], 1)); ?>%</div>
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
                        <a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in">
                            <div class="dropdown-header">Période:</div>
                            <a class="dropdown-item" href="#" onclick="updateChart('7days')">7 derniers jours</a>
                            <a class="dropdown-item" href="#" onclick="updateChart('30days')">30 derniers jours</a>
                            <a class="dropdown-item" href="#" onclick="updateChart('90days')">90 derniers jours</a>
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
                        <?php $__currentLoopData = $chartData['projects']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <span class="mr-2">
                                <i class="fas fa-circle" style="color: <?php echo e($project['color']); ?>"></i> <?php echo e($project['name']); ?>

                            </span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                    <?php if(count($recentActivity) > 0): ?>
                        <?php $__currentLoopData = $recentActivity; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="d-flex align-items-center mb-3">
                                <div class="mr-3">
                                    <?php switch($activity['type']):
                                        case ('license_created'): ?>
                                            <i class="fas fa-plus-circle text-success"></i>
                                            <?php break; ?>
                                        <?php case ('license_validated'): ?>
                                            <i class="fas fa-check-circle text-primary"></i>
                                            <?php break; ?>
                                        <?php case ('license_expired'): ?>
                                            <i class="fas fa-exclamation-circle text-warning"></i>
                                            <?php break; ?>
                                        <?php default: ?>
                                            <i class="fas fa-info-circle text-info"></i>
                                    <?php endswitch; ?>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="small text-gray-500"><?php echo e($activity['date']); ?></div>
                                    <div><?php echo e($activity['description']); ?></div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        <div class="text-center py-3">
                            <i class="fas fa-clock fa-2x text-gray-300 mb-2"></i>
                            <p class="text-gray-500">Aucune activité récente</p>
                        </div>
                    <?php endif; ?>
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
                    <?php if(count($topLicenses) > 0): ?>
                        <?php $__currentLoopData = $topLicenses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $license): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div>
                                    <div class="font-weight-bold"><?php echo e($license['name']); ?></div>
                                    <div class="small text-gray-500"><?php echo e($license['project']); ?></div>
                                </div>
                                <div class="text-right">
                                    <div class="font-weight-bold text-primary"><?php echo e(number_format($license['validations'])); ?></div>
                                    <div class="small text-gray-500">validations</div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        <div class="text-center py-3">
                            <i class="fas fa-chart-bar fa-2x text-gray-300 mb-2"></i>
                            <p class="text-gray-500">Aucune donnée disponible</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Configuration des graphiques
const validationsCtx = document.getElementById('validationsChart').getContext('2d');
const validationsChart = new Chart(validationsCtx, {
    type: 'line',
    data: {
        labels: <?php echo json_encode($chartData['validations']['labels']); ?>,
        datasets: [{
            label: 'Validations',
            data: <?php echo json_encode($chartData['validations']['data']); ?>,
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
        }
    }
});

const projectsCtx = document.getElementById('projectsChart').getContext('2d');
const projectsChart = new Chart(projectsCtx, {
    type: 'doughnut',
    data: {
        labels: <?php echo json_encode(array_column($chartData['projects'], 'name')); ?>,
        datasets: [{
            data: <?php echo json_encode(array_column($chartData['projects'], 'value')); ?>,
            backgroundColor: <?php echo json_encode(array_column($chartData['projects'], 'color')); ?>

        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});

// Fonctions d'export
function exportData(format) {
    const url = `<?php echo e(route('client.analytics.export')); ?>?format=${format}`;
    window.location.href = url;
}

function updateChart(period) {
    // Mise à jour AJAX du graphique
    fetch(`<?php echo e(route('client.analytics.chart-data')); ?>?period=${period}`)
        .then(response => response.json())
        .then(data => {
            validationsChart.data.labels = data.labels;
            validationsChart.data.datasets[0].data = data.data;
            validationsChart.update();
        });
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('client.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\Adev\200  -  test\AdminLicence-4.5.1\resources\views\client\analytics\index.blade.php ENDPATH**/ ?>