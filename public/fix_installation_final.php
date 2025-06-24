<?php
/**
 * 🔧 CORRECTIF FINAL - Installation AdminLicence
 * Corrige tous les problèmes identifiés
 */
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>🔧 Correctif Installation AdminLicence</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .success { background: #d4edda; border: 1px solid #28a745; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .error { background: #f8d7da; border: 1px solid #dc3545; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .warning { background: #fff3cd; border: 1px solid #ffc107; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .info { background: #d1ecf1; border: 1px solid #17a2b8; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .btn { padding: 12px 25px; margin: 10px 5px; border: none; border-radius: 5px; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn-primary { background: #007bff; color: white; }
        .btn-success { background: #28a745; color: white; }
        .btn-danger { background: #dc3545; color: white; }
        .btn-warning { background: #ffc107; color: black; }
        .step { background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0; }
        code { background: #f8f9fa; padding: 2px 4px; border-radius: 3px; font-family: monospace; }
        .progress { width: 100%; background: #f0f0f0; border-radius: 5px; margin: 20px 0; }
        .progress-bar { height: 25px; background: #007bff; border-radius: 5px; text-align: center; color: white; line-height: 25px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 Correctif Installation AdminLicence</h1>
        <p>Correction automatique de tous les problèmes identifiés</p>

        <?php
        $fixes = [];
        $errors = [];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            // ==========================================
            // 1. CORRECTION PERMISSIONS .env
            // ==========================================
            if (isset($_POST['fix_env'])) {
                $envPath = '../.env';
                
                // Créer .env si n'existe pas
                if (!file_exists($envPath)) {
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
                    
                    if (file_put_contents($envPath, $defaultEnv)) {
                        $fixes[] = "✅ Fichier .env créé";
                    } else {
                        $errors[] = "❌ Impossible de créer .env";
                    }
                }
                
                // Corriger permissions
                if (file_exists($envPath)) {
                    $permissions = [0666, 0664, 0644];
                    foreach ($permissions as $perm) {
                        if (@chmod($envPath, $perm)) {
                            $fixes[] = "✅ Permissions .env définies à " . decoct($perm);
                            break;
                        }
                    }
                    
                    // Test écriture
                    if (is_writable($envPath)) {
                        $fixes[] = "✅ Fichier .env accessible en écriture";
                    } else {
                        $errors[] = "❌ Fichier .env toujours non accessible en écriture";
                    }
                }
            }
            
            // ==========================================
            // 2. CORRECTION AFFICHAGE ÉTAPE 5
            // ==========================================
            if (isset($_POST['fix_step5'])) {
                $uiFile = 'install/functions/ui.php';
                
                if (file_exists($uiFile)) {
                    $content = file_get_contents($uiFile);
                    
                    // Chercher la section case 5 ou l'ajouter
                    if (strpos($content, 'case 5:') === false) {
                        // Ajouter la gestion de l'étape 5
                        $step5Code = "
            case 5: // Installation finale
                echo '<div class=\"step-5-container\">
                    <div class=\"installation-summary\">
                        <h3>' . t('installation_finalization') . '</h3>
                        <p>' . t('installation_summary_description') . '</p>
                    </div>
                    
                    <div class=\"summary-sections\">
                        <div class=\"summary-section\">
                            <h4>' . t('database_information') . '</h4>
                            <div class=\"summary-item\">
                                <span class=\"summary-label\">' . t('host') . ' :</span>
                                <span class=\"summary-value\">' . (\$_SESSION['db_host'] ?? 'Non défini') . '</span>
                            </div>
                            <div class=\"summary-item\">
                                <span class=\"summary-label\">' . t('database') . ' :</span>
                                <span class=\"summary-value\">' . (\$_SESSION['db_name'] ?? 'Non défini') . '</span>
                            </div>
                            <div class=\"summary-item\">
                                <span class=\"summary-label\">' . t('user') . ' :</span>
                                <span class=\"summary-value\">' . (\$_SESSION['db_user'] ?? 'Non défini') . '</span>
                            </div>
                        </div>
                        
                        <div class=\"summary-section\">
                            <h4>' . t('admin_information') . '</h4>
                            <div class=\"summary-item\">
                                <span class=\"summary-label\">' . t('name') . ' :</span>
                                <span class=\"summary-value\">' . (\$_SESSION['admin_name'] ?? 'Non défini') . '</span>
                            </div>
                            <div class=\"summary-item\">
                                <span class=\"summary-label\">' . t('email') . ' :</span>
                                <span class=\"summary-value\">' . (\$_SESSION['admin_email'] ?? 'Non défini') . '</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class=\"installation-warning\">
                        <p><strong>' . t('warning') . ' :</strong> ' . t('installation_warning_text') . '</p>
                    </div>
                    
                    <form method=\"post\" action=\"install_new.php\" data-step=\"5\" class=\"install-form\">
                        <input type=\"hidden\" name=\"step\" value=\"5\">
                        
                        <div class=\"form-actions\">
                            <a href=\"install_new.php?step=4\" class=\"btn btn-secondary\">' . t('back') . '</a>
                            <button type=\"submit\" class=\"btn btn-success btn-install\">' . t('start_installation') . '</button>
                        </div>
                    </form>
                </div>';
                break;";
                        
                        // Insérer avant le dernier break; de la fonction
                        $insertPos = strrpos($content, 'break;');
                        if ($insertPos !== false) {
                            $content = substr_replace($content, $step5Code, $insertPos + 6, 0);
                            
                            if (file_put_contents($uiFile, $content)) {
                                $fixes[] = "✅ Gestion de l'étape 5 ajoutée dans ui.php";
                            } else {
                                $errors[] = "❌ Impossible de modifier ui.php";
                            }
                        }
                    } else {
                        $fixes[] = "✅ Gestion de l'étape 5 déjà présente";
                    }
                } else {
                    $errors[] = "❌ Fichier ui.php non trouvé";
                }
            }
            
            // ==========================================
            // 3. CORRECTION GESTION SESSION ÉTAPE 5
            // ==========================================
            if (isset($_POST['fix_session'])) {
                $installFile = 'install/install_new.php';
                
                if (file_exists($installFile)) {
                    $content = file_get_contents($installFile);
                    
                    // Chercher et corriger la sauvegarde des données de session
                    $patterns = [
                        // Étape 3 - Sauvegarder les données DB
                        '/\$_SESSION\[\'db_config\'\]/' => '$_SESSION[\'db_host\'] = $_POST[\'db_host\'];
                        $_SESSION[\'db_port\'] = $_POST[\'db_port\'];
                        $_SESSION[\'db_name\'] = $_POST[\'db_name\'];
                        $_SESSION[\'db_user\'] = $_POST[\'db_user\'];
                        $_SESSION[\'db_password\'] = $_POST[\'db_password\'];
                        $_SESSION[\'db_config\']',
                        
                        // Étape 4 - Sauvegarder les données admin
                        '/\$_SESSION\[\'admin_config\'\]/' => '$_SESSION[\'admin_name\'] = $_POST[\'admin_name\'];
                        $_SESSION[\'admin_email\'] = $_POST[\'admin_email\'];
                        $_SESSION[\'admin_password\'] = $_POST[\'admin_password\'];
                        $_SESSION[\'admin_config\']'
                    ];
                    
                    $modified = false;
                    foreach ($patterns as $pattern => $replacement) {
                        if (preg_match($pattern, $content)) {
                            $content = preg_replace($pattern, $replacement, $content);
                            $modified = true;
                        }
                    }
                    
                    if ($modified && file_put_contents($installFile, $content)) {
                        $fixes[] = "✅ Gestion des sessions corrigée dans install_new.php";
                    } else if (!$modified) {
                        $fixes[] = "✅ Gestion des sessions déjà correcte";
                    } else {
                        $errors[] = "❌ Impossible de modifier install_new.php";
                    }
                }
            }
            
            // ==========================================
            // 4. CORRECTION GESTION AJAX ÉTAPE 5
            // ==========================================
            if (isset($_POST['fix_ajax'])) {
                $jsFile = 'install/assets/js/install.js';
                
                if (file_exists($jsFile)) {
                    $content = file_get_contents($jsFile);
                    
                    // Ajouter la fonction verifyStep5Ajax si elle n'existe pas
                    if (strpos($content, 'verifyStep5Ajax') === false) {
                        $step5AjaxFunction = "
// Fonction AJAX pour l'étape 5 (Installation finale)
function verifyStep5Ajax(form) {
    const submitBtn = form.querySelector('button[type=\"submit\"]');
    const originalText = submitBtn.innerHTML;
    
    // Afficher le spinner
    submitBtn.innerHTML = '<span class=\"spinner\"></span> Installation en cours...';
    submitBtn.disabled = true;
    
    const formData = new FormData(form);
    formData.append('ajax', '1');
    
    fetch('install_new.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showMessage('success', data.message || 'Installation terminée avec succès !');
            setTimeout(() => {
                if (data.redirect) {
                    window.location.href = data.redirect;
                }
            }, 2000);
        } else {
            showMessage('error', data.errors ? data.errors.join('<br>') : 'Erreur lors de l\\'installation');
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    })
    .catch(error => {
        console.error('Erreur AJAX:', error);
        showMessage('error', 'Erreur de communication avec le serveur');
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
}";
                        
                        $content .= $step5AjaxFunction;
                        
                        if (file_put_contents($jsFile, $content)) {
                            $fixes[] = "✅ Fonction AJAX étape 5 ajoutée";
                        } else {
                            $errors[] = "❌ Impossible de modifier install.js";
                        }
                    } else {
                        $fixes[] = "✅ Fonction AJAX étape 5 déjà présente";
                    }
                } else {
                    $errors[] = "❌ Fichier install.js non trouvé";
                }
            }
            
            // ==========================================
            // 5. NETTOYAGE FICHIERS TEMPORAIRES
            // ==========================================
            if (isset($_POST['cleanup'])) {
                $tempFiles = [
                    'install_new_fixed.php',
                    'install_cpanel_fix.php',
                    'fix_env_permissions.php',
                    'fix_installation_final.php'
                ];
                
                foreach ($tempFiles as $file) {
                    if (file_exists($file)) {
                        if (unlink($file)) {
                            $fixes[] = "✅ Fichier temporaire $file supprimé";
                        } else {
                            $errors[] = "❌ Impossible de supprimer $file";
                        }
                    }
                }
            }
        }
        
        // Afficher les résultats
        foreach ($fixes as $fix) {
            echo "<div class='success'>$fix</div>";
        }
        
        foreach ($errors as $error) {
            echo "<div class='error'>$error</div>";
        }
        ?>

        <div class="step">
            <h2>🔧 Corrections disponibles</h2>
            
            <form method="POST">
                <h3>1. 📝 Corriger les permissions .env</h3>
                <p>Résout le problème "❌ .env (Écriture) Erreur" à l'étape 2</p>
                <button type="submit" name="fix_env" class="btn btn-primary">Corriger .env</button>
                
                <hr>
                
                <h3>2. 📋 Corriger l'affichage de l'étape 5</h3>
                <p>Affiche correctement les informations de base de données et administrateur</p>
                <button type="submit" name="fix_step5" class="btn btn-primary">Corriger l'affichage</button>
                
                <hr>
                
                <h3>3. 💾 Corriger la gestion des sessions</h3>
                <p>Assure que les données saisies sont correctement sauvegardées</p>
                <button type="submit" name="fix_session" class="btn btn-primary">Corriger les sessions</button>
                
                <hr>
                
                <h3>4. ⚡ Corriger la gestion AJAX</h3>
                <p>Résout le problème "Installation en cours..." qui tourne en boucle</p>
                <button type="submit" name="fix_ajax" class="btn btn-primary">Corriger AJAX</button>
                
                <hr>
                
                <h3>5. 🧹 Nettoyer les fichiers temporaires</h3>
                <p>Supprime tous les fichiers de correction temporaires</p>
                <button type="submit" name="cleanup" class="btn btn-warning">Nettoyer</button>
            </form>
            
            <hr>
            
            <div class="info">
                <h4>🚀 Correction complète automatique</h4>
                <p>Applique toutes les corrections en une fois :</p>
                <form method="POST">
                    <input type="hidden" name="fix_env" value="1">
                    <input type="hidden" name="fix_step5" value="1">
                    <input type="hidden" name="fix_session" value="1">
                    <input type="hidden" name="fix_ajax" value="1">
                    <button type="submit" class="btn btn-success">🔧 TOUT CORRIGER</button>
                </form>
            </div>
        </div>

        <div class="info">
            <h3>📋 État actuel :</h3>
            <ul>
                <li><strong>Fichier .env :</strong> <?= file_exists('../.env') ? '✅ Existe' : '❌ Manquant' ?></li>
                <li><strong>.env accessible en écriture :</strong> <?= is_writable('../.env') ? '✅ Oui' : '❌ Non' ?></li>
                <li><strong>Installateur :</strong> <a href="install/install_new.php" target="_blank">🔗 Tester</a></li>
            </ul>
        </div>

        <p><a href="install/install_new.php" class="btn btn-primary">🔙 Retour à l'installateur</a></p>
    </div>
</body>
</html> 