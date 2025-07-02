

<?php $__env->startSection('title', 'Facturation'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Facturation</h1>

    <div class="row">
        <!-- Abonnement actuel -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Abonnement Actuel</h6>
                </div>
                <div class="card-body">
                    <?php if(isset($subscription) && $subscription): ?>
                        <div class="text-center mb-3">
                            <h4 class="text-primary"><?php echo e($subscription->plan->name ?? 'Plan inconnu'); ?></h4>
                            <p class="text-muted mb-1"><?php echo e(number_format($subscription->plan->price ?? 0, 2)); ?>€/mois</p>
                            <span class="badge badge-<?php echo e($subscription->status === 'active' ? 'success' : 'warning'); ?>">
                                <?php echo e(ucfirst($subscription->status)); ?>

                            </span>
                        </div>
                        
                        <?php if($subscription->ends_at): ?>
                        <div class="mb-3">
                            <small class="text-muted">Expire le</small><br>
                            <strong><?php echo e($subscription->ends_at->format('d/m/Y')); ?></strong>
                        </div>
                        <?php endif; ?>
                        
                        <div class="d-grid gap-2">
                            <a href="<?php echo e(route('client.billing.subscription')); ?>" class="btn btn-primary btn-sm">
                                Gérer l'abonnement
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="text-center">
                            <i class="fas fa-exclamation-circle fa-3x text-warning mb-3"></i>
                            <p class="text-muted">Aucun abonnement actif</p>
                            <a href="<?php echo e(route('client.billing.subscription')); ?>" class="btn btn-primary">
                                Choisir un plan
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Méthode de paiement -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Méthode de Paiement</h6>
                </div>
                <div class="card-body">
                    <?php if(isset($paymentMethod)): ?>
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-credit-card fa-2x text-primary mr-3"></i>
                            <div>
                                <strong>•••• •••• •••• <?php echo e($paymentMethod->last4 ?? '0000'); ?></strong><br>
                                <small class="text-muted">Expire <?php echo e($paymentMethod->exp_month ?? '00'); ?>/<?php echo e($paymentMethod->exp_year ?? '00'); ?></small>
                            </div>
                        </div>
                        <button class="btn btn-outline-primary btn-sm">Modifier</button>
                    <?php else: ?>
                        <div class="text-center">
                            <i class="fas fa-credit-card fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Aucune méthode de paiement</p>
                            <button class="btn btn-primary btn-sm">Ajouter une carte</button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Prochaine facture -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Prochaine Facture</h6>
                </div>
                <div class="card-body">
                    <?php if(isset($nextInvoice)): ?>
                        <div class="mb-3">
                            <small class="text-muted">Montant</small><br>
                            <h4 class="text-primary"><?php echo e(number_format($nextInvoice->amount ?? 0, 2)); ?>€</h4>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted">Date</small><br>
                            <strong><?php echo e($nextInvoice->date ?? 'N/A'); ?></strong>
                        </div>
                    <?php else: ?>
                        <div class="text-center">
                            <i class="fas fa-file-invoice fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Aucune facture prévue</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Historique des factures -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Historique des Factures</h6>
            <a href="<?php echo e(route('client.billing.invoices')); ?>" class="btn btn-primary btn-sm">
                Voir toutes les factures
            </a>
        </div>
        <div class="card-body">
            <?php if(isset($invoices) && $invoices->count() > 0): ?>
                <div class="table-responsive">
                    <table class="table table-bordered">
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
                            <?php $__currentLoopData = $invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($invoice->number); ?></td>
                                <td><?php echo e($invoice->created_at->format('d/m/Y')); ?></td>
                                <td><?php echo e(number_format($invoice->amount, 2)); ?>€</td>
                                <td>
                                    <span class="badge badge-<?php echo e($invoice->status === 'paid' ? 'success' : 'warning'); ?>">
                                        <?php echo e($invoice->status === 'paid' ? 'Payée' : 'En attente'); ?>

                                    </span>
                                </td>
                                <td>
                                    <a href="<?php echo e(route('client.billing.download-invoice', $invoice)); ?>" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-download"></i> Télécharger
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-4">
                    <i class="fas fa-file-invoice fa-3x text-gray-300 mb-3"></i>
                    <p class="text-muted">Aucune facture disponible</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('client.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\Adev\200  -  test\AdminLicence-4.5.1\resources\views\client\billing\index.blade.php ENDPATH**/ ?>