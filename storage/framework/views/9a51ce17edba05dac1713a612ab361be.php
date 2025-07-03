<?php $__env->startSection('title', 'Modifier ' . $project->name); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0">Modifier le projet</h1>
                    <p class="text-muted"><?php echo e($project->name); ?></p>
                </div>
                <div class="d-flex gap-2">
                    <a href="<?php echo e(route('client.projects.show', $project)); ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Retour
                    </a>
                    <button type="submit" form="editForm" class="btn btn-primary">
                        <i class="fas fa-save"></i> Enregistrer
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-edit me-2"></i>Informations du projet
                    </h5>
                </div>
                <div class="card-body">
                    <form id="editForm" action="<?php echo e(route('client.projects.update', $project)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">
                                        Nom du projet <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="name" 
                                           name="name" 
                                           value="<?php echo e(old('name', $project->name)); ?>" 
                                           required>
                                    <?php $__errorArgs = ['name'];
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
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="version" class="form-label">Version</label>
                                    <input type="text" 
                                           class="form-control <?php $__errorArgs = ['version'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="version" 
                                           name="version" 
                                           value="<?php echo e(old('version', $project->version)); ?>" 
                                           placeholder="1.0.0">
                                    <?php $__errorArgs = ['version'];
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
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                      id="description" 
                                      name="description" 
                                      rows="4"><?php echo e(old('description', $project->description)); ?></textarea>
                            <?php $__errorArgs = ['description'];
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

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="website_url" class="form-label">URL du site web</label>
                                    <input type="url" 
                                           class="form-control <?php $__errorArgs = ['website_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="website_url" 
                                           name="website_url" 
                                           value="<?php echo e(old('website_url', $project->website_url)); ?>" 
                                           placeholder="https://monapp.com">
                                    <?php $__errorArgs = ['website_url'];
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
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Statut</label>
                                    <select class="form-select <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                            id="status" 
                                            name="status" 
                                            required>
                                        <option value="active" <?php echo e(old('status', $project->status) == 'active' ? 'selected' : ''); ?>>Actif</option>
                                        <option value="inactive" <?php echo e(old('status', $project->status) == 'inactive' ? 'selected' : ''); ?>>Inactif</option>
                                    </select>
                                    <?php $__errorArgs = ['status'];
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
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Actions avancées -->
            <div class="card shadow mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-cog me-2"></i>Actions avancées
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="d-grid">
                                <button type="button" class="btn btn-outline-warning" onclick="duplicateProject(<?php echo e($project->id); ?>)">
                                    <i class="fas fa-copy me-2"></i>Dupliquer le projet
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-grid">
                                <button type="button" class="btn btn-outline-info" onclick="exportProject(<?php echo e($project->id); ?>)">
                                    <i class="fas fa-download me-2"></i>Exporter les données
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Statistiques du projet -->
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-chart-pie me-2"></i>Statistiques
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span>Total licences</span>
                            <span class="badge bg-primary"><?php echo e($project->serialKeys()->count()); ?></span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span>Licences actives</span>
                            <span class="badge bg-success"><?php echo e($project->serialKeys()->where('status', 'active')->count()); ?></span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span>Licences expirées</span>
                            <span class="badge bg-warning"><?php echo e($project->serialKeys()->where('expires_at', '<', now())->count()); ?></span>
                        </div>
                    </div>
                    <div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span>Total activations</span>
                            <span class="badge bg-info"><?php echo e($project->serialKeys()->sum('current_activations')); ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informations système -->
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>Informations système
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">ID du projet</label>
                        <p class="mb-0"><code><?php echo e($project->id); ?></code></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Créé le</label>
                        <p class="mb-0"><?php echo e($project->created_at->format('d/m/Y à H:i')); ?></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Modifié le</label>
                        <p class="mb-0"><?php echo e($project->updated_at->format('d/m/Y à H:i')); ?></p>
                    </div>
                    <div>
                        <label class="form-label fw-bold">Tenant ID</label>
                        <p class="mb-0"><code><?php echo e($project->tenant_id); ?></code></p>
                    </div>
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-bolt me-2"></i>Actions rapides
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="<?php echo e(route('client.licenses.create', ['project' => $project->id])); ?>" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Créer une licence
                        </a>
                        <a href="<?php echo e(route('client.licenses.index', ['project' => $project->id])); ?>" class="btn btn-outline-primary">
                            <i class="fas fa-key me-2"></i>Gérer les licences
                        </a>
                        <a href="<?php echo e(route('client.api-keys.index', ['project' => $project->id])); ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-code me-2"></i>Clés API
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation de duplication -->
<div class="modal fade" id="duplicateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Dupliquer le projet</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Voulez-vous dupliquer le projet <strong><?php echo e($project->name); ?></strong> ?</p>
                <p class="text-muted">Une copie sera créée avec le suffixe "(Copie)".</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-warning" id="confirmDuplicate">Dupliquer</button>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// Validation en temps réel
document.getElementById('name').addEventListener('input', function() {
    const name = this.value;
    const submitBtn = document.querySelector('button[type="submit"]');
    
    if (name.length < 3) {
        this.classList.add('is-invalid');
        submitBtn.disabled = true;
    } else {
        this.classList.remove('is-invalid');
        submitBtn.disabled = false;
    }
});

// Prévisualisation de la description
document.getElementById('description').addEventListener('input', function() {
    const maxLength = 1000;
    const currentLength = this.value.length;
    const remaining = maxLength - currentLength;
    
    if (!this.nextElementSibling || !this.nextElementSibling.classList.contains('form-text')) {
        const helpText = document.createElement('div');
        helpText.className = 'form-text';
        this.parentNode.appendChild(helpText);
    }
    
    this.nextElementSibling.textContent = `${remaining} caractères restants`;
    
    if (currentLength > maxLength * 0.9) {
        this.nextElementSibling.classList.add('text-warning');
    } else {
        this.nextElementSibling.classList.remove('text-warning');
    }
});

// Actions
function duplicateProject(projectId) {
    const modal = new bootstrap.Modal(document.getElementById('duplicateModal'));
    modal.show();
    
    document.getElementById('confirmDuplicate').onclick = function() {
        fetch(`/client/projects/${projectId}/duplicate`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = data.redirect_url;
            } else {
                alert('Erreur lors de la duplication : ' + data.message);
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors de la duplication');
        });
    };
}

function exportProject(projectId) {
    const format = prompt('Format d\'export (json/csv) :', 'json');
    if (format && ['json', 'csv'].includes(format.toLowerCase())) {
        window.open(`/client/projects/${projectId}/export?format=${format}`, '_blank');
    }
}

// Sauvegarde automatique (optionnel)
let autoSaveTimer;
document.getElementById('editForm').addEventListener('input', function() {
    clearTimeout(autoSaveTimer);
    autoSaveTimer = setTimeout(function() {
        // Ici on pourrait implémenter une sauvegarde automatique
        console.log('Sauvegarde automatique...');
    }, 3000);
});
</script>
<?php $__env->stopPush(); ?> 
<?php echo $__env->make('layouts.client', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\Adev\200  -  test\AdminLicence-4.5.1\resources\views\client\projects\edit.blade.php ENDPATH**/ ?>