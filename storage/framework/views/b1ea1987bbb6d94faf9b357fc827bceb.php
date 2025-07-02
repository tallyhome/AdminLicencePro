<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AdminLicence - Configuration requise</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body text-center p-5">
                        <div class="mb-4">
                            <i class="fas fa-paint-brush fa-4x text-warning"></i>
                        </div>
                        
                        <h1 class="h3 mb-3">Configuration CMS requise</h1>
                        
                        <p class="text-muted mb-4">
                            Aucun template n'est configuré pour le moment. 
                            Veuillez vous connecter à l'administration pour configurer votre site.
                        </p>
                        
                        <div class="d-grid gap-2">
                            <a href="<?php echo e(url('/admin')); ?>" class="btn btn-primary">
                                <i class="fas fa-cog"></i>
                                Accéder à l'administration
                            </a>
                            
                            <a href="<?php echo e(url('/admin/cms')); ?>" class="btn btn-outline-primary">
                                <i class="fas fa-palette"></i>
                                Configurer le CMS
                            </a>
                        </div>
                        
                        <hr class="my-4">
                        
                        <small class="text-muted">
                            <i class="fas fa-shield-alt"></i>
                            Powered by AdminLicence
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html> <?php /**PATH R:\Adev\200  -  test\AdminLicence-4.5.1\resources\views\frontend\no-template.blade.php ENDPATH**/ ?>