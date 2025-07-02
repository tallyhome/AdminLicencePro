<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Dashboard - <?php echo e(config('app.name')); ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        :root {
            --sidebar-width: 280px;
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --card-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            --card-shadow-hover: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        body {
            background-color: #f8f9fa;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: var(--primary-gradient);
            transition: all 0.3s ease;
            z-index: 1000;
            overflow-y: auto;
        }
        
        .sidebar::-webkit-scrollbar {
            width: 4px;
        }
        
        .sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }
        
        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 2px;
        }
        
        .content-wrapper {
            margin-left: var(--sidebar-width);
            transition: all 0.3s ease;
            min-height: 100vh;
        }
        
        .sidebar-brand {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .sidebar-nav {
            padding: 1rem 0;
        }
        
        .nav-link {
            color: rgba(255, 255, 255, 0.8) !important;
            padding: 0.75rem 1.5rem;
            margin: 0.25rem 0;
            border-radius: 0;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
        }
        
        .nav-link:hover,
        .nav-link.active {
            color: white !important;
            background: rgba(255, 255, 255, 0.1);
            transform: translateX(5px);
        }
        
        .nav-link i {
            width: 20px;
            margin-right: 0.75rem;
        }

        .card-stats {
            background: white;
            border: none;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
            overflow: hidden;
        }
        
        .card-stats:hover {
            transform: translateY(-5px);
            box-shadow: var(--card-shadow-hover);
        }
        
        .card-stats .card-body {
            padding: 1.5rem;
        }
        
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
        }
        
        .progress-circle {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: white;
            font-size: 0.8rem;
        }
        
        .notification-card {
            border: none;
            border-radius: 8px;
            border-left: 4px solid;
            margin-bottom: 1rem;
            box-shadow: var(--card-shadow);
        }
        
        .notification-warning {
            border-left-color: #ffc107;
            background: linear-gradient(90deg, #fff8e1 0%, #ffffff 100%);
        }
        
        .notification-danger {
            border-left-color: #dc3545;
            background: linear-gradient(90deg, #ffebee 0%, #ffffff 100%);
        }
        
        .notification-info {
            border-left-color: #0dcaf0;
            background: linear-gradient(90deg, #e1f5fe 0%, #ffffff 100%);
        }
        
        .notification-success {
            border-left-color: #198754;
            background: linear-gradient(90deg, #e8f5e8 0%, #ffffff 100%);
        }

        .chart-container {
            position: relative;
            height: 300px;
        }

        .activity-item {
            padding: 1rem;
            border-bottom: 1px solid #e9ecef;
            transition: background-color 0.3s ease;
        }

        .activity-item:hover {
            background-color: #f8f9fa;
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            background: #f8f9fa;
        }

        .btn-gradient {
            background: var(--primary-gradient);
            border: none;
            color: white;
            transition: all 0.3s ease;
        }

        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            color: white;
        }

        .welcome-card {
            background: var(--primary-gradient);
            color: white;
            border: none;
            border-radius: 12px;
        }

        .table-modern {
            border-radius: 8px;
            overflow: hidden;
            box-shadow: var(--card-shadow);
        }

        .table-modern th {
            background-color: #f8f9fa;
            border: none;
            font-weight: 600;
            color: #495057;
        }

        .table-modern td {
            border: none;
            vertical-align: middle;
        }

        .badge-status {
            padding: 0.5rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .content-wrapper {
                margin-left: 0;
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar text-white">
        <div class="sidebar-brand">
            <h4 class="mb-0"><?php echo e(config('app.name')); ?></h4>
            <small class="opacity-75">Espace Client</small>
        </div>
        
        <nav class="sidebar-nav">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link active" href="<?php echo e(route('client.dashboard')); ?>">
                        <i class="fas fa-tachometer-alt"></i>
                        Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo e(route('client.projects.index')); ?>">
                        <i class="fas fa-folder"></i>
                        Projets
                        <span class="badge bg-light text-dark ms-auto"><?php echo e($usageStats['projects']['count']); ?></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo e(route('client.licenses.index')); ?>">
                        <i class="fas fa-key"></i>
                        Licences
                        <span class="badge bg-light text-dark ms-auto"><?php echo e($usageStats['licenses']['count']); ?></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo e(route('client.analytics.index')); ?>">
                        <i class="fas fa-chart-line"></i>
                        Analytics
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo e(route('client.support.index')); ?>">
                        <i class="fas fa-life-ring"></i>
                        Support
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="fas fa-credit-card"></i>
                        Facturation
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo e(route('client.settings.index')); ?>">
                        <i class="fas fa-cog"></i>
                        Paramètres
                    </a>
                </li>
            </ul>
            
            <div class="mt-auto p-3">
                <div class="d-flex align-items-center mb-3">
                    <div class="avatar-sm bg-white bg-opacity-20 rounded-circle d-flex align-items-center justify-content-center me-3">
                        <i class="fas fa-user"></i>
                    </div>
                    <div>
                        <div class="fw-semibold"><?php echo e($client->name); ?></div>
                        <small class="opacity-75"><?php echo e($client->email); ?></small>
                    </div>
                </div>
                
                <?php if($subscription): ?>
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <small>Plan <?php echo e($subscription->plan->name ?? 'Inconnu'); ?></small>
                        <span class="badge bg-<?php echo e($subscription->status === 'active' ? 'success' : ($subscription->status === 'trial' ? 'info' : 'warning')); ?>">
                            <?php echo e(ucfirst($subscription->status)); ?>

                        </span>
                    </div>
                    <?php if($subscription->ends_at): ?>
                    <small class="opacity-75">
                        Expire le <?php echo e($subscription->ends_at->format('d/m/Y')); ?>

                    </small>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
                
                <form method="POST" action="<?php echo e(route('client.logout')); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-outline-light btn-sm w-100">
                        <i class="fas fa-sign-out-alt me-2"></i>
                        Déconnexion
                    </button>
                </form>
            </div>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="content-wrapper">
        <div class="container-fluid p-4">
            <!-- Header -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="welcome-card card">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h2 class="mb-2">Bienvenue, <?php echo e($client->name); ?> ! 👋</h2>
                                    <p class="mb-0 opacity-90">
                                        Voici un aperçu de votre activité pour <?php echo e($tenant->name); ?>

                                    </p>
                                </div>
                                <div class="text-end">
                                    <div class="h5 mb-1"><?php echo e(now()->format('d M Y')); ?></div>
                                    <small class="opacity-75"><?php echo e(now()->format('H:i')); ?></small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notifications -->
            <?php if(!empty($notifications)): ?>
            <div class="row mb-4">
                <div class="col-12">
                    <?php $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="notification-card notification-<?php echo e($notification['type']); ?> card">
                        <div class="card-body d-flex align-items-center">
                            <i class="<?php echo e($notification['icon']); ?> me-3 fa-lg"></i>
                            <div class="flex-grow-1">
                                <h6 class="mb-1 fw-semibold"><?php echo e($notification['title']); ?></h6>
                                <p class="mb-0 text-muted"><?php echo e($notification['message']); ?></p>
                            </div>
                            <?php if(isset($notification['action'])): ?>
                            <a href="<?php echo e($notification['action']['url']); ?>" class="btn btn-sm btn-primary">
                                <?php echo e($notification['action']['text']); ?>

                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card-stats card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <h6 class="text-muted mb-2 fw-semibold">Projets</h6>
                                    <h3 class="mb-1"><?php echo e($usageStats['projects']['count']); ?></h3>
                                    <?php if($usageStats['projects']['limit'] > 0): ?>
                                    <small class="text-muted">sur <?php echo e($usageStats['projects']['limit']); ?> autorisés</small>
                                    <?php else: ?>
                                    <small class="text-success">Illimité</small>
                                    <?php endif; ?>
                                </div>
                                <div class="stat-icon" style="background: linear-gradient(45deg, #4e73df, #224abe);">
                                    <i class="fas fa-folder"></i>
                                </div>
                            </div>
                            <?php if($usageStats['projects']['limit'] > 0): ?>
                            <div class="mt-3">
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar bg-primary" 
                                         style="width: <?php echo e($usageStats['projects']['percentage']); ?>%"></div>
                                </div>
                                <small class="text-muted"><?php echo e($usageStats['projects']['percentage']); ?>% utilisé</small>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card-stats card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <h6 class="text-muted mb-2 fw-semibold">Licences</h6>
                                    <h3 class="mb-1"><?php echo e($usageStats['licenses']['count']); ?></h3>
                                    <?php if($usageStats['licenses']['limit'] > 0): ?>
                                    <small class="text-muted">sur <?php echo e($usageStats['licenses']['limit']); ?> autorisées</small>
                                    <?php else: ?>
                                    <small class="text-success">Illimité</small>
                                    <?php endif; ?>
                                </div>
                                <div class="stat-icon" style="background: linear-gradient(45deg, #1cc88a, #13855c);">
                                    <i class="fas fa-key"></i>
                                </div>
                            </div>
                            <?php if($usageStats['licenses']['limit'] > 0): ?>
                            <div class="mt-3">
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar bg-success" 
                                         style="width: <?php echo e($usageStats['licenses']['percentage']); ?>%"></div>
                                </div>
                                <small class="text-muted"><?php echo e($usageStats['licenses']['percentage']); ?>% utilisé</small>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card-stats card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <h6 class="text-muted mb-2 fw-semibold">Licences Actives</h6>
                                    <h3 class="mb-1"><?php echo e($usageStats['active_licenses']); ?></h3>
                                    <small class="text-success">
                                        <i class="fas fa-arrow-up me-1"></i>
                                        Opérationnelles
                                    </small>
                                </div>
                                <div class="stat-icon" style="background: linear-gradient(45deg, #36b9cc, #258391);">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card-stats card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <h6 class="text-muted mb-2 fw-semibold">Activations</h6>
                                    <h3 class="mb-1"><?php echo e($usageStats['total_activations']); ?></h3>
                                    <small class="text-info">
                                        <i class="fas fa-sync me-1"></i>
                                        Total
                                    </small>
                                </div>
                                <div class="stat-icon" style="background: linear-gradient(45deg, #f6c23e, #dda20a);">
                                    <i class="fas fa-rocket"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts and Activity -->
            <div class="row mb-4">
                <div class="col-xl-8 col-lg-7">
                    <div class="card card-stats">
                        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 fw-semibold text-primary">
                                <i class="fas fa-chart-area me-2"></i>
                                Évolution des Licences
                            </h6>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-primary dropdown-toggle" 
                                        type="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-calendar me-1"></i>
                                    Période
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#" onclick="updateChart(7)">7 jours</a></li>
                                    <li><a class="dropdown-item" href="#" onclick="updateChart(30)">30 jours</a></li>
                                    <li><a class="dropdown-item" href="#" onclick="updateChart(90)">90 jours</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="licensesChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-lg-5">
                    <div class="card card-stats h-100">
                        <div class="card-header bg-white py-3">
                            <h6 class="m-0 fw-semibold text-primary">
                                <i class="fas fa-clock me-2"></i>
                                Activité Récente
                            </h6>
                        </div>
                        <div class="card-body p-0">
                            <?php $__empty_1 = true; $__currentLoopData = $recentActivity; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="activity-item d-flex align-items-center">
                                <div class="activity-icon">
                                    <i class="<?php echo e($activity['icon']); ?>"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-semibold"><?php echo e($activity['title']); ?></div>
                                    <small class="text-muted"><?php echo e($activity['description']); ?></small>
                                    <div class="small text-muted">
                                        <?php echo e($activity['date']->diffForHumans()); ?>

                                    </div>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="text-center py-4">
                                <i class="fas fa-history fa-2x text-muted mb-3"></i>
                                <p class="text-muted mb-0">Aucune activité récente</p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Projects and Licenses -->
            <div class="row">
                <div class="col-xl-6">
                    <div class="card card-stats">
                        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 fw-semibold text-primary">
                                <i class="fas fa-folder me-2"></i>
                                Projets Récents
                            </h6>
                                                         <a href="<?php echo e(route('client.projects.create')); ?>" class="btn btn-sm btn-gradient">
                                 <i class="fas fa-plus me-1"></i>
                                 Nouveau projet
                             </a>
                        </div>
                        <div class="card-body p-0">
                            <?php $__empty_1 = true; $__currentLoopData = $recentProjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="activity-item d-flex align-items-center">
                                <div class="activity-icon">
                                    <i class="fas fa-folder text-primary"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-semibold"><?php echo e($project->name); ?></div>
                                    <small class="text-muted">
                                        <?php echo e($project->serialKeys->count()); ?> licence(s) • 
                                        Créé <?php echo e($project->created_at->diffForHumans()); ?>

                                    </small>
                                </div>
                                <span class="badge badge-status bg-<?php echo e($project->status === 'active' ? 'success' : 'secondary'); ?>">
                                    <?php echo e(ucfirst($project->status)); ?>

                                </span>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="text-center py-4">
                                <i class="fas fa-folder-open fa-2x text-muted mb-3"></i>
                                <p class="text-muted mb-2">Aucun projet pour le moment</p>
                                                                 <a href="<?php echo e(route('client.projects.create')); ?>" class="btn btn-sm btn-gradient">
                                     Créer votre premier projet
                                 </a>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="col-xl-6">
                    <div class="card card-stats">
                        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 fw-semibold text-primary">
                                <i class="fas fa-key me-2"></i>
                                Licences Récentes
                            </h6>
                                                         <a href="<?php echo e(route('client.licenses.create')); ?>" class="btn btn-sm btn-gradient">
                                 <i class="fas fa-plus me-1"></i>
                                 Nouvelle licence
                             </a>
                        </div>
                        <div class="card-body p-0">
                            <?php $__empty_1 = true; $__currentLoopData = $recentLicenses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $license): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="activity-item d-flex align-items-center">
                                <div class="activity-icon">
                                    <i class="fas fa-key text-success"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-semibold"><?php echo e($license->serial_key); ?></div>
                                    <small class="text-muted">
                                        <?php echo e($license->project->name ?? 'Projet inconnu'); ?> • 
                                        <?php echo e($license->created_at->diffForHumans()); ?>

                                    </small>
                                </div>
                                <span class="badge badge-status bg-<?php echo e($license->status === 'active' ? 'success' : ($license->status === 'inactive' ? 'secondary' : 'warning')); ?>">
                                    <?php echo e(ucfirst($license->status)); ?>

                                </span>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="text-center py-4">
                                <i class="fas fa-key fa-2x text-muted mb-3"></i>
                                <p class="text-muted mb-2">Aucune licence générée</p>
                                                                 <a href="<?php echo e(route('client.licenses.create')); ?>" class="btn btn-sm btn-gradient">
                                     Générer votre première licence
                                 </a>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Configuration Chart.js
        Chart.defaults.font.family = 'Inter, sans-serif';
        Chart.defaults.color = '#6c757d';
        
        // Graphique des licences
        const ctx = document.getElementById('licensesChart').getContext('2d');
        const licensesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($chartsData['labels'] ?? []); ?>,
                datasets: [{
                    label: 'Licences créées',
                    data: <?php echo json_encode($chartsData['data'] ?? []); ?>,
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#667eea',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8
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
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            maxTicksLimit: 7
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            stepSize: 1
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                }
            }
        });

        // Fonction pour mettre à jour le graphique
        function updateChart(period) {
            fetch(`<?php echo e(route('client.dashboard')); ?>/chart-data?period=${period}`)
                .then(response => response.json())
                .then(data => {
                    licensesChart.data.labels = Object.keys(data.licenses_over_time);
                    licensesChart.data.datasets[0].data = Object.values(data.licenses_over_time);
                    licensesChart.update();
                })
                .catch(error => console.error('Erreur:', error));
        }

        // Auto-refresh des données toutes les 5 minutes
        setInterval(() => {
            updateChart(<?php echo e($chartsData['period']); ?>);
        }, 300000);
    </script>
</body>
</html> <?php /**PATH R:\Adev\200  -  test\AdminLicence-4.5.1\resources\views\client\dashboard.blade.php ENDPATH**/ ?>