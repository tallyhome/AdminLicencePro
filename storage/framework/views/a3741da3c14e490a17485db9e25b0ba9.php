<?php $__env->startSection('title', t('dashboard.title')); ?>

<?php $__env->startSection('styles'); ?>
<style>
    .card-link {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }
    .card-link:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }
    .card-link .card {
        transition: border-color 0.2s ease-in-out;
    }
    .card-link:hover .card {
        border-color: #007bff;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <?php if(session('license_warning')): ?>
    <div class="alert alert-warning alert-dismissible fade show mb-4" role="alert">
        <strong><i class="fas fa-exclamation-triangle"></i> <?php echo e(session('license_warning')); ?></strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <?php endif; ?>

    <!-- Statistiques principales -->
    <div class="row">
        <div class="col-xl col-lg-4 col-md-6 mb-4">
            <a href="<?php echo e(route('admin.serial-keys.index')); ?>" class="text-decoration-none card-link">
                <div class="card border-left-primary shadow h-100 py-2 dashboard-stat-card">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    <?php echo e(t('dashboard.total_keys')); ?></div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($stats['total_keys']); ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-key fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-xl col-lg-4 col-md-6 mb-4">
            <a href="<?php echo e(route('admin.serial-keys.index', ['status' => 'active'])); ?>" class="text-decoration-none card-link">
                <div class="card border-left-success shadow h-100 py-2 dashboard-stat-card">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    <?php echo e(t('dashboard.active_keys')); ?></div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($stats['active_keys']); ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-xl col-lg-4 col-md-6 mb-4">
            <a href="<?php echo e(route('admin.serial-keys.index', ['used' => 'true'])); ?>" class="text-decoration-none card-link">
                <div class="card border-left-info shadow h-100 py-2 dashboard-stat-card">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    <?php echo e(t('dashboard.used_keys')); ?></div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($stats['used_keys']); ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-laptop fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-xl col-lg-6 col-md-6 mb-4">
            <a href="<?php echo e(route('admin.serial-keys.index', ['status' => 'suspended'])); ?>" class="text-decoration-none card-link">
                <div class="card border-left-warning shadow h-100 py-2 dashboard-stat-card">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    <?php echo e(t('dashboard.suspended_keys')); ?></div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($stats['suspended_keys']); ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-pause-circle fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-xl col-lg-6 col-md-6 mb-4">
            <a href="<?php echo e(route('admin.serial-keys.index', ['status' => 'revoked'])); ?>" class="text-decoration-none card-link">
                <div class="card border-left-danger shadow h-100 py-2 dashboard-stat-card">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                    <?php echo e(t('dashboard.revoked_keys')); ?></div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($stats['revoked_keys']); ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-ban fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Statistiques Licences Single vs Multi -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-pie"></i> Répartition des Licences Single/Multi
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Licences Single -->
                        <div class="col-md-6">
                            <div class="card border-left-primary h-100">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                <i class="fas fa-user"></i> Licences Single
                                            </div>
                                            <div class="h4 mb-2 font-weight-bold text-gray-800"><?php echo e($licenceStats['single_licences']); ?></div>
                                            <div class="row">
                                                <div class="col-6">
                                                    <small class="text-success">
                                                        <i class="fas fa-check"></i> Actives: <?php echo e($licenceStats['single_active']); ?>

                                                    </small>
                                                </div>
                                                <div class="col-6">
                                                    <small class="text-info">
                                                        <i class="fas fa-laptop"></i> Utilisées: <?php echo e($licenceStats['single_used']); ?>

                                                    </small>
                                                </div>
                                            </div>
                                            <div class="mt-2">
                                                <div class="progress" style="height: 8px;">
                                                    <div class="progress-bar bg-primary" style="width: <?php echo e($licenceStats['single_utilization_rate']); ?>%"></div>
                                                </div>
                                                <small class="text-muted">Taux d'utilisation: <?php echo e($licenceStats['single_utilization_rate']); ?>%</small>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-user fa-3x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Licences Multi -->
                        <div class="col-md-6">
                            <div class="card border-left-warning h-100">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                <i class="fas fa-users"></i> Licences Multi
                                            </div>
                                            <div class="h4 mb-2 font-weight-bold text-gray-800"><?php echo e($licenceStats['multi_licences']); ?></div>
                                            <div class="row">
                                                <div class="col-6">
                                                    <small class="text-success">
                                                        <i class="fas fa-check"></i> Actives: <?php echo e($licenceStats['multi_active']); ?>

                                                    </small>
                                                </div>
                                                <div class="col-6">
                                                    <small class="text-info">
                                                        <i class="fas fa-hashtag"></i> Slots: <?php echo e($licenceStats['multi_accounts_used']); ?>/<?php echo e($licenceStats['multi_accounts_total']); ?>

                                                    </small>
                                                </div>
                                            </div>
                                            <div class="mt-2">
                                                <div class="progress" style="height: 8px;">
                                                    <div class="progress-bar bg-warning" style="width: <?php echo e($licenceStats['multi_utilization_rate']); ?>%"></div>
                                                </div>
                                                <small class="text-muted">Taux d'utilisation: <?php echo e($licenceStats['multi_utilization_rate']); ?>%</small>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-users fa-3x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Licences Multi les plus utilisées -->
    <?php if(count($topMultiLicences) > 0): ?>
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-trophy"></i> Top 5 Licences Multi les plus utilisées
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Clé de série</th>
                                    <th>Projet</th>
                                    <th>Utilisation</th>
                                    <th>Taux</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $topMultiLicences; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $licence): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><code><?php echo e($licence->serial_key); ?></code></td>
                                    <td><?php echo e($licence->project->name); ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="me-2"><?php echo e($licence->used_accounts); ?>/<?php echo e($licence->max_accounts); ?></span>
                                            <div class="progress flex-grow-1" style="height: 6px; min-width: 100px;">
                                                <div class="progress-bar <?php echo e($licence->used_accounts >= $licence->max_accounts ? 'bg-danger' : ($licence->used_accounts > $licence->max_accounts * 0.8 ? 'bg-warning' : 'bg-success')); ?>" 
                                                     style="width: <?php echo e($licence->max_accounts > 0 ? ($licence->used_accounts / $licence->max_accounts) * 100 : 0); ?>%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?php echo e($licence->max_accounts > 0 ? (($licence->used_accounts / $licence->max_accounts) >= 1 ? 'danger' : (($licence->used_accounts / $licence->max_accounts) > 0.8 ? 'warning' : 'success')) : 'secondary'); ?>">
                                            <?php echo e($licence->max_accounts > 0 ? round(($licence->used_accounts / $licence->max_accounts) * 100) : 0); ?>%
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?php echo e($licence->status === 'active' ? 'success' : ($licence->status === 'suspended' ? 'warning' : 'danger')); ?>">
                                            <?php echo e(ucfirst($licence->status)); ?>

                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- <?php echo e(t('dashboard.keys_usage_by_project')); ?> -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary"><?php echo e(t('dashboard.keys_usage_by_project')); ?></h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th><?php echo e(t('projects.project')); ?></th>
                                    <th><?php echo e(t('dashboard.total_keys')); ?></th>
                                    <th><?php echo e(t('dashboard.used_keys')); ?></th>
                                    <th><?php echo e(t('dashboard.available_keys')); ?></th>
                                    <th><?php echo e(t('common.status')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $projectStats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($project->name); ?></td>
                                    <td><?php echo e($project->serial_keys_count); ?></td>
                                    <td><?php echo e($project->used_keys_count); ?></td>
                                    <td><?php echo e($project->available_keys_count); ?></td>
                                    <td>
                                        <?php if($project->serial_keys_count > 0): ?>
                                            <div class="progress mb-2" style="height: 20px;">
                                                <?php
                                                    $usedPercentage = ($project->used_keys_count / $project->serial_keys_count) * 100;
                                                    $availablePercentage = 100 - $usedPercentage;
                                                    $progressClass = $project->is_running_low ? 'bg-danger' : 'bg-success';
                                                ?>
                                                <div class="progress-bar bg-primary" role="progressbar" style="width: <?php echo e($usedPercentage); ?>%" 
                                                     aria-valuenow="<?php echo e($usedPercentage); ?>" aria-valuemin="0" aria-valuemax="100">
                                                    <?php echo e(round($usedPercentage)); ?>% <?php echo e(t('dashboard.used')); ?>

                                                </div>
                                                <div class="progress-bar <?php echo e($progressClass); ?>" role="progressbar" style="width: <?php echo e($availablePercentage); ?>%" 
                                                     aria-valuenow="<?php echo e($availablePercentage); ?>" aria-valuemin="0" aria-valuemax="100">
                                                    <?php echo e(round($availablePercentage)); ?>% <?php echo e(t('dashboard.available')); ?>

                                                </div>
                                            </div>
                                            <?php if($project->is_running_low): ?>
                                                <span class="badge bg-danger"><?php echo e(t('dashboard.low_stock')); ?></span>
                                            <?php else: ?>
                                                <span class="badge bg-success"><?php echo e(t('dashboard.sufficient_stock')); ?></span>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="badge bg-secondary"><?php echo e(t('dashboard.no_keys')); ?></span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- <?php echo e(t('dashboard.charts')); ?> -->
    <div class="row">
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><?php echo e(t('dashboard.keys_usage_last_30_days')); ?></h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="usageChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><?php echo e(t('dashboard.distribution_by_project')); ?></h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4">
                        <canvas id="projectChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- <?php echo e(t('dashboard.recent_keys')); ?> -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary"><?php echo e(t('dashboard.recent_keys')); ?></h6>
            <div>
                <select class="form-select" id="perPageSelect" onchange="window.location.href='<?php echo e(route('admin.dashboard')); ?>?per_page=' + this.value">
                    <option value="10" <?php echo e($validPerPage == 10 ? 'selected' : ''); ?>><?php echo e(t('pagination.per_page', ['number' => 10])); ?></option>
                    <option value="25" <?php echo e($validPerPage == 25 ? 'selected' : ''); ?>><?php echo e(t('pagination.per_page', ['number' => 25])); ?></option>
                    <option value="50" <?php echo e($validPerPage == 50 ? 'selected' : ''); ?>><?php echo e(t('pagination.per_page', ['number' => 50])); ?></option>
                    <option value="100" <?php echo e($validPerPage == 100 ? 'selected' : ''); ?>><?php echo e(t('pagination.per_page', ['number' => 100])); ?></option>
                    <option value="500" <?php echo e($validPerPage == 500 ? 'selected' : ''); ?>><?php echo e(t('pagination.per_page', ['number' => 500])); ?></option>
                    <option value="1000" <?php echo e($validPerPage == 1000 ? 'selected' : ''); ?>><?php echo e(t('pagination.per_page', ['number' => 1000])); ?></option>
                </select>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th><?php echo e(t('common.serial_keys')); ?></th>
                            <th><?php echo e(t('common.projects')); ?></th>
                            <th><?php echo e(t('common.status')); ?></th>
                            <th><?php echo e(t('common.date')); ?></th>
                            <th><?php echo e(t('common.actions')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $recentKeys; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($key->serial_key); ?></td>
                            <td><?php echo e($key->project->name); ?></td>
                            <td>
                                <span class="badge-status badge-<?php echo e($key->status); ?>">
                                    <?php echo e(t('common.' . $key->status)); ?>

                                </span>
                            </td>
                            <td><?php echo e($key->created_at->format('d/m/Y H:i')); ?></td>
                            <td>
                                <a href="<?php echo e(route('admin.serial-keys.show', $key)); ?>" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            <div class="pagination-tailwind">
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // <?php echo e(t('dashboard.usage_chart')); ?>

    const usageCtx = document.getElementById('usageChart').getContext('2d');
    new Chart(usageCtx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($usageStats->pluck('date')); ?>,
            datasets: [{
                label: '<?php echo e(t("dashboard.usage")); ?>',
                data: <?php echo json_encode($usageStats->pluck('count')); ?>,
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // <?php echo e(t('dashboard.project_distribution_chart')); ?>

    const projectCtx = document.getElementById('projectChart').getContext('2d');
    new Chart(projectCtx, {
        type: 'pie',
        data: {
            labels: <?php echo json_encode($projectStats->pluck('name')); ?>,
            datasets: [{
                data: <?php echo json_encode($projectStats->pluck('serial_keys_count')); ?>,
                backgroundColor: [
                    'rgb(255, 99, 132)',
                    'rgb(54, 162, 235)',
                    'rgb(255, 205, 86)',
                    'rgb(75, 192, 192)',
                    'rgb(153, 102, 255)'
                ]
            }]
        },
        options: {
            responsive: true
        }
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\Adev\200  -  test\AdminLicence-4.5.1\resources\views\admin\dashboard.blade.php ENDPATH**/ ?>