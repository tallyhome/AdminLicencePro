<?php
    // S'assurer que $settings existe avec des valeurs par défaut
    $settings = $settings ?? [
        'site_title' => 'AdminLicence',
        'site_tagline' => 'Système de gestion de licences ultra-sécurisé',
        'contact_email' => '',
        'contact_phone' => '',
        'footer_text' => '© 2025 AdminLicence. Solution sécurisée de gestion de licences.'
    ];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', ($settings['site_title'] ?? 'AdminLicence')); ?></title>
    <meta name="description" content="<?php echo $__env->yieldContent('description', $settings['site_tagline'] ?? 'Système de gestion de licences ultra-sécurisé'); ?>">
    
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #64748b;
            --accent-color: #059669;
            --font-family: 'Inter, sans-serif';
        }

        * {
            font-family: var(--font-family);
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: color-mix(in srgb, var(--primary-color) 85%, black);
            border-color: color-mix(in srgb, var(--primary-color) 85%, black);
        }

        .text-primary {
            color: var(--primary-color) !important;
        }

        .bg-primary {
            background-color: var(--primary-color) !important;
        }

        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }

        .hero-section {
            background: linear-gradient(135deg, var(--primary-color), color-mix(in srgb, var(--primary-color) 80%, var(--accent-color)));
            color: white;
            padding: 80px 0;
        }

        .feature-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
            border-radius: 12px;
            height: 100%;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .section-title {
            position: relative;
            margin-bottom: 3rem;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: var(--accent-color);
            border-radius: 2px;
        }

        .footer {
            background: #1a1a1a;
            color: #ffffff;
        }

        .security-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(5, 150, 105, 0.1);
            color: var(--accent-color);
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 500;
            margin-bottom: 20px;
        }

        .testimonial-card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(0, 0, 0, 0.05);
            height: 100%;
        }

        .faq-item {
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            margin-bottom: 1rem;
        }

        .faq-header {
            background: rgba(var(--primary-color), 0.05);
            padding: 20px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .faq-header:hover {
            background: rgba(var(--primary-color), 0.1);
        }

        @media (max-width: 768px) {
            .hero-section {
                padding: 60px 0;
            }
            
            .hero-section h1 {
                font-size: 2rem;
            }
        }
    </style>

    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="<?php echo e(route('frontend.home')); ?>">
                <i class="fas fa-shield-alt"></i>
                <?php echo e($settings['site_title'] ?? 'AdminLicence'); ?>

            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo e(route('frontend.home')); ?>">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo e(route('frontend.features')); ?>">Fonctionnalités</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo e(route('frontend.pricing')); ?>">Tarifs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo e(route('frontend.about')); ?>">À propos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo e(route('frontend.faqs')); ?>">FAQ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo e(route('frontend.contact')); ?>">Contact</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo e(route('frontend.support')); ?>">Support</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-primary text-white px-3 ms-2" href="<?php echo e(url('/admin')); ?>">
                            <i class="fas fa-sign-in-alt"></i> Admin
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <main style="margin-top: 76px;">
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <!-- Footer -->
    <footer class="footer py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h5 class="fw-bold mb-3">
                        <i class="fas fa-shield-alt text-primary"></i>
                        <?php echo e($settings['site_title'] ?? 'AdminLicence'); ?>

                    </h5>
                    <p class="text-light"><?php echo e($settings['site_tagline'] ?? 'Système de gestion de licences ultra-sécurisé'); ?></p>
                    <div class="security-badge">
                        <i class="fas fa-lock"></i>
                        <span>Chiffrement AES-256</span>
                    </div>
                </div>
                
                <div class="col-lg-2 col-md-6 mb-4">
                    <h6 class="fw-bold mb-3">Produit</h6>
                    <ul class="list-unstyled">
                        <li><a href="<?php echo e(route('frontend.home')); ?>" class="text-light text-decoration-none">Fonctionnalités</a></li>
                        <li><a href="<?php echo e(route('frontend.about')); ?>" class="text-light text-decoration-none">À propos</a></li>
                        <li><a href="<?php echo e(route('frontend.faqs')); ?>" class="text-light text-decoration-none">FAQ</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-2 col-md-6 mb-4">
                    <h6 class="fw-bold mb-3">Support</h6>
                    <ul class="list-unstyled">
                        <li><a href="<?php echo e(route('frontend.contact')); ?>" class="text-light text-decoration-none">Contact</a></li>
                        <li><a href="#" class="text-light text-decoration-none">Documentation</a></li>
                        <li><a href="#" class="text-light text-decoration-none">API</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-4 mb-4">
                    <h6 class="fw-bold mb-3">Contact</h6>
                    <?php if($settings['contact_email']): ?>
                        <p class="text-light">
                            <i class="fas fa-envelope"></i>
                            <?php echo e($settings['contact_email']); ?>

                        </p>
                    <?php endif; ?>
                    <?php if($settings['contact_phone']): ?>
                        <p class="text-light">
                            <i class="fas fa-phone"></i>
                            <?php echo e($settings['contact_phone']); ?>

                        </p>
                    <?php endif; ?>
                </div>
            </div>
            
            <hr class="my-4 opacity-25">
            
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="text-light mb-0">
                        <?php echo e($settings['footer_text'] ?? '© 2025 AdminLicence. Solution sécurisée de gestion de licences.'); ?>

                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    <small class="text-light opacity-75">
                        <i class="fas fa-shield-check text-success"></i>
                        Site sécurisé par AdminLicence
                    </small>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <?php if($isPreview ?? false): ?>
    <div class="position-fixed top-0 start-0 w-100 bg-warning text-center py-2 text-dark fw-bold" style="z-index: 9999;">
        <i class="fas fa-eye"></i> MODE PRÉVISUALISATION - 
        <a href="<?php echo e(url('/admin/cms')); ?>" class="text-dark">Retour à l'admin</a>
    </div>
    <?php endif; ?>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html> <?php /**PATH R:\Adev\200  -  test\AdminLicence-4.5.1\resources\views\frontend\templates\modern\layout.blade.php ENDPATH**/ ?>