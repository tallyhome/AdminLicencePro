<?php $__env->startSection('title', 'Voir Section À propos'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Section : <?php echo e(Str::limit($about->title, 50)); ?></h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('admin.cms.index')); ?>">CMS</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo e(route('admin.cms.about.index')); ?>">À propos</a></li>
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
                    <h5 class="card-title mb-0">Détails de la Section</h5>
                    <div>
                        <a href="<?php echo e(route('admin.cms.about.edit', $about)); ?>" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit me-1"></i> Modifier
                        </a>
                        <a href="<?php echo e(route('admin.cms.about.index')); ?>" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i> Retour
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Prévisualisation -->
                    <div class="row">
                        <div class="col-md-12 mb-4">
                            <div class="section-preview p-4 bg-light rounded">
                                <?php if($about->section_type === 'text'): ?>
                                    <!-- Section texte simple -->
                                    <div class="text-center">
                                        <h5 class="fw-bold mb-3"><?php echo e($about->title); ?></h5>
                                        <div class="text-muted"><?php echo nl2br(e($about->content)); ?></div>
                                    </div>
                                <?php elseif($about->section_type === 'image_left'): ?>
                                    <!-- Image à gauche -->
                                    <div class="row align-items-center">
                                        <div class="col-md-4">
                                            <?php if($about->image_url): ?>
                                                <img src="<?php echo e(asset('storage/' . $about->image_url)); ?>" 
                                                     class="img-fluid rounded" 
                                                     alt="<?php echo e($about->title); ?>">
                                            <?php else: ?>
                                                <div class="bg-secondary rounded d-flex align-items-center justify-content-center" 
                                                     style="height: 200px;">
                                                    <span class="text-white">Aucune image</span>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-md-8">
                                            <h5 class="fw-bold mb-3"><?php echo e($about->title); ?></h5>
                                            <div class="text-muted"><?php echo nl2br(e($about->content)); ?></div>
                                        </div>
                                    </div>
                                <?php elseif($about->section_type === 'image_right'): ?>
                                    <!-- Image à droite -->
                                    <div class="row align-items-center">
                                        <div class="col-md-8">
                                            <h5 class="fw-bold mb-3"><?php echo e($about->title); ?></h5>
                                            <div class="text-muted"><?php echo nl2br(e($about->content)); ?></div>
                                        </div>
                                        <div class="col-md-4">
                                            <?php if($about->image_url): ?>
                                                <img src="<?php echo e(asset('storage/' . $about->image_url)); ?>" 
                                                     class="img-fluid rounded" 
                                                     alt="<?php echo e($about->title); ?>">
                                            <?php else: ?>
                                                <div class="bg-secondary rounded d-flex align-items-center justify-content-center" 
                                                     style="height: 200px;">
                                                    <span class="text-white">Aucune image</span>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <!-- Pleine largeur -->
                                    <div class="text-center">
                                        <?php if($about->image_url): ?>
                                            <div class="mb-4">
                                                <img src="<?php echo e(asset('storage/' . $about->image_url)); ?>" 
                                                     class="img-fluid rounded" 
                                                     style="max-height: 300px; object-fit: cover;"
                                                     alt="<?php echo e($about->title); ?>">
                                            </div>
                                        <?php endif; ?>
                                        <h5 class="fw-bold mb-3"><?php echo e($about->title); ?></h5>
                                        <div class="text-muted"><?php echo nl2br(e($about->content)); ?></div>
                                    </div>
                                <?php endif; ?>
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
                                        <td><?php echo e($about->title); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold">Type :</td>
                                        <td>
                                            <span class="badge bg-info">
                                                <?php switch($about->section_type):
                                                    case ('text'): ?>
                                                        Texte simple
                                                        <?php break; ?>
                                                    <?php case ('image_left'): ?>
                                                        Image à gauche
                                                        <?php break; ?>
                                                    <?php case ('image_right'): ?>
                                                        Image à droite
                                                        <?php break; ?>
                                                    <?php case ('full_width'): ?>
                                                        Pleine largeur
                                                        <?php break; ?>
                                                    <?php default: ?>
                                                        <?php echo e($about->section_type); ?>

                                                <?php endswitch; ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold">Ordre :</td>
                                        <td><?php echo e($about->sort_order); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <td class="fw-semibold" style="width: 40%;">Statut :</td>
                                        <td>
                                            <?php if($about->is_active): ?>
                                                <span class="badge bg-success">Publié</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Brouillon</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold">Image :</td>
                                        <td>
                                            <?php if($about->image_url): ?>
                                                <span class="badge bg-success">Présente</span>
                                            <?php else: ?>
                                                <span class="text-muted">Aucune</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold">Créé le :</td>
                                        <td><?php echo e($about->created_at->format('d/m/Y à H:i')); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Contenu complet -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h6 class="fw-semibold mb-3">Contenu complet :</h6>
                            <div class="p-3 bg-light rounded">
                                <?php echo nl2br(e($about->content)); ?>

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
                        <a href="<?php echo e(route('admin.cms.about.edit', $about)); ?>" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i> Modifier cette section
                        </a>
                        
                        <form action="<?php echo e(route('admin.cms.about.destroy', $about)); ?>" 
                              method="POST" 
                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette section ?')">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="fas fa-trash me-2"></i> Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <?php if($about->image_url): ?>
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Image</h5>
                </div>
                <div class="card-body">
                    <img src="<?php echo e(asset('storage/' . $about->image_url)); ?>" 
                         class="img-fluid rounded" 
                         alt="<?php echo e($about->title); ?>">
                </div>
            </div>
            <?php endif; ?>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informations</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-0">
                        <h6 class="alert-heading">État de la section :</h6>
                        <ul class="mb-0 small">
                            <li><strong>Créé :</strong> <?php echo e($about->created_at->format('d/m/Y à H:i')); ?></li>
                            <?php if($about->updated_at != $about->created_at): ?>
                                <li><strong>Modifié :</strong> <?php echo e($about->updated_at->format('d/m/Y à H:i')); ?></li>
                            <?php endif; ?>
                            <li><strong>Statut :</strong> <?php echo e($about->is_active ? 'Publié' : 'Brouillon'); ?></li>
                            <?php if($about->image_url): ?>
                                <li><strong>Image :</strong> Présente</li>
                            <?php endif; ?>
                            <li><strong>Type :</strong> <?php echo e(ucfirst(str_replace('_', ' ', $about->section_type))); ?></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\Adev\200  -  test\AdminLicence-4.5.1\resources\views\admin\cms\about\show.blade.php ENDPATH**/ ?>