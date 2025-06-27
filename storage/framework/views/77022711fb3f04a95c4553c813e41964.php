<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-16">
    <h1 class="text-3xl font-bold text-blue-800 mb-8"><?php echo e(__('features.title')); ?></h1>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="font-semibold text-blue-700 mb-2"><?php echo e(__('features.license_management')); ?></h2>
            <p class="text-gray-600"><?php echo e(__('features.license_management_desc')); ?></p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="font-semibold text-blue-700 mb-2"><?php echo e(__('features.api')); ?></h2>
            <p class="text-gray-600"><?php echo e(__('features.api_desc')); ?></p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="font-semibold text-blue-700 mb-2"><?php echo e(__('features.updates')); ?></h2>
            <p class="text-gray-600"><?php echo e(__('features.updates_desc')); ?></p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="font-semibold text-blue-700 mb-2"><?php echo e(__('features.security')); ?></h2>
            <p class="text-gray-600"><?php echo e(__('features.security_desc')); ?></p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="font-semibold text-blue-700 mb-2"><?php echo e(__('features.integrations')); ?></h2>
            <p class="text-gray-600"><?php echo e(__('features.integrations_desc')); ?></p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="font-semibold text-blue-700 mb-2"><?php echo e(__('features.support')); ?></h2>
            <p class="text-gray-600"><?php echo e(__('features.support_desc')); ?></p>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\Adev\200  -  test\AdminLicence-4.5.1\resources\views\features.blade.php ENDPATH**/ ?>