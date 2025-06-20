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

        <!-- Mailgun -->
        <div class="col-md-6 col-xl-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Mailgun</h5>
                    <p class="card-text">Service d'envoi d'emails transactionnels via l'API Mailgun.</p>
                    <a href="<?php echo e(route('admin.mail.providers.mailgun.index')); ?>" class="btn btn-primary">
                        <i class="fas fa-cog me-2"></i>Configurer
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
    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\Adev\200  -  test\AdminLicence-4.5.1\resources\views\admin\mail\providers\index.blade.php ENDPATH**/ ?>