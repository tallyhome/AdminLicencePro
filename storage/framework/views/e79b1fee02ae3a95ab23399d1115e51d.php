<?php $__env->startSection('title', 'Paramètres de paiement'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-4">
    <h1 class="mt-4">Paramètres de paiement</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Tableau de bord</a></li>
        <li class="breadcrumb-item active">Paramètres de paiement</li>
    </ol>
    
    <?php if(session('success')): ?>
        <div class="alert alert-success">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>
    
    <?php if(session('error')): ?>
        <div class="alert alert-danger">
            <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>
    
    <form action="<?php echo e(route('admin.subscriptions.payment-settings.update')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        
        <div class="row">
            <div class="col-xl-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <i class="fab fa-cc-stripe me-2"></i>
                            <span>Configuration Stripe</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="stripe_key" class="form-label">Clé publique (Publishable Key)</label>
                            <input type="text" class="form-control <?php $__errorArgs = ['stripe_key'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="stripe_key" name="stripe_key" value="<?php echo e(old('stripe_key', $stripeSettings['key'])); ?>">
                            <?php $__errorArgs = ['stripe_key'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        
                        <div class="mb-3">
                            <label for="stripe_secret" class="form-label">Clé secrète (Secret Key)</label>
                            <input type="password" class="form-control <?php $__errorArgs = ['stripe_secret'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="stripe_secret" name="stripe_secret" value="<?php echo e(old('stripe_secret', $stripeSettings['secret'])); ?>">
                            <?php $__errorArgs = ['stripe_secret'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        
                        <div class="mb-3">
                            <label for="stripe_webhook_secret" class="form-label">Clé secrète Webhook</label>
                            <input type="password" class="form-control <?php $__errorArgs = ['stripe_webhook_secret'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="stripe_webhook_secret" name="stripe_webhook_secret" value="<?php echo e(old('stripe_webhook_secret', $stripeSettings['webhook_secret'])); ?>">
                            <?php $__errorArgs = ['stripe_webhook_secret'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        
                        <div class="alert alert-info">
                            <h5 class="alert-heading">URL de Webhook Stripe</h5>
                            <p class="mb-0"><?php echo e(url('/webhooks/stripe')); ?></p>
                            <small>Configurez cette URL dans votre tableau de bord Stripe.</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <i class="fab fa-paypal me-2"></i>
                            <span>Configuration PayPal</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="paypal_client_id" class="form-label">Client ID</label>
                            <input type="text" class="form-control <?php $__errorArgs = ['paypal_client_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="paypal_client_id" name="paypal_client_id" value="<?php echo e(old('paypal_client_id', $paypalSettings['client_id'])); ?>">
                            <?php $__errorArgs = ['paypal_client_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        
                        <div class="mb-3">
                            <label for="paypal_secret" class="form-label">Secret</label>
                            <input type="password" class="form-control <?php $__errorArgs = ['paypal_secret'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="paypal_secret" name="paypal_secret" value="<?php echo e(old('paypal_secret', $paypalSettings['secret'])); ?>">
                            <?php $__errorArgs = ['paypal_secret'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        
                        <div class="mb-3">
                            <label for="paypal_webhook_id" class="form-label">Webhook ID</label>
                            <input type="text" class="form-control <?php $__errorArgs = ['paypal_webhook_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="paypal_webhook_id" name="paypal_webhook_id" value="<?php echo e(old('paypal_webhook_id', $paypalSettings['webhook_id'])); ?>">
                            <?php $__errorArgs = ['paypal_webhook_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="paypal_sandbox" name="paypal_sandbox" <?php echo e(old('paypal_sandbox', $paypalSettings['sandbox']) ? 'checked' : ''); ?>>
                            <label class="form-check-label" for="paypal_sandbox">Mode Sandbox (développement)</label>
                        </div>
                        
                        <div class="alert alert-info">
                            <h5 class="alert-heading">URL de Webhook PayPal</h5>
                            <p class="mb-0"><?php echo e(url('/webhooks/paypal')); ?></p>
                            <small>Configurez cette URL dans votre tableau de bord PayPal.</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="d-flex justify-content-end mb-4">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-1"></i> Enregistrer les modifications
            </button>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\Adev\200  -  test\AdminLicence-4.5.1\resources\views\admin\subscriptions\payment_settings.blade.php ENDPATH**/ ?>