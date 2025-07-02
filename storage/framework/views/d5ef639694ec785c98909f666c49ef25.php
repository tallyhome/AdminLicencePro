

<?php $__env->startSection('title', $project->name); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="<?php echo e(route('client.projects.index')); ?>" class="text-decoration-none">Projets</a>
                    </li>
                    <li class="breadcrumb-item active"><?php echo e($project->name); ?></li>
                </ol>
            </nav>
            <h1 class="h3 mb-0 mt-2"><?php echo e($project->name); ?></h1>
            <p class="text-muted mb-0"><?php echo e($project->description ?: 'Aucune description'); ?></p>
        </div>
        <div class="d-flex gap-2">
            <a href="<?php echo e(route('client.projects.edit', $project)); ?>" class="btn btn-outline-primary">
                <i class="fas fa-edit me-2"></i>Modifier
            </a>
            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="fas fa-ellipsis-v"></i>
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <a class="dropdown-item" href="<?php echo e(route('client.licenses.create', ['project_id' => $project->id])); ?>">
                            <i class="fas fa-plus me-2"></i>Générer des licences
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item text-danger" href="#" onclick="deleteProject(<?php echo e($project->id); ?>)">
                            <i class="fas fa-trash me-2"></i>Supprimer
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Informations principales -->
        <div class="col-lg-8">
            <!-- Statistiques du projet -->
            <div class="row mb-4">
                <div class="col-md-3 mb-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                <i class="fas fa-key text-primary fa-lg"></i>
                            </div>
                            <h4 class="mb-1"><?php echo e($projectStats['total_licenses']); ?></h4>
                            <small class="text-muted">Total licences</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                <i class="fas fa-check-circle text-success fa-lg"></i>
                            </div>
                            <h4 class="mb-1"><?php echo e($projectStats['active_licenses']); ?></h4>
                            <small class="text-muted">Licences actives</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                <i class="fas fa-download text-info fa-lg"></i>
                            </div>
                            <h4 class="mb-1"><?php echo e($projectStats['total_activations']); ?></h4>
                            <small class="text-muted">Total activations</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                <i class="fas fa-clock text-warning fa-lg"></i>
                            </div>
                            <h4 class="mb-1"><?php echo e($projectStats['recent_activations']); ?></h4>
                            <small class="text-muted">Activations récentes</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Détails du projet -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0">Informations du projet</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Nom</label>
                            <p class="mb-0 fw-semibold"><?php echo e($project->name); ?></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Version</label>
                            <p class="mb-0">
                                <span class="badge bg-light text-dark"><?php echo e($project->version); ?></span>
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Type de licence</label>
                            <p class="mb-0">
                                <?php switch($project->licence_type):
                                    case ('single'): ?>
                                        <span class="badge bg-info">Unique</span>
                                        <?php break; ?>
                                    <?php case ('multi'): ?>
                                        <span class="badge bg-warning">Multiple</span>
                                        <?php if($project->max_activations): ?>
                                            <small class="text-muted ms-2">(max <?php echo e($project->max_activations); ?>)</small>
                                        <?php endif; ?>
                                        <?php break; ?>
                                    <?php case ('unlimited'): ?>
                                        <span class="badge bg-success">Illimitée</span>
                                        <?php break; ?>
                                <?php endswitch; ?>
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Statut</label>
                            <p class="mb-0">
                                <?php if($project->status === 'active'): ?>
                                    <span class="badge bg-success">Actif</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Inactif</span>
                                <?php endif; ?>
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Durée de validité</label>
                            <p class="mb-0">
                                <?php if($project->expiry_days): ?>
                                    <?php echo e($project->expiry_days); ?> jours
                                <?php else: ?>
                                    <span class="text-muted">Permanente</span>
                                <?php endif; ?>
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Créé le</label>
                            <p class="mb-0"><?php echo e($project->created_at->format('d/m/Y à H:i')); ?></p>
                        </div>
                        <?php if($project->description): ?>
                            <div class="col-12">
                                <label class="form-label text-muted">Description</label>
                                <p class="mb-0"><?php echo e($project->description); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Licences récentes -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Licences récentes</h5>
                    <a href="<?php echo e(route('client.licenses.index', ['project_id' => $project->id])); ?>" class="btn btn-sm btn-outline-primary">
                        Voir toutes
                    </a>
                </div>
                <div class="card-body p-0">
                    <?php if($recentLicenses->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Clé de licence</th>
                                        <th>Type</th>
                                        <th>Statut</th>
                                        <th>Activations</th>
                                        <th>Créée le</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $recentLicenses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $license): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td>
                                                <code class="small"><?php echo e($license->serial_key); ?></code>
                                            </td>
                                            <td>
                                                <?php switch($license->licence_type):
                                                    case ('single'): ?>
                                                        <span class="badge bg-info">Unique</span>
                                                        <?php break; ?>
                                                    <?php case ('multi'): ?>
                                                        <span class="badge bg-warning">Multiple</span>
                                                        <?php break; ?>
                                                    <?php case ('unlimited'): ?>
                                                        <span class="badge bg-success">Illimitée</span>
                                                        <?php break; ?>
                                                <?php endswitch; ?>
                                            </td>
                                            <td>
                                                <?php if($license->status === 'active'): ?>
                                                    <span class="badge bg-success">Actif</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Inactif</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php echo e($license->current_activations); ?>

                                                <?php if($license->max_activations): ?>
                                                    / <?php echo e($license->max_activations); ?>

                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <small class="text-muted"><?php echo e($license->created_at->format('d/m/Y')); ?></small>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-key fa-2x text-muted mb-3"></i>
                            <p class="text-muted mb-0">Aucune licence créée pour ce projet</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Actions rapides -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="mb-0">Actions rapides</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="<?php echo e(route('client.licenses.create', ['project_id' => $project->id])); ?>" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Générer des licences
                        </a>
                        <a href="<?php echo e(route('client.licenses.index', ['project_id' => $project->id])); ?>" class="btn btn-outline-primary">
                            <i class="fas fa-list me-2"></i>Voir toutes les licences
                        </a>
                        <a href="<?php echo e(route('client.projects.edit', $project)); ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-edit me-2"></i>Modifier le projet
                        </a>
                    </div>
                </div>
            </div>

            <!-- Activations récentes -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="mb-0">Activations récentes</h6>
                </div>
                <div class="card-body">
                    <?php if($recentActivations->count() > 0): ?>
                        <div class="list-group list-group-flush">
                            <?php $__currentLoopData = $recentActivations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $license): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="list-group-item px-0 py-2">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <code class="small"><?php echo e(Str::limit($license->serial_key, 12)); ?></code>
                                            <br>
                                            <small class="text-muted"><?php echo e($license->last_activation_at->diffForHumans()); ?></small>
                                        </div>
                                        <span class="badge bg-success"><?php echo e($license->current_activations); ?></span>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted text-center mb-0">Aucune activation récente</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Informations techniques -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="mb-0">Informations techniques</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label text-muted small">ID du projet</label>
                        <p class="mb-0">
                            <code class="small"><?php echo e($project->id); ?></code>
                        </p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small">Dernière modification</label>
                        <p class="mb-0"><?php echo e($project->updated_at->format('d/m/Y à H:i')); ?></p>
                    </div>
                    <div class="mb-0">
                        <label class="form-label text-muted small">API Endpoint</label>
                        <p class="mb-0">
                            <code class="small">/api/v1/check/<?php echo e($project->id); ?></code>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation de suppression -->
<div class="modal fade" id="deleteProjectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmer la suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer le projet <strong><?php echo e($project->name); ?></strong> ?</p>
                <p class="text-danger"><small>Cette action est irréversible et supprimera toutes les licences associées.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form id="deleteProjectForm" method="POST" style="display: inline;">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function deleteProject(projectId) {
    const modal = new bootstrap.Modal(document.getElementById('deleteProjectModal'));
    const form = document.getElementById('deleteProjectForm');
    form.action = `/client/projects/${projectId}`;
    modal.show();
}
</script>
<?php $__env->stopPush(); ?> 
<?php echo $__env->make('layouts.client', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\Adev\200  -  test\AdminLicence-4.5.1\resources\views\client\projects\show.blade.php ENDPATH**/ ?>