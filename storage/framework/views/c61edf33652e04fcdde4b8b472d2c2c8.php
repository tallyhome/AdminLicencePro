<?php $__env->startSection('content'); ?>
    <div class="space-y-6">
        <h3 class="text-lg font-medium text-gray-900">
            <?php echo e(t('install.language_step')); ?>

        </h3>
        
        <p class="text-sm text-gray-600">
            <?php echo e(t('install.language_message')); ?>

        </p>

        <form method="POST" action="<?php echo e(route('install.language.process')); ?>">
            <?php echo csrf_field(); ?>

            <div class="space-y-4">
                <!-- Sélection de la langue -->
                <div>
                    <label for="locale" class="block text-sm font-medium text-gray-700"><?php echo e(t('language.select')); ?></label>
                    <select id="locale" name="locale" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                        <?php $__currentLoopData = $localeNames; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($code); ?>" <?php echo e($code === app()->getLocale() ? 'selected' : ''); ?>><?php echo e($name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
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
<?php echo $__env->make('install.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\Adev\200  -  test\AdminLicence-4.5.1\resources\views\install\language.blade.php ENDPATH**/ ?>