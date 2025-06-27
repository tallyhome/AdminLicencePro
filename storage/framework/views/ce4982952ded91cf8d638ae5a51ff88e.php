<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e(frontend('app_name')); ?> - Prévisualisation</title>
    <link rel="icon" type="image/x-icon" href="<?php echo e(frontend('favicon_url')); ?>">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- CSS personnalisé généré depuis les paramètres -->
    <?php if(\App\Helpers\FrontendHelper::getCustomCSSUrl()): ?>
        <link href="<?php echo e(\App\Helpers\FrontendHelper::getCustomCSSUrl()); ?>" rel="stylesheet">
    <?php endif; ?>
    
    <style>
        .hero-section {
            background: linear-gradient(135deg, <?php echo e(frontend('primary_color')); ?>22, <?php echo e(frontend('secondary_color')); ?>22);
            padding: 100px 0;
        }
        .feature-card {
            border-left: 4px solid <?php echo e(frontend('primary_color')); ?>;
            transition: transform 0.3s ease;
        }
        .feature-card:hover {
            transform: translateY(-5px);
        }
        .social-links a {
            color: <?php echo e(frontend('primary_color')); ?>;
            font-size: 1.5rem;
            margin: 0 10px;
            transition: color 0.3s ease;
        }
        .social-links a:hover {
            color: <?php echo e(frontend('secondary_color')); ?>;
        }
        .maintenance-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }
    </style>
