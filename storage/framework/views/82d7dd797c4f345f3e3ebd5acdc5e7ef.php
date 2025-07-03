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
                        <?php echo method_field('PUT'); ?>
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
                            <div class="progress-bar" role="progressbar" style="width: <?php echo e($usageStats['storage_used']['percentage']); ?>%"></div>
                        </div>
                        <small><?php echo e($usageStats['storage_used']['used_formatted']); ?> / <?php echo e($usageStats['storage_used']['limit_mb']); ?>MB (<?php echo e($usageStats['storage_used']['percentage']); ?>% utilisé)</small>
                        <br><small class="text-muted">Basé sur <?php echo e($tenant ? $tenant->projects()->count() : 0); ?> projets et <?php echo e($tenant ? $tenant->serialKeys()->count() : 0); ?> licences</small>
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
                    <div class="text-center mb-3">
                        <h5 class="text-primary"><?php echo e($subscription->plan->name); ?></h5>
                        <p class="text-muted"><?php echo e(number_format($subscription->plan->price, 2)); ?>€/mois</p>
                        <small class="text-muted">
                            Expire le <?php echo e($subscription->ends_at ? $subscription->ends_at->format('d/m/Y') : 'Jamais'); ?>

                        </small>
                    </div>
                    
                    <!-- Limites du plan -->
                    <div class="row text-sm">
                        <div class="col-6 mb-2">
                            <strong>Stockage:</strong><br>
                            <span class="text-muted"><?php echo e($subscription->plan->storage_limit_mb); ?>MB</span>
                        </div>
                        <div class="col-6 mb-2">
                            <strong>Projets:</strong><br>
                            <span class="text-muted"><?php echo e($subscription->plan->max_projects ?? 'Illimité'); ?></span>
                        </div>
                        <div class="col-6 mb-2">
                            <strong>Licences:</strong><br>
                            <span class="text-muted"><?php echo e($subscription->plan->max_licenses ?? 'Illimité'); ?></span>
                        </div>
                        <div class="col-6 mb-2">
                            <strong>Clients:</strong><br>
                            <span class="text-muted"><?php echo e($subscription->plan->max_clients ?? 'Illimité'); ?></span>
                        </div>
                    </div>
                    
                    <?php if($subscription->plan->features && count($subscription->plan->features) > 0): ?>
                    <hr>
                    <div class="text-sm">
                        <strong>Fonctionnalités:</strong>
                        <ul class="list-unstyled mt-1 mb-0">
                            <?php $__currentLoopData = $subscription->plan->features; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><i class="fas fa-check text-success"></i> <?php echo e($feature); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.client', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\Adev\200  -  test\AdminLicence-4.5.1\resources\views/client/settings/index.blade.php ENDPATH**/ ?>