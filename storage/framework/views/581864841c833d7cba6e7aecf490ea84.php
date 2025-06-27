

<?php $__env->startSection('title', 'Voir Fonctionnalité'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Fonctionnalité : <?php echo e(Str::limit($feature->title, 50)); ?></h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('admin.cms.index')); ?>">CMS</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo e(route('admin.cms.features.index')); ?>">Fonctionnalités</a></li>
                        <li class="breadcrumb-item active">Voir</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Détails de la Fonctionnalité</h5>
                    <div>
                        <a href="<?php echo e(route('admin.cms.features.edit', $feature)); ?>" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit me-1"></i> Modifier
                        </a>
                        <a href="<?php echo e(route('admin.cms.features.index')); ?>" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i> Retour
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Prévisualisation -->
                    <div class="row">
                        <div class="col-md-12 mb-4">
                            <div class="feature-preview p-4 bg-light rounded">
                                <div class="text-center">
                                    <?php if($feature->image_url): ?>
                                        <div class="mb-3">
                                            <img src="<?php echo e(asset('storage/' . $feature->image_url)); ?>" 
                                                 class="img-fluid rounded" 
                                                 style="max-height: 200px; object-fit: cover;"
                                                 alt="<?php echo e($feature->title); ?>">
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 rounded-circle mb-3" 
                                         style="width: 64px; height: 64px;">
                                        <i class="<?php echo e($feature->icon); ?> text-primary" style="font-size: 24px;"></i>
                                    </div>
                                    
                                    <h5 class="fw-bold mb-3"><?php echo e($feature->title); ?></h5>
                                    <p class="text-muted mb-3"><?php echo e($feature->description); ?></p>
                                    
                                    <?php if($feature->link_url && $feature->link_text): ?>
                                        <a href="<?php echo e($feature->link_url); ?>" class="btn btn-primary" target="_blank">
                                            <?php echo e($feature->link_text); ?>

                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Détails -->
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <td class="fw-semibold" style="width: 40%;">Titre :</td>
                                        <td><?php echo e($feature->title); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold">Icône :</td>
                                        <td>
                                            <i class="<?php echo e($feature->icon); ?> me-2"></i>
                                            <code><?php echo e($feature->icon); ?></code>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold">Ordre :</td>
                                        <td><?php echo e($feature->sort_order); ?></td>
                                    </tr>
                                    <?php if($feature->link_url): ?>
                                        <tr>
                                            <td class="fw-semibold">Lien :</td>
                                            <td>
                                                <a href="<?php echo e($feature->link_url); ?>" target="_blank" class="text-decoration-none">
                                                    <?php echo e($feature->link_text ?: 'Voir plus'); ?>

                                                    <i class="fas fa-external-link-alt ms-1 small"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <td class="fw-semibold" style="width: 40%;">Statut :</td>
                                        <td>
                                            <?php if($feature->is_active): ?>
                                                <span class="badge bg-success">Actif</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Inactif</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold">Image :</td>
                                        <td>
                                            <?php if($feature->image_url): ?>
                                                <span class="badge bg-success">Présente</span>
                                            <?php else: ?>
                                                <span class="text-muted">Aucune</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold">Créé le :</td>
                                        <td><?php echo e($feature->created_at->format('d/m/Y à H:i')); ?></td>
                                    </tr>
                                    <?php if($feature->updated_at != $feature->created_at): ?>
                                        <tr>
                                            <td class="fw-semibold">Modifié le :</td>
                                            <td><?php echo e($feature->updated_at->format('d/m/Y à H:i')); ?></td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Description complète -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h6 class="fw-semibold mb-3">Description complète :</h6>
                            <div class="p-3 bg-light rounded">
                                <?php echo nl2br(e($feature->description)); ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="<?php echo e(route('admin.cms.features.edit', $feature)); ?>" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i> Modifier cette fonctionnalité
                        </a>
                        
                        <form action="<?php echo e(route('admin.cms.features.destroy', $feature)); ?>" 
                              method="POST" 
                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette fonctionnalité ?')">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="fas fa-trash me-2"></i> Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <?php if($feature->image_url): ?>
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Image</h5>
                </div>
                <div class="card-body">
                    <img src="<?php echo e(asset('storage/' . $feature->image_url)); ?>" 
                         class="img-fluid rounded" 
                         alt="<?php echo e($feature->title); ?>">
                </div>
            </div>
            <?php endif; ?>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informations</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-0">
                        <h6 class="alert-heading">État de la fonctionnalité :</h6>
                        <ul class="mb-0 small">
                            <li><strong>Créé :</strong> <?php echo e($feature->created_at->format('d/m/Y à H:i')); ?></li>
                            <?php if($feature->updated_at != $feature->created_at): ?>
                                <li><strong>Modifié :</strong> <?php echo e($feature->updated_at->format('d/m/Y à H:i')); ?></li>
                            <?php endif; ?>
                            <li><strong>Statut :</strong> <?php echo e($feature->is_active ? 'Actif' : 'Inactif'); ?></li>
                            <?php if($feature->image_url): ?>
                                <li><strong>Image :</strong> Présente</li>
                            <?php endif; ?>
                            <?php if($feature->link_url): ?>
                                <li><strong>Lien externe :</strong> Configuré</li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\Adev\200  -  test\AdminLicence-4.5.1\resources\views\admin\cms\features\show.blade.php ENDPATH**/ ?>