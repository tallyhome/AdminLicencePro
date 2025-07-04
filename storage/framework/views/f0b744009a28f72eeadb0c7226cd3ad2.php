<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Dashboard - <?php echo e(config('app.name')); ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 260px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: all 0.3s;
            z-index: 1000;
        }
        
        .content-wrapper {
            margin-left: 260px;
            transition: all 0.3s;
        }
        
        .card-stats {
            background: white;
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
            transition: transform 0.3s ease;
        }
        
        .card-stats:hover {
            transform: translateY(-5px);
        }
        
        .progress-circle {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: white;
        }
        
        .notification-card {
            border-left: 4px solid;
            border-radius: 0 8px 8px 0;
        }
        
        .notification-warning {
            border-left-color: #ffc107;
            background-color: #fff8e1;
        }
        
        .notification-danger {
            border-left-color: #dc3545;
            background-color: #ffebee;
        }
        
        .notification-info {
            border-left-color: #17a2b8;
            background-color: #e1f5fe;
        }
        
        .notification-success {
            border-left-color: #28a745;
            background-color: #e8f5e8;
        }
    </style>
</head>
<body class="bg-light">
    <!-- Sidebar -->
    <div class="sidebar text-white">
        <div class="p-4">
            <h4 class="mb-0"><?php echo e(config('app.name')); ?></h4>
            <small>Espace Client</small>
        </div>
        
        <nav class="mt-4">
            <ul class="nav nav-pills flex-column">
                <li class="nav-item">
                    <a class="nav-link active text-white" href="<?php echo e(route('client.dashboard')); ?>">
                        <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white-50" href="<?php echo e(route('client.projects.index')); ?>">
                        <i class="fas fa-folder me-2"></i> Projets
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white-50" href="<?php echo e(route('client.licenses.index')); ?>">
                        <i class="fas fa-key me-2"></i> Licences
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white-50" href="<?php echo e(route('client.support.index')); ?>">
                        <i class="fas fa-life-ring me-2"></i> Support
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white-50" href="<?php echo e(route('subscription.plans')); ?>">
                        <i class="fas fa-credit-card me-2"></i> Abonnement
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white-50" href="<?php echo e(route('client.settings.index')); ?>">
                        <i class="fas fa-cog me-2"></i> Paramètres
                    </a>
                </li>
            </ul>
        </nav>
        
        <div class="position-absolute bottom-0 w-100 p-4">
            <div class="d-flex align-items-center">
                <div class="me-3">
                    <div class="bg-white bg-opacity-20 rounded-circle p-2">
                        <i class="fas fa-user"></i>
                    </div>
                </div>
                <div class="flex-grow-1">
                    <div class="fw-bold"><?php echo e($client->name); ?></div>
                    <small class="text-white-50"><?php echo e($client->company_name); ?></small>
                </div>
                <form method="POST" action="<?php echo e(route('client.logout')); ?>" class="d-inline">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-link text-white-50 p-0">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="content-wrapper">
        <div class="container-fluid p-4">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0">Dashboard</h1>
                    <p class="text-muted">Bienvenue <?php echo e($client->name); ?>, voici un aperçu de votre compte</p>
                </div>
                <?php if($subscription): ?>
                    <div class="text-end">
                        <span class="badge bg-<?php echo e($subscription->status === 'active' ? 'success' : ($subscription->status === 'trial' ? 'info' : 'warning')); ?> me-2">
                            <?php echo e(ucfirst($subscription->status)); ?>

                        </span>
                        <small class="text-muted"><?php echo e($subscription->plan->name ?? 'Plan inconnu'); ?></small>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Notifications -->
            <?php if(!empty($notifications)): ?>
                <div class="row mb-4">
                    <div class="col-12">
                        <?php $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="alert notification-card notification-<?php echo e($notification['type']); ?> d-flex align-items-center mb-3">
                                <i class="<?php echo e($notification['icon']); ?> me-3 fa-lg"></i>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1"><?php echo e($notification['title']); ?></h6>
                                    <p class="mb-0"><?php echo e($notification['message']); ?></p>
                                </div>
                                <?php if(isset($notification['action'])): ?>
                                    <a href="<?php echo e($notification['action']['url']); ?>" class="btn btn-sm btn-outline-primary">
                                        <?php echo e($notification['action']['text']); ?>

                                    </a>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card card-stats h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-2">Projets</h6>
                                    <h3 class="mb-0"><?php echo e($usageStats['projects']['count']); ?></h3>
                                    <?php if($usageStats['projects']['limit'] > 0): ?>
                                        <small class="text-muted">/ <?php echo e($usageStats['projects']['limit']); ?></small>
                                    <?php endif; ?>
                                </div>
                                <div class="progress-circle" style="background: linear-gradient(45deg, #4e73df, #224abe);">
                                    <?php echo e($usageStats['projects']['percentage']); ?>%
                                </div>
                            </div>
                            <?php if($usageStats['projects']['limit'] > 0): ?>
                                <div class="mt-3">
                                    <div class="progress" style="height: 4px;">
                                        <div class="progress-bar" style="width: <?php echo e($usageStats['projects']['percentage']); ?>%"></div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card card-stats h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-2">Licences</h6>
                                    <h3 class="mb-0"><?php echo e($usageStats['licenses']['count']); ?></h3>
                                    <?php if($usageStats['licenses']['limit'] > 0): ?>
                                        <small class="text-muted">/ <?php echo e($usageStats['licenses']['limit']); ?></small>
                                    <?php endif; ?>
                                </div>
                                <div class="progress-circle" style="background: linear-gradient(45deg, #1cc88a, #13855c);">
                                    <?php echo e($usageStats['licenses']['percentage']); ?>%
                                </div>
                            </div>
                            <?php if($usageStats['licenses']['limit'] > 0): ?>
                                <div class="mt-3">
                                    <div class="progress" style="height: 4px;">
                                        <div class="progress-bar bg-success" style="width: <?php echo e($usageStats['licenses']['percentage']); ?>%"></div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card card-stats h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-2">Licences Actives</h6>
                                    <h3 class="mb-0"><?php echo e($usageStats['licenses']['active']); ?></h3>
                                    <small class="text-success">
                                        <i class="fas fa-check-circle me-1"></i>
                                        Fonctionnelles
                                    </small>
                                </div>
                                <div class="progress-circle" style="background: linear-gradient(45deg, #36b9cc, #258391);">
                                    <i class="fas fa-key fa-lg"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card card-stats h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-2">Support</h6>
                                    <h3 class="mb-0"><?php echo e($usageStats['support_tickets']['open']); ?></h3>
                                    <small class="text-muted">Tickets ouverts</small>
                                </div>
                                <div class="progress-circle" style="background: linear-gradient(45deg, #f6c23e, #dda20a);">
                                    <i class="fas fa-life-ring fa-lg"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts and Recent Activity -->
            <div class="row">
                <div class="col-xl-8 col-lg-7">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold text-primary">Activité des 30 derniers jours</h6>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    Période
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#" data-period="7">7 jours</a></li>
                                    <li><a class="dropdown-item" href="#" data-period="30">30 jours</a></li>
                                    <li><a class="dropdown-item" href="#" data-period="90">90 jours</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas id="activityChart" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-lg-5">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Projets Récents</h6>
                        </div>
                        <div class="card-body">
                            <?php $__empty_1 = true; $__currentLoopData = $recentProjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <div class="d-flex align-items-center mb-3">
                                    <div class="me-3">
                                        <div class="bg-primary bg-opacity-10 text-primary rounded p-2">
                                            <i class="fas fa-folder"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0"><?php echo e($project->name); ?></h6>
                                        <small class="text-muted"><?php echo e($project->created_at->diffForHumans()); ?></small>
                                    </div>
                                    <div>
                                        <span class="badge bg-<?php echo e($project->status === 'active' ? 'success' : 'secondary'); ?>">
                                            <?php echo e($project->status); ?>

                                        </span>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <div class="text-center text-muted py-4">
                                    <i class="fas fa-folder-open fa-3x mb-3 opacity-50"></i>
                                    <p class="mb-0">Aucun projet récent</p>
                                    <a href="<?php echo e(route('client.projects.create')); ?>" class="btn btn-primary btn-sm mt-2">
                                        Créer un projet
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Licenses -->
            <div class="row">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold text-primary">Licences Récentes</h6>
                            <a href="<?php echo e(route('client.licenses.index')); ?>" class="btn btn-sm btn-primary">
                                Voir toutes les licences
                            </a>
                        </div>
                        <div class="card-body">
                            <?php $__empty_1 = true; $__currentLoopData = $recentLicenses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $license): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <div class="row align-items-center mb-3 pb-3 <?php echo e(!$loop->last ? 'border-bottom' : ''); ?>">
                                    <div class="col-md-4">
                                        <h6 class="mb-0"><?php echo e($license->serial_key); ?></h6>
                                        <small class="text-muted"><?php echo e($license->project->name ?? 'Projet supprimé'); ?></small>
                                    </div>
                                    <div class="col-md-2">
                                        <span class="badge bg-<?php echo e($license->status === 'active' ? 'success' : 'secondary'); ?>">
                                            <?php echo e(ucfirst($license->status)); ?>

                                        </span>
                                    </div>
                                    <div class="col-md-2">
                                        <small class="text-muted">
                                            <?php echo e($license->current_activations); ?>/<?php echo e($license->max_activations); ?>

                                        </small>
                                    </div>
                                    <div class="col-md-2">
                                        <small class="text-muted">
                                            <?php if($license->expires_at): ?>
                                                Exp. <?php echo e($license->expires_at->format('d/m/Y')); ?>

                                            <?php else: ?>
                                                Permanent
                                            <?php endif; ?>
                                        </small>
                                    </div>
                                    <div class="col-md-2 text-end">
                                        <a href="<?php echo e(route('client.licenses.show', $license)); ?>" class="btn btn-sm btn-outline-primary">
                                            Détails
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <div class="text-center text-muted py-4">
                                    <i class="fas fa-key fa-3x mb-3 opacity-50"></i>
                                    <p class="mb-0">Aucune licence récente</p>
                                    <a href="<?php echo e(route('client.licenses.create')); ?>" class="btn btn-primary btn-sm mt-2">
                                        Créer une licence
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Initialiser le graphique d'activité
        const ctx = document.getElementById('activityChart').getContext('2d');
        let activityChart;

        function loadActivityChart(days = 30) {
            fetch(`<?php echo e(route('client.dashboard')); ?>/activity-chart?days=${days}`)
                .then(response => response.json())
                .then(data => {
                    if (activityChart) {
                        activityChart.destroy();
                    }
                    
                    activityChart = new Chart(ctx, {
                        type: 'line',
                        data: data,
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'top',
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        stepSize: 1
                                    }
                                }
                            }
                        }
                    });
                });
        }

        // Charger le graphique initial
        loadActivityChart();

        // Gérer les changements de période
        document.querySelectorAll('[data-period]').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const days = this.dataset.period;
                loadActivityChart(days);
            });
        });
    </script>
</body>
</html> <?php /**PATH R:\Adev\200  -  test\AdminLicence-4.5.1\resources\views\client\dashboard\index.blade.php ENDPATH**/ ?>