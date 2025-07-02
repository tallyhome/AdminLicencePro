<?php $__env->startSection('title', 'Tableau de bord'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    <!-- En-tête avec le titre -->
    <div class="card bg-primary text-white mb-4">
        <div class="card-body p-4">
            <h4 class="mb-2">Tableau de bord</h4>
            <p class="mb-0">Bienvenue<?php echo e($client ? ', ' . $client->first_name : ''); ?> ! Voici un aperçu de votre activité.</p>
        </div>
    </div>

    

    <!-- Statistiques principales -->
    <div class="row g-4 mb-4">
        <!-- Projets -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card-stats">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="stat-icon bg-primary">
                            <i class="fas fa-folder"></i>
                        </div>
                        <div class="text-end">
                            <h3 class="mb-1"><?php echo e($usageStats['projects']['count']); ?></h3>
                            <p class="text-muted mb-0"><?php echo e($usageStats['projects']['text']); ?></p>
                        </div>
                    </div>
                    <h6 class="mb-2">Projets</h6>
                    <?php if($usageStats['projects']['limit'] !== 'Illimité'): ?>
                        <div class="progress" style="height: 4px;">
                            <div class="progress-bar bg-<?php echo e($usageStats['projects']['status']); ?>" 
                                 role="progressbar" 
                                 style="width: <?php echo e($usageStats['projects']['percentage']); ?>%">
                            </div>
                        </div>
                        <small class="text-<?php echo e($usageStats['projects']['status']); ?>">
                            <?php echo e($usageStats['projects']['percentage']); ?>% utilisé
                        </small>
                    <?php else: ?>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-infinity text-primary me-1"></i>
                            <small class="text-primary">Projets illimités</small>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Licences -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card-stats">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="stat-icon bg-success">
                            <i class="fas fa-key"></i>
                        </div>
                        <div class="text-end">
                            <h3 class="mb-1"><?php echo e($usageStats['licenses']['count']); ?></h3>
                            <p class="text-muted mb-0"><?php echo e($usageStats['licenses']['text']); ?></p>
                        </div>
                    </div>
                    <h6 class="mb-2">Licences</h6>
                    <?php if($usageStats['licenses']['limit'] !== 'Illimité'): ?>
                        <div class="progress" style="height: 4px;">
                            <div class="progress-bar bg-<?php echo e($usageStats['licenses']['status']); ?>" 
                                 role="progressbar" 
                                 style="width: <?php echo e($usageStats['licenses']['percentage']); ?>%">
                            </div>
                        </div>
                        <small class="text-<?php echo e($usageStats['licenses']['status']); ?>">
                            <?php echo e($usageStats['licenses']['percentage']); ?>% utilisé
                        </small>
                    <?php else: ?>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-infinity text-success me-1"></i>
                            <small class="text-success">Licences illimitées</small>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Licences Actives -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card-stats">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="stat-icon bg-info">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="text-end">
                            <h3 class="mb-1"><?php echo e($usageStats['active_licenses']['count']); ?></h3>
                            <p class="text-muted mb-0"><?php echo e($usageStats['active_licenses']['text']); ?></p>
                        </div>
                    </div>
                    <h6 class="mb-2">Licences Actives</h6>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-arrow-up text-info me-1"></i>
                        <small class="text-info">En cours d'utilisation</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activations -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card-stats">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="stat-icon bg-warning">
                            <i class="fas fa-bolt"></i>
                        </div>
                        <div class="text-end">
                            <h3 class="mb-1"><?php echo e($usageStats['total_activations']['count']); ?></h3>
                            <p class="text-muted mb-0"><?php echo e($usageStats['total_activations']['text']); ?></p>
                        </div>
                    </div>
                    <h6 class="mb-2">Activations</h6>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-sync text-warning me-1"></i>
                        <small class="text-warning">Mises à jour en temps réel</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Graphique d'évolution -->
        <div class="col-12 col-xl-8">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="card-title mb-0">Évolution des Activations</h5>
                        <div class="btn-group">
                            <button type="button" class="btn btn-outline-primary btn-sm" data-period="7">7j</button>
                            <button type="button" class="btn btn-outline-primary btn-sm active" data-period="30">30j</button>
                            <button type="button" class="btn btn-outline-primary btn-sm" data-period="90">90j</button>
                        </div>
                    </div>
                    <div class="chart-container" style="height: 300px;">
                        <canvas id="licensesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activité Récente -->
        <div class="col-12 col-xl-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="card-title mb-0">Activité Récente</h5>
                    </div>

                    <?php $__empty_1 = true; $__currentLoopData = $recentActivity; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="activity-item mb-3">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <div class="bg-<?php echo e($activity['color']); ?> rounded-circle p-2 text-white">
                                        <i class="<?php echo e($activity['icon']); ?>"></i>
                                    </div>
                                </div>
                                <div>
                                    <h6 class="mb-1">
                                        <a href="<?php echo e($activity['url']); ?>" class="text-decoration-none">
                                            <?php echo e($activity['title']); ?>

                                        </a>
                                    </h6>
                                    <p class="mb-0 text-muted"><?php echo e($activity['description']); ?></p>
                                    <small class="text-muted"><?php echo e($activity['date']->diffForHumans()); ?></small>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="text-center py-4">
                            <i class="fas fa-clock fa-3x text-muted mb-3"></i>
                            <h6>Aucune activité récente</h6>
                            <p class="text-muted mb-0">Vos activités apparaîtront ici</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('licensesChart').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($chartsData['labels'] ?? [], 15, 512) ?>,
            datasets: [{
                label: 'Activations',
                data: <?php echo json_encode($chartsData['data'] ?? [], 15, 512) ?>,
                borderColor: '#4e73df',
                backgroundColor: 'rgba(78, 115, 223, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        drawBorder: false
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Gestion des boutons de période
    document.querySelectorAll('[data-period]').forEach(button => {
        button.addEventListener('click', function() {
            const period = this.dataset.period;
            
            // Mettre à jour l'état actif des boutons
            document.querySelectorAll('[data-period]').forEach(btn => {
                btn.classList.remove('active');
            });
            this.classList.add('active');

            // Charger les nouvelles données
            fetch(`/client/dashboard/chart-data?period=${period}`)
                .then(response => response.json())
                .then(data => {
                    chart.data.labels = data.labels;
                    chart.data.datasets[0].data = data.data;
                    chart.update();
                });
        });
    });
});
</script>
<?php $__env->stopPush(); ?> 
<?php echo $__env->make('layouts.client', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\Adev\200  -  test\AdminLicence-4.5.1\resources\views\client\dashboard-simple.blade.php ENDPATH**/ ?>