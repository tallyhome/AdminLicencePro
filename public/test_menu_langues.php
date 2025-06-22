<?php
/**
 * Test du nouveau menu déroulant de langues
 */

// Inclure les fonctions nécessaires
require_once __DIR__ . '/install/config.php';
require_once __DIR__ . '/install/functions/language.php';

// Initialiser la langue
$currentLang = initLanguage();

?>
<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars($currentLang); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Menu Langues - AdminLicence</title>
    <link rel="stylesheet" href="install/assets/css/install.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        .test-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 1.5rem;
            padding: 3rem;
            max-width: 800px;
            width: 100%;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        .test-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .test-header h1 {
            color: #1e293b;
            margin-bottom: 0.5rem;
        }
        .test-header p {
            color: #64748b;
        }
        .language-selector {
            position: relative;
            text-align: center;
            margin: 2rem 0;
        }
        .demo-section {
            background: #f8fafc;
            border-radius: 0.75rem;
            padding: 2rem;
            margin: 2rem 0;
        }
        .demo-section h3 {
            color: #1e293b;
            margin-bottom: 1rem;
        }
        .demo-section p {
            color: #64748b;
            line-height: 1.6;
        }
        .feature-list {
            list-style: none;
            padding: 0;
            margin: 1rem 0;
        }
        .feature-list li {
            padding: 0.5rem 0;
            color: #10b981;
            font-weight: 500;
        }
        .feature-list li::before {
            content: '✓';
            margin-right: 0.5rem;
            color: #10b981;
        }
    </style>
</head>
<body>
    <div class="test-container">
        <!-- Sélecteur de langue -->
        <div class="language-selector">
            <h2 style="margin-bottom: 1rem; color: #1e293b;">🌍 Nouveau Menu Déroulant de Langues</h2>
            <?php echo getLanguageLinks(); ?>
        </div>
        
        <div class="test-header">
            <h1><?php echo t('installation_title'); ?></h1>
            <p><?php echo t('installation_assistant'); ?></p>
        </div>
        
        <div class="demo-section">
            <h3>🎯 Fonctionnalités du Menu</h3>
            <ul class="feature-list">
                <li>Menu déroulant élégant au lieu de liens dispersés</li>
                <li>Drapeaux emoji pour chaque langue</li>
                <li>Indication visuelle de la langue active</li>
                <li>Animation fluide d'ouverture/fermeture</li>
                <li>Fermeture automatique en cliquant à l'extérieur</li>
                <li>Design responsive et moderne</li>
                <li>12 langues supportées</li>
            </ul>
        </div>
        
        <div class="demo-section">
            <h3>🌐 Langues Disponibles</h3>
            <p>Le menu supporte actuellement <strong>12 langues</strong> :</p>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-top: 1rem;">
                <?php foreach (AVAILABLE_LANGUAGES as $code => $name): ?>
                    <div style="padding: 0.5rem; background: white; border-radius: 0.5rem; text-align: center;">
                        <span style="font-size: 1.2rem;"><?php echo getLanguageFlag($code); ?></span>
                        <span style="margin-left: 0.5rem; font-weight: 500;"><?php echo $name; ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <div class="demo-section">
            <h3>📋 Instructions</h3>
            <p>
                1. Cliquez sur le bouton du menu déroulant ci-dessus<br>
                2. Sélectionnez une langue dans la liste<br>
                3. La page se rechargera dans la nouvelle langue<br>
                4. Le menu affichera la langue active avec une coche ✓
            </p>
        </div>
        
        <div style="text-align: center; margin-top: 2rem;">
            <a href="install/install_new.php" style="display: inline-block; padding: 0.75rem 1.5rem; background: #3b82f6; color: white; text-decoration: none; border-radius: 0.5rem; font-weight: 500;">
                🚀 Tester dans l'Installateur
            </a>
        </div>
    </div>
    
    <script src="install/assets/js/install.js"></script>
</body>
</html> 