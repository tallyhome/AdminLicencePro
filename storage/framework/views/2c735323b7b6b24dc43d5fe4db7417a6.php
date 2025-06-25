<?php $__env->startSection('title', t('serial_keys.details')); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><?php echo e(t('serial_keys.details')); ?></h1>
        <div class="btn-group">
            <a href="<?php echo e(route('admin.serial-keys.index')); ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> <?php echo e(t('common.back')); ?>

            </a>
            <a href="<?php echo e(route('admin.serial-keys.edit', $serialKey)); ?>" class="btn btn-primary">
                <i class="fas fa-edit"></i> <?php echo e(t('serial_keys.edit')); ?>

            </a>
            <?php if($serialKey->status === 'active'): ?>
                <form action="<?php echo e(route('admin.serial-keys.suspend', $serialKey)); ?>" method="POST" class="d-inline">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PATCH'); ?>
                    <button type="submit" class="btn btn-warning" onclick="return confirm('<?php echo e(t('serial_keys.confirm_suspend')); ?>')">
                        <i class="fas fa-pause"></i> <?php echo e(t('serial_keys.suspend')); ?>

                    </button>
                </form>
            <?php elseif($serialKey->status === 'suspended'): ?>
                <form action="<?php echo e(route('admin.serial-keys.revoke', $serialKey)); ?>" method="POST" class="d-inline">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PATCH'); ?>
                    <button type="submit" class="btn btn-danger" onclick="return confirm('<?php echo e(t('serial_keys.confirm_revoke')); ?>')">
                        <i class="fas fa-ban"></i> <?php echo e(t('serial_keys.revoke')); ?>

                    </button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <div class="row">
        <!-- Informations principales -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-key"></i> <?php echo e(t('serial_keys.information')); ?>

                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <dl class="row">
                                <dt class="col-sm-4"><?php echo e(t('serial_keys.license_key')); ?></dt>
                                <dd class="col-sm-8">
                                    <code class="bg-light p-2 rounded"><?php echo e($serialKey->serial_key); ?></code>
                                </dd>

                                <dt class="col-sm-4"><?php echo e(t('serial_keys.project')); ?></dt>
                                <dd class="col-sm-8">
                                    <a href="<?php echo e(route('admin.projects.show', $serialKey->project)); ?>" class="text-decoration-none">
                                        <i class="fas fa-folder"></i> <?php echo e($serialKey->project->name); ?>

                                    </a>
                                </dd>

                                <dt class="col-sm-4">Type de licence</dt>
                                <dd class="col-sm-8">
                                    <?php if($serialKey->licence_type === 'single'): ?>
                                        <span class="badge bg-primary fs-6">
                                            <i class="fas fa-user"></i> Licence Single
                                        </span>
                                        <br><small class="text-muted">1 licence = 1 domaine</small>
                                    <?php else: ?>
                                        <span class="badge bg-warning fs-6">
                                            <i class="fas fa-users"></i> Licence Multi
                                        </span>
                                        <br><small class="text-muted">1 licence = <?php echo e($serialKey->max_accounts); ?> domaines max</small>
                                    <?php endif; ?>
                                </dd>

                                <dt class="col-sm-4"><?php echo e(t('serial_keys.status')); ?></dt>
                                <dd class="col-sm-8">
                                    <span class="badge bg-<?php echo e($serialKey->status === 'active' ? 'success' : ($serialKey->status === 'suspended' ? 'warning' : 'danger')); ?> fs-6">
                                        <i class="fas fa-<?php echo e($serialKey->status === 'active' ? 'check-circle' : ($serialKey->status === 'suspended' ? 'pause-circle' : 'times-circle')); ?>"></i>
                                        <?php echo e(t('serial_keys.status_' . $serialKey->status)); ?>

                                    </span>
                                </dd>
                            </dl>
                        </div>
                        <div class="col-md-6">
                            <dl class="row">
                                <?php if($serialKey->licence_type === 'single'): ?>
                                    <dt class="col-sm-4"><?php echo e(t('serial_keys.domain')); ?></dt>
                                    <dd class="col-sm-8">
                                        <?php if($serialKey->domain): ?>
                                            <i class="fas fa-globe text-success"></i> <?php echo e($serialKey->domain); ?>

                                        <?php else: ?>
                                            <i class="fas fa-globe text-muted"></i> <?php echo e(t('serial_keys.not_specified')); ?>

                                        <?php endif; ?>
                                    </dd>

                                    <dt class="col-sm-4"><?php echo e(t('serial_keys.ip_address')); ?></dt>
                                    <dd class="col-sm-8">
                                        <?php if($serialKey->ip_address): ?>
                                            <i class="fas fa-network-wired text-success"></i> <?php echo e($serialKey->ip_address); ?>

                                        <?php else: ?>
                                            <i class="fas fa-network-wired text-muted"></i> <?php echo e(t('serial_keys.not_specified')); ?>

                                        <?php endif; ?>
                                    </dd>
                                <?php else: ?>
                                    <dt class="col-sm-4">Utilisation</dt>
                                    <dd class="col-sm-8">
                                        <div class="d-flex align-items-center mb-2">
                                            <span class="me-2 fw-bold"><?php echo e($serialKey->used_accounts); ?>/<?php echo e($serialKey->max_accounts); ?> comptes</span>
                                        </div>
                                        <div class="progress" style="height: 8px;">
                                            <div class="progress-bar <?php echo e($serialKey->used_accounts >= $serialKey->max_accounts ? 'bg-danger' : ($serialKey->used_accounts > $serialKey->max_accounts * 0.8 ? 'bg-warning' : 'bg-success')); ?>" 
                                                 style="width: <?php echo e($serialKey->max_accounts > 0 ? ($serialKey->used_accounts / $serialKey->max_accounts) * 100 : 0); ?>%"></div>
                                        </div>
                                        <small class="text-muted">
                                            <?php echo e($serialKey->max_accounts - $serialKey->used_accounts); ?> slot(s) disponible(s)
                                        </small>
                                    </dd>

                                    <dt class="col-sm-4">Comptes actifs</dt>
                                    <dd class="col-sm-8">
                                        <span class="badge bg-info"><?php echo e($serialKey->activeAccounts()->count()); ?></span> comptes actifs
                                    </dd>
                                <?php endif; ?>

                                <dt class="col-sm-4"><?php echo e(t('serial_keys.expiration_date')); ?></dt>
                                <dd class="col-sm-8">
                                    <?php if($serialKey->expires_at): ?>
                                        <i class="fas fa-calendar-alt text-warning"></i> <?php echo e($serialKey->expires_at->format('d/m/Y H:i')); ?>

                                        <?php if($serialKey->expires_at->isPast()): ?>
                                            <span class="badge bg-danger ms-2">Expirée</span>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <i class="fas fa-infinity text-success"></i> <?php echo e(t('serial_keys.no_expiration')); ?>

                                    <?php endif; ?>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <?php if($serialKey->licence_type === 'multi'): ?>
                <!-- Gestion des comptes pour les licences multi -->
                <div class="card mt-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">
                            <i class="fas fa-users"></i> Comptes actifs (<?php echo e($serialKey->accounts()->count()); ?>)
                        </h4>
                        <?php if($serialKey->canAcceptNewAccount()): ?>
                            <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#addAccountModal">
                                <i class="fas fa-plus"></i> Ajouter un compte
                            </button>
                        <?php endif; ?>
                    </div>
                    <div class="card-body">
                        <?php if($serialKey->accounts()->count() > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Domaine</th>
                                            <th>IP</th>
                                            <th>Statut</th>
                                            <th>Activé le</th>
                                            <th>Dernière utilisation</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $serialKey->accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td>
                                                    <i class="fas fa-globe"></i> <?php echo e($account->domain ?? 'Non spécifié'); ?>

                                                </td>
                                                <td><?php echo e($account->ip_address ?? 'Non spécifié'); ?></td>
                                                <td>
                                                    <span class="badge bg-<?php echo e($account->status === 'active' ? 'success' : ($account->status === 'suspended' ? 'warning' : 'danger')); ?>">
                                                        <?php echo e(ucfirst($account->status)); ?>

                                                    </span>
                                                </td>
                                                <td><?php echo e($account->activated_at?->format('d/m/Y H:i') ?? '-'); ?></td>
                                                <td><?php echo e($account->last_used_at?->format('d/m/Y H:i') ?? 'Jamais'); ?></td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <?php if($account->status === 'active'): ?>
                                                            <form action="<?php echo e(route('admin.serial-keys.accounts.suspend', [$serialKey, $account])); ?>" method="POST" class="d-inline">
                                                                <?php echo csrf_field(); ?>
                                                                <?php echo method_field('PATCH'); ?>
                                                                <button type="submit" class="btn btn-warning btn-sm" title="Suspendre" onclick="return confirm('Êtes-vous sûr de vouloir suspendre ce compte ?')">
                                                                    <i class="fas fa-pause"></i>
                                                                </button>
                                                            </form>
                                                        <?php elseif($account->status === 'suspended'): ?>
                                                            <form action="<?php echo e(route('admin.serial-keys.accounts.reactivate', [$serialKey, $account])); ?>" method="POST" class="d-inline">
                                                                <?php echo csrf_field(); ?>
                                                                <?php echo method_field('PATCH'); ?>
                                                                <button type="submit" class="btn btn-success btn-sm" title="Réactiver" onclick="return confirm('Êtes-vous sûr de vouloir réactiver ce compte ?')">
                                                                    <i class="fas fa-play"></i>
                                                                </button>
                                                            </form>
                                                        <?php endif; ?>
                                                        <form action="<?php echo e(route('admin.serial-keys.accounts.destroy', [$serialKey, $account])); ?>" method="POST" class="d-inline">
                                                            <?php echo csrf_field(); ?>
                                                            <?php echo method_field('DELETE'); ?>
                                                            <button type="submit" class="btn btn-danger btn-sm" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce compte ? Cette action est irréversible.')">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="text-center text-muted py-4">
                                <i class="fas fa-users fa-3x mb-3 opacity-50"></i>
                                <p>Aucun compte activé pour cette licence multi.</p>
                                <?php if($serialKey->canAcceptNewAccount()): ?>
                                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAccountModal">
                                        <i class="fas fa-plus"></i> Ajouter le premier compte
                                    </button>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Statistiques et actions -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        <i class="fas fa-chart-pie"></i> Statistiques
                    </h4>
                </div>
                <div class="card-body">
                    <?php if($serialKey->licence_type === 'single'): ?>
                        <div class="text-center">
                            <div class="mb-3">
                                <?php if($serialKey->used_accounts > 0): ?>
                                    <i class="fas fa-check-circle fa-3x text-success"></i>
                                    <h5 class="mt-2 text-success">Licence utilisée</h5>
                                <?php else: ?>
                                    <i class="fas fa-clock fa-3x text-warning"></i>
                                    <h5 class="mt-2 text-warning">En attente d'activation</h5>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="border-end">
                                    <h3 class="text-primary"><?php echo e($serialKey->used_accounts); ?></h3>
                                    <small class="text-muted">Utilisés</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <h3 class="text-success"><?php echo e($serialKey->max_accounts - $serialKey->used_accounts); ?></h3>
                                <small class="text-muted">Disponibles</small>
                            </div>
                        </div>
                        <hr>
                        <div class="d-grid">
                            <small class="text-muted mb-2">Taux d'utilisation</small>
                            <div class="progress mb-2" style="height: 20px;">
                                <div class="progress-bar" style="width: <?php echo e($serialKey->max_accounts > 0 ? ($serialKey->used_accounts / $serialKey->max_accounts) * 100 : 0); ?>%">
                                    <?php echo e($serialKey->max_accounts > 0 ? round(($serialKey->used_accounts / $serialKey->max_accounts) * 100) : 0); ?>%
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <hr>
                    <div class="d-grid gap-2">
                        <small class="text-muted">Actions rapides</small>
                        <?php if($serialKey->status === 'active'): ?>
                            <form action="<?php echo e(route('admin.serial-keys.suspend', $serialKey)); ?>" method="POST" class="d-inline">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PATCH'); ?>
                                <button type="submit" class="btn btn-outline-warning btn-sm w-100" onclick="return confirm('Êtes-vous sûr de vouloir suspendre cette clé de licence ?')">
                                    <i class="fas fa-pause"></i> Suspendre
                                </button>
                            </form>
                        <?php elseif($serialKey->status === 'suspended'): ?>
                            <form action="<?php echo e(route('admin.serial-keys.reactivate', $serialKey)); ?>" method="POST" class="d-inline">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PATCH'); ?>
                                <button type="submit" class="btn btn-outline-success btn-sm w-100" onclick="return confirm('Êtes-vous sûr de vouloir réactiver cette clé de licence ?')">
                                    <i class="fas fa-play"></i> Réactiver
                                </button>
                            </form>
                        <?php endif; ?>
                        <a href="<?php echo e(route('admin.serial-keys.history', $serialKey)); ?>" class="btn btn-outline-secondary btn-sm" onclick="showHistory(event)">
                            <i class="fas fa-history"></i> Historique
                        </a>
                        <button class="btn btn-outline-info btn-sm" onclick="exportData()">
                            <i class="fas fa-download"></i> Exporter les données
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if($serialKey->licence_type === 'multi'): ?>
    <!-- Modal pour ajouter un compte -->
    <div class="modal fade" id="addAccountModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ajouter un nouveau compte</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="<?php echo e(route('admin.serial-keys.accounts.store', $serialKey)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="domain" class="form-label">Domaine *</label>
                            <input type="text" class="form-control" id="domain" name="domain" placeholder="exemple.com" required>
                        </div>
                        <div class="mb-3">
                            <label for="ip_address" class="form-label">Adresse IP (optionnel)</label>
                            <input type="text" class="form-control" id="ip_address" name="ip_address" placeholder="192.168.1.1">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Ajouter le compte</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>

<script>
function showHistory(event) {
    event.preventDefault();
    
    // Créer et afficher un modal pour l'historique
    const modal = document.createElement('div');
    modal.className = 'modal fade';
    modal.id = 'historyModal';
    modal.setAttribute('tabindex', '-1');
    modal.innerHTML = `
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-history"></i> Historique de la clé <?php echo e($serialKey->serial_key); ?>

                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Chargement...</span>
                        </div>
                        <p class="mt-2">Chargement de l'historique...</p>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    const bootstrapModal = new bootstrap.Modal(modal);
    bootstrapModal.show();
    
    // Charger l'historique via AJAX
    fetch('<?php echo e(route("admin.serial-keys.history", $serialKey)); ?>', {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            let historyHtml = '';
            if (data.history.length > 0) {
                historyHtml = `
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Action</th>
                                    <th>Détails</th>
                                    <th>Administrateur</th>
                                    <th>IP</th>
                                </tr>
                            </thead>
                            <tbody>
                `;
                
                data.history.forEach(item => {
                    const actionBadge = getActionBadge(item.action);
                    historyHtml += `
                        <tr>
                            <td>${item.date}</td>
                            <td>${actionBadge}</td>
                            <td>${item.details || '-'}</td>
                            <td>${item.admin}</td>
                            <td><small class="text-muted">${item.ip_address}</small></td>
                        </tr>
                    `;
                });
                
                historyHtml += `
                            </tbody>
                        </table>
                    </div>
                `;
            } else {
                historyHtml = `
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        Aucun historique disponible pour cette clé de licence.
                    </div>
                `;
            }
            
            modal.querySelector('.modal-body').innerHTML = historyHtml;
        } else {
            modal.querySelector('.modal-body').innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    Erreur lors du chargement de l'historique.
                </div>
            `;
        }
    })
    .catch(error => {
        modal.querySelector('.modal-body').innerHTML = `
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle"></i>
                Erreur lors du chargement de l'historique : ${error.message}
            </div>
        `;
    });
    
    // Nettoyer le modal quand il est fermé
    modal.addEventListener('hidden.bs.modal', () => {
        modal.remove();
    });
}

