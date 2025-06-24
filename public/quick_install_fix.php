<?php
/**
 * 🚀 CORRECTIF RAPIDE - Installation AdminLicence
 */

session_start();

// 1. Corriger le fichier .env
$envPath = '../.env';
if (!file_exists($envPath) || !is_writable($envPath)) {
    $defaultEnv = "APP_NAME=AdminLicence
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=" . (isset($_SERVER['HTTPS']) ? 'https' : 'http') . "://" . $_SERVER['HTTP_HOST'] . "

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync";
    
    file_put_contents($envPath, $defaultEnv);
    @chmod($envPath, 0666);
}

// 2. Corriger la gestion des sessions dans install_new.php
$installFile = 'install/install_new.php';
if (file_exists($installFile)) {
    $content = file_get_contents($installFile);
    
    // Ajouter la sauvegarde des données individuelles
    $sessionFix = "
                    // CORRECTION: Sauvegarder les données individuellement pour l'affichage
                    \$_SESSION['db_host'] = \$_POST['db_host'];
                    \$_SESSION['db_port'] = \$_POST['db_port'];
                    \$_SESSION['db_name'] = \$_POST['db_name'];
                    \$_SESSION['db_user'] = \$_POST['db_user'];
                    \$_SESSION['db_password'] = \$_POST['db_password'];
                    
                    \$_SESSION['db_config'] = [";
    
    if (strpos($content, "\$_SESSION['db_host'] = \$_POST['db_host'];") === false) {
        $content = str_replace(
            "\$_SESSION['db_config'] = [",
            $sessionFix,
            $content
        );
    }
    
    $adminSessionFix = "
                    // CORRECTION: Sauvegarder les données individuellement pour l'affichage
                    \$_SESSION['admin_name'] = \$_POST['admin_name'];
                    \$_SESSION['admin_email'] = \$_POST['admin_email'];
                    \$_SESSION['admin_password'] = \$_POST['admin_password'];
                    
                    \$_SESSION['admin_config'] = [";
    
    if (strpos($content, "\$_SESSION['admin_name'] = \$_POST['admin_name'];") === false) {
        $content = str_replace(
            "\$_SESSION['admin_config'] = [",
            $adminSessionFix,
            $content
        );
    }
    
    file_put_contents($installFile, $content);
}

// 3. Corriger l'affichage de l'étape 5 dans ui.php
$uiFile = 'install/functions/ui.php';
if (file_exists($uiFile)) {
    $content = file_get_contents($uiFile);
    
    // Ajouter la gestion de l'étape 5 si elle n'existe pas
    if (strpos($content, 'case 5:') === false) {
        $step5Code = '
            case 5: // Installation finale
                echo \'<div class="step-5-container">
                    <div class="installation-summary">
                        <h3>\' . t(\'installation_finalization\') . \'</h3>
                        <p>\' . t(\'installation_summary_description\') . \'</p>
                    </div>
                    
                    <div class="summary-sections">
                        <div class="summary-section">
                            <h4>\' . t(\'database_information\') . \'</h4>
                            <div class="summary-item">
                                <span class="summary-label">\' . t(\'host\') . \' :</span>
                                <span class="summary-value">\' . ($_SESSION[\'db_host\'] ?? \'Non défini\') . \'</span>
                            </div>
                            <div class="summary-item">
                                <span class="summary-label">\' . t(\'database\') . \' :</span>
                                <span class="summary-value">\' . ($_SESSION[\'db_name\'] ?? \'Non défini\') . \'</span>
                            </div>
                            <div class="summary-item">
                                <span class="summary-label">\' . t(\'user\') . \' :</span>
                                <span class="summary-value">\' . ($_SESSION[\'db_user\'] ?? \'Non défini\') . \'</span>
                            </div>
                        </div>
                        
                        <div class="summary-section">
                            <h4>\' . t(\'admin_information\') . \'</h4>
                            <div class="summary-item">
                                <span class="summary-label">\' . t(\'name\') . \' :</span>
                                <span class="summary-value">\' . ($_SESSION[\'admin_name\'] ?? \'Non défini\') . \'</span>
                            </div>
                            <div class="summary-item">
                                <span class="summary-label">\' . t(\'email\') . \' :</span>
                                <span class="summary-value">\' . ($_SESSION[\'admin_email\'] ?? \'Non défini\') . \'</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="installation-warning">
                        <p><strong>\' . t(\'warning\') . \' :</strong> \' . t(\'installation_warning_text\') . \'</p>
                    </div>
                    
                    <form method="post" action="install_new.php" data-step="5" class="install-form">
                        <input type="hidden" name="step" value="5">
                        
                        <div class="form-actions">
                            <a href="install_new.php?step=4" class="btn btn-secondary">\' . t(\'back\') . \'</a>
                            <button type="submit" class="btn btn-success btn-install">\' . t(\'start_installation\') . \'</button>
                        </div>
                    </form>
                </div>\';
                break;
';
        
        // Insérer avant la fermeture du switch
        $insertPos = strrpos($content, '}', strrpos($content, 'break;'));
        if ($insertPos !== false) {
            $content = substr_replace($content, $step5Code, $insertPos, 0);
            file_put_contents($uiFile, $content);
        }
    }
}

// 4. Ajouter la fonction AJAX pour l'étape 5
$jsFile = 'install/assets/js/install.js';
if (file_exists($jsFile)) {
    $content = file_get_contents($jsFile);
    
    if (strpos($content, 'verifyStep5Ajax') === false) {
        $step5Ajax = '
// Fonction AJAX pour l\'étape 5 (Installation finale)
function verifyStep5Ajax(form) {
    const submitBtn = form.querySelector(\'button[type="submit"]\');
    const originalText = submitBtn.innerHTML;
    
    // Afficher le spinner
    submitBtn.innerHTML = \'<span class="spinner"></span> Installation en cours...\';
    submitBtn.disabled = true;
    
    const formData = new FormData(form);
    formData.append(\'ajax\', \'1\');
    
    fetch(\'install_new.php\', {
        method: \'POST\',
        body: formData
    })
    .then(response => {
        // Vérifier si la réponse est du JSON ou du HTML
        const contentType = response.headers.get(\'content-type\');
        if (contentType && contentType.includes(\'application/json\')) {
            return response.json();
        } else {
            // Si ce n\'est pas du JSON, c\'est probablement une erreur HTML
            return response.text().then(text => {
                throw new Error(\'HTML détecté mais pas de succès confirmé: \' + text.substring(0, 100));
            });
        }
    })
    .then(data => {
        if (data.success) {
            showMessage(\'success\', data.message || \'Installation terminée avec succès !\');
            setTimeout(() => {
                if (data.redirect) {
                    window.location.href = data.redirect;
                }
            }, 2000);
        } else {
            showMessage(\'error\', data.errors ? data.errors.join(\'<br>\') : \'Erreur lors de l\\\'installation\');
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    })
    .catch(error => {
        console.error(\'Erreur AJAX:\', error);
        showMessage(\'error\', \'Erreur lors de l\\\'installation finale: \' + error.message);
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
}
';
        
        $content .= $step5Ajax;
        file_put_contents($jsFile, $content);
    }
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>✅ Correctif appliqué</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; text-align: center; }
        .success { background: #d4edda; border: 1px solid #28a745; padding: 20px; border-radius: 5px; margin: 20px 0; }
        .btn { padding: 15px 30px; background: #007bff; color: white; border: none; border-radius: 5px; text-decoration: none; display: inline-block; margin: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>✅ Correctif appliqué avec succès</h1>
        
        <div class="success">
            <h3>🔧 Corrections effectuées :</h3>
            <ul style="text-align: left;">
                <li>✅ Fichier .env créé/corrigé avec permissions</li>
                <li>✅ Gestion des sessions améliorée</li>
                <li>✅ Affichage de l'étape 5 corrigé</li>
                <li>✅ Fonction AJAX étape 5 ajoutée</li>
            </ul>
        </div>
        
        <p><strong>Vous pouvez maintenant retourner à l'installateur :</strong></p>
        
        <a href="install/install_new.php" class="btn">🚀 Continuer l'installation</a>
        
        <p style="margin-top: 30px; font-size: 12px; color: #666;">
            Ce fichier de correction peut être supprimé après utilisation.
        </p>
    </div>
</body>
</html> 