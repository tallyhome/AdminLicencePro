<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-16">
    <h1 class="text-3xl font-bold text-blue-800 mb-8"><?php echo e(__('faq.title')); ?></h1>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div>
            <h2 class="font-semibold text-blue-700 mb-2"><?php echo e(__('faq.q1')); ?></h2>
            <p class="text-gray-600"><?php echo e(__('faq.a1')); ?></p>
        </div>
        <div>
            <h2 class="font-semibold text-blue-700 mb-2"><?php echo e(__('faq.q2')); ?></h2>
            <p class="text-gray-600"><?php echo e(__('faq.a2')); ?></p>
        </div>
        <div>
            <h2 class="font-semibold text-blue-700 mb-2"><?php echo e(__('faq.q3')); ?></h2>
            <p class="text-gray-600"><?php echo e(__('faq.a3')); ?></p>
        </div>
        <div>
            <h2 class="font-semibold text-blue-700 mb-2"><?php echo e(__('faq.q4')); ?></h2>
            <p class="text-gray-600"><?php echo e(__('faq.a4')); ?></p>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\Adev\200  -  test\AdminLicence-4.5.1\resources\views\faq.blade.php ENDPATH**/ ?>