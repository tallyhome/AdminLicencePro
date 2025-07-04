<?php $__env->startSection('title', 'Fournisseurs d\'email'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h1 class="h3">Fournisseurs d'email</h1>
        </div>
    </div>

    <div class="row">
        <!-- PHPMail -->
        <div class="col-md-6 col-xl-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-envelope-open-text me-2"></i>
                        PHPMail
                    </h5>
                    <p class="card-text">Gestion SMTP avancée pour l'envoi d'emails avec support des templates personnalisés et suivi des envois.</p>
                    <a href="<?php echo e(route('admin.mail.providers.phpmail.index')); ?>" class="btn btn-primary">
                        <i class="fas fa-envelope"></i> PHPMail
                    </a>
                </div>
            </div>
        </div>

        <!-- Mailchimp -->
        <div class="col-md-6 col-xl-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fab fa-mailchimp me-2"></i>
                        Mailchimp
                    </h5>
                    <p class="card-text">Gestion des campagnes d'emailing, listes de diffusion et templates avec intégration Mailchimp.</p>
                    <a href="<?php echo e(route('admin.mail.providers.mailchimp.index')); ?>" class="btn btn-primary">
                        <i class="fab fa-mailchimp"></i> Mailchimp
                    </a>
                </div>
            </div>
        </div>

        <!-- Rapidmail -->
        <div class="col-md-6 col-xl-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-paper-plane me-2"></i>
                        Rapidmail
                    </h5>
                    <p class="card-text">Solution d'envoi en masse avec gestion des listes de destinataires et statistiques détaillées.</p>
                    <a href="<?php echo e(route('admin.mail.providers.rapidmail.index')); ?>" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i> Rapidmail
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Configuration globale -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Configuration globale</h5>
                    <form action="<?php echo e(route('admin.mail.providers.update')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        
                        <div class="mb-3">
                            <label for="default_provider" class="form-label">Fournisseur par défaut</label>
                            <select name="default_provider" id="default_provider" class="form-select">
                                <option value="phpmail" <?php echo e($settings->default_provider === 'phpmail' ? 'selected' : ''); ?>>PHPMail</option>
                                <option value="mailchimp" <?php echo e($settings->default_provider === 'mailchimp' ? 'selected' : ''); ?>>Mailchimp</option>
                                <option value="rapidmail" <?php echo e($settings->default_provider === 'rapidmail' ? 'selected' : ''); ?>>Rapidmail</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i> Enregistrer
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\Adev\200  -  test\AdminLicence-4.5.1\resources\views\admin\email\providers\index.blade.php ENDPATH**/ ?>