<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold mb-4">Mon abonnement</h1>
        
        <?php if($subscription): ?>
            <div class="bg-white rounded-lg shadow p-6 mb-8">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h2 class="text-xl font-semibold mb-2"><?php echo e($subscription->plan->name); ?></h2>
                        <p class="text-gray-600"><?php echo e($subscription->plan->description); ?></p>
                    </div>
                    <div class="text-right">
                        <p class="text-lg font-bold"><?php echo e(number_format($subscription->renewal_price, 2)); ?> €/<?php echo e($subscription->billing_cycle); ?></p>
                        <p class="text-sm text-gray-600">Prochain renouvellement : <?php echo e($subscription->ends_at->format('d/m/Y')); ?></p>
                    </div>
                </div>

                <div class="flex gap-4">
                    <?php if($subscription->auto_renew): ?>
                        <form action="<?php echo e(route('subscription.cancel-renewal')); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded transition duration-200">
                                Annuler le renouvellement automatique
                            </button>
                        </form>
                    <?php else: ?>
                        <form action="<?php echo e(route('subscription.enable-renewal')); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded transition duration-200">
                                Activer le renouvellement automatique
                            </button>
                        </form>
                    <?php endif; ?>
                    
                    <a href="<?php echo e(route('subscription.plans')); ?>" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded transition duration-200">
                        Changer de plan
                    </a>
                </div>
            </div>
        <?php else: ?>
            <div class="bg-white rounded-lg shadow p-6 mb-8 text-center">
                <p class="text-gray-600 mb-4">Vous n'avez pas d'abonnement actif.</p>
                <a href="<?php echo e(route('subscription.plans')); ?>" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded transition duration-200">
                    Voir les plans disponibles
                </a>
            </div>
        <?php endif; ?>

        <h2 class="text-2xl font-bold mb-4">Historique des factures</h2>
        <?php if($invoices->count() > 0): ?>
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Numéro</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php $__currentLoopData = $invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    <?php echo e($invoice->number); ?>

                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php echo e($invoice->created_at->format('d/m/Y')); ?>

                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?php echo e(number_format($invoice->amount, 2)); ?> €
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        <?php echo e($invoice->status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'); ?>">
                                        <?php echo e($invoice->status === 'paid' ? 'Payée' : 'En attente'); ?>

                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <a href="<?php echo e(route('invoice.download', $invoice->id)); ?>" class="text-blue-600 hover:text-blue-900">
                                        Télécharger
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4">
                <?php echo e($invoices->links()); ?>

            </div>
        <?php else: ?>
            <div class="bg-white rounded-lg shadow p-6 text-center text-gray-600">
                Aucune facture disponible.
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\Adev\200  -  test\AdminLicence-4.5.1\resources\views\subscriptions\index.blade.php ENDPATH**/ ?>