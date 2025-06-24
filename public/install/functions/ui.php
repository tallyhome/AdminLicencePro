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
        case 1: // Vérification de la licence
            echo '<div class="license-verification-section">
                <!-- Indicateur d\'initialisation automatique -->
                <div class="initialization-status" style="background: linear-gradient(135deg, #d4edda, #c3e6cb); border: 2px solid #28a745; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                    <h4 style="margin: 0 0 10px 0; color: #155724;">🚀 Initialisation automatique terminée</h4>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 10px; font-size: 14px;">
                        <div>✅ Caches Laravel vidés</div>
                        <div>✅ Fichier .env vérifié/créé</div>
                        <div>✅ Répertoires système créés</div>
                        <div>✅ Permissions vérifiées</div>
                    </div>
                    <p style="margin: 10px 0 0 0; font-size: 12px; color: #155724; font-style: italic;">
                        Votre installation est prête ! Aucune configuration manuelle requise.
                    </p>
                </div>
                
                <div class="license-info">
                    <h3>' . t('license_verification') . '</h3>
                    <p><strong>' . t('info') . ' :</strong> ' . t('license_verification_description') . '</p>
                    <p><strong>' . t('license_api') . ' :</strong> <code>https://licence.myvcard.fr</code></p>
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
                        <small>' . t('required_format') . ' : XXXX-XXXX-XXXX-XXXX</small>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-verify">' . t('verify_license') . '</button>
                    </div>
                </form>
            </div>';
            break;
            
        case 2: // Vérification des prérequis système
            echo '<div class="system-requirements-section">
                <div class="system-info">
                    <h3>' . t('system_requirements') . '</h3>
                    <p>' . t('system_requirements_description') . '</p>
                </div>
                
                <div class="requirements-check">
                    <div class="requirement-group">
                        <h4>' . t('php_version') . '</h4>
                        <div class="requirement-item">
                            <span class="requirement-name">PHP >= 8.1</span>
                            <span class="requirement-status status-' . (version_compare(PHP_VERSION, '8.1.0', '>=') ? 'ok' : 'error') . '">
                                ' . (version_compare(PHP_VERSION, '8.1.0', '>=') ? t('status_ok') : t('status_error')) . ' (' . PHP_VERSION . ')
                            </span>
                        </div>
                    </div>
                    
                    <div class="requirement-group">
                        <h4>' . t('php_extensions') . '</h4>';
                        
                        $extensions = [
                            'pdo' => ['required' => true, 'name' => 'PDO'],
                            'pdo_mysql' => ['required' => true, 'name' => 'PDO MySQL'],
                            'mbstring' => ['required' => true, 'name' => 'Mbstring'],
                            'openssl' => ['required' => true, 'name' => 'OpenSSL'],
                            'tokenizer' => ['required' => true, 'name' => 'Tokenizer'],
                            'xml' => ['required' => true, 'name' => 'XML'],
                            'ctype' => ['required' => true, 'name' => 'Ctype'],
                            'json' => ['required' => true, 'name' => 'JSON'],
                            'curl' => ['required' => false, 'name' => 'cURL'],
                            'gd' => ['required' => false, 'name' => 'GD'],
                            'zip' => ['required' => false, 'name' => 'ZIP']
                        ];
                        
                        foreach ($extensions as $ext => $info) {
                            $loaded = extension_loaded($ext);
                            $status = $loaded ? 'ok' : ($info['required'] ? 'error' : 'warning');
                            
                            echo '<div class="requirement-item">
                                <span class="requirement-name">' . $info['name'] . ' (' . ($info['required'] ? t('extension_required') : t('extension_optional')) . ')</span>
                                <span class="requirement-status status-' . $status . '">
                                    ' . ($loaded ? t('status_ok') : ($info['required'] ? t('status_error') : t('status_warning'))) . '
                                </span>
                            </div>';
                        }
                        
                    echo '</div>
                    
                    <div class="requirement-group">
                        <h4>' . t('file_permissions') . '</h4>';
                        
                        $directories = [
                            'storage' => '../storage',
                            'bootstrap/cache' => '../bootstrap/cache',
                            '.env' => '../.env.example'
                        ];
                        
                        foreach ($directories as $name => $path) {
                            $writable = is_writable($path);
                            $status = $writable ? 'ok' : 'error';
                            
                            echo '<div class="requirement-item">
                                <span class="requirement-name">' . $name . ' (' . t('permission_writable') . ')</span>
                                <span class="requirement-status status-' . $status . '">
                                    ' . ($writable ? t('status_ok') : t('status_error')) . '
                                </span>
                            </div>';
                        }
                        
                    echo '</div>
                </div>
                
                <form method="post" action="install_new.php" data-step="2" class="install-form">
                    <input type="hidden" name="step" value="2">
                    
                    <div class="form-actions">
                        <a href="install_new.php" class="btn btn-secondary">' . t('back') . '</a>
                        <button type="submit" class="btn btn-primary">' . t('next') . '</button>
                    </div>
                </form>
            </div>';
            break;
            
        case 3: // Configuration de la base de données
            echo '<div class="step-3-container">
                <div class="database-config-section">
                    <div class="database-info">
                        <h3>' . t('database_configuration') . '</h3>
                        <p>' . t('database_configuration_description') . '</p>
                    </div>
                    
                    <form method="post" action="install_new.php" data-step="3" class="install-form">
                        <input type="hidden" name="step" value="3">
                        
                        <div class="db-form-row">
                            <div class="form-group">
                                <label for="db_host">' . t('db_host') . '</label>
                                <input type="text" id="db_host" name="db_host" required 
                                       value="' . htmlspecialchars($_POST['db_host'] ?? 'localhost') . '"
                                       placeholder="localhost">
                            </div>
                            
                            <div class="form-group">
                                <label for="db_port">' . t('db_port') . '</label>
                                <input type="text" id="db_port" name="db_port" required 
                                       value="' . htmlspecialchars($_POST['db_port'] ?? '3306') . '"
                                       placeholder="3306">
                            </div>
                        </div>
                        
                        <div class="form-group db-form-full">
                            <label for="db_name">' . t('db_name') . '</label>
                            <input type="text" id="db_name" name="db_name" required 
                                   value="' . htmlspecialchars($_POST['db_name'] ?? '') . '"
                                   placeholder="adminlicence">
                            <small>' . t('database_will_be_created') . '</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="db_user">' . t('db_user') . '</label>
                            <input type="text" id="db_user" name="db_user" required 
                                   value="' . htmlspecialchars($_POST['db_user'] ?? '') . '"
                                   placeholder="root">
                        </div>
                        
                        <div class="form-group">
                            <label for="db_password">' . t('db_password') . '</label>
                            <input type="password" id="db_password" name="db_password" 
                                   value="' . htmlspecialchars($_POST['db_password'] ?? '') . '"
                                   placeholder="' . t('optional') . '">
                            <small>' . t('leave_empty_if_no_password') . '</small>
                        </div>
                        
                        <div class="db-test-section" style="margin: 1.5rem 0;">
                            <button type="button" id="test-db-btn" class="btn btn-info" style="margin-bottom: 1rem;">
                                🔧 Tester la connexion SQL
                            </button>
                            <div class="db-test-alert"></div>
                        </div>
                        
                        <div class="form-actions">
                            <a href="install_new.php?step=2" class="btn btn-secondary">' . t('back') . '</a>
                            <button type="submit" class="btn btn-primary">' . t('next') . '</button>
                        </div>
                    </form>
                </div>
            </div>';
            break;
            
        case 4: // Configuration du compte admin
            echo '<div class="step-4-container">
                <div class="admin-config-section">
                    <div class="admin-info">
                        <h3>' . t('admin_setup') . '</h3>
                        <p>' . t('admin_setup_description') . '</p>
                    </div>
                    
                    <form method="post" action="install_new.php" data-step="4" class="install-form">
                        <input type="hidden" name="step" value="4">
                        
                        <div class="form-group">
                            <label for="admin_name">' . t('admin_name') . '</label>
                            <input type="text" id="admin_name" name="admin_name" required 
                                   value="' . htmlspecialchars($_POST['admin_name'] ?? '') . '"
                                   placeholder="Administrateur">
                        </div>
                        
                        <div class="form-group">
                            <label for="admin_email">' . t('admin_email') . '</label>
                            <input type="email" id="admin_email" name="admin_email" required 
                                   value="' . htmlspecialchars($_POST['admin_email'] ?? '') . '"
                                   placeholder="admin@example.com">
                        </div>
                        
                        <div class="form-group">
                            <label for="admin_password">' . t('admin_password') . '</label>
                            <input type="password" id="admin_password" name="admin_password" required
                                   placeholder="Mot de passe sécurisé">
                        </div>
                        
                        <div class="form-group">
                            <label for="admin_password_confirm">' . t('admin_password_confirm') . '</label>
                            <input type="password" id="admin_password_confirm" name="admin_password_confirm" required
                                   placeholder="Confirmez le mot de passe">
                        </div>
                        
                        <div class="form-actions">
                            <a href="install_new.php?step=3" class="btn btn-secondary">' . t('back') . '</a>
                            <button type="submit" class="btn btn-primary">' . t('next') . '</button>
                        </div>
                    </form>
                </div>
            </div>';
            break;
            
        case 5: // Installation finale
            echo '<div class="step-5-container">
                <div class="finalization-section">
                    <div class="finalization-info">
                        <h3>' . t('finalization') . '</h3>
                        <p>' . t('finalization_description') . '</p>
                    </div>
                    
                    <form method="post" action="install_new.php" data-step="5" class="install-form">
                        <input type="hidden" name="step" value="5">
                        
                        <div class="installation-summary">
                            <h4>Résumé de l\'installation</h4>
                            <p>Vérifiez les informations ci-dessous avant de procéder à l\'installation finale.</p>
                            
                            <div class="summary-section">
                                <h5>Informations de la base de données</h5>
                                <p><strong>Hôte :</strong> ' . htmlspecialchars($_SESSION['db_config']['host'] ?? '') . '</p>
                                <p><strong>Base de données :</strong> ' . htmlspecialchars($_SESSION['db_config']['database'] ?? '') . '</p>
                                <p><strong>Utilisateur :</strong> ' . htmlspecialchars($_SESSION['db_config']['username'] ?? '') . '</p>
                                
                                <h5>Informations de l\'administrateur</h5>
                                <p><strong>Nom :</strong> ' . htmlspecialchars($_SESSION['admin_config']['name'] ?? '') . '</p>
                                <p><strong>Email :</strong> ' . htmlspecialchars($_SESSION['admin_config']['email'] ?? '') . '</p>
                            </div>
                            
                            <p><strong>Attention :</strong> Cette opération va créer les tables de la base de données et configurer l\'application.</p>
                        </div>
                        
                        <div class="form-actions">
                            <a href="install_new.php?step=4" class="btn btn-secondary">' . t('back') . '</a>
                            <button type="submit" class="btn btn-primary">Installer maintenant</button>
                        </div>
                    </form>
                </div>
            </div>';
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
