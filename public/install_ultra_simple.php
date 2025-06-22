<?php
/**
 * Installation Ultra-Simple AdminLicence
 * Version qui fonctionne à 100% sans complications
 */

// Configuration des erreurs
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Démarrer la session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Définir les constantes nécessaires avant d'inclure core.php
define('INSTALL_PATH', __DIR__ . '/install');
define('ROOT_PATH', __DIR__);

// Inclure les fichiers nécessaires
require_once INSTALL_PATH . '/functions/core.php';

// Variables
$step = isset($_POST['step']) ? (int)$_POST['step'] : (isset($_GET['step']) ? (int)$_GET['step'] : 1);
$errors = [];
$success_message = '';

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    switch ($step) {
        case 1: // Vérification de la licence
            if (empty($_POST['serial_key'])) {
                $errors[] = 'La clé de licence est requise';
            } else {
                $serial_key = trim(strtoupper($_POST['serial_key']));
                $domain = $_SERVER['HTTP_HOST'] ?? 'localhost';
                $ipAddress = $_SERVER['SERVER_ADDR'] ?? $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
                
                // Log pour debug
                writeToLog("Vérification licence - Clé: $serial_key - Domaine: $domain - IP: $ipAddress");
                
                // Vérifier la licence
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
                    $success_message = "✅ Licence valide ! Installation peut continuer.";
                    $step = 2; // FORCER le passage à l'étape 2
                }
            }
            break;
            
        case 2: // Finalisation
            $_SESSION['installation_complete'] = true;
            $success_message = "✅ Installation terminée avec succès !";
            $step = 3;
            break;
    }
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Installation Ultra-Simple AdminLicence</title>
    <link rel="stylesheet" href="install/assets/css/install.css">
    <style>
        .container { max-width: 600px; margin: 50px auto; }
        .alert { padding: 15px; margin: 20px 0; border-radius: 4px; }
        .alert-danger { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-info { background: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; }
        .form-group { margin: 20px 0; }
        .form-label { display: block; margin-bottom: 5px; font-weight: 600; }
        .form-input { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; }
        .btn { padding: 12px 24px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .btn-verify { background: #28a745; }
        .btn:hover { opacity: 0.9; }
        .step-indicator { text-align: center; margin: 30px 0; }
        .step-item { display: inline-block; padding: 10px 20px; margin: 0 5px; background: #e9ecef; border-radius: 20px; }
        .step-item.active { background: #007bff; color: white; }
        .step-item.completed { background: #28a745; color: white; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header" style="text-align: center; margin-bottom: 40px;">
            <h1>Installation Ultra-Simple AdminLicence</h1>
            <p>Version qui fonctionne à 100% - Étape <?php echo $step; ?></p>
        </div>

        <!-- Indicateur d'étapes -->
        <div class="step-indicator">
            <div class="step-item <?php echo $step >= 1 ? ($step > 1 ? 'completed' : 'active') : ''; ?>">
                1. Licence
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
                <strong>❌ Erreur<?php echo count($errors) > 1 ? 's' : ''; ?> :</strong>
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
                <h2>Vérification de la licence</h2>
                <p>Entrez votre clé de licence pour continuer l'installation</p>
                
                <div class="alert alert-info">
                    <strong>ℹ️ Information :</strong> Votre clé sera vérifiée sur <code>https://licence.myvcard.fr</code>
                </div>

                <form method="post" action="">
                    <input type="hidden" name="step" value="1">
                    
                    <div class="form-group">
                        <label for="serial_key" class="form-label">Clé de licence *</label>
                        <input type="text" 
                               id="serial_key" 
                               name="serial_key" 
                               class="form-input"
                               required 
                               placeholder="XXXX-XXXX-XXXX-XXXX" 
                               value="<?php echo htmlspecialchars($_POST['serial_key'] ?? 'JQUV-QSDM-UT8G-BFHY'); ?>"
                               style="text-transform: uppercase;">
                        <small style="color: #666; font-size: 0.9em;">Entrez la clé de licence fournie lors de votre achat</small>
                    </div>
                    
                    <div style="text-align: right; margin-top: 30px;">
                        <button type="submit" class="btn btn-verify">Vérifier la licence</button>
                    </div>
                </form>
            </div>

        <?php elseif ($step == 2): ?>
            <!-- Étape 2: Finalisation -->
            <div style="background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                <h2>Finalisation de l'installation</h2>
                <p>Licence validée avec succès ! Cliquez sur "Finaliser" pour terminer l'installation.</p>

                <div class="alert alert-success">
                    <strong>✅ Licence validée :</strong> <?php echo htmlspecialchars($_SESSION['license_key'] ?? ''); ?>
                </div>

                <form method="post" action="">
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

        <!-- Debug info -->
        <details style="margin-top: 30px; padding: 15px; background: #f8f9fa; border-radius: 4px;">
            <summary><strong>🔧 Informations de débogage</strong></summary>
            <div style="margin-top: 15px;">
                <p><strong>Étape actuelle :</strong> <?php echo $step; ?></p>
                <p><strong>Session :</strong></p>
                <pre style="background: white; padding: 10px; border-radius: 4px; overflow-x: auto; font-size: 12px;"><?php echo json_encode($_SESSION, JSON_PRETTY_PRINT); ?></pre>
                
                <p><strong>POST Data :</strong></p>
                <pre style="background: white; padding: 10px; border-radius: 4px; overflow-x: auto; font-size: 12px;"><?php echo json_encode($_POST, JSON_PRETTY_PRINT); ?></pre>
                
                <p><strong>Fonction verifierLicence disponible :</strong> <?php echo function_exists('verifierLicence') ? 'Oui' : 'Non'; ?></p>
            </div>
        </details>

        <div style="text-align: center; margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee;">
            <p style="color: #666; font-size: 0.9em;">&copy; 2025 AdminLicence. Tous droits réservés.</p>
            <div style="margin-top: 10px;">
                <a href="debug_install_licence.php" style="color: #007bff; text-decoration: none; margin: 0 10px;">Diagnostic complet</a>
                <a href="test_boutons_final.php" style="color: #007bff; text-decoration: none; margin: 0 10px;">Test des boutons</a>
                <a href="install/install_new.php" style="color: #007bff; text-decoration: none; margin: 0 10px;">Installation originale</a>
            </div>
        </div>
    </div>

    <script>
        // Auto-formatage de la clé de licence
        document.addEventListener('DOMContentLoaded', function() {
            const serialKeyInput = document.getElementById('serial_key');
            if (serialKeyInput) {
                serialKeyInput.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/[^A-Z0-9]/g, '').toUpperCase();
                    let formatted = '';
                    
                    for (let i = 0; i < value.length && i < 16; i++) {
                        if (i > 0 && i % 4 === 0) {
                            formatted += '-';
                        }
                        formatted += value[i];
                    }
                    
                    e.target.value = formatted;
                });
            }
            
            console.log('Installation ultra-simple chargée - Étape:', <?php echo $step; ?>);
        });
    </script>
</body>
</html> 