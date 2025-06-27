

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
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" id="settingsTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab">
                                <i class="fas fa-cog me-1"></i> Général
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="navigation-tab" data-bs-toggle="tab" data-bs-target="#navigation" type="button" role="tab">
                                <i class="fas fa-bars me-1"></i> Navigation
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="sections-tab" data-bs-toggle="tab" data-bs-target="#sections" type="button" role="tab">
                                <i class="fas fa-th-large me-1"></i> Sections
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="cta-tab" data-bs-toggle="tab" data-bs-target="#cta" type="button" role="tab">
                                <i class="fas fa-bullhorn me-1"></i> Call-to-Action
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="footer-tab" data-bs-toggle="tab" data-bs-target="#footer" type="button" role="tab">
                                <i class="fas fa-grip-horizontal me-1"></i> Footer
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <form action="<?php echo e(route('admin.cms.settings.update')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        
                        <div class="tab-content" id="settingsTabsContent">
                            <!-- Onglet Général -->
                            <div class="tab-pane fade show active" id="general" role="tabpanel">
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
                                </div>
                            </div>

                            <!-- Onglet Navigation -->
                            <div class="tab-pane fade" id="navigation" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="nav_home_text" class="form-label">Texte "Accueil"</label>
                                        <input type="text" class="form-control" id="nav_home_text" name="nav_home_text" 
                                               value="<?php echo e($settings['nav_home_text'] ?? 'Accueil'); ?>">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="nav_features_text" class="form-label">Texte "Fonctionnalités"</label>
                                        <input type="text" class="form-control" id="nav_features_text" name="nav_features_text" 
                                               value="<?php echo e($settings['nav_features_text'] ?? 'Fonctionnalités'); ?>">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="nav_pricing_text" class="form-label">Texte "Tarifs"</label>
                                        <input type="text" class="form-control" id="nav_pricing_text" name="nav_pricing_text" 
                                               value="<?php echo e($settings['nav_pricing_text'] ?? 'Tarifs'); ?>">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="nav_about_text" class="form-label">Texte "À propos"</label>
                                        <input type="text" class="form-control" id="nav_about_text" name="nav_about_text" 
                                               value="<?php echo e($settings['nav_about_text'] ?? 'À propos'); ?>">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="nav_faq_text" class="form-label">Texte "FAQ"</label>
                                        <input type="text" class="form-control" id="nav_faq_text" name="nav_faq_text" 
                                               value="<?php echo e($settings['nav_faq_text'] ?? 'FAQ'); ?>">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="nav_contact_text" class="form-label">Texte "Contact"</label>
                                        <input type="text" class="form-control" id="nav_contact_text" name="nav_contact_text" 
                                               value="<?php echo e($settings['nav_contact_text'] ?? 'Contact'); ?>">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="nav_support_text" class="form-label">Texte "Support"</label>
                                        <input type="text" class="form-control" id="nav_support_text" name="nav_support_text" 
                                               value="<?php echo e($settings['nav_support_text'] ?? 'Support'); ?>">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="nav_admin_text" class="form-label">Texte bouton "Admin"</label>
                                        <input type="text" class="form-control" id="nav_admin_text" name="nav_admin_text" 
                                               value="<?php echo e($settings['nav_admin_text'] ?? 'Admin'); ?>">
                                    </div>
                                </div>
                            </div>

                            <!-- Onglet Sections -->
                            <div class="tab-pane fade" id="sections" role="tabpanel">
                                <div class="row">
                                    <div class="col-12 mb-4">
                                        <h6>Textes des Sections</h6>
                                    </div>

                                    <!-- Section Fonctionnalités -->
                                    <div class="col-md-6 mb-3">
                                        <label for="features_section_title" class="form-label">Titre Section Fonctionnalités</label>
                                        <input type="text" class="form-control" id="features_section_title" name="features_section_title" 
                                               value="<?php echo e($settings['features_section_title'] ?? 'Fonctionnalités Avancées'); ?>">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="features_section_subtitle" class="form-label">Sous-titre Section Fonctionnalités</label>
                                        <input type="text" class="form-control" id="features_section_subtitle" name="features_section_subtitle" 
                                               value="<?php echo e($settings['features_section_subtitle'] ?? 'Découvrez pourquoi AdminLicence est la solution de référence pour la sécurisation de vos licences'); ?>">
                                    </div>

                                    <!-- Section Témoignages -->
                                    <div class="col-md-6 mb-3">
                                        <label for="testimonials_section_title" class="form-label">Titre Section Témoignages</label>
                                        <input type="text" class="form-control" id="testimonials_section_title" name="testimonials_section_title" 
                                               value="<?php echo e($settings['testimonials_section_title'] ?? 'Ce que disent nos clients'); ?>">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="testimonials_section_subtitle" class="form-label">Sous-titre Section Témoignages</label>
                                        <input type="text" class="form-control" id="testimonials_section_subtitle" name="testimonials_section_subtitle" 
                                               value="<?php echo e($settings['testimonials_section_subtitle'] ?? 'Découvrez les témoignages de nos utilisateurs satisfaits'); ?>">
                                    </div>

                                    <!-- Section FAQs -->
                                    <div class="col-md-6 mb-3">
                                        <label for="faqs_section_title" class="form-label">Titre Section FAQs</label>
                                        <input type="text" class="form-control" id="faqs_section_title" name="faqs_section_title" 
                                               value="<?php echo e($settings['faqs_section_title'] ?? 'Questions Fréquentes'); ?>">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="faqs_section_subtitle" class="form-label">Sous-titre Section FAQs</label>
                                        <input type="text" class="form-control" id="faqs_section_subtitle" name="faqs_section_subtitle" 
                                               value="<?php echo e($settings['faqs_section_subtitle'] ?? 'Trouvez rapidement les réponses à vos questions'); ?>">
                                    </div>
                                </div>

                                <hr>

                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <h6>Sections à Afficher</h6>
                                    </div>
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
                            </div>

                            <!-- Onglet Call-to-Action -->
                            <div class="tab-pane fade" id="cta" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="cta_title" class="form-label">Titre CTA</label>
                                        <input type="text" class="form-control" id="cta_title" name="cta_title" 
                                               value="<?php echo e($settings['cta_title'] ?? 'Prêt à sécuriser vos licences ?'); ?>">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="cta_button_text" class="form-label">Texte du bouton CTA</label>
                                        <input type="text" class="form-control" id="cta_button_text" name="cta_button_text" 
                                               value="<?php echo e($settings['cta_button_text'] ?? 'Commencer maintenant'); ?>">
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label for="cta_subtitle" class="form-label">Sous-titre CTA</label>
                                        <textarea class="form-control" id="cta_subtitle" name="cta_subtitle" rows="3"><?php echo e($settings['cta_subtitle'] ?? 'Rejoignez des milliers d\'entreprises qui font confiance à AdminLicence pour protéger leurs logiciels.'); ?></textarea>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="cta_button_url" class="form-label">URL du bouton CTA</label>
                                        <input type="text" class="form-control" id="cta_button_url" name="cta_button_url" 
                                               value="<?php echo e($settings['cta_button_url'] ?? '/admin'); ?>">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="hero_button_text" class="form-label">Texte bouton Hero principal</label>
                                        <input type="text" class="form-control" id="hero_button_text" name="hero_button_text" 
                                               value="<?php echo e($settings['hero_button_text'] ?? 'Commencer maintenant'); ?>">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="hero_button_secondary_text" class="form-label">Texte bouton Hero secondaire</label>
                                        <input type="text" class="form-control" id="hero_button_secondary_text" name="hero_button_secondary_text" 
                                               value="<?php echo e($settings['hero_button_secondary_text'] ?? 'En savoir plus'); ?>">
                                    </div>
                                </div>
                            </div>

                            <!-- Onglet Footer -->
                            <div class="tab-pane fade" id="footer" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label for="footer_text" class="form-label">Texte de copyright</label>
                                        <textarea class="form-control" id="footer_text" name="footer_text" rows="2"><?php echo e($settings['footer_text'] ?? ''); ?></textarea>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="footer_security_badge_text" class="form-label">Texte badge sécurité</label>
                                        <input type="text" class="form-control" id="footer_security_badge_text" name="footer_security_badge_text" 
                                               value="<?php echo e($settings['footer_security_badge_text'] ?? 'Chiffrement AES-256'); ?>">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="footer_secure_text" class="form-label">Texte "Site sécurisé"</label>
                                        <input type="text" class="form-control" id="footer_secure_text" name="footer_secure_text" 
                                               value="<?php echo e($settings['footer_secure_text'] ?? 'Site sécurisé par AdminLicence'); ?>">
                                    </div>

                                    <!-- Colonne Produit -->
                                    <div class="col-md-4 mb-3">
                                        <label for="footer_product_title" class="form-label">Titre colonne "Produit"</label>
                                        <input type="text" class="form-control" id="footer_product_title" name="footer_product_title" 
                                               value="<?php echo e($settings['footer_product_title'] ?? 'Produit'); ?>">
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="footer_product_features" class="form-label">Lien "Fonctionnalités"</label>
                                        <input type="text" class="form-control" id="footer_product_features" name="footer_product_features" 
                                               value="<?php echo e($settings['footer_product_features'] ?? 'Fonctionnalités'); ?>">
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="footer_product_about" class="form-label">Lien "À propos"</label>
                                        <input type="text" class="form-control" id="footer_product_about" name="footer_product_about" 
                                               value="<?php echo e($settings['footer_product_about'] ?? 'À propos'); ?>">
                                    </div>

                                    <!-- Colonne Support -->
                                    <div class="col-md-4 mb-3">
                                        <label for="footer_support_title" class="form-label">Titre colonne "Support"</label>
                                        <input type="text" class="form-control" id="footer_support_title" name="footer_support_title" 
                                               value="<?php echo e($settings['footer_support_title'] ?? 'Support'); ?>">
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="footer_support_contact" class="form-label">Lien "Contact"</label>
                                        <input type="text" class="form-control" id="footer_support_contact" name="footer_support_contact" 
                                               value="<?php echo e($settings['footer_support_contact'] ?? 'Contact'); ?>">
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="footer_support_documentation" class="form-label">Lien "Documentation"</label>
                                        <input type="text" class="form-control" id="footer_support_documentation" name="footer_support_documentation" 
                                               value="<?php echo e($settings['footer_support_documentation'] ?? 'Documentation'); ?>">
                                    </div>

                                    <!-- Colonne Contact -->
                                    <div class="col-md-12 mb-3">
                                        <label for="footer_contact_title" class="form-label">Titre colonne "Contact"</label>
                                        <input type="text" class="form-control" id="footer_contact_title" name="footer_contact_title" 
                                               value="<?php echo e($settings['footer_contact_title'] ?? 'Contact'); ?>">
                                    </div>

                                    <hr class="my-4">
                                    
                                    <!-- Liens légaux -->
                                    <div class="col-12 mb-3">
                                        <h6 class="text-primary">Liens Légaux</h6>
                                        <p class="text-muted small">Textes des liens vers les pages légales (s'affichent dans le footer)</p>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="footer_terms_text" class="form-label">Texte "Termes et Conditions"</label>
                                        <input type="text" class="form-control" id="footer_terms_text" name="footer_terms_text" 
                                               value="<?php echo e($settings['footer_terms_text'] ?? 'Termes et Conditions'); ?>">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="footer_privacy_text" class="form-label">Texte "Politique de confidentialité"</label>
                                        <input type="text" class="form-control" id="footer_privacy_text" name="footer_privacy_text" 
                                               value="<?php echo e($settings['footer_privacy_text'] ?? 'Politique de confidentialité'); ?>">
                                    </div>

                                    <hr class="my-4">
                                    
                                    <!-- Contenu des pages légales -->
                                    <div class="col-12 mb-3">
                                        <h6 class="text-primary">Contenu des Pages Légales</h6>
                                        <p class="text-muted small">Modifiez le contenu complet des pages Termes et Conditions et Politique de confidentialité</p>
                                    </div>

                                    <div class="col-12 mb-4">
                                        <label for="terms_content" class="form-label fw-bold">Termes et Conditions:</label>
                                        <textarea class="form-control" id="terms_content" name="terms_content" rows="10"><?php echo e($settings['terms_content'] ?? ''); ?></textarea>
                                    </div>

                                    <div class="col-12 mb-4">
                                        <label for="privacy_content" class="form-label fw-bold">Politique de confidentialité:</label>
                                        <textarea class="form-control" id="privacy_content" name="privacy_content" rows="10"><?php echo e($settings['privacy_content'] ?? ''); ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Sauvegarder tous les paramètres
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Configuration CKEditor pour les éditeurs de contenu légal
    function initializeEditor(elementId) {
        ClassicEditor
            .create(document.querySelector('#' + elementId), {
                toolbar: {
                    items: [
                        'undo', 'redo',
                        '|', 'heading',
                        '|', 'fontfamily', 'fontsize', 'fontColor', 'fontBackgroundColor',
                        '|', 'bold', 'italic', 'underline', 'strikethrough',
                        '|', 'link', 'insertTable', 'blockQuote',
                        '|', 'alignment',
                        '|', 'bulletedList', 'numberedList', 'outdent', 'indent',
                        '|', 'insertImage', 'mediaEmbed',
                        '|', 'sourceEditing'
                    ]
                },
                heading: {
                    options: [
                        { model: 'paragraph', title: 'Paragraphe', class: 'ck-heading_paragraph' },
                        { model: 'heading1', view: 'h1', title: 'Titre 1', class: 'ck-heading_heading1' },
                        { model: 'heading2', view: 'h2', title: 'Titre 2', class: 'ck-heading_heading2' },
                        { model: 'heading3', view: 'h3', title: 'Titre 3', class: 'ck-heading_heading3' },
                        { model: 'heading4', view: 'h4', title: 'Titre 4', class: 'ck-heading_heading4' }
                    ]
                },
                fontSize: {
                    options: [
                        9, 11, 13, 'default', 17, 19, 21, 27, 35
                    ]
                },
                fontFamily: {
                    options: [
                        'default',
                        'Arial, Helvetica, sans-serif',
                        'Georgia, serif',
                        'Times New Roman, serif',
                        'Verdana, sans-serif'
                    ]
                },
                language: 'fr',
                table: {
                    contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells']
                },
                image: {
                    toolbar: [
                        'imageTextAlternative',
                        'imageStyle:full',
                        'imageStyle:side'
                    ]
                }
            })
            .then(editor => {
                // Sauvegarder automatiquement les changements
                editor.model.document.on('change:data', () => {
                    document.querySelector('#' + elementId).value = editor.getData();
                });

                // Ajuster la hauteur
                editor.editing.view.change(writer => {
                    writer.setStyle('height', '400px', editor.editing.view.document.getRoot());
                });

                // Bouton de prévisualisation personnalisé
                const previewButton = document.createElement('button');
                previewButton.type = 'button';
                previewButton.className = 'btn btn-outline-primary btn-sm ms-2';
                previewButton.innerHTML = '<i class="fas fa-eye me-1"></i> Prévisualiser';
                previewButton.onclick = function() {
                    const content = editor.getData();
                    const win = window.open('', '_blank', 'width=800,height=600,scrollbars=yes');
                    win.document.write(`
                        <html>
                            <head>
                                <title>Prévisualisation - ${elementId === 'terms_content' ? 'Termes et Conditions' : 'Politique de confidentialité'}</title>
                                <meta charset="utf-8">
                                <meta name="viewport" content="width=device-width, initial-scale=1">
                                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
                                <style>
                                    body { 
                                        font-family: Inter, Arial, sans-serif; 
                                        line-height: 1.7; 
                                        padding: 20px; 
                                        background: #f8f9fa;
                                    }
                                    .container {
                                        max-width: 800px;
                                        background: white;
                                        padding: 2rem;
                                        border-radius: 8px;
                                        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                                    }
                                    h1, h2, h3, h4, h5, h6 { 
                                        color: #2563eb; 
                                        font-weight: 600; 
                                        margin-top: 2rem;
                                        margin-bottom: 1rem;
                                    }
                                    h1 { font-size: 1.75rem; }
                                    h2 { font-size: 1.5rem; }
                                    h3 { font-size: 1.25rem; }
                                    h4 { font-size: 1.1rem; }
                                    p { margin-bottom: 1rem; }
                                    ul, ol { margin-bottom: 1rem; padding-left: 1.5rem; }
                                    li { margin-bottom: 0.5rem; }
                                    blockquote { 
                                        border-left: 4px solid #2563eb; 
                                        padding-left: 1rem; 
                                        margin: 1.5rem 0;
                                        font-style: italic; 
                                        color: #6b7280; 
                                        background: #f8fafc;
                                        padding: 1rem;
                                        border-radius: 0 4px 4px 0;
                                    }
                                    table {
                                        width: 100%;
                                        margin: 1rem 0;
                                        border-collapse: collapse;
                                    }
                                    table, th, td {
                                        border: 1px solid #dee2e6;
                                    }
                                    th, td {
                                        padding: 0.75rem;
                                        text-align: left;
                                    }
                                    th {
                                        background-color: #f8f9fa;
                                        font-weight: 600;
                                    }
                                </style>
                            </head>
                            <body>
                                <div class="container">
                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                        <h1 class="mb-0">${elementId === 'terms_content' ? 'Termes et Conditions' : 'Politique de confidentialité'}</h1>
                                        <button class="btn btn-secondary btn-sm" onclick="window.close()">Fermer</button>
                                    </div>
                                    <hr>
                                    <div class="content">
                                        ${content}
                                    </div>
                                </div>
                            </body>
                        </html>
                    `);
                    win.document.close();
                };

                // Ajouter le bouton après le label
                const label = document.querySelector('label[for="' + elementId + '"]');
                if (label) {
                    label.appendChild(previewButton);
                }
            })
            .catch(error => {
                console.error('Erreur lors de l\'initialisation de CKEditor:', error);
            });
    }

    // Initialiser les éditeurs
    initializeEditor('terms_content');
    initializeEditor('privacy_content');
});
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?> 
<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\Adev\200  -  test\AdminLicence-4.5.1\resources\views\admin\cms\settings\index.blade.php ENDPATH**/ ?>