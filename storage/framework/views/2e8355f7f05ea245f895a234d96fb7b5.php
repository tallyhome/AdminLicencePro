

<?php $__env->startSection('title', 'CMS - Gestion du Site'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Header -->
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
                    <a href="<?php echo e(route('admin.cms.preview')); ?>" target="_blank" class="btn btn-success btn-sm">
                        <i class="fas fa-eye me-1"></i> Prévisualiser
                    </a>
                    <?php if($stats['features'] == 0 && $stats['faqs'] == 0): ?>
                    <form action="<?php echo e(route('admin.cms.initialize')); ?>" method="POST" class="d-inline">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fas fa-rocket me-1"></i> Initialiser
                        </button>
                    </form>
                    <?php endif; ?>
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
                        <div class="h4 mb-0 fw-bold"><?php echo e($stats['features']); ?></div>
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
                        <div class="h4 mb-0 fw-bold"><?php echo e($stats['faqs']); ?></div>
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
                        <div class="h4 mb-0 fw-bold"><?php echo e($stats['testimonials']); ?></div>
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
                        <div class="text-uppercase fw-semibold small">À propos</div>
                        <div class="h4 mb-0 fw-bold"><?php echo e($stats['about_sections']); ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gestion du contenu -->
    <div class="row g-4 mb-4">
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
                        <!-- Fonctionnalités -->
                        <div class="col-12 col-md-6 col-xl-3">
                            <div class="card h-100 border-0 hover-lift" style="background: linear-gradient(135deg, #e3f2fd 0%, #f3e5f5 100%);">
                                <div class="card-body p-4 text-center">
                                    <div class="mb-3">
                                        <i class="fas fa-star text-primary fs-1"></i>
                                    </div>
                                    <h6 class="card-title fw-bold mb-2 text-dark">Fonctionnalités</h6>
                                    <p class="card-text text-muted small mb-3">Gérez les fonctionnalités mises en avant sur votre site</p>
                                    <a href="<?php echo e(route('admin.cms.features.index')); ?>" class="btn btn-primary">
                                        <i class="fas fa-cog me-1"></i> Gérer
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Questions Fréquentes -->
                        <div class="col-12 col-md-6 col-xl-3">
                            <div class="card h-100 border-0 hover-lift" style="background: linear-gradient(135deg, #e8f5e8 0%, #f0f8f0 100%);">
                                <div class="card-body p-4 text-center">
                                    <div class="mb-3">
                                        <i class="fas fa-question-circle text-success fs-1"></i>
                                    </div>
                                    <h6 class="card-title fw-bold mb-2 text-dark">Questions Fréquentes</h6>
                                    <p class="card-text text-muted small mb-3">Gérez la section FAQ de votre site</p>
                                    <a href="<?php echo e(route('admin.cms.faqs.index')); ?>" class="btn btn-success">
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
                                    <p class="card-text text-muted small mb-3">Gérez les témoignages clients</p>
                                    <a href="<?php echo e(route('admin.cms.testimonials.index')); ?>" class="btn btn-info">
                                        <i class="fas fa-cog me-1"></i> Gérer
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- À Propos -->
                        <div class="col-12 col-md-6 col-xl-3">
                            <div class="card h-100 border-0 hover-lift" style="background: linear-gradient(135deg, #fff3e0 0%, #fce4ec 100%);">
                                <div class="card-body p-4 text-center">
                                    <div class="mb-3">
                                        <i class="fas fa-info-circle text-warning fs-1"></i>
                                    </div>
                                    <h6 class="card-title fw-bold mb-2 text-dark">À Propos</h6>
                                    <p class="card-text text-muted small mb-3">Gérez les sections de la page À propos</p>
                                    <a href="<?php echo e(route('admin.cms.about.index')); ?>" class="btn btn-warning">
                                        <i class="fas fa-cog me-1"></i> Gérer
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
                    <?php if($currentTemplate): ?>
                        <div class="row align-items-center">
                            <div class="col-12 col-md-4 text-center mb-3 mb-md-0">
                                <?php if($currentTemplate->preview_image): ?>
                                    <img src="<?php echo e($currentTemplate->preview_image); ?>" alt="<?php echo e($currentTemplate->display_name); ?>" 
                                         class="img-fluid rounded shadow-sm" style="max-height: 120px;">
                                <?php else: ?>
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center shadow-sm" style="height: 120px;">
                                        <i class="fas fa-palette fa-2x text-muted"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="col-12 col-md-8">
                                <h5 class="fw-bold mb-2"><?php echo e($currentTemplate->display_name); ?></h5>
                                <p class="text-muted mb-3"><?php echo e($currentTemplate->description); ?></p>
                                <a href="<?php echo e(route('admin.cms.templates')); ?>" class="btn btn-primary btn-sm">
                                    <i class="fas fa-exchange-alt me-1"></i> Changer Template
                                </a>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-exclamation-triangle fa-2x mb-3"></i>
                            <p class="mb-0">Aucun template activé</p>
                        </div>
                    <?php endif; ?>
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
                        <a href="<?php echo e(route('admin.cms.preview')); ?>" target="_blank" class="btn btn-outline-info btn-sm d-flex align-items-center">
                            <i class="fas fa-eye me-2"></i>
                            Prévisualiser le Site
                        </a>
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
</style>

<?php $__env->stopSection(); ?> 
<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\Adev\200  -  test\AdminLicence-4.5.1\resources\views\admin\cms\index.blade.php ENDPATH**/ ?>