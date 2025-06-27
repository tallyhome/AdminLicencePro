

<?php $__env->startSection('title', 'Templates CMS'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Templates CMS</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('admin.cms.index')); ?>">CMS</a></li>
                        <li class="breadcrumb-item active">Templates</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Templates Disponibles</h5>
                    <p class="text-muted mb-0">Choisissez le template qui correspond le mieux à votre image de marque</p>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Template Modern (par défaut) -->
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card template-card border-primary">
                                <div class="template-preview">
                                    <div class="preview-header bg-primary">
                                        <div class="preview-nav">
                                            <div class="nav-brand text-white fw-bold">
                                                <i class="fas fa-shield-alt"></i> AdminLicence
                                            </div>
                                            <div class="nav-links">
                                                <span class="nav-dot"></span>
                                                <span class="nav-dot"></span>
                                                <span class="nav-dot"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="preview-hero bg-primary text-white p-3">
                                        <h6 class="mb-1">Sécurisez vos licences</h6>
                                        <small class="opacity-75">Solution professionnelle</small>
                                        <div class="mt-2">
                                            <span class="btn btn-light btn-sm">Commencer</span>
                                        </div>
                                    </div>
                                    <div class="preview-content p-3">
                                        <div class="row g-2">
                                            <div class="col-4">
                                                <div class="card-mini">
                                                    <i class="fas fa-shield text-primary"></i>
                                                    <small>Sécurité</small>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="card-mini">
                                                    <i class="fas fa-cog text-primary"></i>
                                                    <small>Gestion</small>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="card-mini">
                                                    <i class="fas fa-chart text-primary"></i>
                                                    <small>Analytics</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="preview-footer bg-dark text-white p-2">
                                        <small>© AdminLicence</small>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="mb-0 fw-bold">Modern</h6>
                                        <span class="badge bg-primary">Actuel</span>
                                    </div>
                                    <p class="text-muted small mb-3">Design moderne et épuré avec des couleurs vives et une navigation intuitive</p>
                                    <div class="d-flex justify-content-between">
                                        <a href="<?php echo e(route('frontend.home')); ?>?preview=1" target="_blank" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-eye"></i> Prévisualiser
                                        </a>
                                        <button class="btn btn-primary btn-sm" disabled>
                                            <i class="fas fa-check"></i> Activé
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Template Professional -->
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card template-card">
                                <div class="template-preview">
                                    <div class="preview-header bg-dark">
                                        <div class="preview-nav">
                                            <div class="nav-brand text-white fw-bold">
                                                <i class="fas fa-briefcase"></i> AdminLicence
                                            </div>
                                            <div class="nav-links">
                                                <span class="nav-dot bg-secondary"></span>
                                                <span class="nav-dot bg-secondary"></span>
                                                <span class="nav-dot bg-secondary"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="preview-hero bg-gradient-dark text-white p-3">
                                        <h6 class="mb-1">Solutions Enterprise</h6>
                                        <small class="opacity-75">Sécurité professionnelle</small>
                                        <div class="mt-2">
                                            <span class="btn btn-outline-light btn-sm">Découvrir</span>
                                        </div>
                                    </div>
                                    <div class="preview-content p-3 bg-light">
                                        <div class="row g-2">
                                            <div class="col-12">
                                                <div class="card-mini-horizontal">
                                                    <i class="fas fa-lock text-dark"></i>
                                                    <div class="ms-2">
                                                        <small class="fw-bold">Cryptage Avancé</small>
                                                        <small class="d-block text-muted">AES-256</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="card-mini-horizontal">
                                                    <i class="fas fa-users text-dark"></i>
                                                    <div class="ms-2">
                                                        <small class="fw-bold">Multi-utilisateurs</small>
                                                        <small class="d-block text-muted">Gestion d'équipe</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="preview-footer bg-secondary text-white p-2">
                                        <small>Enterprise Solution</small>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="mb-0 fw-bold">Professional</h6>
                                        <span class="badge bg-success">Nouveau</span>
                                    </div>
                                    <p class="text-muted small mb-3">Design professionnel et sobre, idéal pour les entreprises avec une approche corporate</p>
                                    <div class="d-flex justify-content-between">
                                        <a href="#" class="btn btn-outline-primary btn-sm" onclick="previewTemplate('professional')">
                                            <i class="fas fa-eye"></i> Prévisualiser
                                        </a>
                                        <button class="btn btn-outline-secondary btn-sm" onclick="activateTemplate('professional')">
                                            <i class="fas fa-rocket"></i> Activer
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Template Creative (à venir) -->
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card template-card border-secondary">
                                <div class="template-preview opacity-50">
                                    <div class="preview-header bg-gradient-creative">
                                        <div class="preview-nav">
                                            <div class="nav-brand text-white fw-bold">
                                                <i class="fas fa-palette"></i> AdminLicence
                                            </div>
                                            <div class="nav-links">
                                                <span class="nav-dot bg-warning"></span>
                                                <span class="nav-dot bg-info"></span>
                                                <span class="nav-dot bg-success"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="preview-hero bg-gradient-creative text-white p-3">
                                        <h6 class="mb-1">Créativité & Innovation</h6>
                                        <small class="opacity-75">Design unique</small>
                                        <div class="mt-2">
                                            <span class="btn btn-warning btn-sm">Explorer</span>
                                        </div>
                                    </div>
                                    <div class="preview-content p-3">
                                        <div class="row g-2">
                                            <div class="col-6">
                                                <div class="card-mini bg-warning">
                                                    <i class="fas fa-star text-white"></i>
                                                    <small class="text-white">Premium</small>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="card-mini bg-info">
                                                    <i class="fas fa-magic text-white"></i>
                                                    <small class="text-white">Créatif</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="preview-footer bg-gradient text-white p-2">
                                        <small>Creative Design</small>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="mb-0 fw-bold">Creative</h6>
                                        <span class="badge bg-warning">Bientôt</span>
                                    </div>
                                    <p class="text-muted small mb-3">Design créatif et coloré avec des animations et effets visuels innovants</p>
                                    <div class="text-center">
                                        <button class="btn btn-secondary btn-sm" disabled>
                                            <i class="fas fa-clock"></i> Bientôt disponible
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.template-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    height: 100%;
}

