<?php
/**
 * Installateur optimisé pour AdminLicence
 * Version: 1.0.0
 * Date: 2025-04-15
 */

// Inclure les fichiers nécessaires
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions/language.php';
require_once __DIR__ . '/functions/core.php';
require_once __DIR__ . '/functions/database.php';
require_once __DIR__ . '/functions/installation.php';
require_once __DIR__ . '/functions/ui.php';

// Fonction pour capturer les erreurs fatales
register_shutdown_function(function() {
    $error = error_get_last();
    if ($error !== null && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        writeToLog("ERREUR FATALE: {$error['message']} dans {$error['file']} à la ligne {$error['line']}", 'FATAL');
    }
});

// Démarrer la session si elle n'est pas déjà démarrée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Gestion du changement de langue
if (isset($_GET['language']) && in_array($_GET['language'], ['fr', 'en'])) {
    $_SESSION['installer_language'] = $_GET['language'];
    
    // Conserver l'étape actuelle lors de la redirection
    $currentStep = isset($_GET['step']) ? (int)$_GET['step'] : 1;
    $redirectUrl = 'install_new.php';
    if ($currentStep > 1) {
        $redirectUrl .= '?step=' . $currentStep;
    }
    
    header("Location: $redirectUrl");
    exit;
}

// Initialiser la langue
$currentLang = initLanguage();

// Vérifier si Laravel est déjà installé
if (isLaravelInstalled() && !isset($_GET['force'])) {
    showError('Application déjà installée' . ' <a href="install_new.php?force=1" style="color: #007bff;">Forcer la réinstallation</a>');
    exit;
}

// Gérer les étapes d'installation
$step = isset($_POST['step']) ? (int)$_POST['step'] : (isset($_GET['step']) ? (int)$_GET['step'] : 1);
$errors = [];

// Vérifier si c'est une requête AJAX
$isAjax = isset($_POST['ajax']) || isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';

try {
    // Traitement du formulaire
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        switch ($step) {
            case 1: // Vérification de la licence
                if (empty($_POST['serial_key'])) {
                    $errors[] = t('license_key_required');
                    writeToLog("Erreur: Clé de licence non fournie", 'ERROR');
                    // Rester à l'étape 1
                    $step = 1;
                } else {
                    // Obtenir le domaine et l'IP pour la vérification
                    $domain = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
                    $ipAddress = isset($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : 
                                 (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1');
                    
                    // Vérifier la licence sur le serveur distant
                    writeToLog("Vérification de licence pour la clé: " . $_POST['serial_key'] . " - Domaine: " . $domain . " - IP: " . $ipAddress);
                    
                    // Appel obligatoire à verifierLicence avec le domaine et l'IP
                    $licenseCheck = verifierLicence($_POST['serial_key'], $domain, $ipAddress);
                    
                    // Vérification de la validité de la licence
                    if (!$licenseCheck['valide']) {
                        // Licence invalide - rester à l'étape 1
                        $errors[] = $licenseCheck['message'];
                        writeToLog("Licence invalide: " . $licenseCheck['message'], 'ERROR');
                        
                        // Réponse AJAX pour licence invalide
                        if ($isAjax) {
                            header('Content-Type: application/json');
                            echo json_encode([
                                'success' => false,
                                'message' => $licenseCheck['message'],
                                'step' => 1
                            ]);
                            exit;
                        }
                        
                        // Forcer explicitement le maintien à l'étape 1
                        $step = 1;
                    } else {
                        // Licence valide, stocker les données et passer à l'étape 2
                        $_SESSION['license_data'] = $licenseCheck['donnees'];
                        $_SESSION['license_key'] = $_POST['serial_key'];
                        $_SESSION['license_valid'] = true;
                        writeToLog("Licence valide - Passage à l'étape 2", 'SUCCESS');
                        
                        // Réponse AJAX pour licence valide - REDIRECTION
                        if ($isAjax) {
                            header('Content-Type: application/json');
                            echo json_encode([
                                'success' => true,
                                'message' => t('license_valid_next_step'),
                                'redirect' => 'install_new.php?step=2'
                            ]);
                            exit;
                        }
                        
                        // Passage explicite à l'étape 2
                        $step = 2;
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
                    $errors[] = 'Champs requis manquants';
                } else {
                    // Tester la connexion à la base de données
                    try {
                        $dsn = "mysql:host={$_POST['db_host']};port={$_POST['db_port']}";
                        $pdo = new PDO($dsn, $_POST['db_user'], $_POST['db_password'], [
                            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                            PDO::ATTR_TIMEOUT => 5
                        ]);
                        
                        // Vérifier si la base de données existe
                        $stmt = $pdo->query("SHOW DATABASES LIKE '{$_POST['db_name']}'");
                        if ($stmt->rowCount() === 0) {
                            // Essayer de créer la base de données
                            try {
                                $pdo->exec("CREATE DATABASE `{$_POST['db_name']}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                            } catch (PDOException $e) {
                                $errors[] = 'Erreur lors de la création de la base de données';
                                writeToLog("Erreur lors de la création de la base de données: " . $e->getMessage(), 'ERROR');
                                break;
                            }
                        }
                        
                        // Stocker les informations de connexion en session
                        $_SESSION['db_config'] = [
                            'host' => $_POST['db_host'],
                            'port' => $_POST['db_port'],
                            'database' => $_POST['db_name'],
                            'username' => $_POST['db_user'],
                            'password' => $_POST['db_password']
                        ];
                        
                        $step = 3;
                    } catch (PDOException $e) {
                        $errors[] = 'Erreur de connexion à la base de données';
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
                    $errors[] = 'Champs requis manquants';
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
                }
                break;
                
            case 4: // Installation finale
                try {
                    // Créer le fichier .env à partir du modèle
                    if (!createEnvFile()) {
                        $errors[] = 'Erreur lors de la création du fichier .env';
                        break;
                    }
                    
                    // Exécuter les migrations et créer l'administrateur
                    if (!runMigrations()) {
                        $errors[] = 'Erreur lors de l\'exécution des migrations';
                        break;
                    }
                    
                    if (!createAdminUser()) {
                        $errors[] = 'Erreur lors de la création de l\'administrateur';
                        break;
                    }
                    
                    // Marquer l'installation comme terminée
                    if (!finalizeInstallation()) {
                        $errors[] = 'Erreur lors de la finalisation de l\'installation';
                        break;
                    }
                    
                    // Rediriger vers la page de succès
                    header('Location: install_new.php?success=1');
                    exit;
                } catch (Exception $e) {
                    $errors[] = 'Erreur lors de l\'installation';
                    writeToLog("Erreur lors de l'installation finale: " . $e->getMessage(), 'ERROR');
                }
                break;
        }
    }
    
    // Afficher la page de succès si l'installation est terminée
    if (isset($_GET['success']) && $_GET['success'] == 1) {
        displaySuccessPage();
        exit;
    }
} catch (Exception $e) {
    writeToLog("Erreur lors du traitement du formulaire: " . $e->getMessage());
    showError('Erreur lors de l\'installation', $e->getMessage());
}

// Afficher le formulaire d'installation
displayInstallationForm($step, $errors);
