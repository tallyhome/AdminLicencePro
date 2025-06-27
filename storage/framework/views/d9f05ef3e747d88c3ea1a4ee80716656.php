<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-16">
    <h1 class="text-3xl font-bold text-blue-800 mb-8"><?php echo e(__('pricing.title')); ?></h1>
    <div class="flex flex-col md:flex-row gap-8 justify-center">
        <div class="bg-white rounded-lg shadow p-8 flex-1 max-w-sm">
            <div class="text-xl font-bold text-blue-700 mb-2"><?php echo e(__('pricing.basic')); ?></div>
            <div class="text-3xl font-bold mb-4">$65</div>
            <ul class="text-gray-600 mb-6 text-left list-disc list-inside">
                <li><?php echo e(__('pricing.basic_1')); ?></li>
                <li><?php echo e(__('pricing.basic_2')); ?></li>
                <li><?php echo e(__('pricing.basic_3')); ?></li>
            </ul>
            <a href="#" class="block w-full text-center px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"><?php echo e(__('pricing.buy')); ?></a>
        </div>
        <div class="bg-white rounded-lg shadow p-8 flex-1 max-w-sm border-2 border-blue-600">
            <div class="text-xl font-bold text-blue-700 mb-2"><?php echo e(__('pricing.extended')); ?></div>
            <div class="text-3xl font-bold mb-4">$240</div>
            <ul class="text-gray-600 mb-6 text-left list-disc list-inside">
                <li><?php echo e(__('pricing.extended_1')); ?></li>
                <li><?php echo e(__('pricing.extended_2')); ?></li>
                <li><?php echo e(__('pricing.extended_3')); ?></li>
            </ul>
            <a href="#" class="block w-full text-center px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"><?php echo e(__('pricing.buy')); ?></a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\Adev\200  -  test\AdminLicence-4.5.1\resources\views\pricing.blade.php ENDPATH**/ ?>