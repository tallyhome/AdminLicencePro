<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-center mb-12">Nos Plans & Tarifs</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <?php $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="bg-white rounded-lg shadow-lg overflow-hidden transition-transform duration-300 hover:scale-105">
                <!-- En-tête du plan -->
                <div class="p-6 bg-gradient-to-r from-blue-600 to-blue-800 text-white">
                    <h2 class="text-2xl font-bold mb-2"><?php echo e($plan->name); ?></h2>
                    <div class="text-3xl font-bold mb-2">
                        <?php echo e(number_format($plan->price, 2)); ?>€
                        <span class="text-sm font-normal">/mois</span>
                    </div>
                    <p class="text-blue-100"><?php echo e($plan->description); ?></p>
                </div>

                <!-- Caractéristiques du plan -->
                <div class="p-6">
                    <ul class="space-y-4">
                        <?php $__currentLoopData = json_decode($plan->features); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-3"></i>
                                <span><?php echo e($feature); ?></span>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>

                <!-- Bouton de souscription -->
                <div class="p-6 bg-gray-50">
                    <?php if(isset($currentSubscription) && $currentSubscription->plan_id === $plan->id): ?>
                        <button disabled class="w-full bg-gray-400 text-white py-3 px-4 rounded-lg font-semibold">
                            Plan actuel
                        </button>
                    <?php else: ?>
                        <form action="<?php echo e(route('subscription.create')); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="plan_id" value="<?php echo e($plan->id); ?>">
                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 px-4 rounded-lg font-semibold transition duration-200">
                                Souscrire
                            </button>
                        </form>
                    <?php endif; ?>
                </div>

                <?php if($plan->trial_days > 0): ?>
                    <div class="px-6 pb-6 text-center text-sm text-gray-600">
                        Essai gratuit de <?php echo e($plan->trial_days); ?> jours
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <!-- Section FAQ -->
    <div class="mt-16">
        <h2 class="text-2xl font-bold text-center mb-8">Questions fréquentes</h2>
        <div class="max-w-3xl mx-auto space-y-4">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="font-semibold mb-2">Comment fonctionne la période d'essai ?</h3>
                <p class="text-gray-600">La période d'essai vous permet de tester toutes les fonctionnalités du plan choisi gratuitement. À la fin de la période d'essai, votre carte sera débitée automatiquement si vous ne résiliez pas votre abonnement.</p>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="font-semibold mb-2">Puis-je changer de plan à tout moment ?</h3>
                <p class="text-gray-600">Oui, vous pouvez passer à un plan supérieur ou inférieur à tout moment. La différence de prix sera calculée au prorata de la période restante.</p>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="font-semibold mb-2">Comment puis-je annuler mon abonnement ?</h3>
                <p class="text-gray-600">Vous pouvez annuler votre abonnement à tout moment depuis votre espace client. L'accès aux services reste actif jusqu'à la fin de la période en cours.</p>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\Adev\200  -  test\AdminLicence-4.5.1\resources\views\subscriptions\pricing.blade.php ENDPATH**/ ?>