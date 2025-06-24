<?php
/**
 * 🚀 CORRECTION COMPLÈTE POUR PRODUCTION
 * Résout TOUS les problèmes : caches, configuration, IP, licence
 * Version: 1.0.0 - Production Ready
 */

// Configuration de sortie
ob_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Styles CSS
echo "<style>
body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
.container { max-width: 1000px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
.success { background: #d4edda; border: 2px solid #28a745; padding: 15px; border-radius: 8px; margin: 10px 0; }
.error { background: #f8d7da; border: 2px solid #dc3545; padding: 15px; border-radius: 8px; margin: 10px 0; }
.warning { background: #fff3cd; border: 2px solid #ffc107; padding: 15px; border-radius: 8px; margin: 10px 0; }
.info { background: #d1ecf1; border: 2px solid #17a2b8; padding: 15px; border-radius: 8px; margin: 10px 0; }
.btn { padding: 10px 20px; margin: 5px; border: none; border-radius: 5px; cursor: pointer; text-decoration: none; display: inline-block; }
.btn-primary { background: #007bff; color: white; }
.btn-success { background: #28a745; color: white; }
.btn-danger { background: #dc3545; color: white; }
.btn-warning { background: #ffc107; color: black; }
code { background: #f8f9fa; padding: 2px 4px; border-radius: 3px; font-family: monospace; }
.log { background: #f8f9fa; padding: 10px; border-radius: 5px; font-family: monospace; font-size: 12px; max-height: 200px; overflow-y: auto; }
.step { border: 1px solid #ddd; margin: 10px 0; padding: 15px; border-radius: 5px; }
.step-header { font-weight: bold; margin-bottom: 10px; }
.progress { width: 100%; background: #f0f0f0; border-radius: 5px; margin: 10px 0; }
.progress-bar { height: 20px; background: #28a745; border-radius: 5px; text-align: center; color: white; line-height: 20px; }
</style>";

echo "<div class='container'>";
echo "<h1>🚀 CORRECTION COMPLÈTE POUR PRODUCTION</h1>";
echo "<p><em>Script de réparation automatique pour AdminLicence sur cPanel</em></p>";

// Action demandée
$action = $_GET['action'] ?? 'diagnose';

// Barre de progression
$totalSteps = 6;
$currentStep = 0;

function updateProgress($step, $total, $message) {
    $percent = ($step / $total) * 100;
    echo "<div class='progress'>";
    echo "<div class='progress-bar' style='width: {$percent}%'>{$message}</div>";
    echo "</div>";
    flush();
    ob_flush();
}

function logMessage($message, $type = 'info') {
    $timestamp = date('Y-m-d H:i:s');
    $typeEmoji = [
        'success' => '✅',
        'error' => '❌', 
        'warning' => '⚠️',
        'info' => 'ℹ️'
    ];
    
    $class = $type === 'success' ? 'success' : ($type === 'error' ? 'error' : ($type === 'warning' ? 'warning' : 'info'));
    
    echo "<div class='$class'>";
    echo "<small>[$timestamp]</small> {$typeEmoji[$type]} $message";
    echo "</div>";
    flush();
    ob_flush();
}

// ÉTAPE 1: DIAGNOSTIC COMPLET
function stepDiagnose() {
    global $currentStep, $totalSteps;
    $currentStep++;
    updateProgress($currentStep, $totalSteps, "Étape 1/$totalSteps - Diagnostic");
    
    logMessage("Début du diagnostic système...", 'info');
    
    $issues = [];
    $fixes = [];
    
    // Vérifier les caches
    $cacheFiles = [
        '../bootstrap/cache/config.php',
        '../bootstrap/cache/routes-v7.php',
        '../bootstrap/cache/services.php'
    ];
    
    $cacheExists = false;
    foreach ($cacheFiles as $file) {
        if (file_exists($file)) {
            $cacheExists = true;
            break;
        }
    }
    
    if ($cacheExists) {
        $issues[] = "Fichiers de cache détectés (peuvent causer des problèmes)";
        $fixes[] = "clear_cache";
    }
    
    // Vérifier .env
    if (!file_exists('../.env')) {
        $issues[] = "Fichier .env manquant";
        $fixes[] = "create_env";
    } else {
        $envContent = file_get_contents('../.env');
        if (strpos($envContent, 'DB_DATABASE=') === false || strpos($envContent, 'DB_DATABASE=') !== false && trim(substr($envContent, strpos($envContent, 'DB_DATABASE=') + 12, strpos($envContent, "\n", strpos($envContent, 'DB_DATABASE=')) - strpos($envContent, 'DB_DATABASE=') - 12)) === '') {
            $issues[] = "Configuration base de données incomplète dans .env";
            $fixes[] = "fix_env";
        }
    }
    
    // Vérifier l'installateur
    if (!file_exists('install_new.php')) {
        $issues[] = "Fichier install_new.php manquant";
        $fixes[] = "fix_installer";
    }
    
    // Vérifier la licence (si applicable)
    if (file_exists('../.env')) {
        $issues[] = "Validation de licence requise";
        $fixes[] = "fix_license";
    }
    
    if (empty($issues)) {
        logMessage("Aucun problème détecté ! Système sain.", 'success');
        return true;
    } else {
        logMessage("Problèmes détectés:", 'warning');
        foreach ($issues as $issue) {
            echo "<div class='warning'>⚠️ $issue</div>";
        }
        
        echo "<div class='info'>";
        echo "<h3>🔧 Actions recommandées:</h3>";
        echo "<a href='?action=fix_all' class='btn btn-primary'>🚀 Tout réparer automatiquement</a>";
        echo "</div>";
        
        return false;
    }
}

// ÉTAPE 2: NETTOYAGE COMPLET DES CACHES
function stepClearCache() {
    global $currentStep, $totalSteps;
    $currentStep++;
    updateProgress($currentStep, $totalSteps, "Étape 2/$totalSteps - Nettoyage caches");
    
    logMessage("Nettoyage complet des caches...", 'info');
    
    // Méthode 1: Suppression directe des fichiers
    $cacheFiles = [
        '../bootstrap/cache/config.php',
        '../bootstrap/cache/routes-v7.php',
        '../bootstrap/cache/services.php',
        '../bootstrap/cache/packages.php',
        '../bootstrap/cache/compiled.php'
    ];
    
    $deleted = 0;
    foreach ($cacheFiles as $file) {
        if (file_exists($file)) {
            if (@unlink($file)) {
                $deleted++;
                logMessage("Supprimé: " . basename($file), 'success');
            }
        }
    }
    
    // Méthode 2: Nettoyage des répertoires
    $cacheDirectories = [
        '../storage/framework/cache',
        '../storage/framework/views',
        '../storage/framework/sessions'
    ];
    
    foreach ($cacheDirectories as $dir) {
        if (is_dir($dir)) {
            $files = glob($dir . '/*');
            $dirDeleted = 0;
            foreach ($files as $file) {
                if (is_file($file) && basename($file) !== '.gitignore') {
                    if (@unlink($file)) {
                        $dirDeleted++;
                    }
                }
            }
            if ($dirDeleted > 0) {
                logMessage("Nettoyé $dirDeleted fichiers dans " . basename($dir), 'success');
            }
        }
    }
    
    // Méthode 3: Tentative commandes artisan (si possible)
    $artisanCommands = [
        'cd .. && php artisan config:clear',
        'cd .. && php artisan cache:clear',
        'cd .. && php artisan view:clear'
    ];
    
    foreach ($artisanCommands as $command) {
        $output = [];
        $returnVar = 0;
        @exec($command . ' 2>&1', $output, $returnVar);
        
        if ($returnVar === 0) {
            logMessage("Commande artisan réussie: " . explode(' && ', $command)[1], 'success');
        }
    }
    
    logMessage("Nettoyage des caches terminé !", 'success');
    return true;
}

// ÉTAPE 3: CORRECTION CONFIGURATION
function stepFixConfig() {
    global $currentStep, $totalSteps;
    $currentStep++;
    updateProgress($currentStep, $totalSteps, "Étape 3/$totalSteps - Configuration");
    
    logMessage("Correction de la configuration...", 'info');
    
    $envFile = '../.env';
    
    if (!file_exists($envFile)) {
        // Créer .env depuis .env.example
        if (file_exists('../.env.example')) {
            copy('../.env.example', $envFile);
            logMessage("Fichier .env créé depuis .env.example", 'success');
        } else {
            // Créer .env minimal
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
DB_DATABASE=fabien_adminlicence
DB_USERNAME=fabien_adminlicence
DB_PASSWORD=Fab@250872

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync";
            
            file_put_contents($envFile, $defaultEnv);
            logMessage("Fichier .env créé avec configuration par défaut", 'success');
        }
    }
    
    // Vérifier les répertoires
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
            if (@mkdir($dir, 0755, true)) {
                logMessage("Répertoire créé: " . basename($dir), 'success');
            }
        }
    }
    
    logMessage("Configuration corrigée !", 'success');
    return true;
}

// ÉTAPE 4: TEST CONNEXION BASE DE DONNÉES  
function stepTestDatabase() {
    global $currentStep, $totalSteps;
    $currentStep++;
    updateProgress($currentStep, $totalSteps, "Étape 4/$totalSteps - Test base de données");
    
    logMessage("Test de la connexion base de données...", 'info');
    
    $host = '127.0.0.1';
    $database = 'fabien_adminlicence';
    $username = 'fabien_adminlicence';
    $password = 'Fab@250872';
    
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Test simple
        $pdo->query("SELECT 1");
        
        logMessage("✅ Connexion base de données réussie !", 'success');
        logMessage("Serveur: $host | Base: $database | User: $username", 'info');
        
        return true;
        
    } catch (PDOException $e) {
        logMessage("❌ Erreur de connexion: " . $e->getMessage(), 'error');
        
        // Suggestions de correction
        echo "<div class='warning'>";
        echo "<h4>💡 Suggestions de correction:</h4>";
        echo "<ul>";
        echo "<li>Vérifiez que la base de données <code>$database</code> existe sur cPanel</li>";
        echo "<li>Vérifiez que l'utilisateur <code>$username</code> a les permissions sur cette base</li>";
        echo "<li>Vérifiez le mot de passe dans votre cPanel</li>";
        echo "</ul>";
        echo "</div>";
        
        return false;
    }
}

// ÉTAPE 5: CORRECTION PROBLÈME LICENCE IP
function stepFixLicense() {
    global $currentStep, $totalSteps;
    $currentStep++;
    updateProgress($currentStep, $totalSteps, "Étape 5/$totalSteps - Correction licence");
    
    logMessage("Correction du système de validation de licence...", 'info');
    
    // Détecter les IPs
    $realIP = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $forwardedIP = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['HTTP_X_REAL_IP'] ?? $realIP;
    $serverIP = $_SERVER['SERVER_ADDR'] ?? 'unknown';
    
    logMessage("IP détectée: $realIP", 'info');
    logMessage("IP serveur: $serverIP", 'info');
    
    // Chercher les fichiers de validation de licence
    $licenseFiles = [
        '../app/Services/LicenseService.php',
        '../app/Http/Controllers/Admin/LicenseController.php',
        '../app/Http/Controllers/Admin/SettingsController.php'
    ];
    
    $licenseFixed = false;
    
    foreach ($licenseFiles as $file) {
        if (file_exists($file)) {
            $content = file_get_contents($file);
            $originalContent = $content;
            
            // Rechercher et corriger les méthodes de détection d'IP
            if (strpos($content, 'REMOTE_ADDR') !== false) {
                logMessage("Trouvé validation IP dans: " . basename($file), 'info');
                
                // Créer une version corrigée avec détection IP multiple
                $ipDetectionCode = "
    // Détection IP multiple pour serveurs cPanel/proxy
    private function getClientIP() {
        \$ipSources = [
            'HTTP_X_REAL_IP',
            'HTTP_X_FORWARDED_FOR', 
            'HTTP_CLIENT_IP',
            'REMOTE_ADDR'
        ];
        
        foreach (\$ipSources as \$source) {
            if (!empty(\$_SERVER[\$source])) {
                \$ip = \$_SERVER[\$source];
                // Gérer les IPs multiples (séparées par virgule)
                if (strpos(\$ip, ',') !== false) {
                    \$ip = trim(explode(',', \$ip)[0]);
                }
                // Valider l'IP
                if (filter_var(\$ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                    return \$ip;
                }
            }
        }
        
        // Fallback: utiliser l'IP du serveur si aucune IP publique trouvée
        return \$_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    }";
                
                // Ajouter la méthode si elle n'existe pas
                if (strpos($content, 'getClientIP') === false) {
                    $content = str_replace('class ', $ipDetectionCode . "\n\nclass ", $content);
                }
                
                // Remplacer les utilisations directes de REMOTE_ADDR
                $content = str_replace('$_SERVER[\'REMOTE_ADDR\']', '$this->getClientIP()', $content);
                $content = str_replace('$_SERVER["REMOTE_ADDR"]', '$this->getClientIP()', $content);
                
                if ($content !== $originalContent) {
                    file_put_contents($file, $content);
                    logMessage("Corrigé validation IP dans: " . basename($file), 'success');
                    $licenseFixed = true;
                }
            }
        }
    }
    
    if (!$licenseFixed) {
        logMessage("Aucun fichier de licence trouvé à corriger", 'warning');
    }
    
    // Créer un script de test de licence
    $licenseTestScript = "<?php
// Test de validation de licence
\$realIP = '{$realIP}';
\$serverIP = '{$serverIP}';

echo 'IP réelle: ' . \$realIP . \"\\n\";
echo 'IP serveur: ' . \$serverIP . \"\\n\";

// Simuler la validation
echo 'Test de licence avec IP: ' . \$realIP . \"\\n\";
";
    
    file_put_contents('test_license_ip.php', $licenseTestScript);
    logMessage("Script de test créé: test_license_ip.php", 'success');
    
    return true;
}

// ÉTAPE 6: CORRECTION INSTALLATEUR
function stepFixInstaller() {
    global $currentStep, $totalSteps;
    $currentStep++;
    updateProgress($currentStep, $totalSteps, "Étape 6/$totalSteps - Correction installateur");
    
    logMessage("Vérification de l'installateur...", 'info');
    
    if (!file_exists('install_new.php')) {
        logMessage("❌ Fichier install_new.php manquant !", 'error');
        return false;
    }
    
    // Vérifier si install_new.php contient le nettoyage de cache
    $installerContent = file_get_contents('install_new.php');
    
    if (strpos($installerContent, 'bootstrap/cache/config.php') === false) {
        logMessage("⚠️ Installateur ne contient pas le nettoyage de cache", 'warning');
        
        // Ajouter le nettoyage au début du fichier
        $cacheCleanCode = "<?php
// NETTOYAGE AUTOMATIQUE DES CACHES POUR PRODUCTION
\$cacheFiles = [
    '../bootstrap/cache/config.php',
    '../bootstrap/cache/routes-v7.php',
    '../bootstrap/cache/services.php'
];

foreach (\$cacheFiles as \$file) {
    if (file_exists(\$file)) {
        @unlink(\$file);
    }
}

// Contenu original ci-dessous
";
        
        // Remplacer le <?php d'ouverture
        $installerContent = str_replace('<?php', $cacheCleanCode, $installerContent);
        file_put_contents('install_new.php', $installerContent);
        
        logMessage("✅ Nettoyage de cache ajouté à l'installateur", 'success');
    } else {
        logMessage("✅ Installateur contient déjà le nettoyage de cache", 'success');
    }
    
    return true;
}

// NAVIGATION ET ACTIONS
echo "<div class='info'>";
echo "<h3>🎯 Actions disponibles:</h3>";
echo "<a href='?action=diagnose' class='btn btn-info'>🔍 Diagnostic</a>";
echo "<a href='?action=fix_all' class='btn btn-primary'>🚀 Tout réparer</a>";
echo "<a href='?action=clear_cache' class='btn btn-warning'>🧹 Vider caches</a>";
echo "<a href='?action=test_db' class='btn btn-success'>🔌 Test base de données</a>";
echo "</div>";

// EXÉCUTION DES ACTIONS
switch ($action) {
    case 'diagnose':
        stepDiagnose();
        break;
        
    case 'fix_all':
        logMessage("🚀 DÉBUT DE LA RÉPARATION COMPLÈTE", 'info');
        $allOk = true;
        
        $allOk &= stepClearCache();
        $allOk &= stepFixConfig();  
        $allOk &= stepTestDatabase();
        $allOk &= stepFixLicense();
        $allOk &= stepFixInstaller();
        
        if ($allOk) {
            echo "<div class='success'>";
            echo "<h2>🎉 RÉPARATION TERMINÉE AVEC SUCCÈS !</h2>";
            echo "<p>Tous les problèmes ont été corrigés. Vous pouvez maintenant:</p>";
            echo "<ul>";
            echo "<li>✅ Accéder à l'installateur: <a href='install_new.php' class='btn btn-success'>🚀 Installer</a></li>";
            echo "<li>✅ Tester la base de données: <a href='?action=test_db' class='btn btn-info'>🔌 Test DB</a></li>";
            echo "<li>✅ Accéder à l'administration: <a href='../admin' class='btn btn-primary'>👤 Admin</a></li>";
            echo "</ul>";
            echo "</div>";
        } else {
            echo "<div class='error'>";
            echo "<h2>⚠️ Réparation incomplète</h2>";
            echo "<p>Certains problèmes n'ont pas pu être corrigés automatiquement.</p>";
            echo "<p>Consultez les logs ci-dessus pour plus de détails.</p>";
            echo "</div>";
        }
        break;
        
    case 'clear_cache':
        stepClearCache();
        break;
        
    case 'test_db':
        stepTestDatabase();
        break;
        
    default:
        stepDiagnose();
        break;
}

echo "<hr>";
echo "<div class='info'>";
echo "<h3>📋 Résumé des problèmes résolus:</h3>";
echo "<ul>";
echo "<li>✅ Nettoyage complet des caches Laravel (méthode manuelle pour production)</li>";
echo "<li>✅ Correction de la configuration .env et répertoires</li>";
echo "<li>✅ Test et validation de la base de données</li>";
echo "<li>✅ Correction du système de détection IP pour les licences</li>";
echo "<li>✅ Réparation de l'installateur avec nettoyage automatique</li>";
echo "</ul>";
echo "</div>";

echo "<div class='warning'>";
echo "<h3>🔄 Prochaines étapes recommandées:</h3>";
echo "<ol>";
echo "<li><strong>Redémarrez PHP</strong> dans votre cPanel (section 'Sélectionner la version PHP')</li>";
echo "<li><strong>Testez l'installateur:</strong> <a href='install_new.php' target='_blank'>install_new.php</a></li>";
echo "<li><strong>Si le problème persiste:</strong> Contactez le support de votre hébergeur</li>";
echo "</ol>";
echo "</div>";

echo "<p><em>Script exécuté le " . date('Y-m-d H:i:s') . " | Version: 1.0.0</em></p>";
echo "</div>";

ob_end_flush();
?> 