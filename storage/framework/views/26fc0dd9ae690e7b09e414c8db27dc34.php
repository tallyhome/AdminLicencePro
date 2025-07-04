

<?php $__env->startSection('title', 'Mes Factures'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-file-invoice-dollar mr-2"></i>
                        Mes Factures
                    </h3>
                    <div class="card-tools">
                        <a href="<?php echo e(route('client.billing.index')); ?>" class="btn btn-sm btn-secondary">
                            <i class="fas fa-arrow-left"></i> Retour à la facturation
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <?php if($invoices && $invoices->count() > 0): ?>
                        <div class="table-responsive">
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
                                    <?php $__currentLoopData = $invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td>
                                                <strong><?php echo e($invoice->number ?? '#' . $invoice->id); ?></strong>
                                            </td>
                                            <td>
                                                <?php echo e($invoice->created_at->format('d/m/Y')); ?>

                                            </td>
                                            <td>
                                                <span class="badge badge-info">
                                                    <?php echo e(number_format($invoice->amount ?? 0, 2)); ?> €
                                                </span>
                                            </td>
                                            <td>
                                                <?php switch($invoice->status ?? 'pending'):
                                                    case ('paid'): ?>
                                                        <span class="badge badge-success">Payée</span>
                                                        <?php break; ?>
                                                    <?php case ('pending'): ?>
                                                        <span class="badge badge-warning">En attente</span>
                                                        <?php break; ?>
                                                    <?php case ('failed'): ?>
                                                        <span class="badge badge-danger">Échec</span>
                                                        <?php break; ?>
                                                    <?php default: ?>
                                                        <span class="badge badge-secondary"><?php echo e($invoice->status); ?></span>
                                                <?php endswitch; ?>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <?php if(method_exists($invoice, 'downloadUrl')): ?>
                                                        <a href="<?php echo e($invoice->downloadUrl()); ?>" 
                                                           class="btn btn-outline-primary" 
                                                           target="_blank">
                                                            <i class="fas fa-download"></i> Télécharger
                                                        </a>
                                                    <?php else: ?>
                                                        <button class="btn btn-outline-primary" 
                                                                onclick="downloadInvoice(<?php echo e($invoice->id); ?>)">
                                                            <i class="fas fa-download"></i> Télécharger
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>

                        <?php if(method_exists($invoices, 'links')): ?>
                            <div class="d-flex justify-content-center">
                                <?php echo e($invoices->links()); ?>

                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-file-invoice-dollar fa-3x text-muted mb-3"></i>
                            <h4 class="text-muted">Aucune facture</h4>
                            <p class="text-muted">Vous n'avez encore aucune facture.</p>
                            <a href="<?php echo e(route('client.billing.index')); ?>" class="btn btn-primary">
                                <i class="fas fa-credit-card"></i> Voir mes abonnements
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function downloadInvoice(invoiceId) {
    // Rediriger vers la route de téléchargement
    window.open(`<?php echo e(route('client.billing.index')); ?>/download-invoice/${invoiceId}`, '_blank');
}
</script>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.client', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\Adev\200  -  test\AdminLicence-4.5.1\resources\views\client\billing\invoices.blade.php ENDPATH**/ ?>