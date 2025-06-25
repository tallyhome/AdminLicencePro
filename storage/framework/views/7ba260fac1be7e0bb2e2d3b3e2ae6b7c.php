<?php $__env->startSection('title', t('serial_keys.title')); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4><?php echo e(t('serial_keys.title')); ?></h4>
        <div>
            <a href="<?php echo e(route('admin.serial-keys.create')); ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> <?php echo e(t('serial_keys.create_key')); ?>

            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-light">
            <h5 class="card-title mb-0"><?php echo e(t('serial_keys.list')); ?></h5>
        </div>
        <div class="card-body border-bottom pb-2 pt-2">
            <form action="<?php echo e(route('admin.serial-keys.index')); ?>" method="GET" id="searchForm">
                <input type="hidden" name="per_page" value="<?php echo e(request()->input('per_page', 10)); ?>">
                
                <div class="row align-items-end">
                    <div class="col-md-1">
                        <div class="form-group" style="width: 100%; min-width: 50px;">
                            <label for="licence_type" class="small">Type</label>
                            <select name="licence_type" id="licence_type" class="form-control form-control-sm">
                                <option value=""><?php echo e(t('common.all')); ?></option>
                                <option value="single" <?php echo e(request('licence_type') === 'single' ? 'selected' : ''); ?>>Single</option>
                                <option value="multi" <?php echo e(request('licence_type') === 'multi' ? 'selected' : ''); ?>>Multi</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group" style="width: 100%; min-width: 60px;">
                            <label for="status" class="small"><?php echo e(t('serial_keys.status')); ?></label>
                            <select name="status" id="status" class="form-control form-control-sm">
                                <option value=""><?php echo e(t('common.all')); ?></option>
                                <option value="active" <?php echo e(request('status') === 'active' ? 'selected' : ''); ?>><?php echo e(t('serial_keys.status_active')); ?></option>
                                <option value="suspended" <?php echo e(request('status') === 'suspended' ? 'selected' : ''); ?>><?php echo e(t('serial_keys.status_suspended')); ?></option>
                                <option value="revoked" <?php echo e(request('status') === 'revoked' ? 'selected' : ''); ?>><?php echo e(t('serial_keys.status_revoked')); ?></option>
                                <option value="expired" <?php echo e(request('status') === 'expired' ? 'selected' : ''); ?>><?php echo e(t('serial_keys.status_expired')); ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-1-5">
                        <div class="form-group" style="width: 100%; min-width: 80px;">
                            <label for="project" class="small"><?php echo e(t('serial_keys.project')); ?></label>
                            <select name="project_id" id="project" class="form-control form-control-sm">
                                <option value=""><?php echo e(t('common.all')); ?></option>
                                <?php $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($project->id); ?>" <?php echo e(request('project_id') == $project->id ? 'selected' : ''); ?>>
                                        <?php echo e($project->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group" style="width: 100%; min-width: 60px;">
                            <label for="used" class="small"><?php echo e(t('serial_keys.key_used')); ?></label>
                            <select name="used" id="used" class="form-control form-control-sm">
                                <option value=""><?php echo e(t('common.all')); ?></option>
                                <option value="true" <?php echo e(request('used') === 'true' ? 'selected' : ''); ?>><?php echo e(t('common.used')); ?></option>
                                <option value="false" <?php echo e(request('used') === 'false' ? 'selected' : ''); ?>><?php echo e(t('common.unused')); ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-1-5">
                        <div class="form-group">
                            <label for="domain" class="small"><?php echo e(t('serial_keys.domain')); ?></label>
                            <input type="text" name="domain" id="domain" class="form-control form-control-sm" value="<?php echo e(request('domain')); ?>" placeholder="exemple.com">
                        </div>
                    </div>
                    <div class="col-md-1-5">
                        <div class="form-group">
                            <label for="ip_address" class="small">Adresse IP</label>
                            <input type="text" name="ip_address" id="ip_address" class="form-control form-control-sm" value="<?php echo e(request('ip_address')); ?>" placeholder="255.255.255.255">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="search" class="small"><?php echo e(t('common.search')); ?></label>
                            <input type="text" name="search" id="search" class="form-control form-control-sm" value="<?php echo e(request('search')); ?>" placeholder="<?php echo e(t('serial_keys.search_placeholder')); ?>">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group" style="width: 100%; min-width: 40px;">
                            <label for="per_page" class="small"><?php echo e(t('pagination.per_page', ['number' => ''])); ?></label>
                            <select name="per_page" id="per_page" class="form-control form-control-sm" onchange="document.getElementById('searchForm').submit()">
                                <option value="10" <?php echo e(request('per_page') == 10 ? 'selected' : ''); ?>>10</option>
                                <option value="25" <?php echo e(request('per_page') == 25 ? 'selected' : ''); ?>>25</option>
                                <option value="50" <?php echo e(request('per_page') == 50 ? 'selected' : ''); ?>>50</option>
                                <option value="100" <?php echo e(request('per_page') == 100 ? 'selected' : ''); ?>>100</option>
                                <option value="500" <?php echo e(request('per_page') == 500 ? 'selected' : ''); ?>>500</option>
                                <option value="1000" <?php echo e(request('per_page') == 1000 ? 'selected' : ''); ?>>1000</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-1 d-flex justify-content-end">
                        <div class="form-group" style="width: 100%; min-width: 50px;">
                            <label>&nbsp;</label>
                            <button type="submit" class="btn btn-primary btn-sm w-100">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-body">
            <style>
                /* Ajustement des colonnes pour un meilleur affichage */
                .table th:nth-child(1), .table td:nth-child(1) { /* Colonne Clé */
                    width: 180px;
                    max-width: 180px;
                    overflow: hidden;
                    text-overflow: ellipsis;
                    white-space: nowrap;
                }
                .table th:nth-child(2), .table td:nth-child(2) { /* Colonne Projet */
                    width: 160px; /* Agrandi de 5 caractères (120px + 40px) */
                    max-width: 160px;
                    overflow: hidden;
                    text-overflow: ellipsis;
                    white-space: nowrap;
                }
                .table th:nth-child(3), .table td:nth-child(3) { /* Colonne Type */
                    width: 90px;
                    max-width: 90px;
                    text-align: center;
                }
                .table th:nth-child(4), .table td:nth-child(4) { /* Colonne Utilisation */
                    width: 100px; /* Réduit de 40px */
                    max-width: 100px;
                    padding-left: 15px; /* Décalage vers la droite */
                }
                .table th:nth-child(5), .table td:nth-child(5) { /* Colonne Statut */
                    width: 90px;
                    max-width: 90px;
                    text-align: center;
                }
                .table th:nth-child(6), .table td:nth-child(6) { /* Colonne Domaine */
                    width: 140px;
                    max-width: 140px;
                    overflow: hidden;
                    text-overflow: ellipsis;
                    white-space: nowrap;
                }
                .table th:nth-child(7), .table td:nth-child(7) { /* Colonne Adresse IP */
                    width: 120px;
                    max-width: 120px;
                    overflow: hidden;
                    text-overflow: ellipsis;
                    white-space: nowrap;
                }
                .table th:nth-child(8), .table td:nth-child(8) { /* Colonne Expiration */
                    width: 110px;
                    max-width: 110px;
                }
                .table th:nth-child(9), .table td:nth-child(9) { /* Colonne Actions */
                    width: 180px;
                    min-width: 180px;
                }
                /* Couleur orange pour le bouton révoquer */
                .btn-revoke {
                    background-color: #fd7e14 !important;
                    border-color: #fd7e14 !important;
                    color: white !important;
                }
                .btn-revoke:hover {
                    background-color: #e86d01 !important;
                    border-color: #e86d01 !important;
                }
            </style>
            <div class="table-responsive">
                <table class="table table-striped table-sm">
                    <thead>
                        <tr>
                            <th><?php echo e(t('serial_keys.key')); ?></th>
                            <th><?php echo e(t('serial_keys.project')); ?></th>
                            <th>Type</th>
                            <th>Utilisation</th>
                            <th><?php echo e(t('serial_keys.status')); ?></th>
                            <th><?php echo e(t('serial_keys.domain')); ?></th>
                            <th><?php echo e(t('serial_keys.ip_address')); ?></th>
                            <th><?php echo e(t('serial_keys.expiration')); ?></th>
                            <th><?php echo e(t('serial_keys.actions')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $serialKeys; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td>
                                    <code><?php echo e($key->serial_key); ?></code>
                                </td>
                                <td><?php echo e($key->project->name); ?></td>
                                <td>
                                    <?php if($key->licence_type === 'single'): ?>
                                        <span class="badge bg-primary">
                                            <i class="fas fa-user"></i> Single
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-warning">
                                            <i class="fas fa-users"></i> Multi
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($key->licence_type === 'single'): ?>
                                        <?php if($key->used_accounts > 0): ?>
                                            <span class="badge bg-success">Utilisée</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Libre</span>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <div class="d-flex align-items-center">
                                            <span class="me-2"><?php echo e($key->used_accounts); ?>/<?php echo e($key->max_accounts); ?></span>
                                            <div class="progress flex-grow-1" style="height: 6px; min-width: 50px;">
                                                <div class="progress-bar <?php echo e($key->used_accounts >= $key->max_accounts ? 'bg-danger' : ($key->used_accounts > $key->max_accounts * 0.8 ? 'bg-warning' : 'bg-success')); ?>" 
                                                     style="width: <?php echo e($key->max_accounts > 0 ? ($key->used_accounts / $key->max_accounts) * 100 : 0); ?>%"></div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($key->status === 'active'): ?>
                                        <span class="badge bg-success"><?php echo e(t('serial_keys.status_active')); ?></span>
                                    <?php elseif($key->status === 'suspended'): ?>
                                        <span class="badge bg-warning"><?php echo e(t('serial_keys.status_suspended')); ?></span>
                                    <?php elseif($key->status === 'revoked'): ?>
                                        <span class="badge bg-danger"><?php echo e(t('serial_keys.status_revoked')); ?></span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary"><?php echo e(t('serial_keys.status_expired')); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($key->licence_type === 'single'): ?>
                                        <?php echo e($key->domain ?? t('serial_keys.not_specified')); ?>

                                    <?php else: ?>
                                        <small class="text-muted">Multi-domaines</small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php echo e($key->ip_address ?? t('serial_keys.not_specified')); ?>

                                </td>
                                <td><?php echo e($key->expires_at ? $key->expires_at->format('d/m/Y') : t('serial_keys.no_expiration')); ?></td>
                                <td>
                                    <div class="btn-group">
                                        <a href="<?php echo e(route('admin.serial-keys.show', $key)); ?>" class="btn btn-sm btn-info" title="<?php echo e(t('serial_keys.view')); ?>">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?php echo e(route('admin.serial-keys.edit', $key)); ?>" class="btn btn-sm btn-primary" title="<?php echo e(t('serial_keys.edit')); ?>">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <?php if($key->status === 'active'): ?>
                                            <form action="<?php echo e(route('admin.serial-keys.suspend', $key)); ?>" method="POST" class="d-inline">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('PATCH'); ?>
                                                <button type="submit" class="btn btn-sm btn-warning" onclick="return confirm('<?php echo e(t('serial_keys.confirm_suspend')); ?>')" title="<?php echo e(t('serial_keys.suspend')); ?>">
                                                    <i class="fas fa-pause"></i>
                                                </button>
                                            </form>
                                            <form action="<?php echo e(route('admin.serial-keys.revoke', $key)); ?>" method="POST" class="d-inline">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('PATCH'); ?>
                                                <button type="submit" class="btn btn-sm btn-revoke" onclick="return confirm('<?php echo e(t('serial_keys.confirm_revoke')); ?>')" title="<?php echo e(t('serial_keys.revoke')); ?>">
                                                    <i class="fas fa-ban"></i>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                        <form action="<?php echo e(route('admin.serial-keys.destroy', $key)); ?>" method="POST" class="d-inline">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('<?php echo e(t('serial_keys.confirm_delete')); ?>')" title="<?php echo e(t('common.delete')); ?>">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="9" class="text-center"><?php echo e(t('serial_keys.no_keys')); ?></td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3">
                <?php echo e($serialKeys->links('pagination::bootstrap-4')); ?>

            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\Adev\200  -  test\AdminLicence-4.5.1\resources\views\admin\serial-keys\index.blade.php ENDPATH**/ ?>