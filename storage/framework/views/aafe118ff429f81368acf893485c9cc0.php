<?php $__env->startSection('title', 'Mes Projets'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    <!-- En-tête avec le titre -->
    <div class="card bg-primary text-white mb-4">
        <div class="card-body p-4">
            <h4 class="mb-2">Mes Projets</h4>
            <p class="mb-0">Gérez vos projets et leurs licences</p>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="row g-4 mb-4">
        <!-- Total Projets -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card-stats">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="stat-icon bg-primary">
                            <i class="fas fa-folder"></i>
                        </div>
                        <div class="text-end">
                            <h3 class="mb-1"><?php echo e($projects->total()); ?></h3>
                            <p class="text-muted mb-0">Total</p>
                        </div>
                    </div>
                    <h6 class="mb-2">Projets</h6>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-chart-line text-primary me-1"></i>
                        <small class="text-primary">Vue d'ensemble</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Projets Actifs -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card-stats">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="stat-icon bg-success">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="text-end">
                            <h3 class="mb-1"><?php echo e($projects->where('status', 'active')->count()); ?></h3>
                            <p class="text-muted mb-0">Actifs</p>
                        </div>
                    </div>
                    <h6 class="mb-2">Projets Actifs</h6>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-arrow-up text-success me-1"></i>
                        <small class="text-success">En cours d'utilisation</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Licences -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card-stats">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="stat-icon bg-info">
                            <i class="fas fa-key"></i>
                        </div>
                        <div class="text-end">
                            <h3 class="mb-1"><?php echo e($projects->sum(function($project) { return $project->serialKeys->count(); })); ?></h3>
                            <p class="text-muted mb-0">Total</p>
                        </div>
                    </div>
                    <h6 class="mb-2">Licences</h6>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-chart-bar text-info me-1"></i>
                        <small class="text-info">Toutes licences</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Licences Actives -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card-stats">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="stat-icon bg-warning">
                            <i class="fas fa-rocket"></i>
                        </div>
                        <div class="text-end">
                            <h3 class="mb-1"><?php echo e($projects->sum(function($project) { return $project->serialKeys->where('status', 'active')->count(); })); ?></h3>
                            <p class="text-muted mb-0">Actives</p>
                        </div>
                    </div>
                    <h6 class="mb-2">Licences Actives</h6>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-bolt text-warning me-1"></i>
                        <small class="text-warning">En utilisation</small>
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
                        <form method="GET" action="<?php echo e(route('client.projects.index')); ?>" class="row g-3">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Rechercher</label>
                                    <input type="text" class="form-control" name="search" 
                                           value="<?php echo e(request('search')); ?>" placeholder="Nom du projet...">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Statut</label>
                                    <select class="form-select" name="status">
                                        <option value="">Tous les statuts</option>
                                        <option value="active" <?php echo e(request('status') === 'active' ? 'selected' : ''); ?>>Actif</option>
                                        <option value="inactive" <?php echo e(request('status') === 'inactive' ? 'selected' : ''); ?>>Inactif</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Trier par</label>
                                    <select class="form-select" name="sort">
                                        <option value="created_at" <?php echo e(request('sort') === 'created_at' ? 'selected' : ''); ?>>Date de création</option>
                                        <option value="name" <?php echo e(request('sort') === 'name' ? 'selected' : ''); ?>>Nom</option>
                                        <option value="updated_at" <?php echo e(request('sort') === 'updated_at' ? 'selected' : ''); ?>>Dernière modification</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-search me-2"></i>Filtrer
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Liste des projets -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="card-title mb-0">Liste des Projets</h5>
                        <a href="<?php echo e(route('client.projects.create')); ?>" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Nouveau Projet
                        </a>
                    </div>

                    <?php if($projects->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
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
                                                <div class="me-3">
                                                    <div class="bg-primary rounded-circle p-2 text-white">
                                                        <i class="fas fa-folder"></i>
                                                    </div>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0"><?php echo e($project->name); ?></h6>
                                                    <small class="text-muted">ID: <?php echo e($project->id); ?></small>
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
                                                    <i class="fas fa-external-link-alt me-1"></i>Voir le site
                                                </a>
                                            <?php else: ?>
                                                <span class="text-muted">Non défini</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">
                                                <?php echo e($project->serialKeys->count()); ?> licence(s)
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?php echo e($project->status === 'active' ? 'success' : 'secondary'); ?>">
                                                <?php echo e(ucfirst($project->status)); ?>

                                            </span>
                                        </td>
                                        <td><?php echo e($project->created_at->format('d/m/Y H:i')); ?></td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="<?php echo e(route('client.projects.show', $project)); ?>" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="<?php echo e(route('client.projects.edit', $project)); ?>" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-danger" onclick="deleteProject(<?php echo e($project->id); ?>)">
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
                            <?php echo e($projects->links()); ?>

                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                            <h5>Aucun projet pour le moment</h5>
                            <p class="text-muted">Commencez par créer votre premier projet</p>
                            <a href="<?php echo e(route('client.projects.create')); ?>" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Créer un projet
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
    function deleteProject(projectId) {
        if (confirm('Êtes-vous sûr de vouloir supprimer ce projet ?')) {
            fetch(`/client/projects/${projectId}`, {
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
                    alert('Une erreur est survenue lors de la suppression du projet.');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Une erreur est survenue lors de la suppression du projet.');
            });
        }
    }
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.client', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\Adev\200  -  test\AdminLicence-4.5.1\resources\views\client\projects\index.blade.php ENDPATH**/ ?>