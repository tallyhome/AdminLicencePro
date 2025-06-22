<?php
/**
 * Template d'en-tête pour l'installation
 * 
 * @var int $step Étape actuelle de l'installation
 * @var string $currentLang Langue actuelle
 */

// Générer le token CSRF
$csrfToken = bin2hex(random_bytes(32));
$_SESSION['csrf_token'] = $csrfToken;
?>
<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars($currentLang); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo htmlspecialchars($csrfToken); ?>">
    <title><?php echo t('installation_title'); ?> - Étape <?php echo $step; ?></title>
    <link rel="stylesheet" href="assets/css/install.css">
    <link rel="icon" type="image/x-icon" href="assets/images/favicon.ico">
</head>
<body>
    <div class="loading-overlay">
        <div class="loading-spinner"></div>
    </div>
    
    <div class="container">
        <div class="language-selector">
            <?php echo getLanguageLinks(); ?>
        </div>
        
        <div class="header">
            <h1><?php echo t('installation_title'); ?></h1>
            <div class="step-indicator">
                <div class="step <?php echo ($step >= 1) ? 'active' : ''; ?> <?php echo ($step > 1) ? 'completed' : ''; ?>">
                    <span>1</span>
                    <div class="step-label"><?php echo t('license_verification'); ?></div>
                </div>
                <div class="step <?php echo ($step >= 2) ? 'active' : ''; ?> <?php echo ($step > 2) ? 'completed' : ''; ?>">
                    <span>2</span>
                    <div class="step-label"><?php echo t('system_requirements'); ?></div>
                </div>
                <div class="step <?php echo ($step >= 3) ? 'active' : ''; ?> <?php echo ($step > 3) ? 'completed' : ''; ?>">
                    <span>3</span>
                    <div class="step-label"><?php echo t('database_configuration'); ?></div>
                </div>
                <div class="step <?php echo ($step >= 4) ? 'active' : ''; ?> <?php echo ($step > 4) ? 'completed' : ''; ?>">
                    <span>4</span>
                    <div class="step-label"><?php echo t('admin_setup'); ?></div>
                </div>
                <div class="step <?php echo ($step >= 5) ? 'active' : ''; ?> <?php echo ($step > 5) ? 'completed' : ''; ?>">
                    <span>5</span>
                    <div class="step-label"><?php echo t('finalization'); ?></div>
                </div>
            </div>
        </div>
        
        <div class="content">
            <!-- Le contenu sera inséré ici -->