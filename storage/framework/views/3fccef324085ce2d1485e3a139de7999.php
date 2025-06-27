<?php $__env->startSection('title', 'Guide d\'intégration API - AdminLicence'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-light min-vh-100">
    <!-- Hero Section -->
    <div class="hero-section text-center">
        <div class="container">
            <h1 class="display-4 fw-bold mb-4">Guide d'intégration API</h1>
            <p class="lead mb-4">Instructions détaillées pour intégrer l'API AdminLicence dans vos applications</p>
            <a href="<?php echo e(route('documentation.index')); ?>" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-2"></i>Retour à la documentation
            </a>
        </div>
    </div>

    <!-- Contenu -->
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-sm">
                    <div class="card-body p-5">
                        <div class="api-docs">
                            <?php echo $content; ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.api-docs {
    line-height: 1.7;
}

.api-docs h1, .api-docs h2, .api-docs h3, .api-docs h4, .api-docs h5, .api-docs h6 {
    color: #2563eb;
    font-weight: 600;
    margin-top: 2rem;
    margin-bottom: 1rem;
}

.api-docs h1 { font-size: 1.75rem; }
.api-docs h2 { font-size: 1.5rem; }
.api-docs h3 { font-size: 1.25rem; }
.api-docs h4 { font-size: 1.1rem; }

.api-docs p {
    margin-bottom: 1rem;
}

.api-docs ul, .api-docs ol {
    margin-bottom: 1rem;
    padding-left: 1.5rem;
}

.api-docs li {
    margin-bottom: 0.5rem;
}

.api-docs pre {
    background-color: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 0.375rem;
    padding: 1rem;
    margin: 1rem 0;
    overflow-x: auto;
}

.api-docs code {
    background-color: #f8f9fa;
    color: #d63384;
    padding: 0.2rem 0.4rem;
    border-radius: 0.25rem;
    font-size: 0.875em;
}

.api-docs pre code {
    background-color: transparent;
    color: #333;
    padding: 0;
}

.api-docs table {
    width: 100%;
    margin: 1rem 0;
    border-collapse: collapse;
}

.api-docs table, .api-docs th, .api-docs td {
    border: 1px solid #dee2e6;
}

.api-docs th, .api-docs td {
    padding: 0.75rem;
    text-align: left;
}

.api-docs th {
    background-color: #f8f9fa;
    font-weight: 600;
}

.api-docs blockquote {
    border-left: 4px solid #2563eb;
    padding-left: 1rem;
    margin: 1.5rem 0;
    font-style: italic;
    color: #6b7280;
    background: #f8fafc;
    padding: 1rem;
    border-radius: 0 4px 4px 0;
}

.api-docs a {
    color: #2563eb;
    text-decoration: none;
}

.api-docs a:hover {
    text-decoration: underline;
}
</style>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.templates.modern.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\Adev\200  -  test\AdminLicence-4.5.1\resources\views/documentation/api.blade.php ENDPATH**/ ?>