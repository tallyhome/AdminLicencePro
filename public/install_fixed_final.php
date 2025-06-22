<?php
/**
 * Installateur AdminLicence - Version corrigée finale
 * Date: 2025-01-21
 * Problèmes corrigés: Sélecteur de langue + Validation de licence
 */

// Démarrer la session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Inclure les fonctions nécessaires
if (!defined('INSTALL_PATH')) {
    define('INSTALL_PATH', __DIR__ . '/install');
}
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', __DIR__);
}

require_once INSTALL_PATH . '/config.php';
require_once INSTALL_PATH . '/functions/language.php';
require_once INSTALL_PATH . '/functions/core.php';

// === GESTION DU CHANGEMENT DE LANGUE ===
if (isset($_GET['language']) && in_array($_GET['language'], ['fr', 'en'])) {
    $_SESSION['installer_language'] = $_GET['language'];
    
    // Conserver l'étape actuelle
    $currentStep = isset($_GET['step']) ? (int)$_GET['step'] : 1;
    $redirectUrl = $_SERVER['PHP_SELF'];
    if ($currentStep > 1) {
        $redirectUrl .= '?step=' . $currentStep;
    }
    
    header("Location: $redirectUrl");
    exit;
}

// Initialiser la langue
$currentLang = initLanguage();

// Obtenir l'étape actuelle
$step = isset($_POST['step']) ? (int)$_POST['step'] : (isset($_GET['step']) ? (int)$_GET['step'] : 1);
$errors = [];
$success_message = '';

// === TRAITEMENT DU FORMULAIRE ===
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    switch ($step) {
        case 1: // Vérification de la licence
            if (empty($_POST['serial_key'])) {
                $errors[] = t('license_key_required');
            } else {
                $serial_key = trim(strtoupper($_POST['serial_key']));
                $domain = $_SERVER['HTTP_HOST'] ?? 'localhost';
                $ipAddress = $_SERVER['SERVER_ADDR'] ?? $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
                
                writeToLog("Vérification licence - Clé: $serial_key - Domaine: $domain - IP: $ipAddress");
                
                // VÉRIFICATION RÉELLE DE LA LICENCE
                $licenseCheck = verifierLicence($serial_key, $domain, $ipAddress);
                
                if (!$licenseCheck['valide']) {
                    $errors[] = $licenseCheck['message'];
                    writeToLog("Licence invalide: " . $licenseCheck['message'], 'ERROR');
                } else {
                    // Licence valide - stocker et passer à l'étape 2
                    $_SESSION['license_data'] = $licenseCheck['donnees'];
                    $_SESSION['license_key'] = $serial_key;
                    $_SESSION['license_valid'] = true;
                    
                    writeToLog("Licence valide - Passage à l'étape 2", 'SUCCESS');
                    $success_message = t('license_valid') . " ! " . t('license_valid_next_step');
                    $step = 2; // FORCER le passage à l'étape 2
                }
            }
            break;
            
        case 2: // Finalisation (simplifié)
            $_SESSION['installation_complete'] = true;
            $success_message = "✅ Installation terminée avec succès !";
            $step = 3;
            break;
    }
}

