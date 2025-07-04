<?php $__env->startSection('title', 'Mes Licences'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    <!-- En-tête avec le titre -->
    <div class="card bg-primary text-white mb-4">
        <div class="card-body p-4">
            <h4 class="mb-2">Mes Licences</h4>
            <p class="mb-0">Gérez vos licences et leurs activations</p>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="row g-4 mb-4">
        <!-- Total Licences -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card-stats">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="stat-icon bg-primary">
                            <i class="fas fa-key"></i>
                        </div>
                        <div class="text-end">
                            <h3 class="mb-1"><?php echo e($stats['total']); ?></h3>
                            <p class="text-muted mb-0">Total</p>
                        </div>
                    </div>
                    <h6 class="mb-2">Licences</h6>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-chart-line text-primary me-1"></i>
                        <small class="text-primary">Vue d'ensemble</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Licences Actives -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card-stats">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="stat-icon bg-success">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="text-end">
                            <h3 class="mb-1"><?php echo e($stats['active']); ?></h3>
                            <p class="text-muted mb-0">Actives</p>
                        </div>
                    </div>
                    <h6 class="mb-2">Licences Actives</h6>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-arrow-up text-success me-1"></i>
                        <small class="text-success">En cours d'utilisation</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Licences Expirant Bientôt -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card-stats">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="stat-icon bg-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="text-end">
                            <h3 class="mb-1"><?php echo e($stats['expiring']); ?></h3>
                            <p class="text-muted mb-0">À surveiller</p>
                        </div>
                    </div>
                    <h6 class="mb-2">Expirent Bientôt</h6>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-clock text-warning me-1"></i>
                        <small class="text-warning">Dans les 30 jours</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Licences Expirées -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card-stats">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="stat-icon bg-danger">
                            <i class="fas fa-times-circle"></i>
                        </div>
                        <div class="text-end">
                            <h3 class="mb-1"><?php echo e($stats['expired']); ?></h3>
                            <p class="text-muted mb-0">Expirées</p>
                        </div>
                    </div>
                    <h6 class="mb-2">Licences Expirées</h6>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation text-danger me-1"></i>
                        <small class="text-danger">À renouveler</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres et Liste -->
    <div class="row g-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <!-- Filtres -->
                    <div class="mb-4">
                        <form method="GET" action="<?php echo e(route('client.licenses.index')); ?>" class="row g-3">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Recherche</label>
                                    <input type="text" class="form-control" name="search" 
                                           value="<?php echo e(request('search')); ?>" placeholder="Nom, clé...">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="form-label">Projet</label>
                                    <select class="form-select" name="project">
                                        <option value="">Tous</option>
                                        <?php $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($project->id); ?>" <?php echo e(request('project') == $project->id ? 'selected' : ''); ?>>
                                                <?php echo e($project->name); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="form-label">Statut</label>
                                    <select class="form-select" name="status">
                                        <option value="">Tous</option>
                                        <option value="active" <?php echo e(request('status') == 'active' ? 'selected' : ''); ?>>Active</option>
                                        <option value="inactive" <?php echo e(request('status') == 'inactive' ? 'selected' : ''); ?>>Inactive</option>
                                        <option value="expired" <?php echo e(request('status') == 'expired' ? 'selected' : ''); ?>>Expirée</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="form-label">Type</label>
                                    <select class="form-select" name="type">
                                        <option value="">Tous</option>
                                        <option value="single" <?php echo e(request('type') == 'single' ? 'selected' : ''); ?>>Unique</option>
                                        <option value="multi" <?php echo e(request('type') == 'multi' ? 'selected' : ''); ?>>Multi-domaine</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <div class="d-flex gap-2 w-100">
                                    <button type="submit" class="btn btn-primary flex-grow-1">
                                        <i class="fas fa-search me-2"></i>Filtrer
                                    </button>
                                    <a href="<?php echo e(route('client.licenses.index')); ?>" class="btn btn-secondary">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Liste des licences -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="card-title mb-0">Liste des Licences</h5>
                        <a href="<?php echo e(route('client.licenses.create')); ?>" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Nouvelle Licence
                        </a>
                    </div>

                    <?php if($licenses->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Nom</th>
                                        <th>Projet</th>
                                        <th>Type</th>
                                        <th>Statut</th>
                                        <th>Expiration</th>
                                        <th>Créée le</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $licenses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $license): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="me-3">
                                                        <div class="bg-primary rounded-circle p-2 text-white">
                                                            <i class="fas fa-key"></i>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0"><?php echo e($license->name); ?></h6>
                                                        <small class="text-muted"><?php echo e(Str::limit($license->license_key, 20)); ?>...</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <?php if($license->project): ?>
                                                    <span class="badge bg-info"><?php echo e($license->project->name); ?></span>
                                                <?php else: ?>
                                                    <span class="text-muted">Aucun</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if($license->type === 'single'): ?>
                                                    <span class="badge bg-primary">Unique</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Multi-domaine</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php switch($license->status):
                                                    case ('active'): ?>
                                                        <span class="badge bg-success">Active</span>
                                                        <?php break; ?>
                                                    <?php case ('inactive'): ?>
                                                        <span class="badge bg-secondary">Inactive</span>
                                                        <?php break; ?>
                                                    <?php case ('expired'): ?>
                                                        <span class="badge bg-danger">Expirée</span>
                                                        <?php break; ?>
                                                    <?php default: ?>
                                                        <span class="badge bg-warning"><?php echo e(ucfirst($license->status)); ?></span>
                                                <?php endswitch; ?>
                                            </td>
                                            <td>
                                                <?php if($license->expires_at): ?>
                                                    <span class="<?php if($license->expires_at->isPast()): ?> text-danger <?php elseif($license->expires_at->diffInDays() < 30): ?> text-warning <?php else: ?> text-success <?php endif; ?>">
                                                        <?php echo e($license->expires_at->format('d/m/Y')); ?>

                                                    </span>
                                                <?php else: ?>
                                                    <span class="text-muted">Jamais</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo e($license->created_at->format('d/m/Y H:i')); ?></td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="<?php echo e(route('client.licenses.show', $license)); ?>" class="btn btn-sm btn-info">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="<?php echo e(route('client.licenses.edit', $license)); ?>" class="btn btn-sm btn-warning">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-danger" onclick="deleteLicense(<?php echo e($license->id); ?>)">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-end mt-4">
                            <?php echo e($licenses->links()); ?>

                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-key fa-3x text-muted mb-3"></i>
                            <h5>Aucune licence pour le moment</h5>
                            <p class="text-muted">Commencez par créer votre première licence</p>
                            <a href="<?php echo e(route('client.licenses.create')); ?>" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Créer une licence
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    function deleteLicense(licenseId) {
        if (confirm('Êtes-vous sûr de vouloir supprimer cette licence ?')) {
            fetch(`/client/licenses/${licenseId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    alert('Une erreur est survenue lors de la suppression de la licence.');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Une erreur est survenue lors de la suppression de la licence.');
            });
        }
    }
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.client', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\Adev\200  -  test\AdminLicence-4.5.1\resources\views\client\licenses\index.blade.php ENDPATH**/ ?>