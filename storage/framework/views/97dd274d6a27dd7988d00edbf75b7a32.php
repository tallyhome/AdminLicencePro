<?php $__env->startSection('title', 'Méthodes de paiement'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-4">
    <h1 class="mt-4">Méthodes de paiement</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Tableau de bord</a></li>
        <li class="breadcrumb-item active">Méthodes de paiement</li>
    </ol>
    
    <div class="row mb-4">
        <div class="col-xl-6 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fab fa-cc-stripe fa-2x me-2"></i>
                            <span class="h4">Stripe</span>
                        </div>
                        <div class="h2"><?php echo e($stripeCount); ?></div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="<?php echo e(route('admin.subscriptions.payment-settings')); ?>">Configurer Stripe</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-md-6">
            <div class="card bg-info text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fab fa-paypal fa-2x me-2"></i>
                            <span class="h4">PayPal</span>
                        </div>
                        <div class="h2"><?php echo e($paypalCount); ?></div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="<?php echo e(route('admin.subscriptions.payment-settings')); ?>">Configurer PayPal</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-credit-card me-1"></i>
            Liste des méthodes de paiement
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Client</th>
                        <th>Type</th>
                        <th>Détails</th>
                        <th>Par défaut</th>
                        <th>Créée le</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $paymentMethods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $method): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($method->tenant->name); ?></td>
                            <td>
                                <?php if($method->provider === 'stripe'): ?>
                                    <i class="fab fa-cc-stripe"></i> Stripe
                                    <?php if($method->card_brand): ?>
                                        - <?php echo e(ucfirst($method->card_brand)); ?>

                                    <?php endif; ?>
                                <?php elseif($method->provider === 'paypal'): ?>
                                    <i class="fab fa-paypal"></i> PayPal
                                <?php else: ?>
                                    <?php echo e($method->provider); ?>

                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($method->provider === 'stripe' && $method->card_last_four): ?>
                                    **** **** **** <?php echo e($method->card_last_four); ?>

                                    <?php if($method->expires_at): ?>
                                        <br><small>Expire: <?php echo e($method->expires_at->format('m/Y')); ?></small>
                                    <?php endif; ?>
                                <?php elseif($method->provider === 'paypal' && $method->paypal_email): ?>
                                    <?php echo e($method->paypal_email); ?>

                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($method->is_default): ?>
                                    <span class="badge bg-success">Oui</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Non</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($method->created_at->format('d/m/Y')); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="text-center">Aucune méthode de paiement trouvée</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            
            <div class="d-flex justify-content-center mt-4">
                <?php echo e($paymentMethods->links()); ?>

            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\Adev\200  -  test\AdminLicence-4.5.1\resources\views\admin\subscriptions\payment_methods.blade.php ENDPATH**/ ?>