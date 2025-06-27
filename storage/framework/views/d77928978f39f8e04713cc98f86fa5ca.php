<?php $__env->startSection('title', 'Documentation - AdminLicence'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-light min-vh-100">
    <!-- Hero Section -->
    <div class="hero-section text-center">
        <div class="container">
            <h1 class="display-4 fw-bold mb-4">Documentation AdminLicence</h1>
            <p class="lead mb-0">Guides et ressources pour intégrer AdminLicence dans vos projets</p>
        </div>
    </div>

    <!-- Contenu -->
    <div class="container py-5">
        <div class="row">
            <!-- Documentation API -->
            <div class="col-lg-6 mb-4">
                <div class="card feature-card h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 rounded-circle me-3" style="width: 48px; height: 48px;">
                                <i class="fas fa-code text-primary" style="font-size: 20px;"></i>
                            </div>
                            <h5 class="card-title fw-bold mb-0">Guide d'intégration API</h5>
                        </div>
                        <p class="card-text text-muted mb-4">
                            Instructions détaillées pour intégrer l'API AdminLicence dans vos applications. 
                            Exemples de code pour PHP, Laravel, JavaScript et Flutter.
                        </p>
                        <div class="mb-3">
                            <small class="text-muted">Inclut :</small>
                            <ul class="list-unstyled small text-muted mt-1">
                                <li><i class="fas fa-check text-success me-2"></i>Authentification API</li>
                                <li><i class="fas fa-check text-success me-2"></i>Validation de licences</li>
                                <li><i class="fas fa-check text-success me-2"></i>Exemples de code</li>
                                <li><i class="fas fa-check text-success me-2"></i>Gestion des erreurs</li>
                            </ul>
                        </div>
                        <a href="<?php echo e(route('documentation.api')); ?>" class="btn btn-primary">
                            <i class="fas fa-book me-2"></i>Consulter le guide
                        </a>
                    </div>
                </div>
            </div>

            <!-- Documentation Technique -->
            <div class="col-lg-6 mb-4">
                <div class="card feature-card h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="d-inline-flex align-items-center justify-content-center bg-success bg-opacity-10 rounded-circle me-3" style="width: 48px; height: 48px;">
                                <i class="fas fa-cogs text-success" style="font-size: 20px;"></i>
                            </div>
                            <h5 class="card-title fw-bold mb-0">Documentation Technique</h5>
                        </div>
                        <p class="card-text text-muted mb-4">
                            Informations techniques approfondies sur le fonctionnement d'AdminLicence, 
                            l'architecture et les bonnes pratiques de sécurité.
                        </p>
                        <div class="mb-3">
                            <small class="text-muted">Inclut :</small>
                            <ul class="list-unstyled small text-muted mt-1">
                                <li><i class="fas fa-check text-success me-2"></i>Architecture système</li>
                                <li><i class="fas fa-check text-success me-2"></i>Sécurité des licences</li>
                                <li><i class="fas fa-check text-success me-2"></i>Configuration avancée</li>
                                <li><i class="fas fa-check text-success me-2"></i>Dépannage</li>
                            </ul>
                        </div>
                        <a href="/admin/login" class="btn btn-success">
                            <i class="fas fa-shield-alt me-2"></i>Documentation Admin
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section FAQ -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body p-5">
                        <div class="text-center mb-5">
                            <h2 class="section-title fw-bold">Questions Fréquentes</h2>
                            <p class="lead text-muted">Réponses aux questions les plus courantes</p>
                        </div>

                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="p-3 border rounded">
                                    <h6 class="fw-semibold text-primary mb-2">Comment commencer ?</h6>
                                    <p class="small text-muted mb-0">
                                        Consultez le guide d'intégration API pour débuter rapidement avec AdminLicence.
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 border rounded">
                                    <h6 class="fw-semibold text-primary mb-2">Quels langages sont supportés ?</h6>
                                    <p class="small text-muted mb-0">
                                        PHP, Laravel, JavaScript, Flutter/Dart et tout langage capable de faire des requêtes HTTP.
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 border rounded">
                                    <h6 class="fw-semibold text-primary mb-2">L'API est-elle sécurisée ?</h6>
                                    <p class="small text-muted mb-0">
                                        Oui, l'API utilise HTTPS et un système d'authentification par clé API sécurisé.
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 border rounded">
                                    <h6 class="fw-semibold text-primary mb-2">Support technique disponible ?</h6>
                                    <p class="small text-muted mb-0">
                                        Oui, notre équipe support est disponible pour vous aider avec l'intégration.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <a href="/support" class="btn btn-outline-primary">
                                <i class="fas fa-life-ring me-2"></i>Obtenir de l'aide
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section Liens Rapides -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="card bg-primary text-white">
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-lg-8">
                                <h5 class="fw-bold mb-2">Prêt à commencer ?</h5>
                                <p class="mb-0">
                                    Consultez notre guide d'intégration API et commencez à sécuriser vos applications dès aujourd'hui.
                                </p>
                            </div>
                            <div class="col-lg-4 text-lg-end">
                                <a href="<?php echo e(route('documentation.api')); ?>" class="btn btn-light">
                                    <i class="fas fa-rocket me-2"></i>Commencer maintenant
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.templates.modern.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\Adev\200  -  test\AdminLicence-4.5.1\resources\views\documentation\index.blade.php ENDPATH**/ ?>