?>
<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars($currentLang); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Installation AdminLicence - Étape <?php echo $step; ?></title>
    <link rel="stylesheet" href="install/assets/css/install.css">
    <style>
        .container { max-width: 800px; margin: 50px auto; padding: 20px; }
        .language-selector { text-align: right; margin-bottom: 20px; }
        .language-selector a { 
            display: inline-block; 
            padding: 5px 10px; 
            margin: 0 5px; 
            text-decoration: none; 
            border-radius: 4px; 
            background: #f8f9fa; 
            color: #333;
        }
        .language-selector a.active { background: #007bff; color: white; }
        .language-selector a:hover { background: #0056b3; color: white; }
        .alert { padding: 15px; margin: 20px 0; border-radius: 4px; }
        .alert-danger { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-info { background: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; }
        .form-group { margin: 20px 0; }
        .form-label { display: block; margin-bottom: 5px; font-weight: 600; }
        .form-input { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 16px; }
        .btn { padding: 12px 24px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; }
        .btn-verify { background: #28a745; }
        .btn:hover { opacity: 0.9; }
        .step-indicator { text-align: center; margin: 30px 0; }
        .step-item { display: inline-block; padding: 10px 20px; margin: 0 5px; background: #e9ecef; border-radius: 20px; }
        .step-item.active { background: #007bff; color: white; }
        .step-item.completed { background: #28a745; color: white; }
        .debug-info { margin-top: 30px; padding: 15px; background: #f8f9fa; border-radius: 4px; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <!-- Sélecteur de langue -->
        <div class="language-selector">
            <strong>Langue :</strong>
            <?php
            $currentLang = getCurrentLanguage();
            $currentStep = $step;
            
            foreach (AVAILABLE_LANGUAGES as $code => $name) {
                $active = $code === $currentLang ? ' class="active"' : '';
                $url = $_SERVER['PHP_SELF'] . '?language=' . $code;
                if ($currentStep > 1) {
                    $url .= '&step=' . $currentStep;
                }
                echo sprintf('<a href="%s"%s>%s</a>', $url, $active, $name);
            }
            ?>
        </div>

        <div class="header" style="text-align: center; margin-bottom: 40px;">
            <h1><?php echo t('installation'); ?> AdminLicence</h1>
            <p>Assistant d'installation pour AdminLicence</p>
        </div>

        <!-- Indicateur d'étapes -->
        <div class="step-indicator">
            <div class="step-item <?php echo $step >= 1 ? ($step > 1 ? 'completed' : 'active') : ''; ?>">
                1. <?php echo t('license_verification'); ?>
            </div>
            <div class="step-item <?php echo $step >= 2 ? ($step > 2 ? 'completed' : 'active') : ''; ?>">
                2. Finalisation
            </div>
            <div class="step-item <?php echo $step >= 3 ? 'active' : ''; ?>">
                3. Terminé
            </div>
        </div>

        <!-- Affichage des erreurs -->
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <strong>❌ Erreur :</strong>
                <ul style="margin: 10px 0 0 20px;">
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Affichage des messages de succès -->
        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success">
                <?php echo $success_message; ?>
            </div>
        <?php endif; ?>

        <?php if ($step == 1): ?>
            <!-- Étape 1: Vérification de la licence -->
            <div style="background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                <h2><?php echo t('license_verification'); ?></h2>
                <p><?php echo t('license_verification_description'); ?></p>
                
                <div class="alert alert-info">
                    <strong>ℹ️ Information :</strong> Votre clé sera vérifiée sur <code>https://licence.myvcard.fr</code>
                </div>

                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <input type="hidden" name="step" value="1">
                    
                    <div class="form-group">
                        <label for="serial_key" class="form-label"><?php echo t('license_key'); ?> *</label>
                        <input type="text" 
                               id="serial_key" 
                               name="serial_key" 
                               class="form-input"
                               required 
                               placeholder="<?php echo t('license_key_placeholder'); ?>" 
                               value="<?php echo htmlspecialchars($_POST['serial_key'] ?? ''); ?>"
                               style="text-transform: uppercase;">
                        <small style="color: #666; font-size: 0.9em;">Format requis : XXXX-XXXX-XXXX-XXXX</small>
                    </div>
                    
                    <div style="text-align: right; margin-top: 30px;">
                        <button type="submit" class="btn btn-verify"><?php echo t('verify_license'); ?></button>
                    </div>
                </form>
            </div>

        <?php elseif ($step == 2): ?>
            <!-- Étape 2: Finalisation -->
            <div style="background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                <h2>Finalisation</h2>
                <p>Licence validée avec succès ! Cliquez sur "Finaliser" pour terminer l'installation.</p>

                <div class="alert alert-success">
                    <strong>✅ Licence validée :</strong> <?php echo htmlspecialchars($_SESSION['license_key'] ?? ''); ?>
                </div>

                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <input type="hidden" name="step" value="2">
                    
                    <div style="text-align: center; margin-top: 30px;">
                        <button type="submit" class="btn">Finaliser l'installation</button>
                    </div>
                </form>
            </div>

        <?php elseif ($step == 3): ?>
            <!-- Étape 3: Installation terminée -->
            <div style="background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); text-align: center;">
                <div style="font-size: 60px; color: #28a745; margin-bottom: 20px;">🎉</div>
                <h2 style="color: #28a745;">Installation terminée !</h2>
                <p>AdminLicence a été installé avec succès.</p>

                <div class="alert alert-success">
                    <strong>Licence :</strong> <?php echo htmlspecialchars($_SESSION['license_key'] ?? 'Non configurée'); ?><br>
                    <strong>Installation :</strong> Terminée avec succès
                </div>

                <div style="margin-top: 30px;">
                    <a href="../" class="btn" style="text-decoration: none; display: inline-block;">Accéder à l'application</a>
                </div>
            </div>

        <?php endif; ?>

        <!-- Informations de débogage -->
        <details class="debug-info">
            <summary><strong>🔧 Informations de débogage</strong></summary>
            <div style="margin-top: 15px;">
                <p><strong>Étape actuelle :</strong> <?php echo $step; ?></p>
                <p><strong>Langue actuelle :</strong> <?php echo $currentLang; ?> (<?php echo getCurrentLanguageName(); ?>)</p>
                <p><strong>Session :</strong></p>
                <pre><?php echo json_encode($_SESSION, JSON_PRETTY_PRINT); ?></pre>
                
                <p><strong>POST Data :</strong></p>
                <pre><?php echo json_encode($_POST, JSON_PRETTY_PRINT); ?></pre>
                
                <p><strong>Fonction verifierLicence disponible :</strong> <?php echo function_exists('verifierLicence') ? 'Oui' : 'Non'; ?></p>
            </div>
        </details>

        <div style="text-align: center; margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee;">
            <p style="color: #666; font-size: 0.9em;">&copy; 2025 AdminLicence. Tous droits réservés.</p>
            <div style="margin-top: 10px;">
                <a href="test_debug_install.php" style="color: #007bff; text-decoration: none; margin: 0 10px;">Diagnostic complet</a>
                <a href="install_ultra_simple.php" style="color: #007bff; text-decoration: none; margin: 0 10px;">Version ultra-simple</a>
                <a href="install/install_new.php" style="color: #007bff; text-decoration: none; margin: 0 10px;">Installation originale</a>
            </div>
        </div>
    </div>
</body>
</html> 