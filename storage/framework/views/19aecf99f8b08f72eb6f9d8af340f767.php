

<?php $__env->startSection('title', 'FAQ - Questions Fréquentes'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    <!-- En-tête avec navigation -->
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('client.dashboard')); ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo e(route('client.support.index')); ?>">Support</a></li>
                    <li class="breadcrumb-item active" aria-current="page">FAQ</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- En-tête avec le titre -->
    <div class="card bg-primary text-white mb-4">
        <div class="card-body p-4">
            <h4 class="mb-2">FAQ - Questions Fréquentes</h4>
            <p class="mb-0">Trouvez rapidement les réponses à vos questions</p>
        </div>
    </div>

    <!-- Barre de recherche -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="input-group">
                        <input type="text" class="form-control" id="searchFAQ" placeholder="Rechercher dans la FAQ...">
                        <button class="btn btn-primary" type="button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Catégories FAQ -->
    <div class="row g-4">
        <!-- Questions Générales -->
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-question-circle text-primary me-2"></i>
                        Questions Générales
                    </h5>
                </div>
                <div class="card-body">
                    <div class="accordion" id="generalAccordion">
                        <!-- Question 1 -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#general1">
                                    Comment créer mon premier projet ?
                                </button>
                            </h2>
                            <div id="general1" class="accordion-collapse collapse" data-bs-parent="#generalAccordion">
                                <div class="accordion-body">
                                    <p>Pour créer votre premier projet :</p>
                                    <ol>
                                        <li>Connectez-vous à votre espace client</li>
                                        <li>Cliquez sur <strong>"Projets"</strong> dans le menu de gauche</li>
                                        <li>Cliquez sur le bouton <strong>"Nouveau Projet"</strong></li>
                                        <li>Remplissez les informations requises :
                                            <ul>
                                                <li>Nom du projet</li>
                                                <li>Description (optionnelle)</li>
                                                <li>Configuration des licences</li>
                                            </ul>
                                        </li>
                                        <li>Cliquez sur <strong>"Créer le Projet"</strong></li>
                                    </ol>
                                    <p>Une fois créé, vous pourrez générer des licences pour ce projet.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Question 2 -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#general2">
                                    Comment générer des licences ?
                                </button>
                            </h2>
                            <div id="general2" class="accordion-collapse collapse" data-bs-parent="#generalAccordion">
                                <div class="accordion-body">
                                    <p>Pour générer des licences :</p>
                                    <ol>
                                        <li>Allez dans la section <strong>"Licences"</strong></li>
                                        <li>Cliquez sur <strong>"Nouvelle Licence"</strong></li>
                                        <li>Sélectionnez le projet associé</li>
                                        <li>Configurez les paramètres :
                                            <ul>
                                                <li>Nom de la licence</li>
                                                <li>Type de licence (Standard, Premium, etc.)</li>
                                                <li>Nombre d'activations maximum</li>
                                                <li>Date d'expiration (optionnelle)</li>
                                            </ul>
                                        </li>
                                        <li>Cliquez sur <strong>"Générer la Licence"</strong></li>
                                    </ol>
                                    <p>La clé de licence sera générée automatiquement et vous pourrez la copier ou la télécharger.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Question 3 -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#general3">
                                    Comment intégrer l'API dans mon application ?
                                </button>
                            </h2>
                            <div id="general3" class="accordion-collapse collapse" data-bs-parent="#generalAccordion">
                                <div class="accordion-body">
                                    <p>Pour intégrer l'API de validation :</p>
                                    <ol>
                                        <li>Allez dans <strong>"Paramètres > Clés API"</strong></li>
                                        <li>Créez une nouvelle clé API</li>
                                        <li>Copiez votre clé API</li>
                                        <li>Utilisez l'endpoint de validation :
                                            <code>POST /api/v1/validate</code>
                                        </li>
                                    </ol>
                                    <p><strong>Exemple de requête :</strong></p>
                                    <pre><code>{
  "api_key": "votre_cle_api",
  "license_key": "cle_de_licence",
  "domain": "example.com"
}</code></pre>
                                    <p>Consultez la <a href="<?php echo e(route('client.support.documentation')); ?>">documentation complète</a> pour plus de détails.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Support Technique -->
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-tools text-warning me-2"></i>
                        Support Technique
                    </h5>
                </div>
                <div class="card-body">
                    <div class="accordion" id="technicalAccordion">
                        <!-- Question 1 -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#tech1">
                                    Ma licence ne fonctionne pas, que faire ?
                                </button>
                            </h2>
                            <div id="tech1" class="accordion-collapse collapse" data-bs-parent="#technicalAccordion">
                                <div class="accordion-body">
                                    <p>Si votre licence ne fonctionne pas, vérifiez :</p>
                                    <ul>
                                        <li><strong>La clé de licence</strong> : Assurez-vous qu'elle est correctement copiée</li>
                                        <li><strong>Le statut du projet</strong> : Le projet doit être actif</li>
                                        <li><strong>La date d'expiration</strong> : Vérifiez que la licence n'est pas expirée</li>
                                        <li><strong>Le domaine</strong> : Pour les licences liées à un domaine</li>
                                        <li><strong>Le nombre d'activations</strong> : Vérifiez que la limite n'est pas atteinte</li>
                                    </ul>
                                    <p>Si le problème persiste, <a href="<?php echo e(route('client.support.create')); ?>">contactez notre support</a>.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Question 2 -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#tech2">
                                    Comment changer le type de licence ?
                                </button>
                            </h2>
                            <div id="tech2" class="accordion-collapse collapse" data-bs-parent="#technicalAccordion">
                                <div class="accordion-body">
                                    <p>Pour modifier le type de licence :</p>
                                    <ol>
                                        <li>Allez dans la liste de vos licences</li>
                                        <li>Cliquez sur la licence à modifier</li>
                                        <li>Cliquez sur <strong>"Modifier"</strong></li>
                                        <li>Changez les paramètres souhaités</li>
                                        <li>Enregistrez les modifications</li>
                                    </ol>
                                    <p><strong>Note :</strong> Certaines modifications peuvent nécessiter la régénération de la clé de licence.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Question 3 -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#tech3">
                                    Problème d'activation de licence
                                </button>
                            </h2>
                            <div id="tech3" class="accordion-collapse collapse" data-bs-parent="#technicalAccordion">
                                <div class="accordion-body">
                                    <p>En cas de problème d'activation :</p>
                                    <ul>
                                        <li><strong>Connectivité</strong> : Vérifiez que votre serveur peut accéder à notre API</li>
                                        <li><strong>Firewall</strong> : Autorisez les connexions sortantes HTTPS</li>
                                        <li><strong>Clé API</strong> : Vérifiez que votre clé API est valide et active</li>
                                        <li><strong>Format de requête</strong> : Respectez le format JSON requis</li>
                                    </ul>
                                    <p><strong>Codes d'erreur courants :</strong></p>
                                    <ul>
                                        <li><code>401</code> : Clé API invalide</li>
                                        <li><code>404</code> : Licence non trouvée</li>
                                        <li><code>403</code> : Licence expirée ou inactive</li>
                                        <li><code>429</code> : Limite d'activations atteinte</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Facturation -->
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-credit-card text-success me-2"></i>
                        Facturation et Abonnements
                    </h5>
                </div>
                <div class="card-body">
                    <div class="accordion" id="billingAccordion">
                        <!-- Question 1 -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#billing1">
                                    Comment changer de plan d'abonnement ?
                                </button>
                            </h2>
                            <div id="billing1" class="accordion-collapse collapse" data-bs-parent="#billingAccordion">
                                <div class="accordion-body">
                                    <p>Pour changer votre plan :</p>
                                    <ol>
                                        <li>Allez dans <strong>"Facturation"</strong></li>
                                        <li>Cliquez sur <strong>"Changer de Plan"</strong></li>
                                        <li>Sélectionnez le nouveau plan souhaité</li>
                                        <li>Confirmez le changement</li>
                                    </ol>
                                    <p><strong>Notes importantes :</strong></p>
                                    <ul>
                                        <li>Les mises à niveau sont effectives immédiatement</li>
                                        <li>Les rétrogradations prennent effet au prochain cycle de facturation</li>
                                        <li>Un prorata est appliqué selon les cas</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Question 2 -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#billing2">
                                    Comment annuler mon abonnement ?
                                </button>
                            </h2>
                            <div id="billing2" class="accordion-collapse collapse" data-bs-parent="#billingAccordion">
                                <div class="accordion-body">
                                    <p>Pour annuler votre abonnement :</p>
                                    <ol>
                                        <li>Allez dans <strong>"Paramètres > Facturation"</strong></li>
                                        <li>Cliquez sur <strong>"Gérer l'Abonnement"</strong></li>
                                        <li>Sélectionnez <strong>"Annuler l'Abonnement"</strong></li>
                                        <li>Confirmez l'annulation</li>
                                    </ol>
                                    <p><strong>Important :</strong></p>
                                    <ul>
                                        <li>Vos données seront conservées jusqu'à la fin de la période payée</li>
                                        <li>Vous pouvez réactiver votre abonnement à tout moment</li>
                                        <li>Aucun remboursement n'est effectué pour la période en cours</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Question 3 -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#billing3">
                                    Problème de paiement, que faire ?
                                </button>
                            </h2>
                            <div id="billing3" class="accordion-collapse collapse" data-bs-parent="#billingAccordion">
                                <div class="accordion-body">
                                    <p>En cas de problème de paiement :</p>
                                    <ol>
                                        <li>Vérifiez que votre carte bancaire est valide</li>
                                        <li>Assurez-vous d'avoir suffisamment de fonds</li>
                                        <li>Vérifiez les informations de facturation</li>
                                        <li>Contactez votre banque si nécessaire</li>
                                    </ol>
                                    <p><strong>Solutions alternatives :</strong></p>
                                    <ul>
                                        <li>Mettre à jour votre méthode de paiement</li>
                                        <li>Utiliser une autre carte bancaire</li>
                                        <li>Contacter notre support pour d'autres options</li>
                                    </ul>
                                    <p>Si le problème persiste, <a href="<?php echo e(route('client.support.create')); ?>">contactez notre équipe</a>.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Support -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card bg-light">
                <div class="card-body text-center">
                    <h5>Vous ne trouvez pas la réponse à votre question ?</h5>
                    <p class="text-muted">Notre équipe de support est là pour vous aider !</p>
                    <div class="d-flex gap-2 justify-content-center">
                        <a href="<?php echo e(route('client.support.create')); ?>" class="btn btn-primary">
                            <i class="fas fa-ticket-alt me-2"></i>Créer un Ticket
                        </a>
                        <a href="<?php echo e(route('client.support.documentation')); ?>" class="btn btn-outline-primary">
                            <i class="fas fa-book me-2"></i>Documentation
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Recherche dans la FAQ
    const searchInput = document.getElementById('searchFAQ');
    const accordionItems = document.querySelectorAll('.accordion-item');
    
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        
        accordionItems.forEach(item => {
            const question = item.querySelector('.accordion-button').textContent.toLowerCase();
            const answer = item.querySelector('.accordion-body').textContent.toLowerCase();
            
            if (question.includes(searchTerm) || answer.includes(searchTerm)) {
                item.style.display = 'block';
                if (searchTerm && searchTerm.length > 2) {
                    // Ouvrir l'accordéon si correspondance
                    const collapse = item.querySelector('.accordion-collapse');
                    const button = item.querySelector('.accordion-button');
                    if (!collapse.classList.contains('show')) {
                        button.click();
                    }
                }
            } else {
                item.style.display = searchTerm ? 'none' : 'block';
            }
        });
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.client', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\Adev\200  -  test\AdminLicence-4.5.1\resources\views\client\support\faq.blade.php ENDPATH**/ ?>