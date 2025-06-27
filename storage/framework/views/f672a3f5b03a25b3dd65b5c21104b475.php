

<?php $__env->startSection('title', 'Paramètres CMS'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Paramètres CMS</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('admin.cms.index')); ?>">CMS</a></li>
                        <li class="breadcrumb-item active">Paramètres</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Configuration Générale</h5>
                </div>
                <div class="card-body">
                    <form action="<?php echo e(route('admin.cms.settings.update')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="site_title" class="form-label">Titre du Site</label>
                                <input type="text" class="form-control" id="site_title" name="site_title" 
                                       value="<?php echo e($settings['site_title'] ?? ''); ?>">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="site_tagline" class="form-label">Slogan</label>
                                <input type="text" class="form-control" id="site_tagline" name="site_tagline" 
                                       value="<?php echo e($settings['site_tagline'] ?? ''); ?>">
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="hero_title" class="form-label">Titre Hero</label>
                                <input type="text" class="form-control" id="hero_title" name="hero_title" 
                                       value="<?php echo e($settings['hero_title'] ?? ''); ?>">
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="hero_subtitle" class="form-label">Sous-titre Hero</label>
                                <textarea class="form-control" id="hero_subtitle" name="hero_subtitle" rows="3"><?php echo e($settings['hero_subtitle'] ?? ''); ?></textarea>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="contact_email" class="form-label">Email de Contact</label>
                                <input type="email" class="form-control" id="contact_email" name="contact_email" 
                                       value="<?php echo e($settings['contact_email'] ?? ''); ?>">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="contact_phone" class="form-label">Téléphone de Contact</label>
                                <input type="text" class="form-control" id="contact_phone" name="contact_phone" 
                                       value="<?php echo e($settings['contact_phone'] ?? ''); ?>">
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="footer_text" class="form-label">Texte du Footer</label>
                                <textarea class="form-control" id="footer_text" name="footer_text" rows="2"><?php echo e($settings['footer_text'] ?? ''); ?></textarea>
                            </div>
                        </div>

                        <hr>

                        <h6>Sections à Afficher</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="show_hero" name="show_hero" 
                                           value="1" <?php echo e(($settings['show_hero'] ?? true) ? 'checked' : ''); ?>>
                                    <label class="form-check-label" for="show_hero">Afficher la section Hero</label>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="show_features" name="show_features" 
                                           value="1" <?php echo e(($settings['show_features'] ?? true) ? 'checked' : ''); ?>>
                                    <label class="form-check-label" for="show_features">Afficher les Fonctionnalités</label>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="show_testimonials" name="show_testimonials" 
                                           value="1" <?php echo e(($settings['show_testimonials'] ?? true) ? 'checked' : ''); ?>>
                                    <label class="form-check-label" for="show_testimonials">Afficher les Témoignages</label>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="show_faqs" name="show_faqs" 
                                           value="1" <?php echo e(($settings['show_faqs'] ?? true) ? 'checked' : ''); ?>>
                                    <label class="form-check-label" for="show_faqs">Afficher les FAQs</label>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="show_about" name="show_about" 
                                           value="1" <?php echo e(($settings['show_about'] ?? true) ? 'checked' : ''); ?>>
                                    <label class="form-check-label" for="show_about">Afficher À Propos</label>
                                </div>
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Sauvegarder
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Aperçu</h5>
                </div>
                <div class="card-body">
                    <p><strong>Titre :</strong> <?php echo e($settings['site_title'] ?? 'AdminLicence'); ?></p>
                    <p><strong>Slogan :</strong> <?php echo e($settings['site_tagline'] ?? 'Système de gestion de licences'); ?></p>
                    <hr>
                    <p class="small text-muted">
                        Les modifications seront visibles immédiatement sur le site frontend.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\Adev\200  -  test\AdminLicence-4.5.1\resources\views\admin\cms\settings\index.blade.php ENDPATH**/ ?>