

<?php $__env->startSection('title', $settings['site_name'] ?? 'AdminLicence Professional'); ?>

<?php $__env->startSection('content'); ?>
<div class="professional-template">
    <!-- Hero Section Professional -->
    <section class="hero-professional bg-gradient-dark text-white py-5" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%); min-height: 80vh;">
        <div class="container">
            <div class="row align-items-center" style="min-height: 75vh;">
                <div class="col-lg-6">
                    <div class="hero-content-professional">
                        <div class="badge bg-light text-dark mb-3 px-3 py-2 rounded-pill">
                            <i class="fas fa-shield-alt me-2"></i>Sécurité Enterprise
                        </div>
                        <h1 class="display-4 fw-bold mb-4 text-white">
                            <?php echo e($content['hero_title'] ?? 'Solution de Licences Enterprise'); ?>

                        </h1>
                        <p class="lead mb-4 text-light">
                            <?php echo e($content['hero_subtitle'] ?? 'Protégez votre propriété intellectuelle avec notre système de gestion de licences de niveau entreprise.'); ?>

                        </p>
                        
                        <!-- Stats rapides -->
                        <div class="row mb-4">
                            <div class="col-4">
                                <div class="text-center">
                                    <h3 class="text-warning mb-0">99.9%</h3>
                                    <small class="text-light">Uptime</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="text-center">
                                    <h3 class="text-success mb-0">256-bit</h3>
                                    <small class="text-light">Encryption</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="text-center">
                                    <h3 class="text-info mb-0">24/7</h3>
                                    <small class="text-light">Support</small>
                                </div>
                            </div>
                        </div>

                        <div class="hero-buttons d-flex flex-wrap gap-3">
                            <button class="btn btn-warning btn-lg px-4 py-3 fw-semibold">
                                <i class="fas fa-rocket me-2"></i>Commencer Maintenant
                            </button>
                            <button class="btn btn-outline-light btn-lg px-4 py-3">
                                <i class="fas fa-play me-2"></i>Voir la Démo
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="hero-image-professional text-center">
                        <div class="dashboard-preview bg-white rounded-3 shadow-lg p-4 position-relative">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-danger rounded-circle me-2" style="width: 12px; height: 12px;"></div>
                                <div class="bg-warning rounded-circle me-2" style="width: 12px; height: 12px;"></div>
                                <div class="bg-success rounded-circle" style="width: 12px; height: 12px;"></div>
                            </div>
                            <div class="bg-light p-3 rounded mb-2">
                                <div class="row">
                                    <div class="col-3"><div class="bg-primary rounded" style="height: 20px;"></div></div>
                                    <div class="col-6"><div class="bg-secondary rounded" style="height: 20px;"></div></div>
                                    <div class="col-3"><div class="bg-success rounded" style="height: 20px;"></div></div>
                                </div>
                            </div>
                            <div class="bg-light p-3 rounded">
                                <div class="row g-2">
                                    <div class="col-4"><div class="bg-info rounded" style="height: 40px;"></div></div>
                                    <div class="col-8"><div class="bg-warning rounded" style="height: 40px;"></div></div>
                                </div>
                            </div>
                            <div class="position-absolute top-0 end-0 m-3">
                                <div class="spinner-grow text-success" style="width: 0.75rem; height: 0.75rem;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section Professional -->
    <section class="features-professional py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold text-dark mb-3">Fonctionnalités Enterprise</h2>
                <p class="lead text-muted">Des outils professionnels pour une gestion de licences robuste</p>
            </div>
            
            <div class="row g-4">
                <?php $__currentLoopData = $features; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card-professional h-100 bg-white rounded-3 shadow-sm p-4 border-start border-4 border-primary">
                        <div class="feature-icon-professional mb-3">
                            <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                <i class="<?php echo e($feature->icon); ?> text-primary fs-4"></i>
                            </div>
                        </div>
                        <h4 class="fw-bold text-dark mb-3"><?php echo e($feature->title); ?></h4>
                        <p class="text-muted mb-3"><?php echo e($feature->description); ?></p>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </section>

    <!-- Testimonials Professional -->
    <section class="testimonials-professional py-5 bg-white">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold text-dark mb-3">Témoignages Clients</h2>
                <p class="lead text-muted">Ce que disent nos clients enterprise</p>
            </div>
            
            <div class="row g-4">
                <?php $__currentLoopData = $testimonials->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $testimonial): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-lg-4">
                    <div class="testimonial-card-professional bg-light rounded-3 p-4 h-100">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                <i class="fas fa-user text-white"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold text-dark"><?php echo e($testimonial->name); ?></h6>
                                <small class="text-muted"><?php echo e($testimonial->position); ?> - <?php echo e($testimonial->company); ?></small>
                            </div>
                        </div>
                        <div class="mb-3">
                            <?php for($i = 1; $i <= 5; $i++): ?>
                                <i class="fas fa-star <?php echo e($i <= $testimonial->rating ? 'text-warning' : 'text-muted'); ?>"></i>
                            <?php endfor; ?>
                        </div>
                        <blockquote class="blockquote">
                            <p class="text-muted">"<?php echo e($testimonial->content); ?>"</p>
                        </blockquote>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </section>

    <!-- FAQ Professional -->
    <section class="faq-professional py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <h2 class="display-6 fw-bold text-dark mb-4">Questions Fréquentes</h2>
                    <p class="lead text-muted mb-4">Trouvez rapidement les réponses à vos questions</p>
                </div>
                <div class="col-lg-6">
                    <div class="accordion accordion-flush" id="faqAccordion">
                        <?php $__currentLoopData = $faqs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $faq): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="accordion-item border rounded mb-3">
                            <h2 class="accordion-header">
                                <button class="accordion-button <?php echo e($index === 0 ? '' : 'collapsed'); ?>" type="button" data-bs-toggle="collapse" data-bs-target="#faq-<?php echo e($index); ?>">
                                    <?php echo e($faq->question); ?>

                                </button>
                            </h2>
                            <div id="faq-<?php echo e($index); ?>" class="accordion-collapse collapse <?php echo e($index === 0 ? 'show' : ''); ?>" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    <p class="text-muted mb-0"><?php echo e($faq->answer); ?></p>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Professional -->
    <section class="cta-professional py-5 bg-dark text-white">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h2 class="display-6 fw-bold mb-3">Prêt à Sécuriser Votre Business ?</h2>
                    <p class="lead mb-0">Rejoignez des milliers d'entreprises qui font confiance à AdminLicence.</p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <button class="btn btn-warning btn-lg px-4 py-3 fw-semibold">
                        <i class="fas fa-shield-alt me-2"></i>Démarrer Maintenant
                    </button>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
.professional-template {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
}

.feature-card-professional {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.feature-card-professional:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.1) !important;
}

.testimonial-card-professional {
    transition: transform 0.3s ease;
    border-left: 4px solid #007bff;
}

.testimonial-card-professional:hover {
    transform: translateY(-3px);
}

.dashboard-preview {
    animation: float 3s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
}

.accordion-button:not(.collapsed) {
    background-color: #f8f9fa;
    color: #0d1117;
}

.cta-professional {
    background: linear-gradient(135deg, #212529 0%, #495057 100%);
}

@media (max-width: 768px) {
    .display-4 {
        font-size: 2.5rem;
    }
    
    .hero-buttons {
        justify-content: center;
    }
    
    .dashboard-preview {
        margin-top: 2rem;
    }
}
</style>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\Adev\200  -  test\AdminLicence-4.5.1\resources\views\frontend\templates\professional.blade.php ENDPATH**/ ?>