<?php
/**
 * Installateur AdminLicence - Version cPanel (sans exec)
 * Compatible avec les serveurs où exec() est désactivé
 */

// Inclure les fichiers nécessaires AVANT l'initialisation
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions/language.php';
require_once __DIR__ . '/functions/core.php';
require_once __DIR__ . '/functions/database.php';
require_once __DIR__ . '/functions/installation.php';
require_once __DIR__ . '/functions/ui.php';

// ÉTAPE 0: INITIALISATION AUTOMATIQUE POUR cPanel (SANS EXEC)
function initializeInstaller() {
    $log = [];
    
    // 1. Vider tous les caches Laravel automatiquement (MÉTHODE MANUELLE UNIQUEMENT)
    $log[] = "🧹 Nettoyage automatique des caches (cPanel compatible)...";
    
    // Supprimer directement les fichiers de cache critiques (PAS D'EXEC)
    $cacheFiles = [
        '../bootstrap/cache/config.php',
        '../bootstrap/cache/routes-v7.php', 
        '../bootstrap/cache/services.php',
        '../bootstrap/cache/packages.php',
        '../bootstrap/cache/compiled.php'
    ];
    
    foreach ($cacheFiles as $file) {
        if (file_exists($file)) {
            @unlink($file);
        }
    }
    
    // NE PAS utiliser exec() - Seulement suppression manuelle des fichiers
    // Les commandes artisan ne fonctionnent pas sur cPanel avec exec() désactivé
    
    // 2. Nettoyer manuellement les fichiers de cache
    $cacheDirectories = [
        '../storage/framework/cache',
        '../storage/framework/views', 
        '../storage/framework/sessions',
        '../bootstrap/cache'
    ];
    
    foreach ($cacheDirectories as $dir) {
        if (is_dir($dir)) {
            $files = glob($dir . '/*');
            foreach ($files as $file) {
                if (is_file($file) && basename($file) !== '.gitignore') {
                    @unlink($file);
                }
            }
        }
    }
    $log[] = "✅ Caches vidés automatiquement (méthode manuelle)";
    
    // 3. Vérifier et créer le fichier .env si nécessaire
    $envFile = '../.env';
    $envExampleFile = '../.env.example';
    
    if (!file_exists($envFile)) {
        if (file_exists($envExampleFile)) {
            copy($envExampleFile, $envFile);
            $log[] = "✅ Fichier .env créé depuis .env.example";
        } else {
            // Créer un .env minimal
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
            
            file_put_contents($envFile, $defaultEnv);
            $log[] = "✅ Fichier .env créé avec configuration par défaut";
        }
    } else {
        $log[] = "✅ Fichier .env existe déjà";
    }
    
    // 4. Créer les répertoires nécessaires s'ils n'existent pas
    $requiredDirs = [
        '../storage/app',
        '../storage/framework/cache',
        '../storage/framework/sessions',
        '../storage/framework/views',
        '../storage/logs',
        '../bootstrap/cache'
    ];
    
    foreach ($requiredDirs as $dir) {
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
    }
    $log[] = "✅ Répertoires système vérifiés";
    
    // 5. Logger l'initialisation (writeToLog est maintenant disponible)
    if (function_exists('writeToLog')) {
        writeToLog("INITIALISATION AUTO cPanel: " . implode(" | ", $log), 'INFO');
    }
    
    return $log;
}

// LANCER L'INITIALISATION AUTOMATIQUE AU PREMIER APPEL
static $initialized = false;
if (!$initialized) {
    $initLog = initializeInstaller();
    $initialized = true;
}