.template-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
}

.template-preview {
    height: 300px;
    background: #f8f9fa;
    border-radius: 8px 8px 0 0;
    overflow: hidden;
    position: relative;
}

.preview-header {
    height: 40px;
    padding: 8px 12px;
}

.preview-nav {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.nav-brand {
    font-size: 12px;
}

.nav-links {
    display: flex;
    gap: 4px;
}

.nav-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: rgba(255,255,255,0.5);
    display: block;
}

.preview-hero {
    height: 120px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.preview-content {
    height: 100px;
    background: white;
}

.preview-footer {
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
}

.card-mini {
    background: #f8f9fa;
    border-radius: 4px;
    padding: 8px;
    text-align: center;
    font-size: 10px;
}

.card-mini-horizontal {
    display: flex;
    align-items: center;
    background: white;
    border-radius: 4px;
    padding: 6px;
    border: 1px solid #eee;
    margin-bottom: 4px;
}

.bg-gradient-dark {
    background: linear-gradient(135deg, #343a40, #495057);
}

.bg-gradient-creative {
    background: linear-gradient(135deg, #6f42c1, #e83e8c, #fd7e14);
}

.bg-gradient {
    background: linear-gradient(135deg, #6c757d, #adb5bd);
}
</style>

<script>
function previewTemplate(templateName) {
    // Ouvrir la prévisualisation dans un nouvel onglet
    window.open(`<?php echo e(route('frontend.home')); ?>?template=${templateName}&preview=1`, '_blank');
}

function activateTemplate(templateName) {
    if (confirm('Êtes-vous sûr de vouloir activer ce template ?')) {
        // Faire une requête pour activer le template
        fetch(`<?php echo e(route('admin.cms.templates.switch')); ?>`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
            },
            body: JSON.stringify({
                template_name: templateName
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Erreur lors de l\'activation du template');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erreur lors de l\'activation du template');
        });
    }
}
</script>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\Adev\200  -  test\AdminLicence-4.5.1\resources\views\admin\cms\templates\index.blade.php ENDPATH**/ ?>