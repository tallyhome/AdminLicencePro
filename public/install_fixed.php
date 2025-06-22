<?php
/**
 * Installation corrigée pour AdminLicence
 * Version: 1.1.0 - Avec affichage d'erreurs amélioré
 */

// Capturer toutes les erreurs
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Inclure les fichiers nécessaires
require_once __DIR__ . '/install/config.php';
require_once __DIR__ . '/install/functions/language.php';
require_once __DIR__ . '/install/functions/core.php';
require_once __DIR__ . '/install/functions/database.php';
require_once __DIR__ . '/install/functions/installation.php';

// Démarrer la session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialiser la langue
$currentLang = initLanguage();

// Gérer les étapes d'installation
$step = isset($_POST['step']) ? (int)$_POST['step'] : (isset($_GET['step']) ? (int)$_GET['step'] : 1);
$errors = [];
$success_message = '';

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    switch ($step) {
        case 1: // Vérification de la licence
            if (empty($_POST['serial_key'])) {
                $errors[] = 'La clé de licence est requise';
                writeToLog("Erreur: Clé de licence non fournie", 'ERROR');
            } else {
                // Obtenir le domaine et l'IP pour la vérification
                $domain = $_SERVER['HTTP_HOST'] ?? 'localhost';
                $ipAddress = $_SERVER['SERVER_ADDR'] ?? $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
                
                // Vérifier la licence sur le serveur distant
                writeToLog("Vérification de licence pour la clé: " . $_POST['serial_key'] . " - Domaine: " . $domain . " - IP: " . $ipAddress);
                
                $licenseCheck = verifierLicence($_POST['serial_key'], $domain, $ipAddress);
                
                if (!$licenseCheck['valide']) {
                    $errors[] = $licenseCheck['message'];
                    writeToLog("Licence invalide: " . $licenseCheck['message'], 'ERROR');
                } else {
                    // Licence valide, stocker les données et passer à l'étape 2
                    $_SESSION['license_data'] = $licenseCheck['donnees'];
                    $_SESSION['license_key'] = $_POST['serial_key'];
                    $_SESSION['license_valid'] = true;
                    writeToLog("Licence valide - Passage à l'étape 2", 'SUCCESS');
                    
                    $step = 2;
                    $success_message = 'Licence valide ! Vous pouvez maintenant configurer la base de données.';
                }
            }
            break;
            
        case 2: // Configuration de la base de données
            $requiredFields = ['db_host', 'db_port', 'db_name', 'db_user'];
            $missingFields = [];
            
            foreach ($requiredFields as $field) {
                if (empty($_POST[$field])) {
                    $missingFields[] = $field;
                }
            }
            
            if (!empty($missingFields)) {
                $errors[] = 'Tous les champs requis doivent être remplis';
            } else {
                // Tester la connexion à la base de données
                try {
                    $dsn = "mysql:host={$_POST['db_host']};port={$_POST['db_port']}";
                    $pdo = new PDO($dsn, $_POST['db_user'], $_POST['db_password'], [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_TIMEOUT => 5
                    ]);
                    
                    // Stocker les informations de connexion en session
                    $_SESSION['db_config'] = [
                        'host' => $_POST['db_host'],
                        'port' => $_POST['db_port'],
                        'database' => $_POST['db_name'],
                        'username' => $_POST['db_user'],
                        'password' => $_POST['db_password']
                    ];
                    
                    $step = 3;
                    $success_message = 'Connexion à la base de données réussie !';
                } catch (PDOException $e) {
                    $errors[] = 'Erreur de connexion à la base de données: ' . $e->getMessage();
                    writeToLog("Erreur de connexion à la base de données: " . $e->getMessage(), 'ERROR');
                }
            }
            break;
            
        case 3: // Configuration du compte admin
            $requiredFields = ['admin_name', 'admin_email', 'admin_password', 'admin_password_confirm'];
            $missingFields = [];
            
            foreach ($requiredFields as $field) {
                if (empty($_POST[$field])) {
                    $missingFields[] = $field;
                }
            }
            
            if (!empty($missingFields)) {
                $errors[] = 'Tous les champs requis doivent être remplis';
            } elseif ($_POST['admin_password'] !== $_POST['admin_password_confirm']) {
                $errors[] = 'Les mots de passe ne correspondent pas';
            } elseif (!filter_var($_POST['admin_email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Adresse email invalide';
            } else {
                // Stocker les informations de l'administrateur en session
                $_SESSION['admin_config'] = [
                    'name' => $_POST['admin_name'],
                    'email' => $_POST['admin_email'],
                    'password' => $_POST['admin_password']
                ];
                
                $step = 4;
                $success_message = 'Configuration de l\'administrateur enregistrée !';
            }
            break;
    }
}

// Affichage de la page
?>
<!DOCTYPE html>
<html lang="<?php echo $currentLang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Installation AdminLicence - Étape <?php echo $step; ?></title>
    <link rel="stylesheet" href="install/assets/css/install.css">
    <style>
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            border: 1px solid transparent;
        }
        .alert-danger {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
        }
        .alert-success {
            background-color: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
        }
        .alert-info {
            background-color: #d1ecf1;
            border-color: #bee5eb;
            color: #0c5460;
        }
        .form-error {
            color: #dc3545;
            font-size: 0.875em;
            margin-top: 5px;
        }
        .step-indicator {
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
        }
        .step-item {
            padding: 10px 20px;
            margin: 0 5px;
            border-radius: 20px;
            background: #e9ecef;
            color: #6c757d;
            font-weight: 500;
        }
        .step-item.active {
            background: #007bff;
            color: white;
        }
        .step-item.completed {
            background: #28a745;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Installation AdminLicence</h1>
            <p>Assistant d'installation - Étape <?php echo $step; ?> sur 4</p>
        </div>

        <!-- Indicateur d'étapes -->
        <div class="step-indicator">
            <div class="step-item <?php echo $step >= 1 ? ($step > 1 ? 'completed' : 'active') : ''; ?>">
                1. Licence
            </div>
            <div class="step-item <?php echo $step >= 2 ? ($step > 2 ? 'completed' : 'active') : ''; ?>">
                2. Base de données
            </div>
            <div class="step-item <?php echo $step >= 3 ? ($step > 3 ? 'completed' : 'active') : ''; ?>">
                3. Administrateur
            </div>
            <div class="step-item <?php echo $step >= 4 ? 'active' : ''; ?>">
                4. Installation
            </div>
        </div>

        <div class="install-content">
            <!-- Affichage des erreurs -->
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <strong>❌ Erreur<?php echo count($errors) > 1 ? 's' : ''; ?> :</strong>
                    <ul style="margin: 10px 0 0 0; padding-left: 20px;">
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <!-- Affichage des messages de succès -->
            <?php if (!empty($success_message)): ?>
                <div class="alert alert-success">
                    <strong>✅ Succès :</strong> <?php echo htmlspecialchars($success_message); ?>
                </div>
            <?php endif; ?>

            <?php switch ($step): 
                case 1: // Vérification de la licence ?>
                    <div class="step-title">Vérification de la licence</div>
                    <div class="step-description">Entrez votre clé de licence pour continuer l'installation</div>
                    
                    <div class="alert alert-info">
                        <strong>ℹ️ Information :</strong> Votre clé de licence sera vérifiée auprès du serveur <code>https://licence.myvcard.fr</code>
                    </div>

                    <form method="post" action="" class="install-form">
                        <input type="hidden" name="step" value="1">
                        
                        <div class="form-group">
                            <label for="serial_key" class="form-label">Clé de licence *</label>
                            <input type="text" 
                                   id="serial_key" 
                                   name="serial_key" 
                                   class="form-input"
                                   required 
                                   placeholder="XXXX-XXXX-XXXX-XXXX" 
                                   value="<?php echo htmlspecialchars($_POST['serial_key'] ?? ''); ?>"
                                   style="text-transform: uppercase;">
                            <small class="form-help">Entrez la clé de licence fournie lors de votre achat</small>
                        </div>
                        
                        <div class="form-actions">
                            <div></div>
                            <button type="submit" class="btn btn-verify">Vérifier la licence</button>
                        </div>
                    </form>
                    <?php break;

                case 2: // Configuration de la base de données ?>
                    <div class="step-title">Configuration de la base de données</div>
                    <div class="step-description">Entrez les informations de connexion à votre base de données MySQL</div>

                    <form method="post" action="" class="install-form">
                        <input type="hidden" name="step" value="2">
                        
                        <div class="form-group">
                            <label for="db_host" class="form-label">Hôte de la base de données *</label>
                            <input type="text" id="db_host" name="db_host" class="form-input" required 
                                   value="<?php echo htmlspecialchars($_POST['db_host'] ?? 'localhost'); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="db_port" class="form-label">Port *</label>
                            <input type="text" id="db_port" name="db_port" class="form-input" required 
                                   value="<?php echo htmlspecialchars($_POST['db_port'] ?? '3306'); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="db_name" class="form-label">Nom de la base de données *</label>
                            <input type="text" id="db_name" name="db_name" class="form-input" required 
                                   value="<?php echo htmlspecialchars($_POST['db_name'] ?? ''); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="db_user" class="form-label">Nom d'utilisateur *</label>
                            <input type="text" id="db_user" name="db_user" class="form-input" required 
                                   value="<?php echo htmlspecialchars($_POST['db_user'] ?? ''); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="db_password" class="form-label">Mot de passe</label>
                            <input type="password" id="db_password" name="db_password" class="form-input" 
                                   value="<?php echo htmlspecialchars($_POST['db_password'] ?? ''); ?>">
                        </div>
                        
                        <div class="form-actions">
                            <a href="?step=1" class="btn btn-secondary">Retour</a>
                            <button type="submit" class="btn btn-primary">Continuer</button>
                        </div>
                    </form>
                    <?php break;

                case 3: // Configuration du compte admin ?>
                    <div class="step-title">Configuration de l'administrateur</div>
                    <div class="step-description">Créez le compte administrateur principal</div>

                    <form method="post" action="" class="install-form">
                        <input type="hidden" name="step" value="3">
                        
                        <div class="form-group">
                            <label for="admin_name" class="form-label">Nom complet *</label>
                            <input type="text" id="admin_name" name="admin_name" class="form-input" required 
                                   value="<?php echo htmlspecialchars($_POST['admin_name'] ?? ''); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="admin_email" class="form-label">Adresse email *</label>
                            <input type="email" id="admin_email" name="admin_email" class="form-input" required 
                                   value="<?php echo htmlspecialchars($_POST['admin_email'] ?? ''); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="admin_password" class="form-label">Mot de passe *</label>
                            <input type="password" id="admin_password" name="admin_password" class="form-input" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="admin_password_confirm" class="form-label">Confirmer le mot de passe *</label>
                            <input type="password" id="admin_password_confirm" name="admin_password_confirm" class="form-input" required>
                        </div>
                        
                        <div class="form-actions">
                            <a href="?step=2" class="btn btn-secondary">Retour</a>
                            <button type="submit" class="btn btn-primary">Continuer</button>
                        </div>
                    </form>
                    <?php break;

                case 4: // Installation finale ?>
                    <div class="step-title">Installation finale</div>
                    <div class="step-description">Prêt à installer AdminLicence avec vos paramètres</div>

                    <div class="alert alert-info">
                        <h4>Résumé de l'installation :</h4>
                        <p><strong>Licence :</strong> <?php echo htmlspecialchars($_SESSION['license_key'] ?? 'Non configurée'); ?></p>
                        <p><strong>Base de données :</strong> <?php echo htmlspecialchars(($_SESSION['db_config']['host'] ?? '') . '/' . ($_SESSION['db_config']['database'] ?? '')); ?></p>
                        <p><strong>Administrateur :</strong> <?php echo htmlspecialchars($_SESSION['admin_config']['email'] ?? 'Non configuré'); ?></p>
                    </div>

                    <form method="post" action="">
                        <input type="hidden" name="step" value="4">
                        
                        <div class="form-actions">
                            <a href="?step=3" class="btn btn-secondary">Retour</a>
                            <button type="submit" class="btn btn-primary">Installer AdminLicence</button>
                        </div>
                    </form>
                    <?php break;

            endswitch; ?>
        </div>

        <div class="footer">
            <div class="footer-content">
                <p>&copy; 2025 AdminLicence. Tous droits réservés.</p>
                <div class="footer-links">
                    <a href="https://adminlicence.com/support">Support</a>
                    <a href="https://adminlicence.com/docs">Documentation</a>
                </div>
            </div>
        </div>
    </div>

    <script src="install/assets/js/install.js"></script>
</body>
</html> 