// Fonction pour capturer les erreurs fatales
register_shutdown_function(function() {
    $error = error_get_last();
    if ($error !== null && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        if (function_exists('writeToLog')) {
            writeToLog("ERREUR FATALE: {$error['message']} dans {$error['file']} à la ligne {$error['line']}", 'FATAL');
        }
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
    $redirectUrl = 'install_new_fixed.php';
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
    showError('Application déjà installée' . ' <a href="install_new_fixed.php?force=1" style="color: #007bff;">Forcer la réinstallation</a>');
    exit;
}

// Gérer les étapes d'installation
$step = isset($_POST['step']) ? (int)$_POST['step'] : (isset($_GET['step']) ? (int)$_GET['step'] : 1);
$errors = [];

// Vérifier si c'est une requête AJAX
$isAjax = isset($_POST['ajax']) || isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';

// CORRECTION: Vérification de la licence UNIQUEMENT pour les requêtes GET et étapes > 2
if ($step > 2 && $_SERVER['REQUEST_METHOD'] === 'GET' && !isset($_SESSION['license_valid'])) {
    $step = 1;
    $errors[] = t('license_key_required');
    writeToLog("Redirection vers étape 1 : licence manquante (GET)", 'WARNING');
}

try {
    // Traitement du formulaire
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Gestion spéciale pour le test de connexion DB
        if (isset($_POST['action']) && $_POST['action'] === 'test_db_connection') {
            testDatabaseConnection();
            exit;
        }
        
        switch ($step) {
            case 1: // Vérification de la licence
                if (empty($_POST['serial_key'])) {
                    $errors[] = t('license_key_required');
                    writeToLog("Erreur: Clé de licence non fournie", 'ERROR');
                    $step = 1;
                } else {
                    // Obtenir le domaine et l'IP pour la vérification
                    $domain = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
                    $ipAddress = isset($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : 
                                 (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1');
                    
                    writeToLog("Vérification de licence pour la clé: " . $_POST['serial_key'] . " - Domaine: " . $domain . " - IP: " . $ipAddress);
                    
                    $licenseCheck = verifierLicence($_POST['serial_key'], $domain, $ipAddress);
                    
                    if (!$licenseCheck['valide']) {
                        $errors[] = $licenseCheck['message'];
                        writeToLog("Licence invalide: " . $licenseCheck['message'], 'ERROR');
                        
                        // Réponse AJAX pour licence invalide
                        if ($isAjax) {
                            header('Content-Type: application/json');
                            echo json_encode([
                                'success' => false,
                                'errors' => $errors,
                                'redirect' => false
                            ]);
                            exit;
                        }
                        $step = 1;
                    } else {
                        // Licence valide - sauvegarder et passer à l'étape suivante
                        $_SESSION['license_valid'] = true;
                        $_SESSION['license_key'] = $_POST['serial_key'];
                        $_SESSION['license_domain'] = $domain;
                        $_SESSION['license_ip'] = $ipAddress;
                        writeToLog("Licence valide confirmée", 'SUCCESS');
                        
                        // Réponse AJAX pour licence valide
                        if ($isAjax) {
                            header('Content-Type: application/json');
                            echo json_encode([
                                'success' => true,
                                'message' => 'Licence validée avec succès',
                                'redirect' => 'install_new_fixed.php?step=2'
                            ]);
                            exit;
                        }
                        
                        // Redirection normale
                        header("Location: install_new_fixed.php?step=2");
                        exit;
                    }
                }
                break;
                
            case 2: // Configuration base de données
                // Validation des champs requis
                $requiredFields = ['db_host', 'db_name', 'db_user', 'db_pass'];
                foreach ($requiredFields as $field) {
                    if (empty($_POST[$field])) {
                        $errors[] = t('field_required', ['field' => $field]);
                    }
                }
                
                if (empty($errors)) {
                    // Test de connexion
                    try {
                        $pdo = new PDO(
                            "mysql:host={$_POST['db_host']};dbname={$_POST['db_name']}", 
                            $_POST['db_user'], 
                            $_POST['db_pass']
                        );
                        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        
                        // Sauvegarder la configuration
                        $_SESSION['db_config'] = [
                            'host' => $_POST['db_host'],
                            'name' => $_POST['db_name'],
                            'user' => $_POST['db_user'],
                            'pass' => $_POST['db_pass']
                        ];
                        
                        writeToLog("Configuration base de données validée", 'SUCCESS');
                        
                        // Réponse AJAX
                        if ($isAjax) {
                            header('Content-Type: application/json');
                            echo json_encode([
                                'success' => true,
                                'message' => 'Configuration base de données validée',
                                'redirect' => 'install_new_fixed.php?step=3'
                            ]);
                            exit;
                        }
                        
                        header("Location: install_new_fixed.php?step=3");
                        exit;
                        
                    } catch (PDOException $e) {
                        $errors[] = t('database_connection_failed') . ': ' . $e->getMessage();
                        writeToLog("Erreur connexion DB: " . $e->getMessage(), 'ERROR');
                        
                        if ($isAjax) {
                            header('Content-Type: application/json');
                            echo json_encode([
                                'success' => false,
                                'errors' => $errors,
                                'redirect' => false
                            ]);
                            exit;
                        }
                    }
                }
                break;
                
            case 3: // Configuration administrateur
                $requiredFields = ['admin_name', 'admin_email', 'admin_password'];
                foreach ($requiredFields as $field) {
                    if (empty($_POST[$field])) {
                        $errors[] = t('field_required', ['field' => $field]);
                    }
                }
                
                if (empty($errors)) {
                    $_SESSION['admin_config'] = [
                        'name' => $_POST['admin_name'],
                        'email' => $_POST['admin_email'],
                        'password' => $_POST['admin_password']
                    ];
                    
                    writeToLog("Configuration administrateur validée", 'SUCCESS');
                    
                    if ($isAjax) {
                        header('Content-Type: application/json');
                        echo json_encode([
                            'success' => true,
                            'message' => 'Configuration administrateur validée',
                            'redirect' => 'install_new_fixed.php?step=4'
                        ]);
                        exit;
                    }
                    
                    header("Location: install_new_fixed.php?step=4");
                    exit;
                }
                break;
                
            case 4: // Installation finale
                if (empty($errors)) {
                    // Effectuer l'installation
                    $installResult = performInstallation();
                    
                    if ($installResult['success']) {
                        writeToLog("Installation terminée avec succès", 'SUCCESS');
                        
                        if ($isAjax) {
                            header('Content-Type: application/json');
                            echo json_encode([
                                'success' => true,
                                'message' => 'Installation terminée avec succès',
                                'redirect' => 'install_new_fixed.php?success=1'
                            ]);
                            exit;
                        }
                        
                        header("Location: install_new_fixed.php?success=1");
                        exit;
                    } else {
                        $errors = $installResult['errors'];
                        writeToLog("Erreur installation: " . implode(', ', $errors), 'ERROR');
                        
                        if ($isAjax) {
                            header('Content-Type: application/json');
                            echo json_encode([
                                'success' => false,
                                'errors' => $errors,
                                'redirect' => false
                            ]);
                            exit;
                        }
                    }
                }
                break;
        }
        
        // Si on arrive ici avec des erreurs en AJAX
        if ($isAjax && !empty($errors)) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'errors' => $errors,
                'redirect' => false
            ]);
            exit;
        }
    }
    
} catch (Exception $e) {
    $errors[] = 'Erreur inattendue: ' . $e->getMessage();
    writeToLog("Exception: " . $e->getMessage(), 'FATAL');
    
    if ($isAjax) {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'errors' => $errors,
            'redirect' => false
        ]);
        exit;
    }
}

