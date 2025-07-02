<?php $__env->startSection('title', 'Termes et Conditions - AdminLicence'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-light min-vh-100">
    <!-- Hero Section -->
    <div class="hero-section text-center">
        <div class="container">
            <h1 class="display-4 fw-bold mb-4">Termes et Conditions</h1>
            <p class="lead mb-0">Conditions générales d'utilisation d'AdminLicence</p>
        </div>
    </div>

    <!-- Contenu -->
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-sm">
                    <div class="card-body p-5">
                        <div class="mb-4">
                            <p class="text-muted">
                                <i class="fas fa-calendar-alt me-2"></i>
                                Dernière mise à jour : <?php echo e(date('d/m/Y')); ?>

                            </p>
                        </div>

                        <?php if(!empty($settings['terms_content'])): ?>
                            <!-- Contenu personnalisé depuis l'admin -->
                            <div class="cms-content">
                                <?php echo $settings['terms_content']; ?>

                            </div>
                        <?php else: ?>
                            <!-- Contenu par défaut si aucun contenu personnalisé -->
                            <section class="mb-5">
                                <h3 class="h5 fw-bold text-primary mb-3">1. Acceptation des Conditions</h3>
                                <p>
                                    En accédant et en utilisant AdminLicence, vous acceptez d'être lié par ces termes et conditions d'utilisation. 
                                    Si vous n'acceptez pas tous les termes et conditions de cet accord, vous ne devez pas utiliser ce service.
                                </p>
                            </section>

                            <section class="mb-5">
                                <h3 class="h5 fw-bold text-primary mb-3">2. Description du Service</h3>
                                <p>
                                    AdminLicence est une plateforme de gestion de licences logicielles qui permet aux développeurs et aux entreprises 
                                    de sécuriser, distribuer et gérer leurs licences de logiciels.
                                </p>
                                <p>Le service comprend :</p>
                                <ul>
                                    <li>Génération et validation de clés de licence</li>
                                    <li>Dashboard d'administration</li>
                                    <li>API de validation</li>
                                    <li>Système de gestion des utilisateurs</li>
                                </ul>
                            </section>

                            <section class="mb-5">
                                <h3 class="h5 fw-bold text-primary mb-3">3. Conditions d'Utilisation</h3>
                                <p>Vous vous engagez à :</p>
                                <ul>
                                    <li>Utiliser le service de manière légale et appropriée</li>
                                    <li>Ne pas tenter de contourner les mesures de sécurité</li>
                                    <li>Maintenir la confidentialité de vos identifiants de connexion</li>
                                    <li>Ne pas utiliser le service pour des activités illégales</li>
                                    <li>Respecter les droits de propriété intellectuelle</li>
                                </ul>
                            </section>

                            <section class="mb-5">
                                <h3 class="h5 fw-bold text-primary mb-3">4. Propriété Intellectuelle</h3>
                                <p>
                                    AdminLicence et tous ses contenus, fonctionnalités et fonctions sont la propriété exclusive de notre société. 
                                    Tous les droits d'auteur, marques commerciales et autres droits de propriété intellectuelle sont réservés.
                                </p>
                            </section>

                            <section class="mb-5">
                                <h3 class="h5 fw-bold text-primary mb-3">5. Limitation de Responsabilité</h3>
                                <p>
                                    AdminLicence est fourni "en l'état" sans garantie d'aucune sorte. Nous ne garantissons pas que le service 
                                    sera ininterrompu, sécurisé ou exempt d'erreurs. En aucun cas, nous ne serons responsables des dommages 
                                    directs, indirects, incidents ou consécutifs.
                                </p>
                            </section>

                            <section class="mb-5">
                                <h3 class="h5 fw-bold text-primary mb-3">6. Protection des Données</h3>
                                <p>
                                    Nous nous engageons à protéger vos données personnelles conformément à notre 
                                    <a href="/privacy" class="text-primary">Politique de Confidentialité</a>. 
                                    Vos données sont traitées selon les réglementations en vigueur, notamment le RGPD.
                                </p>
                            </section>

                            <section class="mb-5">
                                <h3 class="h5 fw-bold text-primary mb-3">7. Modifications des Conditions</h3>
                                <p>
                                    Nous nous réservons le droit de modifier ces termes et conditions à tout moment. 
                                    Les modifications prennent effet immédiatement après leur publication sur cette page. 
                                    Il est de votre responsabilité de consulter régulièrement ces conditions.
                                </p>
                            </section>

                            <section class="mb-5">
                                <h3 class="h5 fw-bold text-primary mb-3">8. Résiliation</h3>
                                <p>
                                    Nous nous réservons le droit de suspendre ou de résilier votre accès au service à tout moment, 
                                    sans préavis, pour non-respect de ces conditions d'utilisation.
                                </p>
                            </section>

                            <section class="mb-5">
                                <h3 class="h5 fw-bold text-primary mb-3">9. Droit Applicable</h3>
                                <p>
                                    Ces conditions sont régies par le droit français. Tout litige sera soumis à la compétence 
                                    exclusive des tribunaux français.
                                </p>
                            </section>

                            <section class="mb-5">
                                <h3 class="h5 fw-bold text-primary mb-3">10. Contact</h3>
                                <p>
                                    Pour toute question concernant ces termes et conditions, vous pouvez nous contacter à :
                                </p>
                                <div class="p-3 bg-light rounded">
                                    <p class="mb-2"><strong>Email :</strong> legal@adminlicence.com</p>
                                    <p class="mb-0"><strong>Adresse :</strong> AdminLicence Legal Department</p>
                                </div>
                            </section>

                            <div class="text-center mt-5">
                                <p class="text-muted">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Ces termes et conditions sont effectifs depuis le <?php echo e(date('d/m/Y')); ?>.
                                </p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.cms-content {
    line-height: 1.7;
}

.cms-content h1, .cms-content h2, .cms-content h3, .cms-content h4, .cms-content h5, .cms-content h6 {
    color: #2563eb;
    font-weight: 600;
    margin-top: 2rem;
    margin-bottom: 1rem;
}

.cms-content h1 { font-size: 1.75rem; }
.cms-content h2 { font-size: 1.5rem; }
.cms-content h3 { font-size: 1.25rem; }
.cms-content h4 { font-size: 1.1rem; }

.cms-content p {
    margin-bottom: 1rem;
}

.cms-content ul, .cms-content ol {
    margin-bottom: 1rem;
    padding-left: 1.5rem;
}

.cms-content li {
    margin-bottom: 0.5rem;
}
</style>

<?php $__env->stopSection(); ?> 
<?php echo $__env->make($layout ?? 'frontend.templates.modern.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\Adev\200  -  test\AdminLicence-4.5.1\resources\views\frontend\pages\terms.blade.php ENDPATH**/ ?>