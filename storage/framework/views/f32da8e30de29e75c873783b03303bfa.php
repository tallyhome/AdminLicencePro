

<?php $__env->startSection('title', 'Templates CMS'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Templates CMS</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('admin.cms.index')); ?>">CMS</a></li>
                        <li class="breadcrumb-item active">Templates</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Templates Disponibles</h5>
                </div>
                <div class="card-body">
                    <?php if(isset($templates) && $templates->count() > 0): ?>
                        <div class="row">
                            <?php $__currentLoopData = $templates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $template): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card template-card <?php echo e($template->is_default ? 'border-primary' : ''); ?>">
                                    <div class="card-header">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0"><?php echo e($template->name); ?></h6>
                                            <?php if($template->is_default): ?>
                                                <span class="badge bg-primary">Actuel</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <p class="text-muted"><?php echo e($template->description ?? 'Template moderne et responsive'); ?></p>
                                        <?php if(!$template->is_default): ?>
                                            <form action="<?php echo e(route('admin.cms.activate-template', $template)); ?>" method="POST">
                                                <?php echo csrf_field(); ?>
                                                <button type="submit" class="btn btn-primary btn-sm">
                                                    Activer
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-palette fa-3x text-muted mb-3"></i>
                            <h5>Aucun template</h5>
                            <p class="text-muted">Les templates seront automatiquement détectés.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\Adev\200  -  test\AdminLicence-4.5.1\resources\views\admin\cms\templates\index.blade.php ENDPATH**/ ?>