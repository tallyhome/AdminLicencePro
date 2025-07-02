<?php $__env->startSection('title', 'Contact - AdminLicence'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-light min-vh-100">
    <!-- Hero Section -->
    <section class="hero-professional">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="hero-content">
                        <div class="security-badge-professional">
                            <span style="font-size: 16px;">📧</span>
                            <span>Contactez-nous</span>
                        </div>
                        <h1 class="display-4 fw-bold mb-4">Parlons de Votre Projet</h1>
                        <p class="lead mb-4 opacity-90">
                            Notre équipe d'experts est là pour répondre à toutes vos questions et vous accompagner 
                            dans la mise en place de votre solution de gestion de licences.
                        </p>
                        
                        <!-- Contact Info -->
                        <div class="contact-info-professional">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-white bg-opacity-20 rounded-circle p-3 me-3 d-flex align-items-center justify-content-center" style="width: 56px; height: 56px;">
                                    <span class="text-white" style="font-size: 20px;">✉️</span>
                                </div>
                                <div>
                                    <h6 class="mb-0 text-white fw-semibold">Email Professionnel</h6>
                                    <small class="text-white-50">contact@adminlicence.com</small>
                                </div>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-white bg-opacity-20 rounded-circle p-3 me-3 d-flex align-items-center justify-content-center" style="width: 56px; height: 56px;">
                                    <span class="text-white" style="font-size: 20px;">📞</span>
                                </div>
                                <div>
                                    <h6 class="mb-0 text-white fw-semibold">Support Téléphonique</h6>
                                    <small class="text-white-50">+33 1 23 45 67 89</small>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="bg-white bg-opacity-20 rounded-circle p-3 me-3 d-flex align-items-center justify-content-center" style="width: 56px; height: 56px;">
                                    <span class="text-white" style="font-size: 20px;">🕒</span>
                                </div>
                                <div>
                                    <h6 class="mb-0 text-white fw-semibold">Horaires d'Ouverture</h6>
                                    <small class="text-white-50">Lun-Ven 9h-18h CET</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="contact-visual-professional text-center">
                        <span style="font-size: 8rem; opacity: 0.3; color: white;">🎧</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Form -->
    <section class="section-professional bg-white">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-lg">
                        <div class="card-header bg-white text-center py-4 border-bottom">
                            <h2 class="mb-2 fw-bold text-primary">Envoyez-nous un Message</h2>
                            <p class="text-muted mb-0">Nous vous répondrons dans les plus brefs délais</p>
                        </div>
                        <div class="card-body p-5">
                            <?php if(session('success')): ?>
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <div class="d-flex align-items-center">
                                        <span class="me-3 text-success" style="font-size: 18px;">✅</span>
                                        <div>
                                            <strong>Succès !</strong> <?php echo e(session('success')); ?>

                                        </div>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            <?php endif; ?>

                            <?php if(session('error')): ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <div class="d-flex align-items-center">
                                        <span class="me-3 text-danger" style="font-size: 18px;">❌</span>
                                        <div>
                                            <strong>Erreur !</strong> <?php echo e(session('error')); ?>

                                        </div>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            <?php endif; ?>

                            <form action="<?php echo e(route('frontend.contact.submit')); ?>" method="POST" id="contactForm">
                                <?php echo csrf_field(); ?>
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                   id="name" name="name" placeholder="Votre nom complet" value="<?php echo e(old('name')); ?>" required>
                                            <label for="name"><span class="me-2 text-primary">👤</span>Nom complet *</label>
                                            <?php $__errorArgs = ['name'];
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
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                   id="email" name="email" placeholder="votre@email.com" value="<?php echo e(old('email')); ?>" required>
                                            <label for="email"><span class="me-2 text-primary">📧</span>Email professionnel *</label>
                                            <?php $__errorArgs = ['email'];
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
                                    </div>
                                    <div class="col-12">
                                        <div class="form-floating">
                                            <input type="text" class="form-control <?php $__errorArgs = ['subject'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                   id="subject" name="subject" placeholder="Sujet de votre demande" value="<?php echo e(old('subject')); ?>" required>
                                            <label for="subject"><span class="me-2 text-primary">🏷️</span>Sujet de votre demande *</label>
                                            <?php $__errorArgs = ['subject'];
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
                                    </div>
                                    <div class="col-12">
                                        <div class="form-floating">
                                            <textarea class="form-control <?php $__errorArgs = ['message'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                      id="message" name="message" placeholder="Décrivez votre projet ou vos besoins" 
                                                      style="height: 150px;" required><?php echo e(old('message')); ?></textarea>
                                            <label for="message"><span class="me-2 text-primary">💬</span>Votre message *</label>
                                            <?php $__errorArgs = ['message'];
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
                                    </div>
                                </div>

                                <div class="text-center mt-4">
                                    <button type="submit" class="btn btn-primary-professional btn-lg px-5 py-3">
                                        <span class="me-2">📤</span>
                                        Envoyer le message
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Support Options -->
    <section class="section-professional bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title-professional">Autres Moyens de Contact</h2>
                <p class="lead text-muted">Choisissez le canal qui vous convient le mieux</p>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm h-100 text-center">
                        <div class="card-body p-4">
                            <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 rounded-circle mb-4" style="width: 64px; height: 64px;">
                                <span class="text-primary" style="font-size: 24px;">🎧</span>
                            </div>
                            <h5 class="fw-bold mb-3">Support Technique</h5>
                            <p class="text-muted mb-4">
                                Assistance technique spécialisée pour l'intégration et la configuration
                            </p>
                            <a href="<?php echo e(route('frontend.support')); ?>" class="btn btn-outline-primary">
                                Centre de Support
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm h-100 text-center">
                        <div class="card-body p-4">
                            <div class="d-inline-flex align-items-center justify-content-center bg-success bg-opacity-10 rounded-circle mb-4" style="width: 64px; height: 64px;">
                                <span class="text-success" style="font-size: 24px;">📅</span>
                            </div>
                            <h5 class="fw-bold mb-3">Démo Personnalisée</h5>
                            <p class="text-muted mb-4">
                                Planifiez une démonstration personnalisée avec nos experts
                            </p>
                            <a href="#" class="btn btn-outline-success">
                                Planifier une Démo
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm h-100 text-center">
                        <div class="card-body p-4">
                            <div class="d-inline-flex align-items-center justify-content-center bg-info bg-opacity-10 rounded-circle mb-4" style="width: 64px; height: 64px;">
                                <span class="text-info" style="font-size: 24px;">📚</span>
                            </div>
                            <h5 class="fw-bold mb-3">Documentation</h5>
                            <p class="text-muted mb-4">
                                Consultez notre documentation complète et nos guides d'intégration
                            </p>
                            <a href="/documentation" class="btn btn-outline-info">
                                Voir la Documentation
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Quick -->
    <section class="section-professional bg-white">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title-professional">Questions Fréquentes</h2>
                <p class="lead text-muted">Peut-être que votre question a déjà une réponse</p>
            </div>
            
            <div class="row">
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body p-4">
                            <h6 class="fw-bold text-primary mb-3">
                                <span class="me-2">❓</span>
                                Comment démarrer avec AdminLicence ?
                            </h6>
                            <p class="text-muted mb-0">
                                Inscrivez-vous pour un compte professionnel et suivez notre guide de démarrage rapide. 
                                Notre équipe peut également vous accompagner dans la mise en place.
                            </p>
                        </div>
                    </div>
                    
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body p-4">
                            <h6 class="fw-bold text-primary mb-3">
                                <span class="me-2">❓</span>
                                Quel niveau de support proposez-vous ?
                            </h6>
                            <p class="text-muted mb-0">
                                Support email pour tous les plans, support prioritaire pour les plans Pro, 
                                et support dédié 24/7 avec SLA pour les plans Enterprise.
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body p-4">
                            <h6 class="fw-bold text-primary mb-3">
                                <span class="me-2">❓</span>
                                Puis-je tester avant d'acheter ?
                            </h6>
                            <p class="text-muted mb-0">
                                Oui ! Nous proposons une période d'essai gratuite de 14 jours sans engagement 
                                avec accès complet aux fonctionnalités professionnelles.
                            </p>
                        </div>
                    </div>
                    
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body p-4">
                            <h6 class="fw-bold text-primary mb-3">
                                <span class="me-2">❓</span>
                                Comment sont sécurisées mes données ?
                            </h6>
                            <p class="text-muted mb-0">
                                Nous utilisons un chiffrement AES-256, des centres de données certifiés ISO 27001, 
                                et respectons les normes de sécurité les plus strictes du secteur.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-5">
                <a href="<?php echo e(route('frontend.faqs')); ?>" class="btn btn-primary-professional me-3">
                    <span class="me-2">❓</span>Voir toutes les FAQs
                </a>
                <a href="<?php echo e(route('frontend.pricing')); ?>" class="btn btn-secondary-professional">
                    <span class="me-2">🏷️</span>Voir les tarifs
                </a>
            </div>
        </div>
    </section>
</div>

<style>
.contact-info-professional .bg-white {
    backdrop-filter: blur(10px);
}

.form-floating > label {
    padding-left: 1rem;
    color: var(--gray-600);
}

.form-control:focus {
    border-color: var(--accent-color);
    box-shadow: 0 0 0 0.25rem rgba(49, 130, 206, 0.25);
}

.contact-visual-professional {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;
}

@media (max-width: 768px) {
    .contact-info-professional {
        margin-top: 2rem;
    }
    
    .contact-visual-professional {
        display: none;
    }
}
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make($layout ?? 'frontend.templates.professional.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\Adev\200  -  test\AdminLicence-4.5.1\resources\views\frontend\templates\professional\contact.blade.php ENDPATH**/ ?>