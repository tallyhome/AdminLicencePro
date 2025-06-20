<?php $__env->startSection('title', 'Abonnements'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-4">
    <h1 class="mt-4">Abonnements</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Tableau de bord</a></li>
        <li class="breadcrumb-item active">Abonnements</li>
    </ol>
    
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-credit-card me-1"></i>
            Liste des abonnements
        </div>
        <div class="card-body">
            <?php if(session('success')): ?>
                <div class="alert alert-success">
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>
            
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Client</th>
                        <th>Plan</th>
                        <th>Statut</th>
                        <th>Méthode de paiement</th>
                        <th>Date de début</th>
                        <th>Date de fin</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $subscriptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subscription): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($subscription->tenant->name); ?></td>
                            <td><?php echo e($subscription->plan->name); ?></td>
                            <td>
                                <?php if($subscription->isActive()): ?>
                                    <span class="badge bg-success">Actif</span>
                                <?php elseif($subscription->isOnTrial()): ?>
                                    <span class="badge bg-info">Période d'essai</span>
                                <?php elseif($subscription->isCanceled()): ?>
                                    <span class="badge bg-warning">Annulé</span>
                                <?php elseif($subscription->isExpired()): ?>
                                    <span class="badge bg-danger">Expiré</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Inconnu</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($subscription->paymentMethod): ?>
                                    <?php if($subscription->paymentMethod->provider === 'stripe'): ?>
                                        <i class="fab fa-cc-stripe"></i> Stripe
                                    <?php elseif($subscription->paymentMethod->provider === 'paypal'): ?>
                                        <i class="fab fa-paypal"></i> PayPal
                                    <?php else: ?>
                                        <?php echo e($subscription->paymentMethod->provider); ?>

                                    <?php endif; ?>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($subscription->created_at->format('d/m/Y')); ?></td>
                            <td><?php echo e($subscription->ends_at ? $subscription->ends_at->format('d/m/Y') : 'N/A'); ?></td>
                            <td>
                                <a href="<?php echo e(route('admin.subscriptions.show', $subscription)); ?>" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="text-center">Aucun abonnement trouvé</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            
            <div class="d-flex justify-content-center mt-4">
                <?php echo e($subscriptions->links()); ?>

            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\Adev\200  -  test\AdminLicence-4.5.1\resources\views\admin\subscriptions\subscriptions.blade.php ENDPATH**/ ?>