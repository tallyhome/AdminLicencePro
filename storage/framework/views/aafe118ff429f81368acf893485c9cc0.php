

<?php $__env->startSection('title', 'Mes Projets'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Mes Projets</h1>
            <p class="text-muted">Gérez vos projets et leurs licences</p>
        </div>
        <a href="<?php echo e(route('client.projects.create')); ?>" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>
            Nouveau Projet
        </a>
    </div>

    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Projets
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($projects->total()); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-folder fa-2x text-gray-300"></i>
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
                                Projets Actifs
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo e($projects->where('status', 'active')->count()); ?>

                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Total Licences
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo e($projects->sum(function($project) { return $project->serialKeys->count(); })); ?>

                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-key fa-2x text-gray-300"></i>
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
                                Licences Actives
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo e($projects->sum(function($project) { return $project->serialKeys->where('status', 'active')->count(); })); ?>

                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-rocket fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filtres</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="<?php echo e(route('client.projects.index')); ?>">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="search">Rechercher</label>
                            <input type="text" class="form-control" id="search" name="search" 
                                   value="<?php echo e(request('search')); ?>" placeholder="Nom du projet...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="status">Statut</label>
                            <select class="form-control" id="status" name="status">
                                <option value="">Tous les statuts</option>
                                <option value="active" <?php echo e(request('status') === 'active' ? 'selected' : ''); ?>>Actif</option>
                                <option value="inactive" <?php echo e(request('status') === 'inactive' ? 'selected' : ''); ?>>Inactif</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="sort">Trier par</label>
                            <select class="form-control" id="sort" name="sort">
                                <option value="created_at" <?php echo e(request('sort') === 'created_at' ? 'selected' : ''); ?>>Date de création</option>
                                <option value="name" <?php echo e(request('sort') === 'name' ? 'selected' : ''); ?>>Nom</option>
                                <option value="updated_at" <?php echo e(request('sort') === 'updated_at' ? 'selected' : ''); ?>>Dernière modification</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <div>
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fas fa-search"></i> Filtrer
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des projets -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Liste des Projets</h6>
        </div>
        <div class="card-body">
            <?php if($projects->count() > 0): ?>
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Description</th>
                                <th>Site Web</th>
                                <th>Licences</th>
                                <th>Statut</th>
                                <th>Créé le</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="mr-3">
                                            <div class="icon-circle bg-primary">
                                                <i class="fas fa-folder text-white"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="font-weight-bold"><?php echo e($project->name); ?></div>
                                            <div class="small text-gray-500">ID: <?php echo e($project->id); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-truncate" style="max-width: 200px;" title="<?php echo e($project->description); ?>">
                                        <?php echo e($project->description ?: 'Aucune description'); ?>

                                    </div>
                                </td>
                                <td>
                                    <?php if($project->website_url): ?>
                                        <a href="<?php echo e($project->website_url); ?>" target="_blank" class="text-primary">
                                            <i class="fas fa-external-link-alt me-1"></i>
                                            Voir le site
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">Non défini</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="badge badge-info me-2"><?php echo e($project->serialKeys->count()); ?></span>
                                        <small class="text-muted">
                                            (<?php echo e($project->serialKeys->where('status', 'active')->count()); ?> actives)
                                        </small>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-<?php echo e($project->status === 'active' ? 'success' : 'secondary'); ?>">
                                        <?php echo e($project->status === 'active' ? 'Actif' : 'Inactif'); ?>

                                    </span>
                                </td>
                                <td><?php echo e($project->created_at->format('d/m/Y H:i')); ?></td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" 
                                                data-toggle="dropdown">
                                            Actions
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="<?php echo e(route('client.projects.show', $project)); ?>">
                                                <i class="fas fa-eye me-2"></i>Voir
                                            </a>
                                            <a class="dropdown-item" href="<?php echo e(route('client.projects.edit', $project)); ?>">
                                                <i class="fas fa-edit me-2"></i>Modifier
                                            </a>
                                            <div class="dropdown-divider"></div>
                                            <button class="dropdown-item text-warning" onclick="toggleStatus(<?php echo e($project->id); ?>)">
                                                <i class="fas fa-toggle-on me-2"></i>
                                                <?php echo e($project->status === 'active' ? 'Désactiver' : 'Activer'); ?>

                                            </button>
                                            <?php if($project->serialKeys->count() === 0): ?>
                                            <div class="dropdown-divider"></div>
                                            <button class="dropdown-item text-danger" onclick="deleteProject(<?php echo e($project->id); ?>)">
                                                <i class="fas fa-trash me-2"></i>Supprimer
                                            </button>
                                            <?php endif; ?>
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
                    <?php echo e($projects->appends(request()->query())->links()); ?>

                </div>
            <?php else: ?>
                <div class="text-center py-4">
                    <i class="fas fa-folder-open fa-3x text-gray-300 mb-3"></i>
                    <h5 class="text-gray-600">Aucun projet trouvé</h5>
                    <p class="text-muted">Commencez par créer votre premier projet.</p>
                    <a href="<?php echo e(route('client.projects.create')); ?>" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Créer un projet
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal de confirmation de suppression -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmer la suppression</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Êtes-vous sûr de vouloir supprimer ce projet ? Cette action est irréversible.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
.icon-circle {
    height: 2rem;
    width: 2rem;
    border-radius: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}

.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}

.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}

.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function toggleStatus(projectId) {
    if (confirm('Êtes-vous sûr de vouloir changer le statut de ce projet ?')) {
        fetch(`/client/projects/${projectId}/toggle-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Erreur: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Une erreur est survenue');
        });
    }
}

function deleteProject(projectId) {
    document.getElementById('deleteForm').action = `/client/projects/${projectId}`;
    $('#deleteModal').modal('show');
}
</script>
<?php $__env->stopPush(); ?> 
<?php echo $__env->make('client.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\Adev\200  -  test\AdminLicence-4.5.1\resources\views\client\projects\index.blade.php ENDPATH**/ ?>