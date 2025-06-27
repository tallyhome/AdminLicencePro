

<?php $__env->startSection('title', 'Politique de Confidentialité - AdminLicence'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-light min-vh-100">
    <!-- Hero Section -->
    <div class="hero-section text-center">
        <div class="container">
            <h1 class="display-4 fw-bold mb-4">Politique de Confidentialité</h1>
            <p class="lead mb-0">Protection et utilisation de vos données personnelles</p>
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
                            <div class="alert alert-info">
                                <i class="fas fa-shield-alt me-2"></i>
                                Cette politique de confidentialité est conforme au RGPD (Règlement Général sur la Protection des Données).
                            </div>
                        </div>

                        <?php if(!empty($settings['privacy_content'])): ?>
                            <!-- Contenu personnalisé depuis l'admin -->
                            <div class="cms-content">
                                <?php echo $settings['privacy_content']; ?>

                            </div>
                        <?php else: ?>
                            <!-- Contenu par défaut si aucun contenu personnalisé -->
                            <section class="mb-5">
                                <h3 class="h5 fw-bold text-primary mb-3">1. Introduction</h3>
                                <p>
                                    AdminLicence s'engage à protéger et respecter votre vie privée. Cette politique explique comment nous 
                                    collectons, utilisons et protégeons vos informations personnelles lorsque vous utilisez notre service.
                                </p>
                            </section>

                            <section class="mb-5">
                                <h3 class="h5 fw-bold text-primary mb-3">2. Responsable du Traitement</h3>
                                <div class="p-3 bg-light rounded">
                                    <p class="mb-2"><strong>Société :</strong> AdminLicence</p>
                                    <p class="mb-2"><strong>Email :</strong> privacy@adminlicence.com</p>
                                    <p class="mb-0"><strong>Adresse :</strong> AdminLicence Data Protection Office</p>
                                </div>
                            </section>

                            <section class="mb-5">
                                <h3 class="h5 fw-bold text-primary mb-3">3. Données Collectées</h3>
                                <p>Nous collectons les types de données suivants :</p>
                                
                                <h6 class="fw-semibold mt-4 mb-2">3.1 Données d'identification</h6>
                                <ul>
                                    <li>Nom et prénom</li>
                                    <li>Adresse email</li>
                                    <li>Nom d'utilisateur</li>
                                    <li>Mot de passe (crypté)</li>
                                </ul>

                                <h6 class="fw-semibold mt-4 mb-2">3.2 Données techniques</h6>
                                <ul>
                                    <li>Adresse IP</li>
                                    <li>Informations sur le navigateur</li>
                                    <li>Logs de connexion</li>
                                    <li>Données d'utilisation de l'API</li>
                                </ul>

                                <h6 class="fw-semibold mt-4 mb-2">3.3 Données de licence</h6>
                                <ul>
                                    <li>Clés de licence générées</li>
                                    <li>Informations sur les produits</li>
                                    <li>Données de validation</li>
                                </ul>
                            </section>

                            <section class="mb-5">
                                <h3 class="h5 fw-bold text-primary mb-3">4. Finalités du Traitement</h3>
                                <p>Vos données sont utilisées pour :</p>
                                <ul>
                                    <li><strong>Fourniture du service :</strong> Gestion des comptes, génération de licences</li>
                                    <li><strong>Sécurité :</strong> Authentification, prévention de la fraude</li>
                                    <li><strong>Support technique :</strong> Assistance et résolution des problèmes</li>
                                    <li><strong>Amélioration du service :</strong> Analyse des performances et des usages</li>
                                    <li><strong>Communication :</strong> Notifications importantes sur le service</li>
                                </ul>
                            </section>

                            <section class="mb-5">
                                <h3 class="h5 fw-bold text-primary mb-3">5. Base Légale du Traitement</h3>
                                <p>Le traitement de vos données repose sur :</p>
                                <ul>
                                    <li><strong>Exécution du contrat :</strong> Fourniture du service AdminLicence</li>
                                    <li><strong>Intérêt légitime :</strong> Sécurité et amélioration du service</li>
                                    <li><strong>Consentement :</strong> Communications marketing (optionnelles)</li>
                                    <li><strong>Obligation légale :</strong> Conservation des logs pour la sécurité</li>
                                </ul>
                            </section>

                            <section class="mb-5">
                                <h3 class="h5 fw-bold text-primary mb-3">6. Partage des Données</h3>
                                <p>
                                    Nous ne vendons ni ne louons vos données personnelles. Vos données peuvent être partagées uniquement dans les cas suivants :
                                </p>
                                <ul>
                                    <li><strong>Prestataires de service :</strong> Hébergement, maintenance (sous contrat strict)</li>
                                    <li><strong>Obligations légales :</strong> Réquisitions judiciaires</li>
                                    <li><strong>Protection des droits :</strong> Prévention de la fraude ou d'abus</li>
                                </ul>
                            </section>

                            <section class="mb-5">
                                <h3 class="h5 fw-bold text-primary mb-3">7. Sécurité des Données</h3>
                                <p>Nous mettons en place des mesures de sécurité appropriées :</p>
                                <ul>
                                    <li><strong>Chiffrement :</strong> HTTPS/TLS pour toutes les communications</li>
                                    <li><strong>Authentification :</strong> Mots de passe cryptés, 2FA disponible</li>
                                    <li><strong>Accès restreint :</strong> Seul le personnel autorisé peut accéder aux données</li>
                                    <li><strong>Surveillance :</strong> Logs de sécurité et monitoring</li>
                                    <li><strong>Sauvegardes :</strong> Sauvegardes sécurisées et régulières</li>
                                </ul>
                            </section>

                            <section class="mb-5">
                                <h3 class="h5 fw-bold text-primary mb-3">8. Conservation des Données</h3>
                                <p>Nous conservons vos données selon les durées suivantes :</p>
                                <ul>
                                    <li><strong>Données de compte :</strong> Tant que votre compte est actif</li>
                                    <li><strong>Logs de sécurité :</strong> 12 mois maximum</li>
                                    <li><strong>Données de licence :</strong> Durée de vie de la licence + 3 ans</li>
                                    <li><strong>Données de support :</strong> 2 ans après résolution</li>
                                </ul>
                            </section>

                            <section class="mb-5">
                                <h3 class="h5 fw-bold text-primary mb-3">9. Vos Droits (RGPD)</h3>
                                <p>Vous disposez des droits suivants :</p>
                                
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="p-3 border rounded">
                                            <h6 class="fw-semibold text-primary">Droit d'accès</h6>
                                            <p class="small mb-0">Consulter vos données personnelles</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-3 border rounded">
                                            <h6 class="fw-semibold text-primary">Droit de rectification</h6>
                                            <p class="small mb-0">Corriger vos données inexactes</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-3 border rounded">
                                            <h6 class="fw-semibold text-primary">Droit à l'effacement</h6>
                                            <p class="small mb-0">Supprimer vos données</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-3 border rounded">
                                            <h6 class="fw-semibold text-primary">Droit à la portabilité</h6>
                                            <p class="small mb-0">Récupérer vos données</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-3 border rounded">
                                            <h6 class="fw-semibold text-primary">Droit d'opposition</h6>
                                            <p class="small mb-0">Vous opposer au traitement</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-3 border rounded">
                                            <h6 class="fw-semibold text-primary">Droit à la limitation</h6>
                                            <p class="small mb-0">Limiter le traitement</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="alert alert-primary mt-3">
                                    <strong>Comment exercer vos droits :</strong> Contactez-nous à privacy@adminlicence.com avec une pièce d'identité.
                                </div>
                            </section>

                            <section class="mb-5">
                                <h3 class="h5 fw-bold text-primary mb-3">10. Cookies et Technologies Similaires</h3>
                                <p>Nous utilisons des cookies pour :</p>
                                <ul>
                                    <li><strong>Cookies essentiels :</strong> Fonctionnement du site (sessions, sécurité)</li>
                                    <li><strong>Cookies de performance :</strong> Analyse des performances (avec votre consentement)</li>
                                </ul>
                                <p>Vous pouvez contrôler les cookies via les paramètres de votre navigateur.</p>
                            </section>

                            <section class="mb-5">
                                <h3 class="h5 fw-bold text-primary mb-3">11. Transferts Internationaux</h3>
                                <p>
                                    Vos données sont hébergées en France/UE. En cas de transfert hors UE, nous nous assurons 
                                    d'un niveau de protection adéquat (clauses contractuelles types, décisions d'adéquation).
                                </p>
                            </section>

                            <section class="mb-5">
                                <h3 class="h5 fw-bold text-primary mb-3">12. Modifications de la Politique</h3>
                                <p>
                                    Cette politique peut être modifiée. Les modifications importantes vous seront notifiées 
                                    par email ou via le service. La version en vigueur est toujours disponible sur cette page.
                                </p>
                            </section>

                            <section class="mb-5">
                                <h3 class="h5 fw-bold text-primary mb-3">13. Contact et Réclamations</h3>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="p-3 bg-light rounded">
                                            <h6 class="fw-semibold">Délégué à la Protection des Données</h6>
                                            <p class="mb-1"><strong>Email :</strong> dpo@adminlicence.com</p>
                                            <p class="mb-0"><strong>Téléphone :</strong> +33 1 23 45 67 89</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-3 bg-light rounded">
                                            <h6 class="fw-semibold">Autorité de Contrôle</h6>
                                            <p class="mb-1">CNIL (Commission Nationale de l'Informatique et des Libertés)</p>
                                            <p class="mb-0"><strong>Site :</strong> www.cnil.fr</p>
                                        </div>
                                    </div>
                                </div>
                            </section>

                            <section class="mb-5">
                                <h3 class="h5 fw-bold text-primary mb-3">14. Contact</h3>
                                <div class="p-3 bg-light rounded">
                                    <p class="mb-1"><strong>Email :</strong> privacy@adminlicence.com</p>
                                    <p class="mb-0"><strong>Pour plus d'informations :</strong> Consultez nos <a href="/terms" class="text-primary">Termes et Conditions</a></p>
                                </div>
                            </section>

                            <div class="text-center mt-5">
                                <div class="alert alert-success">
                                    <i class="fas fa-shield-alt me-2"></i>
                                    <strong>Votre vie privée est importante pour nous.</strong> 
                                    Nous nous engageons à traiter vos données avec le plus grand respect et en toute transparence.
                                </div>
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

.cms-content .alert {
    margin: 1.5rem 0;
}

.cms-content blockquote {
    border-left: 4px solid #2563eb;
    padding-left: 1rem;
    margin: 1.5rem 0;
    font-style: italic;
    color: #6b7280;
}
</style>

<?php $__env->stopSection(); ?> 
<?php echo $__env->make('frontend.templates.modern.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\Adev\200  -  test\AdminLicence-4.5.1\resources\views\frontend\pages\privacy.blade.php ENDPATH**/ ?>