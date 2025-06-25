<!DOCTYPE html>
<html lang="<?php echo e(app()->getLocale()); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title data-i18n="admin_login.title"><?php echo e(t('admin_login.title')); ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/css/login.css']); ?>
    <script src="<?php echo e(asset('js/translations.js')); ?>"></script>
</head>
<body>
    <div class="login-page">
        <!-- Sélecteur de langue -->
        <div class="language-selector" style="position: absolute; top: 20px; right: 20px; z-index: 1000;">
            <select id="language-selector" class="border rounded px-2 py-1 text-sm bg-white shadow-sm">
                    <option value="fr" <?php echo e(App::getLocale() == 'fr' ? 'selected' : ''); ?>>Français</option>
                    <option value="en" <?php echo e(App::getLocale() == 'en' ? 'selected' : ''); ?>>English</option>
                    <option value="es" <?php echo e(App::getLocale() == 'es' ? 'selected' : ''); ?>>Español</option>
                    <option value="ru" <?php echo e(App::getLocale() == 'ru' ? 'selected' : ''); ?>>Русский</option>
                    <option value="de" <?php echo e(App::getLocale() == 'de' ? 'selected' : ''); ?>>Deutsch</option>
                    <option value="it" <?php echo e(App::getLocale() == 'it' ? 'selected' : ''); ?>>Italiano</option>
                    <option value="nl" <?php echo e(App::getLocale() == 'nl' ? 'selected' : ''); ?>>Nederlands</option>
                    <option value="pt" <?php echo e(App::getLocale() == 'pt' ? 'selected' : ''); ?>>Português</option>
                    <option value="zh" <?php echo e(App::getLocale() == 'zh' ? 'selected' : ''); ?>>中文</option>
                    <option value="ja" <?php echo e(App::getLocale() == 'ja' ? 'selected' : ''); ?>>日本語</option>
                    <option value="ar" <?php echo e(App::getLocale() == 'ar' ? 'selected' : ''); ?>>العربية</option>
                    <option value="tr" <?php echo e(App::getLocale() == 'tr' ? 'selected' : ''); ?>>Türkçe</option>
                </select>
        </div>
        
        <!-- Partie gauche -->
        <div class="login-left">
            <div class="login-header">
                <div class="logo-container" style="text-align: center; margin-bottom: 20px;">
                    <img src="<?php echo e(asset('images/logo.png')); ?>" alt="Logo" style="max-width: 150px; height: auto;">
                </div>
                <h1><span data-i18n="admin_login.welcome"><?php echo e(t('admin_login.welcome')); ?></span><br><span data-i18n="admin_login.app_name"><?php echo e(t('admin_login.app_name')); ?></span></h1>
                <p><br><span data-i18n="admin_login.subtitle"><?php echo e(t('admin_login.subtitle')); ?></span></p>
            </div>

            <div class="features-list">
                <div class="feature-item">
                    <i class="fas fa-shield-alt"></i>
                    <span data-i18n="admin_login.features.secure_management"><?php echo e(t('admin_login.features.secure_management')); ?></span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-chart-line"></i>
                    <span data-i18n="admin_login.features.tracking_analysis"><?php echo e(t('admin_login.features.tracking_analysis')); ?></span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-plus-circle"></i>
                    <span data-i18n="admin_login.features.and_more"><?php echo e(t('admin_login.features.and_more')); ?></span>
                </div>
            </div>
        </div>

        <!-- Partie droite -->
        <div class="login-right">
            <div class="login-form">
                <h2 data-i18n="admin_login.login"><?php echo e(t('admin_login.login')); ?></h2>

                <?php if(session('error')): ?>
                    <div class="alert">
                        <?php echo e(session('error')); ?>

                    </div>
                <?php endif; ?>

                <?php if($errors->any()): ?>
                    <div class="alert">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <p><?php echo e($error); ?></p>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="<?php echo e(route('admin.login.post')); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="form-group">
                        <label class="form-label" for="email" data-i18n="admin_login.email"><?php echo e(t('admin_login.email')); ?></label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               class="form-input" 
                               value="<?php echo e(old('email')); ?>" 
                               required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="password" data-i18n="admin_login.password"><?php echo e(t('admin_login.password')); ?></label>
                        <input type="password" 
                               id="password" 
                               name="password" 
                               class="form-input" 
                               required>
                    </div>

                    <div class="checkbox-group">
                        <input type="checkbox" 
                               id="remember" 
                               name="remember">
                        <label for="remember" data-i18n="admin_login.remember_me"><?php echo e(t('admin_login.remember_me')); ?></label>
                    </div>

                    <button type="submit" class="btn-primary" data-i18n="admin_login.login_button"><?php echo e(t('admin_login.login_button')); ?></button>

                    <?php
                        use Illuminate\Support\Facades\Route;
                    ?>
                    
                    <?php if(Route::has('admin.password.request')): ?>
                        <div class="form-footer">
                            <a href="<?php echo e(route('admin.password.request')); ?>">
                                <span data-i18n="admin_login.forgot_password"><?php echo e(t('admin_login.forgot_password')); ?></span>
                            </a>
                        </div>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>
</body>
</html><?php /**PATH R:\Adev\200  -  test\AdminLicence-4.5.1\resources\views/auth/admin-login.blade.php ENDPATH**/ ?>