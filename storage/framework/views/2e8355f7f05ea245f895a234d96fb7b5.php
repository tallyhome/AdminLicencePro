<?php $__env->startSection('title', 'CMS Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- En-tête principal -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                <div class="mb-2 mb-md-0">
                    <h1 class="h3 mb-1 d-flex align-items-center">
                        <i class="fas fa-palette text-primary me-2"></i>
                        Gestion CMS
                    </h1>
                    <p class="text-muted mb-0">Gérez le contenu et l'apparence de votre site</p>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <a href="<?php echo e(route('frontend.home')); ?>?preview=1" target="_blank" class="btn btn-success btn-sm">
                        <i class="fas fa-eye me-1"></i> Prévisualiser
                    </a>
                    <a href="<?php echo e(route('admin.cms.settings')); ?>" class="btn btn-primary btn-sm">
                        <i class="fas fa-cog me-1"></i> Paramètres
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques rectangles colorés -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="card h-100 border-0 text-white" style="background: linear-gradient(135deg, #4285f4 0%, #3367d6 100%);">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="me-3">
                        <i class="fas fa-star text-white" style="font-size: 2rem;"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="text-uppercase fw-semibold small">Fonctionnalités</div>
                        <div class="h4 mb-0 fw-bold"><?php echo e($stats['features'] ?? 0); ?></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card h-100 border-0 text-white" style="background: linear-gradient(135deg, #9c27b0 0%, #7b1fa2 100%);">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="me-3">
                        <i class="fas fa-comments text-white" style="font-size: 2rem;"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="text-uppercase fw-semibold small">Témoignages</div>
                        <div class="h4 mb-0 fw-bold"><?php echo e($stats['testimonials'] ?? 0); ?></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card h-100 border-0 text-white" style="background: linear-gradient(135deg, #34a853 0%, #2d7d32 100%);">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="me-3">
                        <i class="fas fa-question-circle text-white" style="font-size: 2rem;"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="text-uppercase fw-semibold small">FAQs</div>
                        <div class="h4 mb-0 fw-bold"><?php echo e($stats['faqs'] ?? 0); ?></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card h-100 border-0 text-white" style="background: linear-gradient(135deg, #ff9800 0%, #f57400 100%);">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="me-3">
                        <i class="fas fa-info-circle text-white" style="font-size: 2rem;"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="text-uppercase fw-semibold small">Sections À propos</div>
                        <div class="h4 mb-0 fw-bold"><?php echo e($stats['about_sections'] ?? 0); ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions rapides et Templates -->
    <div class="row g-4 mb-4">
        <!-- Gestion du contenu -->
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pb-0">
                    <h5 class="mb-0 d-flex align-items-center text-dark">
                        <i class="fas fa-edit text-primary me-2"></i>
                        Gestion du Contenu
                    </h5>
                </div>
                <div class="card-body pt-3">
                    <div class="row g-3">
                        <!-- Paramètres globaux -->
                        <div class="col-12 col-md-6 col-xl-3">
                            <div class="card h-100 border-0 hover-lift" style="background: linear-gradient(135deg, #e8f5e8 0%, #f0f8f0 100%);">
                                <div class="card-body p-4 text-center">
                                    <div class="mb-3">
                                        <i class="fas fa-cog text-success fs-1"></i>
                                    </div>
                                    <h6 class="card-title fw-bold mb-2 text-dark">Paramètres Globaux</h6>
                                    <p class="card-text text-muted small mb-3">Modifiez tous les textes, navigation, sections et footer</p>
                                    <a href="<?php echo e(route('admin.cms.settings')); ?>" class="btn btn-success">
                                        <i class="fas fa-cog me-1"></i> Configurer
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Fonctionnalités -->
                        <div class="col-12 col-md-6 col-xl-3">
                            <div class="card h-100 border-0 hover-lift" style="background: linear-gradient(135deg, #e3f2fd 0%, #f3e5f5 100%);">
                                <div class="card-body p-4 text-center">
                                    <div class="mb-3">
                                        <i class="fas fa-star text-primary fs-1"></i>
                                    </div>
                                    <h6 class="card-title fw-bold mb-2 text-dark">Fonctionnalités</h6>
                                    <p class="card-text text-muted small mb-3">Gérez les fonctionnalités affichées sur le site</p>
                                    <a href="<?php echo e(route('admin.cms.features.index')); ?>" class="btn btn-primary">
                                        <i class="fas fa-cog me-1"></i> Gérer
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Témoignages -->
                        <div class="col-12 col-md-6 col-xl-3">
                            <div class="card h-100 border-0 hover-lift" style="background: linear-gradient(135deg, #f3e5f5 0%, #fce4ec 100%);">
                                <div class="card-body p-4 text-center">
                                    <div class="mb-3">
                                        <i class="fas fa-comments text-info fs-1"></i>
                                    </div>
                                    <h6 class="card-title fw-bold mb-2 text-dark">Témoignages</h6>
                                    <p class="card-text text-muted small mb-3">Affichez les avis et témoignages clients</p>
                                    <a href="<?php echo e(route('admin.cms.testimonials.index')); ?>" class="btn btn-info">
                                        <i class="fas fa-cog me-1"></i> Gérer
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Questions Fréquentes -->
                        <div class="col-12 col-md-6 col-xl-3">
                            <div class="card h-100 border-0 hover-lift" style="background: linear-gradient(135deg, #fff3e0 0%, #fce4ec 100%);">
                                <div class="card-body p-4 text-center">
                                    <div class="mb-3">
                                        <i class="fas fa-question-circle text-warning fs-1"></i>
                                    </div>
                                    <h6 class="card-title fw-bold mb-2 text-dark">Questions Fréquentes</h6>
                                    <p class="card-text text-muted small mb-3">Créez et organisez vos FAQs</p>
                                    <a href="<?php echo e(route('admin.cms.faqs.index')); ?>" class="btn btn-warning">
                                        <i class="fas fa-cog me-1"></i> Gérer
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Sections À propos -->
                        <div class="col-12 col-md-6 col-xl-3">
                            <div class="card h-100 border-0 hover-lift" style="background: linear-gradient(135deg, #ffe0b2 0%, #ffcc80 100%);">
                                <div class="card-body p-4 text-center">
                                    <div class="mb-3">
                                        <i class="fas fa-info-circle text-primary fs-1"></i>
                                    </div>
                                    <h6 class="card-title fw-bold mb-2 text-dark">Sections À propos</h6>
                                    <p class="card-text text-muted small mb-3">Présentez votre entreprise et vos valeurs</p>
                                    <a href="<?php echo e(route('admin.cms.about.index')); ?>" class="btn btn-primary">
                                        <i class="fas fa-cog me-1"></i> Gérer
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Templates -->
                        <div class="col-12 col-md-6 col-xl-3">
                            <div class="card h-100 border-0 hover-lift" style="background: linear-gradient(135deg, #f0f0f0 0%, #e0e0e0 100%);">
                                <div class="card-body p-4 text-center">
                                    <div class="mb-3">
                                        <i class="fas fa-palette text-dark fs-1"></i>
                                    </div>
                                    <h6 class="card-title fw-bold mb-2 text-dark">Templates</h6>
                                    <p class="card-text text-muted small mb-3">Changez l'apparence de votre site</p>
                                    <a href="<?php echo e(route('admin.cms.templates')); ?>" class="btn btn-dark">
                                        <i class="fas fa-paint-brush me-1"></i> Changer
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Template et Actions -->
    <div class="row g-4">
        <!-- Template actuel -->
        <div class="col-12 col-lg-6 col-xl-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pb-0">
                    <h5 class="mb-0 d-flex align-items-center text-dark">
                        <i class="fas fa-paint-brush text-primary me-2"></i>
                        Template Actuel
                    </h5>
                </div>
                <div class="card-body pt-3">
                    <?php
                        $currentTemplateName = \App\Models\Setting::get('cms_current_template', 'modern');
                        $templateInfo = [
                            'modern' => [
                                'name' => 'Modern',
                                'description' => 'Design moderne et épuré avec des couleurs vives et une navigation intuitive',
                                'color' => 'primary',
                                'icon' => 'fas fa-rocket'
                            ],
                            'professional' => [
                                'name' => 'Professional', 
                                'description' => 'Design corporate et professionnel, idéal pour les entreprises',
                                'color' => 'dark',
                                'icon' => 'fas fa-briefcase'
                            ]
                        ];
                        $current = $templateInfo[$currentTemplateName] ?? $templateInfo['modern'];
                    ?>
                    
                    <div class="row align-items-center">
                        <div class="col-12 col-md-4 text-center mb-3 mb-md-0">
                            <div class="bg-<?php echo e($current['color']); ?> bg-gradient rounded d-flex align-items-center justify-content-center shadow-sm" style="height: 120px;">
                                <i class="<?php echo e($current['icon']); ?> fa-3x text-white"></i>
                            </div>
                        </div>
                        <div class="col-12 col-md-8">
                            <h5 class="fw-bold mb-2"><?php echo e($current['name']); ?></h5>
                            <p class="text-muted mb-3"><?php echo e($current['description']); ?></p>
                            <div class="d-flex gap-2 flex-wrap">
                                <a href="<?php echo e(route('frontend.home')); ?>?preview=1" target="_blank" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-eye me-1"></i> Prévisualiser
                                </a>
                                <a href="<?php echo e(route('admin.cms.templates')); ?>" class="btn btn-primary btn-sm">
                                    <i class="fas fa-exchange-alt me-1"></i> Changer Template
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions rapides -->
        <div class="col-12 col-lg-6 col-xl-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pb-0">
                    <h5 class="mb-0 d-flex align-items-center text-dark">
                        <i class="fas fa-bolt text-primary me-2"></i>
                        Actions Rapides
                    </h5>
                </div>
                <div class="card-body pt-3">
                    <div class="d-grid gap-2">
                        <a href="<?php echo e(route('admin.cms.settings')); ?>" class="btn btn-outline-primary btn-sm d-flex align-items-center">
                            <i class="fas fa-cogs me-2"></i>
                            Paramètres Généraux
                        </a>
                        <a href="<?php echo e(route('admin.cms.templates')); ?>" class="btn btn-outline-success btn-sm d-flex align-items-center">
                            <i class="fas fa-paint-brush me-2"></i>
                            Gestion Templates
                        </a>
                        <a href="<?php echo e(route('frontend.home')); ?>" target="_blank" class="btn btn-outline-info btn-sm d-flex align-items-center">
                            <i class="fas fa-eye me-2"></i>
                            Prévisualiser le Site
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Guide d'utilisation -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pb-0">
                    <h5 class="mb-0 d-flex align-items-center text-dark">
                        <i class="fas fa-graduation-cap text-success me-2"></i>
                        Guide d'utilisation CMS
                    </h5>
                </div>
                <div class="card-body pt-3">
                    <div class="row g-4">
                        <div class="col-md-4">
                            <a href="<?php echo e(route('admin.cms.settings')); ?>" class="text-decoration-none">
                                <div class="text-center p-3 border rounded hover-lift h-100">
                                    <i class="fas fa-cog fa-2x text-primary mb-3"></i>
                                    <h6 class="fw-semibold">1. Configurez les Paramètres</h6>
                                    <p class="text-muted small">Personnalisez tous les textes, navigation, sections et footer selon vos besoins</p>
                                    <div class="mt-2">
                                        <span class="btn btn-primary btn-sm">
                                            <i class="fas fa-arrow-right me-1"></i> Configurer
                                        </span>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4">
                            <div class="dropdown">
                                <div class="text-center p-3 border rounded hover-lift h-100 cursor-pointer" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-plus-circle fa-2x text-success mb-3"></i>
                                    <h6 class="fw-semibold">2. Ajoutez du Contenu</h6>
                                    <p class="text-muted small">Créez vos fonctionnalités, témoignages, FAQs et sections à propos</p>
                                    <div class="mt-2">
                                        <span class="btn btn-success btn-sm">
                                            <i class="fas fa-plus me-1"></i> Ajouter
                                        </span>
                                    </div>
                                </div>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="<?php echo e(route('admin.cms.features.index')); ?>">
                                        <i class="fas fa-star me-2 text-primary"></i> Fonctionnalités
                                    </a></li>
                                    <li><a class="dropdown-item" href="<?php echo e(route('admin.cms.testimonials.index')); ?>">
                                        <i class="fas fa-comments me-2 text-info"></i> Témoignages
                                    </a></li>
                                    <li><a class="dropdown-item" href="<?php echo e(route('admin.cms.faqs.index')); ?>">
                                        <i class="fas fa-question-circle me-2 text-warning"></i> FAQs
                                    </a></li>
                                    <li><a class="dropdown-item" href="<?php echo e(route('admin.cms.about.index')); ?>">
                                        <i class="fas fa-info-circle me-2 text-primary"></i> Sections À propos
                                    </a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <a href="<?php echo e(route('admin.cms.templates')); ?>" class="text-decoration-none">
                                <div class="text-center p-3 border rounded hover-lift h-100">
                                    <i class="fas fa-palette fa-2x text-info mb-3"></i>
                                    <h6 class="fw-semibold">3. Choisissez un Template</h6>
                                    <p class="text-muted small">Sélectionnez le design qui correspond le mieux à votre image de marque</p>
                                    <div class="mt-2">
                                        <span class="btn btn-info btn-sm">
                                            <i class="fas fa-paint-brush me-1"></i> Choisir
                                        </span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.hover-lift {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}
.hover-lift:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}
.cursor-pointer {
    cursor: pointer;
}
</style>

<?php $__env->stopSection(); ?> 
<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\Adev\200  -  test\AdminLicence-4.5.1\resources\views\admin\cms\index.blade.php ENDPATH**/ ?>