

<?php $__env->startSection('title', 'À propos - AdminLicence'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-light min-vh-100">
    <!-- Hero Section -->
    <section class="hero-professional">
        <div class="container">
            <div class="hero-content text-center">
                <div class="security-badge-professional">
                    <i class="fas fa-info-circle"></i>
                    <span>À propos d'AdminLicence</span>
                </div>
                <h1 class="display-4 fw-bold mb-4">Notre Mission</h1>
                <p class="lead mb-0 opacity-90">Fournir des solutions de gestion de licences sécurisées et professionnelles pour protéger votre propriété intellectuelle</p>
            </div>
        </div>
    </section>

    <!-- Company Overview -->
    <section class="section-professional bg-white">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h2 class="section-title-professional">Excellence & Innovation</h2>
                    <p class="lead text-muted mb-4">
                        AdminLicence est une solution professionnelle de gestion de licences logicielles, 
                        conçue pour répondre aux besoins des entreprises modernes en matière de sécurité 
                        et de protection de la propriété intellectuelle.
                    </p>
                    <p class="text-muted mb-4">
                        Depuis notre création, nous nous engageons à fournir des outils robustes et fiables 
                        qui permettent aux développeurs et aux entreprises de protéger efficacement leurs 
                        logiciels contre le piratage et l'utilisation non autorisée.
                    </p>
                    <div class="d-flex gap-3 flex-wrap">
                        <a href="<?php echo e(route('frontend.contact')); ?>" class="btn btn-primary-professional">
                            <i class="fas fa-envelope"></i>
                            Nous contacter
                        </a>
                        <a href="<?php echo e(route('frontend.features')); ?>" class="btn btn-secondary-professional">
                            <i class="fas fa-cog"></i>
                            Nos fonctionnalités
                        </a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="position-relative">
                        <div class="bg-primary bg-opacity-10 rounded-3 p-5 text-center">
                            <i class="fas fa-shield-alt text-primary" style="font-size: 6rem; opacity: 0.8;"></i>
                            <h4 class="mt-3 mb-2 fw-bold text-primary">Sécurité Maximale</h4>
                            <p class="text-muted mb-0">Protection avancée avec chiffrement militaire</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Values Section -->
    <section class="section-professional bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title-professional">Nos Valeurs</h2>
                <p class="lead text-muted">Les principes qui guident notre approche</p>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4 text-center">
                            <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 rounded-circle mb-4" style="width: 64px; height: 64px;">
                                <i class="fas fa-lock text-primary" style="font-size: 24px;"></i>
                            </div>
                            <h5 class="fw-bold mb-3">Sécurité</h5>
                            <p class="text-muted">
                                Protection de vos données et de vos licences avec les technologies de chiffrement les plus avancées
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4 text-center">
                            <div class="d-inline-flex align-items-center justify-content-center bg-success bg-opacity-10 rounded-circle mb-4" style="width: 64px; height: 64px;">
                                <i class="fas fa-handshake text-success" style="font-size: 24px;"></i>
                            </div>
                            <h5 class="fw-bold mb-3">Fiabilité</h5>
                            <p class="text-muted">
                                Services disponibles 24/7 avec un SLA de 99.9% pour garantir la continuité de votre activité
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4 text-center">
                            <div class="d-inline-flex align-items-center justify-content-center bg-info bg-opacity-10 rounded-circle mb-4" style="width: 64px; height: 64px;">
                                <i class="fas fa-rocket text-info" style="font-size: 24px;"></i>
                            </div>
                            <h5 class="fw-bold mb-3">Innovation</h5>
                            <p class="text-muted">
                                Développement constant de nouvelles fonctionnalités pour rester à la pointe de la technologie
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4 text-center">
                            <div class="d-inline-flex align-items-center justify-content-center bg-warning bg-opacity-10 rounded-circle mb-4" style="width: 64px; height: 64px;">
                                <i class="fas fa-users text-warning" style="font-size: 24px;"></i>
                            </div>
                            <h5 class="fw-bold mb-3">Support</h5>
                            <p class="text-muted">
                                Équipe d'experts dédiée pour vous accompagner dans l'implémentation et l'utilisation
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4 text-center">
                            <div class="d-inline-flex align-items-center justify-content-center bg-danger bg-opacity-10 rounded-circle mb-4" style="width: 64px; height: 64px;">
                                <i class="fas fa-chart-line text-danger" style="font-size: 24px;"></i>
                            </div>
                            <h5 class="fw-bold mb-3">Performance</h5>
                            <p class="text-muted">
                                Architecture optimisée pour des temps de réponse ultra-rapides et une scalabilité maximale
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4 text-center">
                            <div class="d-inline-flex align-items-center justify-content-center bg-secondary bg-opacity-10 rounded-circle mb-4" style="width: 64px; height: 64px;">
                                <i class="fas fa-globe text-secondary" style="font-size: 24px;"></i>
                            </div>
                            <h5 class="fw-bold mb-3">Accessibilité</h5>
                            <p class="text-muted">
                                Solution accessible depuis n'importe où dans le monde avec une interface multilingue
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistics Section -->
    <section class="section-professional bg-white">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title-professional">AdminLicence en Chiffres</h2>
                <p class="lead text-muted">La confiance de nos clients en quelques statistiques</p>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-3 col-md-6 text-center">
                    <div class="p-4">
                        <div class="display-4 fw-bold text-primary mb-2">10K+</div>
                        <h6 class="fw-semibold text-muted">Licences Gérées</h6>
                        <p class="small text-muted mb-0">Milliers de licences protégées quotidiennement</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 text-center">
                    <div class="p-4">
                        <div class="display-4 fw-bold text-success mb-2">99.9%</div>
                        <h6 class="fw-semibold text-muted">Disponibilité</h6>
                        <p class="small text-muted mb-0">Service garanti avec SLA professionnel</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 text-center">
                    <div class="p-4">
                        <div class="display-4 fw-bold text-info mb-2">500+</div>
                        <h6 class="fw-semibold text-muted">Entreprises</h6>
                        <p class="small text-muted mb-0">Clients satisfaits dans le monde entier</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 text-center">
                    <div class="p-4">
                        <div class="display-4 fw-bold text-warning mb-2">24/7</div>
                        <h6 class="fw-semibold text-muted">Support</h6>
                        <p class="small text-muted mb-0">Assistance technique disponible en permanence</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="section-professional bg-light">
        <div class="container">
            <div class="text-center">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-5">
                        <h3 class="section-title-professional mb-4">Prêt à Sécuriser Vos Licences ?</h3>
                        <p class="lead text-muted mb-4">
                            Rejoignez des centaines d'entreprises qui font confiance à AdminLicence pour protéger leur propriété intellectuelle
                        </p>
                        <div class="d-flex justify-content-center gap-3 flex-wrap">
                            <a href="<?php echo e(route('frontend.pricing')); ?>" class="btn btn-primary-professional btn-lg">
                                <i class="fas fa-rocket"></i>
                                Commencer maintenant
                            </a>
                            <a href="<?php echo e(route('frontend.contact')); ?>" class="btn btn-secondary-professional btn-lg">
                                <i class="fas fa-calendar"></i>
                                Demander une démo
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make($layout ?? 'frontend.templates.professional.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\Adev\200  -  test\AdminLicence-4.5.1\resources\views\frontend\templates\professional\about.blade.php ENDPATH**/ ?>