<?php
/**
 * Template d'en-tête pour l'installation
 * Version: 2.0.0 - Design moderne
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
    <title><?php echo t('installation_title'); ?> - <?php echo t('step'); ?> <?php echo $step; ?></title>
    
    <!-- Preload fonts for better performance -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <link rel="stylesheet" href="assets/css/install.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../images/logo.png">
    
    <!-- Meta tags for better SEO and social sharing -->
    <meta name="description" content="<?php echo t('installation_description'); ?>">
    <meta name="robots" content="noindex, nofollow">
    <meta name="theme-color" content="#3b82f6">
    
    <!-- Prevent zoom on mobile -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
</head>
<body>
    <!-- Loading overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner"></div>
    </div>
    
    <div class="container">
        <!-- Language selector -->
        <div class="language-selector">
            <div class="language-dropdown" id="languageDropdown">
                <button class="language-toggle" id="languageToggle" aria-label="<?php echo t('select_language'); ?>">
                    <span><?php echo getCurrentLanguageName(); ?></span>
                </button>
                <div class="language-menu" id="languageMenu">
                    <?php echo getLanguageLinks(); ?>
                </div>
            </div>
        </div>
        
        <!-- Header section -->
        <div class="header">
            <h1><?php echo t('installation_title'); ?></h1>
            <p><?php echo t('installation_subtitle'); ?></p>
            
            <!-- Step indicators -->
            <div class="step-indicator">
                <div class="step <?php echo ($step >= 1) ? 'active' : ''; ?> <?php echo ($step > 1) ? 'completed' : ''; ?>">
                    <span>1</span>
                    <div class="step-label"><?php echo t('license_verification'); ?></div>
                </div>
                <div class="step <?php echo ($step >= 2) ? 'active' : ''; ?> <?php echo ($step > 2) ? 'completed' : ''; ?>">
                    <span>2</span>
                    <div class="step-label"><?php echo t('database_configuration'); ?></div>
                </div>
                <div class="step <?php echo ($step >= 3) ? 'active' : ''; ?> <?php echo ($step > 3) ? 'completed' : ''; ?>">
                    <span>3</span>
                    <div class="step-label"><?php echo t('admin_setup'); ?></div>
                </div>
                <div class="step <?php echo ($step >= 4) ? 'active' : ''; ?> <?php echo ($step > 4) ? 'completed' : ''; ?>">
                    <span>4</span>
                    <div class="step-label"><?php echo t('finalization'); ?></div>
                </div>
            </div>
        </div>
        
        <!-- Content area -->
        <div class="install-content">
            <div class="step-title"><?php echo getStepTitle($step); ?></div>
            <div class="step-description"><?php echo getStepDescription($step); ?></div>
            
            <!-- Le contenu sera inséré ici par les autres templates -->