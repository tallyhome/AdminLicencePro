

<?php $__env->startSection('title', 'Ticket #' . $ticket->ticket_number); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    <!-- En-tête avec navigation -->
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('client.dashboard')); ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo e(route('client.support.index')); ?>">Support</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Ticket #<?php echo e($ticket->ticket_number); ?></li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Détails du ticket -->
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0"><?php echo e($ticket->subject); ?></h5>
                <span class="badge bg-<?php echo e($ticket->status === 'open' ? 'success' : ($ticket->status === 'closed' ? 'secondary' : 'warning')); ?>">
                    <?php echo e(ucfirst($ticket->status)); ?>

                </span>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Numéro :</strong> <?php echo e($ticket->ticket_number); ?>

                </div>
                <div class="col-md-6">
                    <strong>Priorité :</strong> 
                    <span class="badge bg-<?php echo e($ticket->priority === 'urgent' ? 'danger' : ($ticket->priority === 'high' ? 'warning' : 'info')); ?>">
                        <?php echo e(ucfirst($ticket->priority)); ?>

                    </span>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Catégorie :</strong> <?php echo e(ucfirst(str_replace('_', ' ', $ticket->category))); ?>

                </div>
                <div class="col-md-6">
                    <strong>Créé le :</strong> <?php echo e($ticket->created_at->format('d/m/Y H:i')); ?>

                </div>
            </div>

            <div class="mb-3">
                <strong>Description :</strong>
                <div class="mt-2 p-3 bg-light rounded">
                    <?php echo nl2br(e($ticket->description)); ?>

                </div>
            </div>

            <?php if($ticket->attachments->count() > 0): ?>
                <div class="mb-3">
                    <strong>Pièces jointes :</strong>
                    <ul class="list-unstyled mt-2">
                        <?php $__currentLoopData = $ticket->attachments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attachment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li>
                                <a href="#" class="text-decoration-none">
                                    <i class="fas fa-paperclip me-1"></i>
                                    <?php echo e($attachment->filename); ?>

                                </a>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Réponses -->
    <?php if($ticket->replies->count() > 0): ?>
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="card-title mb-0">Réponses (<?php echo e($ticket->replies->count()); ?>)</h6>
            </div>
            <div class="card-body">
                <?php $__currentLoopData = $ticket->replies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reply): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="border-bottom pb-3 mb-3">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <strong>
                                    <?php if($reply->user_type === 'client'): ?>
                                        Vous
                                    <?php elseif($reply->user_type === 'admin'): ?>
                                        Support AdminLicence
                                    <?php else: ?>
                                        Système
                                    <?php endif; ?>
                                </strong>
                                <small class="text-muted ms-2"><?php echo e($reply->created_at->format('d/m/Y H:i')); ?></small>
                            </div>
                        </div>
                        <div class="mt-2">
                            <?php echo nl2br(e($reply->message)); ?>

                        </div>
                        
                        <?php if($reply->attachments && count($reply->attachments) > 0): ?>
                            <div class="mt-2">
                                <small class="text-muted">Pièces jointes :</small>
                                <ul class="list-unstyled mt-1">
                                    <?php $__currentLoopData = $reply->attachments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $attachment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li>
                                            <a href="<?php echo e(route('client.support.download-attachment', [$ticket->id, $reply->id, $index])); ?>" class="text-decoration-none">
                                                <i class="fas fa-paperclip me-1"></i>
                                                <?php echo e($attachment['filename']); ?>

                                            </a>
                                        </li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Formulaire de réponse -->
    <?php if($ticket->status !== 'closed'): ?>
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="card-title mb-0">Ajouter une réponse</h6>
            </div>
            <div class="card-body">
                <form action="<?php echo e(route('client.support.reply', $ticket)); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="mb-3">
                        <label for="message" class="form-label">Message</label>
                        <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="attachments" class="form-label">Pièces jointes (optionnel)</label>
                        <input type="file" class="form-control" id="attachments" name="attachments[]" multiple>
                        <div class="form-text">Formats acceptés : JPG, PNG, PDF, DOC, TXT, ZIP (max 10MB par fichier)</div>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-reply me-2"></i>Répondre
                        </button>
                        <a href="<?php echo e(route('client.support.index')); ?>" class="btn btn-outline-secondary">Retour</a>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>

    <!-- Actions -->
    <div class="card mt-4">
        <div class="card-body">
            <div class="d-flex gap-2">
                <?php if($ticket->status !== 'closed'): ?>
                    <form action="<?php echo e(route('client.support.close', $ticket)); ?>" method="POST" class="d-inline">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Êtes-vous sûr de vouloir fermer ce ticket ?')">
                            <i class="fas fa-times me-2"></i>Fermer le ticket
                        </button>
                    </form>
                <?php else: ?>
                    <form action="<?php echo e(route('client.support.reopen', $ticket)); ?>" method="POST" class="d-inline">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-outline-success">
                            <i class="fas fa-redo me-2"></i>Rouvrir le ticket
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.client', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\Adev\200  -  test\AdminLicence-4.5.1\resources\views\client\support\show.blade.php ENDPATH**/ ?>