<?php $__env->startSection('title', 'Clés API'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Clés API</h1>
        <a href="<?php echo e(route('client.api-keys.create')); ?>" class="btn btn-primary btn-sm shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Nouvelle Clé API
        </a>
    </div>

    <!-- Statistiques -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($stats['total']); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-key fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Actives</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($stats['active']); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des clés API -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Mes Clés API</h6>
        </div>
        <div class="card-body">
            <?php if($apiKeys->count() > 0): ?>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Projet</th>
                                <th>Statut</th>
                                <th>Créée le</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $apiKeys; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $apiKey): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($apiKey->name); ?></td>
                                    <td><?php echo e($apiKey->project->name ?? 'N/A'); ?></td>
                                    <td>
                                        <?php if($apiKey->status === 'active'): ?>
                                            <span class="badge badge-success">Active</span>
                                        <?php else: ?>
                                            <span class="badge badge-secondary">Inactive</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e($apiKey->created_at->format('d/m/Y')); ?></td>
                                    <td>
                                        <a href="<?php echo e(route('client.api-keys.show', $apiKey)); ?>" class="btn btn-sm btn-primary">Voir</a>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-key fa-3x text-gray-300 mb-3"></i>
                    <h5 class="text-gray-600">Aucune clé API</h5>
                    <p class="text-gray-500">Créez votre première clé API pour commencer.</p>
                    <a href="<?php echo e(route('client.api-keys.create')); ?>" class="btn btn-primary">Créer une clé API</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.client', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\Adev\200  -  test\AdminLicence-4.5.1\resources\views\client\api-keys\index.blade.php ENDPATH**/ ?>