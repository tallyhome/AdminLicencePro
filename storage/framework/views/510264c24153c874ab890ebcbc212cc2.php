<?php $__env->startSection('title', 'Factures'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-4">
    <h1 class="mt-4">Factures</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Tableau de bord</a></li>
        <li class="breadcrumb-item active">Factures</li>
    </ol>
    
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-file-invoice-dollar me-1"></i>
            Liste des factures
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
                        <th>Numéro</th>
                        <th>Client</th>
                        <th>Abonnement</th>
                        <th>Montant</th>
                        <th>Statut</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($invoice->number); ?></td>
                            <td><?php echo e($invoice->tenant->name); ?></td>
                            <td>
                                <?php if($invoice->subscription): ?>
                                    <?php echo e($invoice->subscription->plan->name); ?>

                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
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
                            <td><?php echo e($invoice->created_at->format('d/m/Y')); ?></td>
                            <td>
                                <a href="<?php echo e(route('admin.subscriptions.invoice', $invoice)); ?>" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="text-center">Aucune facture trouvée</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            
            <div class="d-flex justify-content-center mt-4">
                <?php echo e($invoices->links()); ?>

            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\Adev\200  -  test\AdminLicence-4.5.1\resources\views\admin\subscriptions\invoices.blade.php ENDPATH**/ ?>