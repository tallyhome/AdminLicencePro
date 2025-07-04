

<?php $__env->startSection('title', 'Méthodes de paiement'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-credit-card mr-2"></i>
                        Mes Méthodes de Paiement
                    </h3>
                    <div class="card-tools">
                        <a href="<?php echo e(route('client.payment-methods.create')); ?>" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Ajouter une méthode
                        </a>
                        <a href="<?php echo e(route('client.billing.index')); ?>" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Retour à la facturation
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <?php if($paymentMethods && $paymentMethods->count() > 0): ?>
                        <div class="row">
                            <?php $__currentLoopData = $paymentMethods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paymentMethod): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="card <?php echo e($paymentMethod->is_default ? 'border-success' : ''); ?>">
                                        <div class="card-header">
                                            <h6 class="card-title mb-0">
                                                <i class="<?php echo e($paymentMethod->icon); ?>"></i>
                                                <?php echo e($paymentMethod->display_name); ?>

                                            </h6>
                                            <?php if($paymentMethod->is_default): ?>
                                                <span class="badge badge-success">Par défaut</span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-2">
                                                <strong>Type :</strong> 
                                                <?php if($paymentMethod->isStripe()): ?>
                                                    <span class="badge badge-info">Stripe</span>
                                                <?php elseif($paymentMethod->isPaypal()): ?>
                                                    <span class="badge badge-warning">PayPal</span>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <?php if($paymentMethod->isStripe() && $paymentMethod->exp_month && $paymentMethod->exp_year): ?>
                                                <div class="mb-2">
                                                    <strong>Expire :</strong> <?php echo e($paymentMethod->exp_month); ?>/<?php echo e($paymentMethod->exp_year); ?>

                                                    <?php if($paymentMethod->isExpired()): ?>
                                                        <span class="badge badge-danger ml-1">Expirée</span>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <div class="mb-2">
                                                <small class="text-muted">
                                                    Ajoutée le <?php echo e($paymentMethod->created_at->format('d/m/Y')); ?>

                                                </small>
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <div class="btn-group btn-group-sm w-100">
                                                <?php if(!$paymentMethod->is_default): ?>
                                                    <form method="POST" action="<?php echo e(route('client.payment-methods.set-default', $paymentMethod)); ?>" style="display: inline;">
                                                        <?php echo csrf_field(); ?>
                                                        <button type="submit" class="btn btn-outline-success">
                                                            <i class="fas fa-star"></i> Définir par défaut
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                                
                                                <button type="button" class="btn btn-outline-danger" 
                                                        onclick="confirmDelete(<?php echo e($paymentMethod->id); ?>)">
                                                    <i class="fas fa-trash"></i> Supprimer
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-credit-card fa-3x text-muted mb-3"></i>
                            <h4 class="text-muted">Aucune méthode de paiement</h4>
                            <p class="text-muted">Ajoutez une méthode de paiement pour gérer vos abonnements.</p>
                            <a href="<?php echo e(route('client.payment-methods.create')); ?>" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Ajouter une méthode de paiement
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation de suppression -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmer la suppression</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer cette méthode de paiement ?</p>
                <p class="text-warning">Cette action est irréversible.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(paymentMethodId) {
    const form = document.getElementById('deleteForm');
    form.action = `<?php echo e(route('client.payment-methods.index')); ?>/${paymentMethodId}`;
    $('#deleteModal').modal('show');
}
</script>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.client', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\Adev\200  -  test\AdminLicence-4.5.1\resources\views\client\payment-methods\index.blade.php ENDPATH**/ ?>