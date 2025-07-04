<?php $__env->startSection('title', ($settings['site_title'] ?? 'AdminLicence') . ' - ' . ($settings['site_tagline'] ?? 'Système de gestion de licences')); ?>

<?php $__env->startSection('content'); ?>
    <?php if($settings['show_hero'] ?? true): ?>
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="security-badge">
                        <i class="fas fa-shield-check"></i>
                        <span>Sécurité Certifiée AES-256</span>
                    </div>
                    
                    <h1 class="display-4 fw-bold mb-4">
                        <?php echo e($settings['hero_title'] ?? 'Sécurisez vos licences logicielles'); ?>

                    </h1>
                    
                    <p class="lead mb-4">
                        <?php echo e($settings['hero_subtitle'] ?? 'Solution professionnelle de gestion de licences avec sécurité avancée'); ?>

                    </p>
                    
                    <div class="d-flex flex-wrap gap-3">
                        <a href="<?php echo e($settings['cta_button_url'] ?? '/admin'); ?>" class="btn btn-light btn-lg px-4">
                            <i class="fas fa-rocket"></i>
                            <?php echo e($settings['hero_button_text'] ?? 'Commencer maintenant'); ?>

                        </a>
                        <a href="<?php echo e(route('frontend.about')); ?>" class="btn btn-outline-light btn-lg px-4">
                            <i class="fas fa-info-circle"></i>
                            <?php echo e($settings['hero_button_secondary_text'] ?? 'En savoir plus'); ?>

                        </a>
                    </div>
                </div>
                
                <div class="col-lg-6 text-center">
                    <div class="position-relative">
                        <div class="bg-white bg-opacity-10 rounded-3 p-4 backdrop-blur">
                            <i class="fas fa-shield-alt fa-8x text-white opacity-75"></i>
                            <div class="mt-3">
                                <h3 class="h5">Sécurité Maximale</h3>
                                <p class="small mb-0">Chiffrement cryptographique avancé</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <?php if(($settings['show_features'] ?? true) && !empty($content['features'])): ?>
    <!-- Features Section -->
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title"><?php echo e($settings['features_section_title'] ?? 'Fonctionnalités Avancées'); ?></h2>
                <p class="text-muted"><?php echo e($settings['features_section_subtitle'] ?? 'Découvrez pourquoi AdminLicence est la solution de référence pour la sécurisation de vos licences'); ?></p>
            </div>
            
            <div class="row g-4">
                <?php $__currentLoopData = $content['features']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-lg-4 col-md-6">
                    <div class="card feature-card shadow-sm">
                        <div class="card-body text-center p-4">
                            <?php if($feature->icon): ?>
                                <div class="mb-3">
                                    <i class="<?php echo e($feature->icon); ?> fa-3x text-primary"></i>
                                </div>
                            <?php endif; ?>
                            
                            <h5 class="fw-bold mb-3"><?php echo e($feature->title); ?></h5>
                            <p class="text-muted mb-4"><?php echo e($feature->description); ?></p>
                            
                            <?php if($feature->link_url): ?>
                                <a href="<?php echo e($feature->link_url); ?>" class="btn btn-primary">
                                    <?php echo e($feature->link_text ?? 'En savoir plus'); ?>

                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <?php if(($settings['show_testimonials'] ?? true) && !empty($content['testimonials'])): ?>
    <!-- Testimonials Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title"><?php echo e($settings['testimonials_section_title'] ?? 'Ce que disent nos clients'); ?></h2>
                <p class="text-muted"><?php echo e($settings['testimonials_section_subtitle'] ?? 'Découvrez les témoignages de nos utilisateurs satisfaits'); ?></p>
            </div>
            
            <div class="row g-4">
                <?php $__currentLoopData = $content['testimonials']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $testimonial): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-lg-4 col-md-6">
                    <div class="testimonial-card">
                        <div class="mb-3">
                            <div class="text-warning">
                                <?php for($i = 0; $i < $testimonial->rating; $i++): ?>
                                    <i class="fas fa-star"></i>
                                <?php endfor; ?>
                                <?php for($i = $testimonial->rating; $i < 5; $i++): ?>
                                    <i class="far fa-star"></i>
                                <?php endfor; ?>
                            </div>
                        </div>
                        
                        <blockquote class="mb-4">
                            "<?php echo e($testimonial->content); ?>"
                        </blockquote>
                        
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <?php if($testimonial->avatar_url): ?>
                                    <img src="<?php echo e($testimonial->avatar_url); ?>" alt="<?php echo e($testimonial->name); ?>" class="rounded-circle" width="50" height="50">
                                <?php else: ?>
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                        <?php echo e(strtoupper(substr($testimonial->name, 0, 1))); ?>

                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0 fw-bold"><?php echo e($testimonial->name); ?></h6>
                                <?php if($testimonial->position): ?>
                                    <small class="text-muted"><?php echo e($testimonial->position); ?></small>
                                <?php endif; ?>
                                <?php if($testimonial->company): ?>
                                    <small class="text-muted"><?php echo e($testimonial->position ? ' - ' : ''); ?><?php echo e($testimonial->company); ?></small>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <?php if(($settings['show_faqs'] ?? true) && !empty($content['faqs'])): ?>
    <!-- FAQ Section -->
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title"><?php echo e($settings['faqs_section_title'] ?? 'Questions Fréquentes'); ?></h2>
                <p class="text-muted"><?php echo e($settings['faqs_section_subtitle'] ?? 'Trouvez rapidement les réponses à vos questions'); ?></p>
            </div>
            
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="accordion" id="faqAccordion">
                        <?php $__currentLoopData = $content['faqs']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $faq): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="accordion-item faq-item">
                            <h2 class="accordion-header" id="faq<?php echo e($index); ?>">
                                <button class="accordion-button <?php echo e($index === 0 ? '' : 'collapsed'); ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo e($index); ?>">
                                    <?php echo e($faq->question); ?>

                                </button>
                            </h2>
                            <div id="collapse<?php echo e($index); ?>" class="accordion-collapse collapse <?php echo e($index === 0 ? 'show' : ''); ?>" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    <?php echo nl2br(e($faq->answer)); ?>

                                </div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    
                    <div class="text-center mt-4">
                        <a href="<?php echo e(route('frontend.faqs')); ?>" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-right"></i>
                            Voir toutes les FAQs
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- CTA Section -->
    <section class="py-5 bg-primary text-white">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h2 class="fw-bold mb-3"><?php echo e($settings['cta_title'] ?? 'Prêt à sécuriser vos licences ?'); ?></h2>
                    <p class="lead mb-0"><?php echo e($settings['cta_subtitle'] ?? 'Rejoignez des milliers d\'entreprises qui font confiance à AdminLicence pour protéger leurs logiciels.'); ?></p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="<?php echo e($settings['cta_button_url'] ?? '/admin'); ?>" class="btn btn-light btn-lg px-4">
                        <i class="fas fa-rocket"></i>
                        <?php echo e($settings['cta_button_text'] ?? 'Commencer maintenant'); ?>

                    </a>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('frontend.templates.modern.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\Adev\200  -  test\AdminLicence-4.5.1\resources\views\frontend\templates\modern\home.blade.php ENDPATH**/ ?>