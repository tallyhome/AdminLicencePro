

<?php $__env->startSection('title', 'Mes Licences'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- En-tête de page -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Mes Licences</h1>
        <a href="<?php echo e(route('client.licenses.create')); ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Nouvelle Licence
        </a>
    </div>

    <!-- Cartes de statistiques -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Licences</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($stats['total']); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-key fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Actives</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($stats['active']); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Expirent Bientôt</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($stats['expiring']); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Expirées</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($stats['expired']); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres et recherche -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filtres</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="<?php echo e(route('client.licenses.index')); ?>">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="search">Recherche</label>
                            <input type="text" class="form-control" id="search" name="search" 
                                   value="<?php echo e(request('search')); ?>" placeholder="Nom, clé...">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="project">Projet</label>
                            <select class="form-control" id="project" name="project">
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
                            <label for="status">Statut</label>
                            <select class="form-control" id="status" name="status">
                                <option value="">Tous</option>
                                <option value="active" <?php echo e(request('status') == 'active' ? 'selected' : ''); ?>>Active</option>
                                <option value="inactive" <?php echo e(request('status') == 'inactive' ? 'selected' : ''); ?>>Inactive</option>
                                <option value="expired" <?php echo e(request('status') == 'expired' ? 'selected' : ''); ?>>Expirée</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="type">Type</label>
                            <select class="form-control" id="type" name="type">
                                <option value="">Tous</option>
                                <option value="single" <?php echo e(request('type') == 'single' ? 'selected' : ''); ?>>Unique</option>
                                <option value="multi" <?php echo e(request('type') == 'multi' ? 'selected' : ''); ?>>Multi-domaine</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <div class="d-flex">
                                <button type="submit" class="btn btn-primary mr-2">
                                    <i class="fas fa-search"></i> Filtrer
                                </button>
                                <a href="<?php echo e(route('client.licenses.index')); ?>" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Reset
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Table des licences -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Liste des Licences</h6>
        </div>
        <div class="card-body">
            <?php if($licenses->count() > 0): ?>
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
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
                                        <strong><?php echo e($license->name); ?></strong><br>
                                        <small class="text-muted"><?php echo e(Str::limit($license->license_key, 20)); ?>...</small>
                                    </td>
                                    <td>
                                        <?php if($license->project): ?>
                                            <span class="badge badge-info"><?php echo e($license->project->name); ?></span>
                                        <?php else: ?>
                                            <span class="text-muted">Aucun</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($license->type === 'single'): ?>
                                            <span class="badge badge-primary">Unique</span>
                                        <?php else: ?>
                                            <span class="badge badge-secondary">Multi-domaine</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php switch($license->status):
                                            case ('active'): ?>
                                                <span class="badge badge-success">Active</span>
                                                <?php break; ?>
                                            <?php case ('inactive'): ?>
                                                <span class="badge badge-secondary">Inactive</span>
                                                <?php break; ?>
                                            <?php case ('expired'): ?>
                                                <span class="badge badge-danger">Expirée</span>
                                                <?php break; ?>
                                            <?php default: ?>
                                                <span class="badge badge-secondary"><?php echo e(ucfirst($license->status)); ?></span>
                                        <?php endswitch; ?>
                                    </td>
                                    <td>
                                        <?php if($license->expires_at): ?>
                                            <?php echo e($license->expires_at->format('d/m/Y')); ?>

                                            <?php if($license->expires_at->isPast()): ?>
                                                <br><small class="text-danger">Expirée</small>
                                            <?php elseif($license->expires_at->diffInDays() <= 30): ?>
                                                <br><small class="text-warning"><?php echo e($license->expires_at->diffForHumans()); ?></small>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="text-muted">Illimitée</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e($license->created_at->format('d/m/Y')); ?></td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" 
                                                    data-toggle="dropdown">
                                                Actions
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="<?php echo e(route('client.licenses.show', $license)); ?>">
                                                    <i class="fas fa-eye"></i> Voir
                                                </a>
                                                <a class="dropdown-item" href="<?php echo e(route('client.licenses.edit', $license)); ?>">
                                                    <i class="fas fa-edit"></i> Modifier
                                                </a>
                                                <a class="dropdown-item" href="<?php echo e(route('client.licenses.download', $license)); ?>">
                                                    <i class="fas fa-download"></i> Télécharger
                                                </a>
                                                <div class="dropdown-divider"></div>
                                                <?php if($license->status === 'active'): ?>
                                                    <form method="POST" action="<?php echo e(route('client.licenses.toggle-status', $license)); ?>" style="display: inline;">
                                                        <?php echo csrf_field(); ?>
                                                        <button type="submit" class="dropdown-item text-warning">
                                                            <i class="fas fa-pause"></i> Désactiver
                                                        </button>
                                                    </form>
                                                <?php else: ?>
                                                    <form method="POST" action="<?php echo e(route('client.licenses.toggle-status', $license)); ?>" style="display: inline;">
                                                        <?php echo csrf_field(); ?>
                                                        <button type="submit" class="dropdown-item text-success">
                                                            <i class="fas fa-play"></i> Activer
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                                <div class="dropdown-divider"></div>
                                                <form method="POST" action="<?php echo e(route('client.licenses.destroy', $license)); ?>" 
                                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette licence ?')" style="display: inline;">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" class="dropdown-item text-danger">
                                                        <i class="fas fa-trash"></i> Supprimer
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    <?php echo e($licenses->appends(request()->query())->links()); ?>

                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-key fa-3x text-gray-300 mb-3"></i>
                    <h5 class="text-gray-600">Aucune licence trouvée</h5>
                    <p class="text-gray-500">Commencez par créer votre première licence.</p>
                    <a href="<?php echo e(route('client.licenses.create')); ?>" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Créer une licence
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('client.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\Adev\200  -  test\AdminLicence-4.5.1\resources\views\client\licenses\index.blade.php ENDPATH**/ ?>