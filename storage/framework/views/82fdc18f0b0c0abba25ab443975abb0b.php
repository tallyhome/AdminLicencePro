<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Plans tarifaires</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <?php $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="bg-white rounded-lg shadow-lg p-6 flex flex-col">
                <h2 class="text-2xl font-bold mb-4"><?php echo e($plan['name']); ?></h2>
                <p class="text-gray-600 mb-4"><?php echo e($plan['description']); ?></p>
                
                <div class="text-3xl font-bold mb-6">
                    <?php echo e(number_format($plan['price'], 2)); ?> €
                    <span class="text-sm font-normal text-gray-600">/mois</span>
                </div>

                <ul class="mb-8 flex-grow">
                    <?php $__currentLoopData = $plan['features']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li class="flex items-center mb-2">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <?php echo e($feature); ?>

                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>

                <?php if(isset($currentSubscription) && $currentSubscription->plan_id === $plan['id']): ?>
                    <button disabled class="w-full bg-gray-300 text-gray-700 py-2 px-4 rounded-lg">
                        Plan actuel
                    </button>
                <?php else: ?>
                    <form action="<?php echo e(route('subscription.create')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="plan_id" value="<?php echo e($plan['id']); ?>">
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg transition duration-200">
                            Souscrire
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\Adev\200  -  test\AdminLicence-4.5.1\resources\views\subscriptions\plans.blade.php ENDPATH**/ ?>