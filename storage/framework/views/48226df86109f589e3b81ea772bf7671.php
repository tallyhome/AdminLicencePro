<?php $__env->startSection('title', 'Configuration Mailchimp'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h1 class="h3">Configuration Mailchimp</h1>
        </div>
    </div>

    <div class="row">
        <!-- Configuration API -->
        <div class="col-md-8 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Paramètres API</h5>
                    <form action="<?php echo e(route('admin.email.providers.mailchimp.update')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>

                        <div class="mb-3">
                            <label for="api_key" class="form-label">Clé API</label>
                            <input type="password" class="form-control <?php $__errorArgs = ['api_key'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                id="api_key" name="api_key" value="<?php echo e(old('api_key', $config->api_key ?? '')); ?>">
                            <?php $__errorArgs = ['api_key'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="mb-3">
                            <label for="list_id" class="form-label">Liste par défaut</label>
                            <select class="form-select <?php $__errorArgs = ['list_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                id="list_id" name="list_id">
                                <option value="">Sélectionnez une liste</option>
                                <?php $__currentLoopData = $lists ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $list): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($list->id); ?>" <?php echo e(old('list_id', $config->list_id ?? '') === $list->id ? 'selected' : ''); ?>>
                                        <?php echo e($list->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['list_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Enregistrer
                            </button>
                            <button type="button" class="btn btn-secondary" onclick="testConnection()">
                                <i class="fas fa-vial me-2"></i> Tester la connexion
                            </button>
                            <button type="button" class="btn btn-info" onclick="syncLists()">
                                <i class="fas fa-sync me-2"></i> Synchroniser les listes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Statistiques -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Statistiques</h5>
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            Listes actives
                            <span class="badge bg-primary rounded-pill"><?php echo e($stats->lists_count ?? 0); ?></span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            Abonnés totaux
                            <span class="badge bg-primary rounded-pill"><?php echo e($stats->subscribers_count ?? 0); ?></span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            Campagnes envoyées
                            <span class="badge bg-primary rounded-pill"><?php echo e($stats->campaigns_count ?? 0); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Campagnes -->
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">Campagnes</h5>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newCampaignModal">
                            <i class="fas fa-plus me-2"></i> Nouvelle campagne
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Nom</th>
                                    <th>Liste</th>
                                    <th>Statut</th>
                                    <th>Taux d'ouverture</th>
                                    <th>Taux de clic</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $campaigns ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $campaign): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($campaign->title); ?></td>
                                    <td><?php echo e($campaign->list_name); ?></td>
                                    <td>
                                        <?php switch($campaign->status):
                                            case ('draft'): ?>
                                                <span class="badge bg-secondary">Brouillon</span>
                                                <?php break; ?>
                                            <?php case ('sending'): ?>
                                                <span class="badge bg-info">En cours</span>
                                                <?php break; ?>
                                            <?php case ('sent'): ?>
                                                <span class="badge bg-success">Envoyée</span>
                                                <?php break; ?>
                                            <?php default: ?>
                                                <span class="badge bg-primary"><?php echo e($campaign->status); ?></span>
                                        <?php endswitch; ?>
                                    </td>
                                    <td><?php echo e($campaign->open_rate); ?>%</td>
                                    <td><?php echo e($campaign->click_rate); ?>%</td>
                                    <td>
                                        <?php if($campaign->status === 'draft'): ?>
                                            <button type="button" class="btn btn-sm btn-success" onclick="sendCampaign('<?php echo e($campaign->id); ?>')">
                                                <i class="fas fa-paper-plane"></i>
                                            </button>
                                        <?php endif; ?>
                                        <button type="button" class="btn btn-sm btn-info" onclick="viewReport('<?php echo e($campaign->id); ?>')">
                                            <i class="fas fa-chart-bar"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="6" class="text-center">Aucune campagne disponible</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <?php if(isset($campaigns) && $campaigns->hasPages()): ?>
                    <div class="d-flex justify-content-center mt-3">
                        <?php echo e($campaigns->links()); ?>

                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Nouvelle Campagne -->
<div class="modal fade" id="newCampaignModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nouvelle campagne</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo e(route('admin.email.providers.mailchimp.campaigns.create')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="campaign_title" class="form-label">Titre de la campagne</label>
                        <input type="text" class="form-control" id="campaign_title" name="title" required>
                    </div>

                    <div class="mb-3">
                        <label for="campaign_list" class="form-label">Liste de diffusion</label>
                        <select class="form-select" id="campaign_list" name="list_id" required>
                            <option value="">Sélectionnez une liste</option>
                            <?php $__currentLoopData = $lists ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $list): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($list->id); ?>"><?php echo e($list->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="campaign_template" class="form-label">Template</label>
                        <select class="form-select" id="campaign_template" name="template_id">
                            <option value="">Sélectionnez un template</option>
                            <?php $__currentLoopData = $templates ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $template): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($template->id); ?>"><?php echo e($template->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="campaign_subject" class="form-label">Sujet</label>
                        <input type="text" class="form-control" id="campaign_subject" name="subject" required>
                    </div>

                    <div class="mb-3">
                        <label for="campaign_content" class="form-label">Contenu</label>
                        <textarea class="form-control" id="campaign_content" name="content" rows="5"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Créer la campagne</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function testConnection() {
    fetch('<?php echo e(route("admin.email.providers.mailchimp.test")); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('La connexion a été établie avec succès !');
        } else {
            alert('Erreur lors du test de connexion : ' + data.message);
        }
    })
    .catch(error => {
        alert('Une erreur est survenue lors du test');
        console.error('Erreur:', error);
    });
}

function syncLists() {
    fetch('<?php echo e(route("admin.email.providers.mailchimp.sync-lists")); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Les listes ont été synchronisées avec succès !');
            location.reload();
        } else {
            alert('Erreur lors de la synchronisation : ' + data.message);
        }
    })
    .catch(error => {
        alert('Une erreur est survenue lors de la synchronisation');
        console.error('Erreur:', error);
    });
}

function sendCampaign(campaignId) {
    if (!confirm('Êtes-vous sûr de vouloir envoyer cette campagne ?')) {
        return;
    }

    fetch(`<?php echo e(route("admin.email.providers.mailchimp.campaigns.send", '')); ?>/${campaignId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('La campagne a été envoyée avec succès !');
            location.reload();
        } else {
            alert('Erreur lors de l\'envoi : ' + data.message);
        }
    })
    .catch(error => {
        alert('Une erreur est survenue lors de l\'envoi');
        console.error('Erreur:', error);
    });
}

function viewReport(campaignId) {
    // Implémenter l'affichage du rapport
}
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\Adev\200  -  test\AdminLicence-4.5.1\resources\views\admin\email\providers\mailchimp.blade.php ENDPATH**/ ?>