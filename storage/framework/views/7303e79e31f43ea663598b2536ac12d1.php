<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title'); ?> - <?php echo e(config('app.name')); ?></title>
    
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

        .card {
            background: white;
            border: none;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
            overflow: hidden;
        }
        
        .card:hover {
            box-shadow: var(--card-shadow-hover);
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

        .bg-primary {
            background: var(--primary-gradient) !important;
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
                    <a class="nav-link <?php echo e(request()->routeIs('client.dashboard') ? 'active' : ''); ?>" href="<?php echo e(route('client.dashboard')); ?>">
                        <i class="fas fa-tachometer-alt"></i>
                        Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('client.projects.*') ? 'active' : ''); ?>" href="<?php echo e(route('client.projects.index')); ?>">
                        <i class="fas fa-folder"></i>
                        Projets
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('client.licenses.*') ? 'active' : ''); ?>" href="<?php echo e(route('client.licenses.index')); ?>">
                        <i class="fas fa-key"></i>
                        Licences
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('client.analytics.*') ? 'active' : ''); ?>" href="<?php echo e(route('client.analytics.index')); ?>">
                        <i class="fas fa-chart-line"></i>
                        Analytics
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('client.support.*') ? 'active' : ''); ?>" href="<?php echo e(route('client.support.index')); ?>">
                        <i class="fas fa-life-ring"></i>
                        Support
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('client.billing.*') ? 'active' : ''); ?>" href="<?php echo e(route('client.billing.index')); ?>">
                        <i class="fas fa-credit-card"></i>
                        Facturation
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('client.settings.*') ? 'active' : ''); ?>" href="<?php echo e(route('client.settings.index')); ?>">
                        <i class="fas fa-cog"></i>
                        Paramètres
                    </a>
                </li>
            </ul>
        </nav>

        <div class="mt-auto p-3">
            <?php if(auth()->guard('client')->check()): ?>
            <div class="d-flex align-items-center mb-3">
                <div class="avatar-sm bg-white bg-opacity-20 rounded-circle d-flex align-items-center justify-content-center me-3">
                    <i class="fas fa-user"></i>
                </div>
                <div>
                    <div class="fw-semibold"><?php echo e(auth('client')->user()->name); ?></div>
                    <small class="opacity-75"><?php echo e(auth('client')->user()->email); ?></small>
                </div>
            </div>

            <?php if($subscription = auth('client')->user()->tenant->subscriptions()->latest()->first()): ?>
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
            <?php else: ?>
            <div class="d-flex align-items-center mb-3">
                <div class="avatar-sm bg-white bg-opacity-20 rounded-circle d-flex align-items-center justify-content-center me-3">
                    <i class="fas fa-user"></i>
                </div>
                <div>
                    <div class="fw-semibold">Utilisateur Test</div>
                    <small class="opacity-75">test@example.com</small>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Main Content -->
    <div class="content-wrapper">
        <?php echo $__env->yieldContent('content'); ?>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html><?php /**PATH R:\Adev\200  -  test\AdminLicence-4.5.1\resources\views\layouts\client.blade.php ENDPATH**/ ?>