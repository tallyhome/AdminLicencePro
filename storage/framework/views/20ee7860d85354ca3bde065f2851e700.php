<?php $__env->startSection('title', 'Détails de la facture'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-4">
    <h1 class="mt-4">Détails de la facture</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Tableau de bord</a></li>
        <li class="breadcrumb-item"><a href="<?php echo e(route('admin.subscriptions.invoices')); ?>">Factures</a></li>
        <li class="breadcrumb-item active">Détails</li>
    </ol>
    
    <div class="row">
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-info-circle me-1"></i>
                    Informations de la facture
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th>Numéro de facture:</th>
                            <td><?php echo e($invoice->number); ?></td>
                        </tr>
                        <tr>
                            <th>Client:</th>
                            <td><?php echo e($invoice->tenant->name); ?></td>
                        </tr>
                        <tr>
                            <th>Date:</th>
                            <td><?php echo e($invoice->created_at->format('d/m/Y H:i')); ?></td>
                        </tr>
                        <tr>
                            <th>Statut:</th>
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
                        </tr>
                        <tr>
                            <th>Montant total:</th>
                            <td><?php echo e(number_format($invoice->total, 2)); ?> €</td>
                        </tr>
                        <tr>
                            <th>Abonnement:</th>
                            <td>
                                <?php if($invoice->subscription): ?>
                                    <a href="<?php echo e(route('admin.subscriptions.show', $invoice->subscription)); ?>">
                                        <?php echo e($invoice->subscription->plan->name); ?>

                                    </a>
                                <?php else: ?>
                                    -
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
                    <?php if($invoice->paymentMethod): ?>
                        <table class="table">
                            <tr>
                                <th>Type:</th>
                                <td>
                                    <?php if($invoice->paymentMethod->provider === 'stripe'): ?>
                                        <i class="fab fa-cc-stripe"></i> Stripe
                                        <?php if($invoice->paymentMethod->card_brand): ?>
                                            - <?php echo e(ucfirst($invoice->paymentMethod->card_brand)); ?>

                                        <?php endif; ?>
                                    <?php elseif($invoice->paymentMethod->provider === 'paypal'): ?>
                                        <i class="fab fa-paypal"></i> PayPal
                                    <?php else: ?>
                                        <?php echo e($invoice->paymentMethod->provider); ?>

                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php if($invoice->paymentMethod->provider === 'stripe' && $invoice->paymentMethod->card_last_four): ?>
                                <tr>
                                    <th>Numéro de carte:</th>
                                    <td>**** **** **** <?php echo e($invoice->paymentMethod->card_last_four); ?></td>
                                </tr>
                                <tr>
                                    <th>Expiration:</th>
                                    <td><?php echo e($invoice->paymentMethod->expires_at ? $invoice->paymentMethod->expires_at->format('m/Y') : 'N/A'); ?></td>
                                </tr>
                            <?php endif; ?>
                            <?php if($invoice->paymentMethod->provider === 'paypal' && $invoice->paymentMethod->paypal_email): ?>
                                <tr>
                                    <th>Email PayPal:</th>
                                    <td><?php echo e($invoice->paymentMethod->paypal_email); ?></td>
                                </tr>
                            <?php endif; ?>
                            <tr>
                                <th>ID de transaction:</th>
                                <td><?php echo e($invoice->provider_id ?? 'N/A'); ?></td>
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
            <i class="fas fa-list me-1"></i>
            Éléments facturés
        </div>
        <div class="card-body">
            <?php if($invoice->items->count() > 0): ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Description</th>
                            <th>Type</th>
                            <th>Quantité</th>
                            <th>Prix unitaire</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $invoice->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($item->description); ?></td>
                                <td>
                                    <?php if($item->type === 'subscription'): ?>
                                        <span class="badge bg-primary">Abonnement</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary"><?php echo e($item->type); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo e($item->quantity); ?></td>
                                <td><?php echo e(number_format($item->unit_price, 2)); ?> €</td>
                                <td><?php echo e(number_format($item->total, 2)); ?> €</td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="4" class="text-end">Total:</th>
                            <th><?php echo e(number_format($invoice->total, 2)); ?> €</th>
                        </tr>
                    </tfoot>
                </table>
            <?php else: ?>
                <p class="text-center">Aucun élément facturé trouvé</p>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="d-flex justify-content-between mb-4">
        <a href="<?php echo e(route('admin.subscriptions.invoices')); ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Retour à la liste
        </a>
        <a href="#" class="btn btn-primary" onclick="window.print()">
            <i class="fas fa-print me-1"></i> Imprimer
        </a>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\Adev\200  -  test\AdminLicence-4.5.1\resources\views\admin\subscriptions\show_invoice.blade.php ENDPATH**/ ?>