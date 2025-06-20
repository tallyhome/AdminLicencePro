<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-16 max-w-2xl">
    <h1 class="text-3xl font-bold text-blue-800 mb-8"><?php echo e(__('support.title')); ?></h1>
    <form method="POST" action="/support/send" class="bg-white rounded-lg shadow p-8 mb-8">
        <?php echo csrf_field(); ?>
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2"><?php echo e(__('support.name')); ?></label>
            <input type="text" name="name" class="w-full border rounded px-3 py-2" required>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2"><?php echo e(__('support.email')); ?></label>
            <input type="email" name="email" class="w-full border rounded px-3 py-2" required>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2"><?php echo e(__('support.message')); ?></label>
            <textarea name="message" rows="5" class="w-full border rounded px-3 py-2" required></textarea>
        </div>
        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"><?php echo e(__('support.send')); ?></button>
    </form>
    <div class="bg-blue-50 rounded-lg p-6">
        <h2 class="font-semibold text-blue-700 mb-2"><?php echo e(__('support.contact_info')); ?></h2>
        <p class="text-gray-600"><?php echo e(__('support.contact_desc')); ?></p>
        <p class="text-gray-600 mt-2">Email : <a href="mailto:support@adminlicence.fr" class="text-blue-700 underline">support@adminlicence.fr</a></p>
    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\Adev\200  -  test\AdminLicence-4.5.1\resources\views\support.blade.php ENDPATH**/ ?>