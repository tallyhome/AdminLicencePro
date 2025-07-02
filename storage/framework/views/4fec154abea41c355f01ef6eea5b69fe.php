<?php $__env->startSection('title', 'Support'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- En-tête de page -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Support</h1>
        <a href="<?php echo e(route('client.support.create')); ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Nouveau Ticket
        </a>
    </div>

    <!-- Cartes de statistiques -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Tickets</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($stats['total']); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-ticket-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Ouverts</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($stats['open']); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-folder-open fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                En Cours</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($stats['in_progress']); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-cog fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Fermés</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($stats['closed']); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres et recherche -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filtres</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="<?php echo e(route('client.support.index')); ?>">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="search">Recherche</label>
                            <input type="text" class="form-control" id="search" name="search" 
                                   value="<?php echo e(request('search')); ?>" placeholder="Sujet, description...">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="status">Statut</label>
                            <select class="form-control" id="status" name="status">
                                <option value="">Tous</option>
                                <option value="open" <?php echo e(request('status') == 'open' ? 'selected' : ''); ?>>Ouvert</option>
                                <option value="in_progress" <?php echo e(request('status') == 'in_progress' ? 'selected' : ''); ?>>En cours</option>
                                <option value="closed" <?php echo e(request('status') == 'closed' ? 'selected' : ''); ?>>Fermé</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="priority">Priorité</label>
                            <select class="form-control" id="priority" name="priority">
                                <option value="">Toutes</option>
                                <option value="low" <?php echo e(request('priority') == 'low' ? 'selected' : ''); ?>>Faible</option>
                                <option value="medium" <?php echo e(request('priority') == 'medium' ? 'selected' : ''); ?>>Moyenne</option>
                                <option value="high" <?php echo e(request('priority') == 'high' ? 'selected' : ''); ?>>Haute</option>
                                <option value="urgent" <?php echo e(request('priority') == 'urgent' ? 'selected' : ''); ?>>Urgente</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <div class="d-flex">
                                <button type="submit" class="btn btn-primary mr-2">
                                    <i class="fas fa-search"></i> Filtrer
                                </button>
                                <a href="<?php echo e(route('client.support.index')); ?>" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Reset
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des tickets -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Mes Tickets</h6>
        </div>
        <div class="card-body">
            <?php if($tickets->count() > 0): ?>
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Numéro</th>
                                <th>Sujet</th>
                                <th>Statut</th>
                                <th>Priorité</th>
                                <th>Catégorie</th>
                                <th>Dernière Activité</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $tickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ticket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>
                                        <strong><?php echo e($ticket->ticket_number); ?></strong>
                                    </td>
                                    <td>
                                        <a href="<?php echo e(route('client.support.show', $ticket)); ?>" class="text-decoration-none">
                                            <?php echo e($ticket->subject); ?>

                                        </a>
                                        <?php if($ticket->replies_count > 0): ?>
                                            <br><small class="text-muted"><?php echo e($ticket->replies_count); ?> réponse(s)</small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php switch($ticket->status):
                                            case ('open'): ?>
                                                <span class="badge badge-warning">Ouvert</span>
                                                <?php break; ?>
                                            <?php case ('in_progress'): ?>
                                                <span class="badge badge-info">En cours</span>
                                                <?php break; ?>
                                            <?php case ('closed'): ?>
                                                <span class="badge badge-success">Fermé</span>
                                                <?php break; ?>
                                            <?php default: ?>
                                                <span class="badge badge-secondary"><?php echo e(ucfirst($ticket->status)); ?></span>
                                        <?php endswitch; ?>
                                    </td>
                                    <td>
                                        <?php switch($ticket->priority):
                                            case ('low'): ?>
                                                <span class="badge badge-secondary">Faible</span>
                                                <?php break; ?>
                                            <?php case ('medium'): ?>
                                                <span class="badge badge-primary">Moyenne</span>
                                                <?php break; ?>
                                            <?php case ('high'): ?>
                                                <span class="badge badge-warning">Haute</span>
                                                <?php break; ?>
                                            <?php case ('urgent'): ?>
                                                <span class="badge badge-danger">Urgente</span>
                                                <?php break; ?>
                                        <?php endswitch; ?>
                                    </td>
                                    <td>
                                        <?php switch($ticket->category):
                                            case ('technical'): ?>
                                                <span class="badge badge-info">Technique</span>
                                                <?php break; ?>
                                            <?php case ('billing'): ?>
                                                <span class="badge badge-success">Facturation</span>
                                                <?php break; ?>
                                            <?php case ('general'): ?>
                                                <span class="badge badge-secondary">Général</span>
                                                <?php break; ?>
                                            <?php case ('feature_request'): ?>
                                                <span class="badge badge-primary">Fonctionnalité</span>
                                                <?php break; ?>
                                        <?php endswitch; ?>
                                    </td>
                                    <td>
                                        <?php echo e($ticket->updated_at->diffForHumans()); ?>

                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" 
                                                    data-toggle="dropdown">
                                                Actions
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="<?php echo e(route('client.support.show', $ticket)); ?>">
                                                    <i class="fas fa-eye"></i> Voir
                                                </a>
                                                <?php if($ticket->status !== 'closed'): ?>
                                                    <form method="POST" action="<?php echo e(route('client.support.close', $ticket)); ?>" style="display: inline;">
                                                        <?php echo csrf_field(); ?>
                                                        <button type="submit" class="dropdown-item text-success">
                                                            <i class="fas fa-check"></i> Fermer
                                                        </button>
                                                    </form>
                                                <?php else: ?>
                                                    <form method="POST" action="<?php echo e(route('client.support.reopen', $ticket)); ?>" style="display: inline;">
                                                        <?php echo csrf_field(); ?>
                                                        <button type="submit" class="dropdown-item text-warning">
                                                            <i class="fas fa-undo"></i> Rouvrir
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    <?php echo e($tickets->appends(request()->query())->links()); ?>

                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-ticket-alt fa-3x text-gray-300 mb-3"></i>
                    <h5 class="text-gray-600">Aucun ticket trouvé</h5>
                    <p class="text-gray-500">Créez votre premier ticket de support pour obtenir de l'aide.</p>
                    <a href="<?php echo e(route('client.support.create')); ?>" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Créer un ticket
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Liens utiles -->
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Ressources Utiles</h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <a href="<?php echo e(route('client.support.faq')); ?>" class="list-group-item list-group-item-action">
                            <i class="fas fa-question-circle text-primary mr-2"></i>
                            FAQ - Questions Fréquentes
                        </a>
                        <a href="<?php echo e(route('client.support.documentation')); ?>" class="list-group-item list-group-item-action">
                            <i class="fas fa-book text-info mr-2"></i>
                            Documentation
                        </a>
                        <a href="mailto:support@adminlicence.com" class="list-group-item list-group-item-action">
                            <i class="fas fa-envelope text-success mr-2"></i>
                            Contact Direct
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Horaires de Support</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Support Standard:</strong><br>
                        Lundi - Vendredi: 9h00 - 18h00<br>
                        Temps de réponse: 24-48h
                    </div>
                    <div class="mb-3">
                        <strong>Support Premium:</strong><br>
                        24h/24, 7j/7<br>
                        Temps de réponse: 2-4h
                    </div>
                    <small class="text-muted">
                        Les temps de réponse peuvent varier selon la complexité du problème.
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.client', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\Adev\200  -  test\AdminLicence-4.5.1\resources\views\client\support\index.blade.php ENDPATH**/ ?>