function getActionBadge(action) {
    const badges = {
        'create': '<span class="badge bg-success">Création</span>',
        'update': '<span class="badge bg-primary">Modification</span>',
        'delete': '<span class="badge bg-danger">Suppression</span>',
        'suspend': '<span class="badge bg-warning">Suspension</span>',
        'reactivate': '<span class="badge bg-success">Réactivation</span>',
        'revoke': '<span class="badge bg-danger">Révocation</span>',
        'add_account': '<span class="badge bg-info">Ajout compte</span>',
        'remove_account': '<span class="badge bg-warning">Suppression compte</span>',
        'suspend_account': '<span class="badge bg-warning">Suspension compte</span>',
        'reactivate_account': '<span class="badge bg-success">Réactivation compte</span>',
        'status_change': '<span class="badge bg-primary">Changement statut</span>'
    };
    
    return badges[action] || `<span class="badge bg-secondary">${action}</span>`;
}

function exportData() {
    // Créer les données à exporter
    const data = {
        serial_key: '<?php echo e($serialKey->serial_key); ?>',
        project: '<?php echo e($serialKey->project->name); ?>',
        licence_type: '<?php echo e($serialKey->licence_type); ?>',
        status: '<?php echo e($serialKey->status); ?>',
        max_accounts: <?php echo e($serialKey->max_accounts); ?>,
        used_accounts: <?php echo e($serialKey->used_accounts); ?>,
        domain: '<?php echo e($serialKey->domain ?? ""); ?>',
        ip_address: '<?php echo e($serialKey->ip_address ?? ""); ?>',
        expires_at: '<?php echo e($serialKey->expires_at?->format("d/m/Y H:i") ?? "Aucune"); ?>',
        created_at: '<?php echo e($serialKey->created_at->format("d/m/Y H:i")); ?>',
        <?php if($serialKey->licence_type === 'multi'): ?>
        accounts: [
            <?php $__currentLoopData = $serialKey->accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            {
                domain: '<?php echo e($account->domain); ?>',
                ip_address: '<?php echo e($account->ip_address ?? ""); ?>',
                status: '<?php echo e($account->status); ?>',
                activated_at: '<?php echo e($account->activated_at?->format("d/m/Y H:i") ?? ""); ?>',
                last_used_at: '<?php echo e($account->last_used_at?->format("d/m/Y H:i") ?? "Jamais"); ?>'
            }<?php echo e(!$loop->last ? ',' : ''); ?>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        ]
        <?php endif; ?>
    };
    
    // Créer le fichier JSON
    const jsonString = JSON.stringify(data, null, 2);
    const blob = new Blob([jsonString], { type: 'application/json' });
    const url = URL.createObjectURL(blob);
    
    // Créer un lien de téléchargement
    const link = document.createElement('a');
    link.href = url;
    link.download = `licence_<?php echo e($serialKey->serial_key); ?>_<?php echo e(now()->format('Y-m-d_H-i-s')); ?>.json`;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    URL.revokeObjectURL(url);
    
    // Afficher une notification de succès
    const toast = document.createElement('div');
    toast.className = 'toast-container position-fixed top-0 end-0 p-3';
    toast.innerHTML = `
        <div class="toast show" role="alert">
            <div class="toast-header">
                <i class="fas fa-download text-success me-2"></i>
                <strong class="me-auto">Export réussi</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body">
                Les données de la licence ont été exportées avec succès.
            </div>
        </div>
    `;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.remove();
    }, 5000);
}
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\Adev\200  -  test\AdminLicence-4.5.1\resources\views/admin/serial-keys/show.blade.php ENDPATH**/ ?>