<?php $__env->startSection('title', 'Détails de l\'abonnement'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-4">
    <h1 class="mt-4">Détails de l'abonnement</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Tableau de bord</a></li>
        <li class="breadcrumb-item"><a href="<?php echo e(route('admin.subscriptions.list')); ?>">Abonnements</a></li>
        <li class="breadcrumb-item active">Détails</li>
    </ol>
    
    <div class="row">
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-info-circle me-1"></i>
                    Informations de l'abonnement
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th>Client:</th>
                            <td><?php echo e($subscription->tenant->name); ?></td>
                        </tr>
                        <tr>
                            <th>Plan:</th>
                            <td><?php echo e($subscription->plan->name); ?></td>
                        </tr>
                        <tr>
                            <th>Prix:</th>
                            <td><?php echo e(number_format($subscription->plan->price, 2)); ?> € / <?php echo e($subscription->plan->billing_cycle === 'monthly' ? 'mois' : 'an'); ?></td>
                        </tr>
                        <tr>
                            <th>Statut:</th>
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
                        </tr>
                        <tr>
                            <th>Date de début:</th>
                            <td><?php echo e($subscription->created_at->format('d/m/Y H:i')); ?></td>
                        </tr>
                        <tr>
                            <th>Date de fin:</th>
                            <td><?php echo e($subscription->ends_at ? $subscription->ends_at->format('d/m/Y H:i') : 'N/A'); ?></td>
                        </tr>
                        <tr>
                            <th>Période d'essai:</th>
                            <td>
                                <?php if($subscription->trial_ends_at): ?>
                                    Jusqu'au <?php echo e($subscription->trial_ends_at->format('d/m/Y')); ?>

                                    <?php if($subscription->trial_ends_at->isPast()): ?>
                                        <span class="badge bg-secondary">Terminée</span>
                                    <?php else: ?>
                                        <span class="badge bg-info">En cours</span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    Aucune
                                <?php endif; ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-credit-card me-1"></i>
                    Méthode de paiement
                </div>
                <div class="card-body">
                    <?php if($subscription->paymentMethod): ?>
                        <table class="table">
                            <tr>
                                <th>Type:</th>
                                <td>
                                    <?php if($subscription->paymentMethod->provider === 'stripe'): ?>
                                        <i class="fab fa-cc-stripe"></i> Stripe
                                        <?php if($subscription->paymentMethod->card_brand): ?>
                                            - <?php echo e(ucfirst($subscription->paymentMethod->card_brand)); ?>

                                        <?php endif; ?>
                                    <?php elseif($subscription->paymentMethod->provider === 'paypal'): ?>
                                        <i class="fab fa-paypal"></i> PayPal
                                    <?php else: ?>
                                        <?php echo e($subscription->paymentMethod->provider); ?>

                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php if($subscription->paymentMethod->provider === 'stripe' && $subscription->paymentMethod->card_last_four): ?>
                                <tr>
                                    <th>Numéro de carte:</th>
                                    <td>**** **** **** <?php echo e($subscription->paymentMethod->card_last_four); ?></td>
                                </tr>
                                <tr>
                                    <th>Expiration:</th>
                                    <td><?php echo e($subscription->paymentMethod->expires_at ? $subscription->paymentMethod->expires_at->format('m/Y') : 'N/A'); ?></td>
                                </tr>
                            <?php endif; ?>
                            <?php if($subscription->paymentMethod->provider === 'paypal' && $subscription->paymentMethod->paypal_email): ?>
                                <tr>
                                    <th>Email PayPal:</th>
                                    <td><?php echo e($subscription->paymentMethod->paypal_email); ?></td>
                                </tr>
                            <?php endif; ?>
                            <tr>
                                <th>Par défaut:</th>
                                <td><?php echo e($subscription->paymentMethod->is_default ? 'Oui' : 'Non'); ?></td>
                            </tr>
                        </table>
                    <?php else: ?>
                        <p class="text-center">Aucune méthode de paiement associée</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-file-invoice-dollar me-1"></i>
            Factures
        </div>
        <div class="card-body">
            <?php if($subscription->invoices->count() > 0): ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Numéro</th>
                            <th>Date</th>
                            <th>Montant</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $subscription->invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($invoice->number); ?></td>
                                <td><?php echo e($invoice->created_at->format('d/m/Y')); ?></td>
                                <td><?php echo e(number_format($invoice->total, 2)); ?> €</td>
                                <td>
                                    <?php if($invoice->status === 'paid'): ?>
                                        <span class="badge bg-success">Payée</span>
                                    <?php elseif($invoice->status === 'pending'): ?>
                                        <span class="badge bg-warning">En attente</span>
                                    <?php elseif($invoice->status === 'failed'): ?>
                                        <span class="badge bg-danger">Échouée</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary"><?php echo e($invoice->status); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?php echo e(route('admin.subscriptions.invoice', $invoice)); ?>" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-center">Aucune facture trouvée pour cet abonnement</p>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="d-flex justify-content-between mb-4">
        <a href="<?php echo e(route('admin.subscriptions.list')); ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Retour à la liste
        </a>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\Adev\200  -  test\AdminLicence-4.5.1\resources\views\admin\subscriptions\show_subscription.blade.php ENDPATH**/ ?>