</head>
<body>
    <?php if(frontend('maintenance_mode')): ?>
        <!-- Overlay de maintenance -->
        <div class="maintenance-overlay">
            <div class="text-center text-white">
                <i class="fas fa-tools fa-5x mb-4"></i>
                <h1 class="display-4">Mode Maintenance</h1>
                <p class="lead"><?php echo e(frontend('maintenance_message')); ?></p>
                <div class="mt-4">
                    <a href="<?php echo e(route('admin.settings.frontend.index')); ?>" class="btn btn-primary">
                        <i class="fas fa-cog"></i> Accéder aux paramètres
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="<?php echo e(frontend('logo_url')); ?>" alt="Logo" height="40" class="me-2">
                <div>
                    <strong><?php echo e(frontend('app_name')); ?></strong>
                    <?php if(frontend('app_tagline')): ?>
                        <br><small class="text-muted"><?php echo e(frontend('app_tagline')); ?></small>
                    <?php endif; ?>
                </div>
            </a>
            
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="<?php echo e(route('admin.settings.frontend.index')); ?>">
                    <i class="fas fa-edit"></i> Éditer
                </a>
            </div>
        </div>
    </nav>

    <?php if(frontend('show_hero_section')): ?>
        <!-- Section Hero -->
        <section class="hero-section">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <h1 class="display-4 fw-bold mb-4"><?php echo e(frontend('hero_title')); ?></h1>
                        <?php if(frontend('hero_subtitle')): ?>
                            <p class="lead mb-4"><?php echo e(frontend('hero_subtitle')); ?></p>
                        <?php endif; ?>
                        <div class="d-flex gap-3">
                            <button class="btn btn-primary btn-lg">
                                <i class="fas fa-rocket"></i> Commencer
                            </button>
                            <button class="btn btn-outline-secondary btn-lg">
                                <i class="fas fa-info-circle"></i> En savoir plus
                            </button>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <img src="<?php echo e(frontend('hero_image_url')); ?>" alt="Hero Image" class="img-fluid rounded shadow">
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <?php if(frontend('show_features_section')): ?>
        <!-- Section Fonctionnalités -->
        <section class="py-5">
            <div class="container">
                <div class="row">
                    <div class="col-12 text-center mb-5">
                        <h2 class="display-5 fw-bold">Fonctionnalités</h2>
                        <p class="lead text-muted">Découvrez tout ce que <?php echo e(frontend('app_name')); ?> peut faire pour vous</p>
                    </div>
                </div>
                
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="card feature-card h-100 border-0 shadow-sm">
                            <div class="card-body p-4">
                                <div class="mb-3">
                                    <i class="fas fa-key fa-2x" style="color: <?php echo e(frontend('primary_color')); ?>"></i>
                                </div>
                                <h5 class="card-title">Gestion des Licences</h5>
                                <p class="card-text text-muted">Gérez facilement toutes vos licences logicielles en un seul endroit.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card feature-card h-100 border-0 shadow-sm">
                            <div class="card-body p-4">
                                <div class="mb-3">
                                    <i class="fas fa-shield-alt fa-2x" style="color: <?php echo e(frontend('success_color')); ?>"></i>
                                </div>
                                <h5 class="card-title">Sécurisé</h5>
                                <p class="card-text text-muted">Vos données sont protégées par des mesures de sécurité avancées.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card feature-card h-100 border-0 shadow-sm">
                            <div class="card-body p-4">
                                <div class="mb-3">
                                    <i class="fas fa-chart-line fa-2x" style="color: <?php echo e(frontend('secondary_color')); ?>"></i>
                                </div>
                                <h5 class="card-title">Statistiques</h5>
                                <p class="card-text text-muted">Suivez l'utilisation de vos licences avec des rapports détaillés.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <?php if(frontend('show_contact_section')): ?>
        <!-- Section Contact -->
        <section class="py-5 bg-light">
            <div class="container">
                <div class="row">
                    <div class="col-12 text-center mb-5">
                        <h2 class="display-5 fw-bold">Contactez-nous</h2>
                        <p class="lead text-muted">Nous sommes là pour vous aider</p>
                    </div>
                </div>
                
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="row g-4">
                            <?php if(frontend('contact_email')): ?>
                                <div class="col-md-4 text-center">
                                    <div class="mb-3">
                                        <i class="fas fa-envelope fa-2x" style="color: <?php echo e(frontend('primary_color')); ?>"></i>
                                    </div>
                                    <h5>Email</h5>
                                    <p class="text-muted"><?php echo e(frontend('contact_email')); ?></p>
                                </div>
                            <?php endif; ?>
                            
                            <?php if(frontend('contact_phone')): ?>
                                <div class="col-md-4 text-center">
                                    <div class="mb-3">
                                        <i class="fas fa-phone fa-2x" style="color: <?php echo e(frontend('success_color')); ?>"></i>
                                    </div>
                                    <h5>Téléphone</h5>
                                    <p class="text-muted"><?php echo e(frontend('contact_phone')); ?></p>
                                </div>
                            <?php endif; ?>
                            
                            <?php if(frontend('contact_address')): ?>
                                <div class="col-md-4 text-center">
                                    <div class="mb-3">
                                        <i class="fas fa-map-marker-alt fa-2x" style="color: <?php echo e(frontend('danger_color')); ?>"></i>
                                    </div>
                                    <h5>Adresse</h5>
                                    <p class="text-muted"><?php echo e(frontend('contact_address')); ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0"><?php echo e(frontend('footer_text')); ?></p>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="social-links">
                        <?php if(frontend('social_facebook')): ?>
                            <a href="<?php echo e(frontend('social_facebook')); ?>" target="_blank" rel="noopener">
                                <i class="fab fa-facebook"></i>
                            </a>
                        <?php endif; ?>
                        <?php if(frontend('social_twitter')): ?>
                            <a href="<?php echo e(frontend('social_twitter')); ?>" target="_blank" rel="noopener">
                                <i class="fab fa-twitter"></i>
                            </a>
                        <?php endif; ?>
                        <?php if(frontend('social_linkedin')): ?>
                            <a href="<?php echo e(frontend('social_linkedin')); ?>" target="_blank" rel="noopener">
                                <i class="fab fa-linkedin"></i>
                            </a>
                        <?php endif; ?>
                        <?php if(frontend('social_github')): ?>
                            <a href="<?php echo e(frontend('social_github')); ?>" target="_blank" rel="noopener">
                                <i class="fab fa-github"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Badge de prévisualisation -->
    <div style="position: fixed; top: 20px; right: 20px; z-index: 1000;">
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="fas fa-eye"></i> <strong>Mode Prévisualisation</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
</body>
</html> <?php /**PATH R:\Adev\200  -  test\AdminLicence-4.5.1\resources\views\frontend\preview.blade.php ENDPATH**/ ?>