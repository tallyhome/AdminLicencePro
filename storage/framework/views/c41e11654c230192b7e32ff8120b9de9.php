

<?php $__env->startSection('title', 'Paramètres'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Paramètres</h1>

    <div class="row">
        <div class="col-lg-8">
            <!-- Profil -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informations du Profil</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?php echo e(route('client.settings.update-profile')); ?>">
                        <?php echo csrf_field(); ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="first_name">Prénom</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" 
                                           value="<?php echo e($client->first_name); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="last_name">Nom</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" 
                                           value="<?php echo e($client->last_name); ?>" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="<?php echo e($client->email); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Téléphone</label>
                            <input type="text" class="form-control" id="phone" name="phone" 
                                   value="<?php echo e($client->phone); ?>">
                        </div>
                        <button type="submit" class="btn btn-primary">Mettre à jour</button>
                    </form>
                </div>
            </div>

            <!-- Entreprise -->
            <?php if($tenant): ?>
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informations de l'Entreprise</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Nom:</strong> <?php echo e($tenant->name); ?>

                        </div>
                        <div class="col-md-6">
                            <strong>Domaine:</strong> <?php echo e($tenant->domain); ?>

                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <div class="col-lg-4">
            <!-- Statistiques d'utilisation -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Utilisation</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted">Stockage utilisé</small>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" style="width: 45%"></div>
                        </div>
                        <small>45% utilisé</small>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Dernière connexion</small><br>
                        <small><?php echo e($usageStats['last_login'] ? $usageStats['last_login']->diffForHumans() : 'Jamais'); ?></small>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Compte créé</small><br>
                        <small><?php echo e($usageStats['account_created']->format('d/m/Y')); ?></small>
                    </div>
                </div>
            </div>

            <!-- Abonnement -->
            <?php if($subscription): ?>
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Abonnement</h6>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <h5 class="text-primary"><?php echo e($subscription->plan->name); ?></h5>
                        <p class="text-muted"><?php echo e(number_format($subscription->plan->price, 2)); ?>€/mois</p>
                        <small class="text-muted">
                            Expire le <?php echo e($subscription->ends_at ? $subscription->ends_at->format('d/m/Y') : 'Jamais'); ?>

                        </small>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('client.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\Adev\200  -  test\AdminLicence-4.5.1\resources\views\client\settings\index.blade.php ENDPATH**/ ?>