<?php
/**
 * Fonctions d'interface utilisateur pour l'installateur
 */

/**
 * Affiche une erreur de manière sécurisée
 * 
 * @param string $message Message d'erreur
 * @param string|null $details Détails de l'erreur
 * @return void
 */
function showError($message, $details = null) {
    $lang = 'fr';
    header('Content-Type: text/html; charset=utf-8');
    echo '<!DOCTYPE html>
    <html lang="' . $lang . '">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Erreur d\'installation</title>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; margin: 0; padding: 20px; color: #333; }
            .container { max-width: 800px; margin: 0 auto; background: #f9f9f9; padding: 20px; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
            h1 { color: #d9534f; }
            .error { background: #f8d7da; color: #721c24; padding: 10px; border-radius: 4px; margin-bottom: 20px; }
            .details { background: #f8f9fa; padding: 10px; border-radius: 4px; margin-bottom: 20px; font-family: monospace; }
            .btn { display: inline-block; background: #007bff; color: white; padding: 10px 15px; text-decoration: none; border-radius: 4px; border: none; cursor: pointer; }
            .btn:hover { background: #2980b9; }
            .language-selector { text-align: right; margin-bottom: 20px; }
            .language-selector a { margin-left: 10px; text-decoration: none; }
            .language-selector a.active { font-weight: bold; }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>Erreur d\'installation</h1>
            <div class="error">' . htmlspecialchars($message) . '</div>';
    
    if ($details) {
        echo '<div class="details">' . htmlspecialchars($details) . '</div>';
    }
    
    echo '<a href="install_new.php" class="btn">Réessayer</a>
        </div>
    </body>
    </html>';
    exit;
}

/**
 * Affiche le formulaire d'installation
 * 
 * @param int $step Étape actuelle
 * @param array $errors Erreurs à afficher
 * @return void
 */
function displayInstallationForm($step, $errors = []) {
    $currentLang = getCurrentLanguage();
    
    // Inclure le header
    include 'templates/header.php';
    
    // Afficher les erreurs s'il y en a
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo '<div class="alert alert-danger">' . htmlspecialchars($error) . '</div>';
        }
    }
    
    // Afficher le formulaire en fonction de l'étape
    switch ($step) {
        case 1: // Sélection de la langue et vérification de la licence
            echo '<div class="license-info">
                <h3>' . t('license_verification') . '</h3>
                <p><strong>' . t('info') . ' :</strong> ' . t('license_verification_description') . '</p>
                <p><strong>API de licence :</strong> <code>https://licence.myvcard.fr</code></p>
            </div>
            
            <form method="post" action="install_new.php" data-step="1" class="install-form">
                <input type="hidden" name="step" value="1">
                
                <div class="form-group">
                    <label for="serial_key">' . t('license_key') . '</label>
                    <input type="text" id="serial_key" name="serial_key" required 
                           placeholder="' . t('license_key_placeholder') . '" 
                           pattern="[A-Za-z0-9]{4}-[A-Za-z0-9]{4}-[A-Za-z0-9]{4}-[A-Za-z0-9]{4}" 
                           value=""
                           style="text-transform: uppercase;">
                    <small style="color: #666; font-size: 0.9em;">Format requis : XXXX-XXXX-XXXX-XXXX</small>
                </div>
                
                <div class="form-actions">
                    <div></div>
                    <button type="submit" class="btn btn-verify">' . t('verify_license') . '</button>
                </div>
            </form>';
            break;
            
        case 2: // Configuration de la base de données
            echo '<form method="post" action="install_new.php">
                <input type="hidden" name="step" value="2">
                
                <div class="form-group">
                    <label for="db_host">' . t('db_host') . '</label>
                    <input type="text" id="db_host" name="db_host" required 
                           value="' . htmlspecialchars($_POST['db_host'] ?? 'localhost') . '">
                </div>
                
                <div class="form-group">
                    <label for="db_port">' . t('db_port') . '</label>
                    <input type="text" id="db_port" name="db_port" required 
                           value="' . htmlspecialchars($_POST['db_port'] ?? '3306') . '">
                </div>
                
                <div class="form-group">
                    <label for="db_name">' . t('db_name') . '</label>
                    <input type="text" id="db_name" name="db_name" required 
                           value="' . htmlspecialchars($_POST['db_name'] ?? '') . '">
                </div>
                
                <div class="form-group">
                    <label for="db_user">' . t('db_user') . '</label>
                    <input type="text" id="db_user" name="db_user" required 
                           value="' . htmlspecialchars($_POST['db_user'] ?? '') . '">
                </div>
                
                <div class="form-group">
                    <label for="db_password">' . t('db_password') . '</label>
                    <input type="password" id="db_password" name="db_password" 
                           value="' . htmlspecialchars($_POST['db_password'] ?? '') . '">
                </div>
                
                <div class="form-actions">
                    <a href="install_new.php" class="btn">' . t('back') . '</a>
                    <button type="submit" class="btn">' . t('next') . '</button>
                </div>
            </form>';
            break;
            
        case 3: // Configuration du compte admin
            echo '<form method="post" action="install_new.php">
                <input type="hidden" name="step" value="3">
                
                <div class="form-group">
                    <label for="admin_name">' . t('admin_name') . '</label>
                    <input type="text" id="admin_name" name="admin_name" required 
                           value="' . htmlspecialchars($_POST['admin_name'] ?? '') . '">
                </div>
                
                <div class="form-group">
                    <label for="admin_email">' . t('admin_email') . '</label>
                    <input type="email" id="admin_email" name="admin_email" required 
                           value="' . htmlspecialchars($_POST['admin_email'] ?? '') . '">
                </div>
                
                <div class="form-group">
                    <label for="admin_password">' . t('admin_password') . '</label>
                    <input type="password" id="admin_password" name="admin_password" required>
                </div>
                
                <div class="form-group">
                    <label for="admin_password_confirm">' . t('admin_password_confirm') . '</label>
                    <input type="password" id="admin_password_confirm" name="admin_password_confirm" required>
                </div>
                
                <div class="form-actions">
                    <a href="install_new.php?step=2" class="btn">' . t('back') . '</a>
                    <button type="submit" class="btn">' . t('next') . '</button>
                </div>
            </form>';
            break;
            
        case 4: // Installation finale
            echo '<form method="post" action="install_new.php">
                <input type="hidden" name="step" value="4">
                
                <div style="margin-bottom: 20px;">
                    <h3>Résumé de l\'installation</h3>
                    <p>Vérifiez les informations ci-dessous avant de procéder à l\'installation finale.</p>
                    
                    <div style="background: #f8f9fa; padding: 15px; border-radius: 4px; margin-bottom: 20px;">
                        <h4>Informations de la base de données</h4>
                        <p><strong>Hôte :</strong> ' . htmlspecialchars($_SESSION['db_config']['host'] ?? '') . '</p>
                        <p><strong>Base de données :</strong> ' . htmlspecialchars($_SESSION['db_config']['database'] ?? '') . '</p>
                        <p><strong>Utilisateur :</strong> ' . htmlspecialchars($_SESSION['db_config']['username'] ?? '') . '</p>
                        
                        <h4>Informations de l\'administrateur</h4>
                        <p><strong>Nom :</strong> ' . htmlspecialchars($_SESSION['admin_config']['name'] ?? '') . '</p>
                        <p><strong>Email :</strong> ' . htmlspecialchars($_SESSION['admin_config']['email'] ?? '') . '</p>
                    </div>
                    
                    <p><strong>Attention :</strong> Cette opération va créer les tables de la base de données et configurer l\'application.</p>
                </div>
                
                <div class="form-actions">
                    <a href="install_new.php?step=3" class="btn">Retour</a>
                    <button type="submit" class="btn">Installer maintenant</button>
                </div>
            </form>';
            break;
    }
    
    include 'templates/footer.php';
}

/**
 * Affiche la page de succès après l'installation
 * 
 * @return void
 */
function displaySuccessPage() {
    $step = 5; // Étape finale pour l'affichage
    
    // Inclure le header
    include 'templates/header.php';
    
    echo '<div class="success-content">
        <div class="success-icon">✓</div>
        <div class="success-title">Installation terminée</div>
        <div class="success-description">
            <p>L\'installation d\'AdminLicence s\'est déroulée avec succès !</p>
            <p>Vous pouvez maintenant vous connecter avec le compte administrateur que vous avez créé.</p>
        </div>
        
        <div class="alert alert-success">
            <strong>Félicitations !</strong><br>
            AdminLicence est maintenant installé et prêt à être utilisé.
        </div>
        
        <div class="form-actions" style="justify-content: center; gap: 2rem;">
            <a href="' . dirname(dirname($_SERVER['REQUEST_URI'])) . '/admin/login" class="btn btn-primary">Accéder à l\'administration</a>
            <a href="' . dirname(dirname($_SERVER['REQUEST_URI'])) . '" class="btn btn-secondary">Aller à l\'accueil</a>
        </div>
    </div>';
    
    include 'templates/footer.php';
}
