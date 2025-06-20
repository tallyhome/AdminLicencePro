<!DOCTYPE html>
<?php
use Illuminate\Support\Facades\Auth;
?>

<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(config('app.name', 'AdminLicence')); ?> - <?php echo $__env->yieldContent('title'); ?></title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom Styles -->
    <link rel="stylesheet" href="<?php echo e(asset('css/custom-pagination.css')); ?>">
    <style>
        body {
            font-family: 'Figtree', sans-serif;
        }
        #sidebar {
            min-height: 100vh;
            background-color: #212529;
            color: #fff;
            transition: all 0.3s;
        }
        #sidebar .nav-link {
            color: rgba(255, 255, 255, 0.75);
            padding: 0.75rem 1rem;
            transition: all 0.3s;
        }
        #sidebar .nav-link:hover {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.1);
        }
        #sidebar .nav-link.active {
            color: #fff;
            background-color: #0d6efd;
        }
        #sidebar .sidebar-heading {
            padding: 1rem;
            font-size: 1.2rem;
        }
        .content {
            width: 100%;
        }
        .dropdown-toggle::after {
            display: block;
            position: absolute;
            top: 50%;
            right: 20px;
            transform: translateY(-50%);
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div id="sidebar" class="col-md-3 col-lg-2 d-md-block">
            <div class="position-sticky">
                <div class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1">
                    <a href="<?php echo e(route('admin.dashboard')); ?>" class="text-decoration-none">
                        <span class="fs-4 text-white">AdminLicence</span>
                    </a>
                </div>
                <hr class="my-2">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="<?php echo e(route('admin.dashboard')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.dashboard') ? 'active' : ''); ?>">
                            <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo e(route('admin.projects.index')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.projects.*') ? 'active' : ''); ?>">
                            <i class="fas fa-project-diagram me-2"></i> <?php echo e(t('common.projects')); ?>

                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo e(route('admin.serial-keys.index')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.serial-keys.*') ? 'active' : ''); ?>">
                            <i class="fas fa-key me-2"></i> <?php echo e(t('common.serial_keys')); ?>

                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo e(route('admin.client-example')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.client-example') ? 'active' : ''); ?>">
                            <i class="fas fa-code me-2"></i> <?php echo e(t('common.api_documentation')); ?>

                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo e(route('admin.mail.settings')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.mail.*') ? 'active' : ''); ?>">
                            <i class="fas fa-envelope me-2"></i> <?php echo e(t('common.email')); ?>

                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo e(route('admin.translations.index')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.translations.*') ? 'active' : ''); ?>">
                            <i class="fas fa-language me-2"></i> <?php echo e(t('common.translations')); ?>

                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo e(route('admin.settings')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.settings*') ? 'active' : ''); ?>">
                            <i class="fas fa-cog me-2"></i> <?php echo e(t('common.settings')); ?>

                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo e(route('admin.version')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.version') ? 'active' : ''); ?>">
                            <i class="fas fa-info-circle me-2"></i> <?php echo e(t('common.version')); ?>

                        </a>
                    </li>
                </ul>
                <hr class="my-2">
                <div class="px-3 mb-3">
                    <form method="POST" action="<?php echo e(route('admin.logout')); ?>">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-outline-light w-100">
                            <i class="fas fa-sign-out-alt me-2"></i> <?php echo e(t('common.logout')); ?>

                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="content col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <!-- Top Navigation -->
            <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom mb-4">
                <div class="container-fluid">
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                            <!-- Language Selector -->
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-globe me-1"></i> <?php echo e(strtoupper(app()->getLocale())); ?>

                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <?php $__currentLoopData = config('app.available_locales'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $locale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li>
                                        <form method="POST" action="<?php echo e(route('admin.set.locale')); ?>">
                                            <?php echo csrf_field(); ?>
                                            <input type="hidden" name="locale" value="<?php echo e($locale); ?>">
                                            <button type="submit" class="dropdown-item <?php echo e(app()->getLocale() == $locale ? 'active' : ''); ?>">
                                                <?php echo e(strtoupper($locale)); ?>

                                            </button>
                                        </form>
                                    </li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </li>
                            <!-- User Menu -->
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-user-circle me-1"></i> <?php echo e(Auth::guard('admin')->user()->name ?? 'Admin'); ?>

                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                    <li>
                                        <a class="dropdown-item" href="<?php echo e(route('admin.settings')); ?>">
                                            <i class="fas fa-cog me-1"></i> <?php echo e(t('common.settings')); ?>

                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="<?php echo e(route('admin.logout')); ?>">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="dropdown-item">
                                                <i class="fas fa-sign-out-alt me-1"></i> <?php echo e(t('common.logout')); ?>

                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <!-- Flash Messages -->
            <?php if(session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo e(session('success')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo e(session('error')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <!-- Page Content -->
            <main>
                <?php echo $__env->yieldContent('content'); ?>
            </main>

            <!-- Footer -->
            <footer class="py-4 mt-auto border-top">
                <div class="container-fluid px-4">
                    <div class="d-flex justify-content-between align-items-center small">
                        <div>
                            &copy; <?php echo e(date('Y')); ?> <?php echo e(config('app.name')); ?>

                        </div>
                        <div>
                            <a href="<?php echo e(route('admin.version')); ?>" class="text-decoration-none text-muted">
                                Version <?php echo e(\App\Helpers\VersionHelper::getFullVersion()); ?>

                            </a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom Scripts -->
    <?php echo $__env->yieldContent('scripts'); ?>
    
    <script>
        // Auto-dismiss alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert-dismissible');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    const closeButton = alert.querySelector('.btn-close');
                    if (closeButton) {
                        closeButton.click();
                    }
                }, 5000);
            });
        });
    </script>
</body>
</html>
<?php /**PATH R:\Adev\200  -  test\AdminLicence-4.5.1\resources\views\layouts\sidebar-admin.blade.php ENDPATH**/ ?>