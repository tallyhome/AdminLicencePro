<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold">Historique de la clé de série</h1>
        <a href="<?php echo e(route('admin.serial-keys.show', $serialKey)); ?>" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
            Retour aux détails
        </a>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="p-4 border-b">
            <h2 class="text-lg font-medium">Clé : <?php echo e($serialKey->serial_key); ?></h2>
            <p class="text-gray-600">Projet : <?php echo e($serialKey->project->name); ?></p>
        </div>

        <div class="p-4">
            <div class="space-y-4">
                <?php $__empty_1 = true; $__currentLoopData = $history; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $entry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="border-l-4 border-blue-500 pl-4 py-2">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-medium">
                                    <?php switch($entry->action):
                                        case ('created'): ?>
                                            Création de la clé
                                            <?php break; ?>
                                        <?php case ('updated'): ?>
                                            Mise à jour
                                            <?php break; ?>
                                        <?php case ('revoked'): ?>
                                            Révocation
                                            <?php break; ?>
                                        <?php case ('deleted'): ?>
                                            Suppression
                                            <?php break; ?>
                                        <?php default: ?>
                                            <?php echo e(ucfirst($entry->action)); ?>

                                    <?php endswitch; ?>
                                </p>
                                <p class="text-sm text-gray-600">
                                    Par : <?php echo e($entry->user ? $entry->user->name : 'Système'); ?>

                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-600">
                                    <?php echo e($entry->created_at->format('d/m/Y H:i')); ?>

                                </p>
                                <p class="text-xs text-gray-500">
                                    IP : <?php echo e($entry->ip_address); ?>

                                </p>
                            </div>
                        </div>

                        <?php if($entry->details): ?>
                            <div class="mt-2 text-sm text-gray-700">
                                <?php if($entry->action === 'updated'): ?>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <h4 class="font-medium">Anciennes valeurs :</h4>
                                            <ul class="list-disc list-inside">
                                                <?php $__currentLoopData = $entry->details['old_data']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php if(in_array($key, ['project_id', 'domain', 'ip_address', 'expires_at', 'status'])): ?>
                                                        <li><?php echo e($key); ?>: <?php echo e($value ?? 'Non défini'); ?></li>
                                                    <?php endif; ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </ul>
                                        </div>
                                        <div>
                                            <h4 class="font-medium">Nouvelles valeurs :</h4>
                                            <ul class="list-disc list-inside">
                                                <?php $__currentLoopData = $entry->details['new_data']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php if(in_array($key, ['project_id', 'domain', 'ip_address', 'expires_at', 'status'])): ?>
                                                        <li><?php echo e($key); ?>: <?php echo e($value ?? 'Non défini'); ?></li>
                                                    <?php endif; ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </ul>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <pre class="bg-gray-100 p-2 rounded"><?php echo e(json_encode($entry->details, JSON_PRETTY_PRINT)); ?></pre>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="text-gray-600 text-center py-4">Aucun historique disponible pour cette clé.</p>
                <?php endif; ?>
            </div>

            <div class="mt-4">
                <?php echo e($history->links()); ?>

            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\Adev\200  -  test\AdminLicence-4.5.1\resources\views\admin\serial-keys\history.blade.php ENDPATH**/ ?>