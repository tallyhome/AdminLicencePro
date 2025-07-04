

<?php $__env->startSection('title', 'Gestion de l\'abonnement'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Abonnement actuel -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-crown mr-2"></i>
                        Mon Abonnement Actuel
                    </h3>
                    <div class="card-tools">
                        <a href="<?php echo e(route('client.billing.index')); ?>" class="btn btn-sm btn-secondary">
                            <i class="fas fa-arrow-left"></i> Retour
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <?php if($subscription && $subscription->plan): ?>
                        <div class="row">
                            <div class="col-md-6">
                                <h4><?php echo e($subscription->plan->name); ?></h4>
                                <p class="text-muted"><?php echo e($subscription->plan->description); ?></p>
                                
                                <div class="mt-3">
                                    <strong>Prix :</strong> 
                                    <span class="badge badge-success">
                                        <?php echo e(number_format($subscription->plan->price, 2)); ?> € 
                                        / <?php echo e($subscription->plan->billing_cycle === 'monthly' ? 'mois' : 'an'); ?>

                                    </span>
                                </div>
                                
                                <div class="mt-2">
                                    <strong>Statut :</strong>
                                    <?php switch($subscription->status):
                                        case ('active'): ?>
                                            <span class="badge badge-success">Actif</span>
                                            <?php break; ?>
                                        <?php case ('trial'): ?>
                                            <span class="badge badge-info">Période d'essai</span>
                                            <?php break; ?>
                                        <?php case ('cancelled'): ?>
                                            <span class="badge badge-warning">Annulé</span>
                                            <?php break; ?>
                                        <?php default: ?>
                                            <span class="badge badge-secondary"><?php echo e($subscription->status); ?></span>
                                    <?php endswitch; ?>
                                </div>

                                <?php if($subscription->ends_at): ?>
                                    <div class="mt-2">
                                        <strong>Se termine le :</strong> <?php echo e($subscription->ends_at->format('d/m/Y')); ?>

                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="col-md-6">
                                <h5>Fonctionnalités incluses :</h5>
                                <ul class="list-unstyled">
                                    <?php if($subscription->plan->features): ?>
                                        <?php $__currentLoopData = $subscription->plan->features; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li><i class="fas fa-check text-success mr-2"></i><?php echo e($feature); ?></li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>

                        <div class="mt-4">
                            <div class="btn-group">
                                <?php if($subscription->status === 'active'): ?>
                                    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#cancelModal">
                                        <i class="fas fa-times"></i> Annuler l'abonnement
                                    </button>
                                <?php endif; ?>
                                
                                                <a href="<?php echo e(route('client.payment-methods.index')); ?>" class="btn btn-primary">
                    <i class="fas fa-credit-card"></i> Gérer les modes de paiement
                </a>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-crown fa-3x text-muted mb-3"></i>
                            <h4 class="text-muted">Aucun abonnement actif</h4>
                            <p class="text-muted">Choisissez un plan pour commencer.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Plans disponibles -->
            <?php if($plans && $plans->count() > 0): ?>
                <div class="card mt-4">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-list mr-2"></i>
                            Plans Disponibles
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-md-4 mb-4">
                                    <div class="card <?php echo e($subscription && $subscription->plan_id === $plan->id ? 'border-primary' : ''); ?>">
                                        <div class="card-header text-center">
                                            <h5 class="card-title"><?php echo e($plan->name); ?></h5>
                                            <?php if($subscription && $subscription->plan_id === $plan->id): ?>
                                                <span class="badge badge-primary">Plan actuel</span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="card-body text-center">
                                            <h2 class="text-primary">
                                                <?php echo e(number_format($plan->price, 2)); ?> €
                                                <small class="text-muted">
                                                    / <?php echo e($plan->billing_cycle === 'monthly' ? 'mois' : 'an'); ?>

                                                </small>
                                            </h2>
                                            <p class="text-muted"><?php echo e($plan->description); ?></p>
                                            
                                            <?php if($plan->features): ?>
                                                <ul class="list-unstyled text-left">
                                                    <?php $__currentLoopData = $plan->features; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <li><i class="fas fa-check text-success mr-2"></i><?php echo e($feature); ?></li>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </ul>
                                            <?php endif; ?>
                                            
                                            <?php if(!$subscription || $subscription->plan_id !== $plan->id): ?>
                                                <form method="POST" action="<?php echo e(route('client.billing.upgrade')); ?>">
                                                    <?php echo csrf_field(); ?>
                                                    <input type="hidden" name="plan_id" value="<?php echo e($plan->id); ?>">
                                                    <button type="submit" class="btn btn-primary btn-block">
                                                        <?php if($subscription): ?>
                                                            Changer pour ce plan
                                                        <?php else: ?>
                                                            Choisir ce plan
                                                        <?php endif; ?>
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal d'annulation -->
<div class="modal fade" id="cancelModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Annuler l'abonnement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir annuler votre abonnement ?</p>
                <p class="text-muted">Votre abonnement restera actif jusqu'à la fin de la période de facturation.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form method="POST" action="<?php echo e(route('client.billing.cancel')); ?>" style="display: inline;">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-danger">Confirmer l'annulation</button>
                </form>
            </div>
        </div>
    </div>
</div>


<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.client', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\Adev\200  -  test\AdminLicence-4.5.1\resources\views\client\billing\subscription.blade.php ENDPATH**/ ?>