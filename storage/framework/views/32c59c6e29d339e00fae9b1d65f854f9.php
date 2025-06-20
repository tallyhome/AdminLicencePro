<?php $__env->startSection('content'); ?>
    <div class="space-y-6">
        <h3 class="text-lg font-medium text-gray-900">
            <?php echo e(t('install.admin_step')); ?>

        </h3>
        
        <p class="text-sm text-gray-600">
            <?php echo e(t('install.admin_message')); ?>

        </p>

        <form method="POST" action="<?php echo e(route('install.admin.process')); ?>">
            <?php echo csrf_field(); ?>

            <div class="space-y-4">
                <!-- Nom -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700"><?php echo e(t('install.admin_name')); ?></label>
                    <input type="text" name="name" id="name" value="<?php echo e(old('name')); ?>" required class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                </div>

                <!-- Email -->
                <div class="mt-4">
                    <label for="email" class="block text-sm font-medium text-gray-700"><?php echo e(t('install.admin_email')); ?></label>
                    <input type="email" name="email" id="email" value="<?php echo e(old('email')); ?>" required class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                </div>

                <!-- Mot de passe -->
                <div class="mt-4">
                    <label for="password" class="block text-sm font-medium text-gray-700"><?php echo e(t('install.admin_password')); ?></label>
                    <input type="password" name="password" id="password" required class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                </div>

                <!-- Confirmation du mot de passe -->
                <div class="mt-4">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700"><?php echo e(t('install.admin_password_confirmation')); ?></label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                </div>

                <!-- Activer 2FA -->
                <div class="mt-4">
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input id="enable_2fa" name="enable_2fa" type="checkbox" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="enable_2fa" class="font-medium text-gray-700"><?php echo e(t('install.enable_2fa')); ?></label>
                            <p class="text-gray-500"><?php echo e(t('install.enable_2fa_description')); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-700 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                    <?php echo e(t('common.next')); ?>

                </button>
            </div>
        </form>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('install.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\Adev\200  -  test\AdminLicence-4.5.1\resources\views\install\admin.blade.php ENDPATH**/ ?>