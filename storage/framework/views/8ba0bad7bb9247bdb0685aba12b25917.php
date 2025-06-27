<?php $__env->startSection('title', 'Gestion des FAQs'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Gestion des FAQs</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('admin.cms.index')); ?>">CMS</a></li>
                        <li class="breadcrumb-item active">FAQs</li>
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
                            <h5 class="card-title mb-0">Liste des FAQs (<?php echo e($faqs->total()); ?>)</h5>
                        </div>
                        <div class="col-auto">
                            <a href="<?php echo e(route('admin.cms.faqs.create')); ?>" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i> Nouvelle FAQ
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <?php if($faqs->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Question</th>
                                        <th>Catégorie</th>
                                        <th>Statut</th>
                                        <th>Mise en avant</th>
                                        <th>Ordre</th>
                                        <th>Vues</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $faqs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $faq): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td>
                                            <div class="fw-medium"><?php echo e(Str::limit($faq->question, 60)); ?></div>
                                            <small class="text-muted"><?php echo e(Str::limit(strip_tags($faq->answer), 80)); ?></small>
                                        </td>
                                        <td>
                                            <?php if($faq->category): ?>
                                                <span class="badge bg-info"><?php echo e($faq->category); ?></span>
                                            <?php else: ?>
                                                <span class="text-muted">Aucune</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($faq->is_active): ?>
                                                <span class="badge bg-success">Actif</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Inactif</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($faq->is_featured): ?>
                                                <span class="badge bg-warning">Mise en avant</span>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo e($faq->sort_order); ?></td>
                                        <td><?php echo e($faq->views_count ?? 0); ?></td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-light dropdown-toggle" type="button" 
                                                        data-bs-toggle="dropdown">
                                                    Actions
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item" href="<?php echo e(route('admin.cms.faqs.show', $faq)); ?>">
                                                            <i class="fas fa-eye me-2"></i> Voir
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="<?php echo e(route('admin.cms.faqs.edit', $faq)); ?>">
                                                            <i class="fas fa-edit me-2"></i> Modifier
                                                        </a>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <form action="<?php echo e(route('admin.cms.faqs.destroy', $faq)); ?>" 
                                                              method="POST" class="d-inline"
                                                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette FAQ ?')">
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
                            <?php echo e($faqs->links()); ?>

                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-question-circle fa-3x text-muted mb-3"></i>
                            <h5>Aucune FAQ</h5>
                            <p class="text-muted">Commencez par créer votre première FAQ.</p>
                            <a href="<?php echo e(route('admin.cms.faqs.create')); ?>" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i> Créer une FAQ
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <?php if(isset($categories) && $categories->count() > 0): ?>
    <div class="row mt-4">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Catégories existantes</h5>
                </div>
                <div class="card-body">
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <span class="badge bg-light text-dark me-2 mb-2"><?php echo e($category); ?></span>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\Adev\200  -  test\AdminLicence-4.5.1\resources\views\admin\cms\faqs\index.blade.php ENDPATH**/ ?>