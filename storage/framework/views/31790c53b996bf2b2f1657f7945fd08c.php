<!-- Menu de navigation principal -->
<ul class="navbar-nav">
    <li class="nav-item">
        <a class="nav-link" href="<?php echo e(route('admin.dashboard')); ?>">
            <i class="fas fa-tachometer-alt"></i>
            <span><?php echo e(t('common.dashboard')); ?></span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="<?php echo e(route('admin.projects.index')); ?>">
            <i class="fas fa-project-diagram"></i>
            <span><?php echo e(t('common.projects')); ?></span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="<?php echo e(route('admin.serial-keys.index')); ?>">
            <i class="fas fa-key"></i>
            <span><?php echo e(t('common.serial_keys')); ?></span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="<?php echo e(route('admin.api.index')); ?>">
            <i class="fas fa-code"></i>
            <span><?php echo e(t('api.api_keys')); ?></span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="<?php echo e(route('admin.email.index')); ?>">
            <i class="fas fa-envelope"></i>
            <span><?php echo e(t('common.email')); ?></span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="<?php echo e(route('admin.support.index')); ?>">
            <i class="fas fa-question-circle"></i>
            <span><?php echo e(t('layout.support')); ?></span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="<?php echo e(route('admin.documentation.index')); ?>">
            <i class="fas fa-book"></i>
            <span><?php echo e(t('layout.documentation')); ?></span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="<?php echo e(route('admin.version.index')); ?>">
            <i class="fas fa-info-circle"></i>
            <span><?php echo e(t('layout.version_info')); ?></span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="<?php echo e(route('admin.settings.index')); ?>">
            <i class="fas fa-cog"></i>
            <span><?php echo e(t('common.settings')); ?></span>
        </a>
    </li>
</ul><?php /**PATH R:\Adev\200  -  test\AdminLicence-4.5.1\resources\views\admin\layouts\partials\sidebar.blade.php ENDPATH**/ ?>