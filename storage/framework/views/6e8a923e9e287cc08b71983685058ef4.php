<?php $__env->startSection('title', 'Logs Mailgun'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3">Logs Mailgun</h1>
                <a href="<?php echo e(route('admin.mail.providers.mailgun.index')); ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Retour
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Événement</th>
                                    <th>Destinataire</th>
                                    <th>Sujet</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e(\Carbon\Carbon::createFromTimestamp($event->getTimestamp())->format('d/m/Y H:i:s')); ?></td>
                                    <td><?php echo e($event->getEvent()); ?></td>
                                    <td><?php echo e($event->getRecipient()); ?></td>
                                    <td><?php echo e($event->getMessage()->getHeaders()['subject'] ?? 'N/A'); ?></td>
                                    <td>
                                        <?php
                                            $status = $event->getDeliveryStatus()['message'] ?? null;
                                            $badgeClass = match($event->getEvent()) {
                                                'delivered' => 'bg-success',
                                                'failed' => 'bg-danger',
                                                'bounced' => 'bg-warning',
                                                'complained' => 'bg-danger',
                                                default => 'bg-secondary'
                                            };
                                        ?>
                                        <span class="badge <?php echo e($badgeClass); ?>">
                                            <?php echo e($status ?? $event->getEvent()); ?>

                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="5" class="text-center">Aucun événement trouvé</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\Adev\200  -  test\AdminLicence-4.5.1\resources\views\admin\mail\providers\mailgun\logs.blade.php ENDPATH**/ ?>