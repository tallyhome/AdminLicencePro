

<?php $__env->startSection('title', 'Gestion des Témoignages'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Gestion des Témoignages</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('admin.cms.index')); ?>">CMS</a></li>
                        <li class="breadcrumb-item active">Témoignages</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="card-title mb-0">Liste des Témoignages (<?php echo e($testimonials->total()); ?>)</h5>
                        </div>
                        <div class="col-auto">
                            <a href="<?php echo e(route('admin.cms.testimonials.create')); ?>" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i> Nouveau Témoignage
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <?php if($testimonials->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Client</th>
                                        <th>Entreprise</th>
                                        <th>Note</th>
                                        <th>Statut</th>
                                        <th>Mise en avant</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $testimonials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $testimonial): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <?php if($testimonial->avatar_url): ?>
                                                    <img src="<?php echo e(Storage::url($testimonial->avatar_url)); ?>" class="rounded-circle me-3" width="40" height="40">
                                                <?php else: ?>
                                                    <div class="bg-primary rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                        <i class="fas fa-user text-white"></i>
                                                    </div>
                                                <?php endif; ?>
                                                <div>
                                                    <div class="fw-medium"><?php echo e($testimonial->name); ?></div>
                                                    <small class="text-muted"><?php echo e($testimonial->position); ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?php echo e($testimonial->company); ?></td>
                                        <td>
                                            <div class="text-warning">
                                                <?php for($i = 1; $i <= 5; $i++): ?>
                                                    <?php if($i <= $testimonial->rating): ?>
                                                        <i class="fas fa-star"></i>
                                                    <?php else: ?>
                                                        <i class="far fa-star"></i>
                                                    <?php endif; ?>
                                                <?php endfor; ?>
                                            </div>
                                        </td>
                                        <td>
                                            <?php if($testimonial->is_active): ?>
                                                <span class="badge bg-success">Actif</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Inactif</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($testimonial->is_featured): ?>
                                                <span class="badge bg-warning">Mise en avant</span>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-light dropdown-toggle" type="button" 
                                                        data-bs-toggle="dropdown">
                                                    Actions
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item" href="<?php echo e(route('admin.cms.testimonials.show', $testimonial)); ?>">
                                                            <i class="fas fa-eye me-2"></i> Voir
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="<?php echo e(route('admin.cms.testimonials.edit', $testimonial)); ?>">
                                                            <i class="fas fa-edit me-2"></i> Modifier
                                                        </a>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <form action="<?php echo e(route('admin.cms.testimonials.destroy', $testimonial)); ?>" 
                                                              method="POST" class="d-inline"
                                                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce témoignage ?')">
                                                            <?php echo csrf_field(); ?>
                                                            <?php echo method_field('DELETE'); ?>
                                                            <button type="submit" class="dropdown-item text-danger">
                                                                <i class="fas fa-trash me-2"></i> Supprimer
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center mt-4">
                            <?php echo e($testimonials->links()); ?>

                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                            <h5>Aucun témoignage</h5>
                            <p class="text-muted">Commencez par ajouter votre premier témoignage client.</p>
                            <a href="<?php echo e(route('admin.cms.testimonials.create')); ?>" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i> Créer un Témoignage
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\Adev\200  -  test\AdminLicence-4.5.1\resources\views\admin\cms\testimonials\index.blade.php ENDPATH**/ ?>