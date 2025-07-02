<?php $__env->startSection('title', 'Configuration Rapidmail'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h1 class="h3">Configuration Rapidmail</h1>
        </div>
    </div>

    <div class="row">
        <!-- Configuration API -->
        <div class="col-md-8 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Paramètres API</h5>
                    <form action="<?php echo e(route('admin.email.providers.rapidmail.update')); ?>" method="POST">
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
                            <label for="default_list" class="form-label">Liste par défaut</label>
                            <select class="form-select <?php $__errorArgs = ['default_list'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                id="default_list" name="default_list">
                                <option value="">Sélectionnez une liste</option>
                                <?php $__currentLoopData = $lists ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $list): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($list->id); ?>" <?php echo e(old('default_list', $config->default_list ?? '') === $list->id ? 'selected' : ''); ?>>
                                        <?php echo e($list->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['default_list'];
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
                            Destinataires totaux
                            <span class="badge bg-primary rounded-pill"><?php echo e($stats->recipients_count ?? 0); ?></span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            Mailings envoyés
                            <span class="badge bg-primary rounded-pill"><?php echo e($stats->mailings_count ?? 0); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Listes de destinataires -->
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">Listes de destinataires</h5>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newListModal">
                            <i class="fas fa-plus me-2"></i> Nouvelle liste
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Nom</th>
                                    <th>Description</th>
                                    <th>Destinataires</th>
                                    <th>Dernière modification</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $lists ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $list): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($list->name); ?></td>
                                    <td><?php echo e($list->description); ?></td>
                                    <td><?php echo e($list->recipients_count); ?></td>
                                    <td><?php echo e($list->updated_at->format('d/m/Y H:i')); ?></td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-info" onclick="viewRecipients('<?php echo e($list->id); ?>')">
                                            <i class="fas fa-users"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-primary" onclick="createMailing('<?php echo e($list->id); ?>')">
                                            <i class="fas fa-paper-plane"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="5" class="text-center">Aucune liste disponible</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mailings -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">Mailings</h5>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newMailingModal">
                            <i class="fas fa-plus me-2"></i> Nouveau mailing
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Sujet</th>
                                    <th>Liste</th>
                                    <th>Statut</th>
                                    <th>Taux d'ouverture</th>
                                    <th>Taux de clic</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $mailings ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mailing): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($mailing->subject); ?></td>
                                    <td><?php echo e($mailing->list_name); ?></td>
                                    <td>
                                        <?php switch($mailing->status):
                                            case ('draft'): ?>
                                                <span class="badge bg-secondary">Brouillon</span>
                                                <?php break; ?>
                                            <?php case ('sending'): ?>
                                                <span class="badge bg-info">En cours</span>
                                                <?php break; ?>
                                            <?php case ('sent'): ?>
                                                <span class="badge bg-success">Envoyé</span>
                                                <?php break; ?>
                                            <?php default: ?>
                                                <span class="badge bg-primary"><?php echo e($mailing->status); ?></span>
                                        <?php endswitch; ?>
                                    </td>
                                    <td><?php echo e($mailing->open_rate); ?>%</td>
                                    <td><?php echo e($mailing->click_rate); ?>%</td>
                                    <td>
                                        <?php if($mailing->status === 'draft'): ?>
                                            <button type="button" class="btn btn-sm btn-success" onclick="sendMailing('<?php echo e($mailing->id); ?>')">
                                                <i class="fas fa-paper-plane"></i>
                                            </button>
                                        <?php endif; ?>
                                        <button type="button" class="btn btn-sm btn-info" onclick="viewStats('<?php echo e($mailing->id); ?>')">
                                            <i class="fas fa-chart-bar"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="6" class="text-center">Aucun mailing disponible</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <?php if(isset($mailings) && $mailings->hasPages()): ?>
                    <div class="d-flex justify-content-center mt-3">
                        <?php echo e($mailings->links()); ?>

                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Nouvelle Liste -->
<div class="modal fade" id="newListModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nouvelle liste de destinataires</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo e(route('admin.email.providers.rapidmail.lists.create')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="list_name" class="form-label">Nom de la liste</label>
                        <input type="text" class="form-control" id="list_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="list_description" class="form-label">Description</label>
                        <textarea class="form-control" id="list_description" name="description" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Créer la liste</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Nouveau Mailing -->
<div class="modal fade" id="newMailingModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nouveau mailing</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo e(route('admin.email.providers.rapidmail.mailings.create')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="mailing_list" class="form-label">Liste de destinataires</label>
                        <select class="form-select" id="mailing_list" name="list_id" required>
                            <option value="">Sélectionnez une liste</option>
                            <?php $__currentLoopData = $lists ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $list): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($list->id); ?>"><?php echo e($list->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="mailing_subject" class="form-label">Sujet</label>
                        <input type="text" class="form-control" id="mailing_subject" name="subject" required>
                    </div>

                    <div class="mb-3">
                        <label for="mailing_content" class="form-label">Contenu</label>
                        <textarea class="form-control" id="mailing_content" name="content" rows="10"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Créer le mailing</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function testConnection() {
    fetch('<?php echo e(route("admin.email.providers.rapidmail.test")); ?>', {
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

function viewRecipients(listId) {
    // Implémenter l'affichage des destinataires
}

function createMailing(listId) {
    document.getElementById('mailing_list').value = listId;
    new bootstrap.Modal(document.getElementById('newMailingModal')).show();
}

function sendMailing(mailingId) {
    if (!confirm('Êtes-vous sûr de vouloir envoyer ce mailing ?')) {
        return;
    }

    fetch(`<?php echo e(route("admin.email.providers.rapidmail.mailings.send", '')); ?>/${mailingId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Le mailing a été envoyé avec succès !');
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

function viewStats(mailingId) {
    window.location.href = `<?php echo e(route("admin.email.providers.rapidmail.mailings.stats", '')); ?>/${mailingId}`;
}
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\Adev\200  -  test\AdminLicence-4.5.1\resources\views\admin\email\providers\rapidmail.blade.php ENDPATH**/ ?>