// Affichage de l'interface
if (isset($_GET['success'])) {
    showSuccessPage();
} else {
    showInstallationStep($step, $errors);
}

// Fonction de test de connexion base de données
function testDatabaseConnection() {
    header('Content-Type: application/json');
    
    if (empty($_POST['db_host']) || empty($_POST['db_name']) || empty($_POST['db_user'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Tous les champs sont requis pour tester la connexion'
        ]);
        return;
    }
    
    try {
        $pdo = new PDO(
            "mysql:host={$_POST['db_host']};dbname={$_POST['db_name']}", 
            $_POST['db_user'], 
            $_POST['db_pass'] ?? ''
        );
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Test simple
        $pdo->query("SELECT 1");
        
        echo json_encode([
            'success' => true,
            'message' => 'Connexion à la base de données réussie !'
        ]);
        
    } catch (PDOException $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Erreur de connexion: ' . $e->getMessage()
        ]);
    }
}

// Fonction d'installation finale
function performInstallation() {
    try {
        // Récupérer les configurations sauvegardées
        $dbConfig = $_SESSION['db_config'] ?? null;
        $adminConfig = $_SESSION['admin_config'] ?? null;
        $licenseKey = $_SESSION['license_key'] ?? null;
        
        if (!$dbConfig || !$adminConfig || !$licenseKey) {
            return ['success' => false, 'errors' => ['Configuration incomplète']];
        }
        
        // 1. Mettre à jour le fichier .env
        $envContent = file_get_contents('../.env');
        $envContent = preg_replace('/DB_HOST=.*/', "DB_HOST={$dbConfig['host']}", $envContent);
        $envContent = preg_replace('/DB_DATABASE=.*/', "DB_DATABASE={$dbConfig['name']}", $envContent);
        $envContent = preg_replace('/DB_USERNAME=.*/', "DB_USERNAME={$dbConfig['user']}", $envContent);
        $envContent = preg_replace('/DB_PASSWORD=.*/', "DB_PASSWORD={$dbConfig['pass']}", $envContent);
        
        file_put_contents('../.env', $envContent);
        
        // 2. Créer un fichier de marqueur d'installation
        file_put_contents('../storage/installed', date('Y-m-d H:i:s'));
        
        // 3. Sauvegarder les informations de licence
        $licenseData = [
            'key' => $licenseKey,
            'domain' => $_SESSION['license_domain'] ?? '',
            'ip' => $_SESSION['license_ip'] ?? '',
            'installed_at' => date('Y-m-d H:i:s')
        ];
        
        file_put_contents('../storage/license.json', json_encode($licenseData, JSON_PRETTY_PRINT));
        
        // 4. Nettoyer la session
        session_destroy();
        
        return ['success' => true];
        
    } catch (Exception $e) {
        return ['success' => false, 'errors' => ['Erreur installation: ' . $e->getMessage()]];
    }
}
?> 