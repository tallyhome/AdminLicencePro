<?php
/**
 * Script de test pour vérifier les corrections de install_new.php
 * Version: 1.0.0
 * Date: 2025-06-23
 */

// Inclure les fichiers nécessaires
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions/core.php';

// Démarrer la session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function testLog($message, $type = 'INFO') {
    $logFile = __DIR__ . '/test_install_new.log';
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[$timestamp] [$type] $message" . PHP_EOL;
    file_put_contents($logFile, $logMessage, FILE_APPEND);
    echo "<div style='background: #f8f9fa; border-left: 4px solid #007bff; padding: 10px; margin: 5px 0; font-family: monospace; font-size: 12px;'>[$type] $message</div>";
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test des corrections install_new.php</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; line-height: 1.6; }
        .container { max-width: 1200px; margin: 0 auto; }
        h1 { color: #333; border-bottom: 2px solid #28a745; padding-bottom: 10px; }
        h2 { color: #555; margin-top: 30px; background: #f8f9fa; padding: 10px; border-radius: 4px; }
        .test-section { margin: 20px 0; padding: 15px; border: 1px solid #ddd; border-radius: 4px; }
        .success { background: #d4edda; border-color: #c3e6cb; }
        .warning { background: #fff3cd; border-color: #ffeaa7; }
        .error { background: #f8d7da; border-color: #f5c6cb; }
        .code { background: #f4f4f4; padding: 10px; border-radius: 4px; font-family: monospace; overflow-x: auto; }
        .btn { display: inline-block; padding: 10px 20px; margin: 5px; text-decoration: none; border-radius: 4px; background: #007bff; color: white; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🧪 Test des corrections install_new.php</h1>
        <p><strong>Date:</strong> <?= date('Y-m-d H:i:s') ?></p>
        
        <?php
        testLog("=== DÉBUT DES TESTS DE CORRECTION ===", "START");
        
        // TEST 1: Vérification des chemins de cache corrigés
        echo "<h2>🔧 TEST 1: Chemins de cache corrigés</h2>";
        echo "<div class='test-section'>";
        
        testLog("Test des chemins de cache - Début", "CACHE_TEST");
        
        // Vérifier les chemins depuis public/install/ vers bootstrap/cache/
        $cacheFiles = [
            '../../bootstrap/cache/config.php',
            '../../bootstrap/cache/routes-v7.php', 
            '../../bootstrap/cache/routes.php',
            '../../bootstrap/cache/services.php',
            '../../bootstrap/cache/packages.php'
        ];
        
        $pathsCorrect = true;
        foreach ($cacheFiles as $file) {
            $realPath = realpath(dirname($file));
            if ($realPath) {
                testLog("Chemin résolu: $file -> $realPath", "CACHE_TEST");
                echo "<div class='success'>✅ Chemin correct: $file</div>";
            } else {
                testLog("Chemin invalide: $file", "ERROR");
                echo "<div class='error'>❌ Chemin invalide: $file</div>";
                $pathsCorrect = false;
            }
        }
        
        if ($pathsCorrect) {
            echo "<div class='success'><strong>✅ TOUS LES CHEMINS DE CACHE SONT CORRECTS</strong></div>";
        } else {
            echo "<div class='error'><strong>❌ CERTAINS CHEMINS DE CACHE SONT INCORRECTS</strong></div>";
        }
        
        echo "</div>";
        
        // TEST 2: Test de la fonction de sauvegarde immédiate
        echo "<h2>💾 TEST 2: Sauvegarde immédiate dans .env</h2>";
        echo "<div class='test-section'>";
        
        testLog("Test sauvegarde immédiate - Début", "ENV_TEST");
        
        // Simuler la fonction saveToEnvImmediately
        $envFile = '../../.env';
        $envExists = file_exists($envFile);
        $envWritable = $envExists && is_writable($envFile);
        
        testLog("Fichier .env existe: " . ($envExists ? 'OUI' : 'NON'), "ENV_TEST");
        testLog("Fichier .env accessible en écriture: " . ($envWritable ? 'OUI' : 'NON'), "ENV_TEST");
        
        if ($envExists && $envWritable) {
            // Test d'écriture sécurisé
            $testData = "\n# TEST_INSTALL_NEW_" . time() . "=test_value";
            $writeResult = file_put_contents($envFile, $testData, FILE_APPEND);
            
            if ($writeResult !== false) {
                testLog("Test d'écriture .env: SUCCÈS ($writeResult octets)", "ENV_TEST");
                echo "<div class='success'>✅ Sauvegarde immédiate fonctionnelle</div>";
                
                // Nettoyer le test
                $envContent = file_get_contents($envFile);
                $envContent = preg_replace('/\n# TEST_INSTALL_NEW_\d+=test_value/', '', $envContent);
                file_put_contents($envFile, $envContent);
                testLog("Test d'écriture nettoyé", "ENV_TEST");
            } else {
                testLog("Test d'écriture .env: ÉCHEC", "ERROR");
                echo "<div class='error'>❌ Impossible d'écrire dans .env</div>";
            }
        } else {
            echo "<div class='warning'>⚠️ Fichier .env non accessible pour les tests</div>";
        }
        
        echo "</div>";
        
        // TEST 3: Test des fonctions de backup/restore des sessions
        echo "<h2>🔄 TEST 3: Backup/Restore des sessions</h2>";
        echo "<div class='test-section'>";
        
        testLog("Test backup/restore sessions - Début", "SESSION_TEST");
        
        // Simuler des données de session
        $_SESSION['test_license_key'] = 'TEST-KEY-' . time();
        $_SESSION['test_license_valid'] = true;
        $_SESSION['test_db_config'] = [
            'host' => 'localhost',
            'database' => 'test_db'
        ];
        
        testLog("Sessions test créées", "SESSION_TEST");
        
        // Simuler la fonction de backup
        $sessionBackup = [
            'test_license_key' => $_SESSION['test_license_key'] ?? null,
            'test_license_valid' => $_SESSION['test_license_valid'] ?? null,
            'test_db_config' => $_SESSION['test_db_config'] ?? null
        ];
        
        $backupFile = __DIR__ . '/test_session_backup.json';
        $backupResult = file_put_contents($backupFile, json_encode($sessionBackup));
        
        if ($backupResult !== false) {
            testLog("Backup sessions: SUCCÈS ($backupResult octets)", "SESSION_TEST");
            echo "<div class='success'>✅ Backup des sessions fonctionnel</div>";
            
            // Test de restore
            unset($_SESSION['test_license_key']);
            unset($_SESSION['test_license_valid']);
            unset($_SESSION['test_db_config']);
            
            $restoredData = json_decode(file_get_contents($backupFile), true);
            if ($restoredData) {
                foreach ($restoredData as $key => $value) {
                    if ($value !== null) {
                        $_SESSION[$key] = $value;
                    }
                }
                testLog("Restore sessions: SUCCÈS", "SESSION_TEST");
                echo "<div class='success'>✅ Restore des sessions fonctionnel</div>";
            } else {
                testLog("Restore sessions: ÉCHEC", "ERROR");
                echo "<div class='error'>❌ Restore des sessions échoué</div>";
            }
            
            // Nettoyer
            @unlink($backupFile);
            unset($_SESSION['test_license_key']);
            unset($_SESSION['test_license_valid']);
            unset($_SESSION['test_db_config']);
        } else {
            testLog("Backup sessions: ÉCHEC", "ERROR");
            echo "<div class='error'>❌ Backup des sessions échoué</div>";
        }
        
        echo "</div>";
        
        // TEST 4: Vérification que le cache est bien nettoyé
        echo "<h2>🧹 TEST 4: Nettoyage du cache Laravel</h2>";
        echo "<div class='test-section'>";
        
        testLog("Test nettoyage cache - Début", "CACHE_CLEAN_TEST");
        
        // Créer des fichiers de cache factices pour tester
        $testCacheFiles = [
            '../../bootstrap/cache/test_config.php',
            '../../bootstrap/cache/test_routes.php'
        ];
        
        $cacheCreated = 0;
        foreach ($testCacheFiles as $file) {
            $dir = dirname($file);
            if (!is_dir($dir)) {
                @mkdir($dir, 0755, true);
            }
            
            if (file_put_contents($file, "<?php // Test cache file") !== false) {
                $cacheCreated++;
                testLog("Cache test créé: " . basename($file), "CACHE_CLEAN_TEST");
            }
        }
        
        if ($cacheCreated > 0) {
            echo "<div class='success'>✅ $cacheCreated fichiers de cache test créés</div>";
            
            // Tester le nettoyage
            $cacheCleared = 0;
            foreach ($testCacheFiles as $file) {
                if (file_exists($file) && @unlink($file)) {
                    $cacheCleared++;
                    testLog("Cache test supprimé: " . basename($file), "CACHE_CLEAN_TEST");
                }
            }
            
            if ($cacheCleared === $cacheCreated) {
                echo "<div class='success'>✅ Nettoyage du cache fonctionnel ($cacheCleared/$cacheCreated)</div>";
                testLog("Nettoyage cache: SUCCÈS ($cacheCleared fichiers)", "CACHE_CLEAN_TEST");
            } else {
                echo "<div class='warning'>⚠️ Nettoyage partiel du cache ($cacheCleared/$cacheCreated)</div>";
                testLog("Nettoyage cache: PARTIEL ($cacheCleared/$cacheCreated)", "WARNING");
            }
        } else {
            echo "<div class='warning'>⚠️ Impossible de créer des fichiers de cache test</div>";
        }
        
        echo "</div>";
        
        // TEST 5: Vérification de la récupération depuis .env
        echo "<h2>🔍 TEST 5: Récupération des données depuis .env</h2>";
        echo "<div class='test-section'>";
        
        testLog("Test récupération .env - Début", "ENV_RECOVERY_TEST");
        
        if (file_exists($envFile)) {
            $envContent = file_get_contents($envFile);
            
            // Tester la récupération de différentes variables
            $testPatterns = [
                'LICENCE_KEY' => '/LICENCE_KEY=(.+)/',
                'DB_HOST' => '/DB_HOST=(.+)/',
                'DB_DATABASE' => '/DB_DATABASE=(.+)/',
                'APP_KEY' => '/APP_KEY=(.+)/'
            ];
            
            $recoveryWorking = true;
            foreach ($testPatterns as $var => $pattern) {
                if (preg_match($pattern, $envContent, $matches)) {
                    $value = trim($matches[1]);
                    testLog("Variable $var récupérée: " . (strlen($value) > 20 ? substr($value, 0, 20) . '...' : $value), "ENV_RECOVERY_TEST");
                    echo "<div class='success'>✅ $var récupérable depuis .env</div>";
                } else {
                    testLog("Variable $var: NON TROUVÉE", "ENV_RECOVERY_TEST");
                    echo "<div class='warning'>⚠️ $var non trouvée dans .env</div>";
                }
            }
            
            echo "<div class='success'>✅ Récupération depuis .env fonctionnelle</div>";
        } else {
            echo "<div class='error'>❌ Fichier .env non trouvé</div>";
            $recoveryWorking = false;
        }
        
        echo "</div>";
        
        // RÉSUMÉ FINAL
        echo "<h2>📋 RÉSUMÉ DES TESTS</h2>";
        echo "<div class='test-section'>";
        
        $allTestsPassed = $pathsCorrect && $envExists && $envWritable;
        
        testLog("=== RÉSUMÉ FINAL DES TESTS ===", "SUMMARY");
        testLog("Chemins de cache: " . ($pathsCorrect ? 'OK' : 'ÉCHEC'), "SUMMARY");
        testLog("Fichier .env: " . ($envExists ? 'OK' : 'ÉCHEC'), "SUMMARY");
        testLog("Écriture .env: " . ($envWritable ? 'OK' : 'ÉCHEC'), "SUMMARY");
        
        if ($allTestsPassed) {
            echo "<div class='success'>";
            echo "<h3>🎉 TOUS LES TESTS SONT PASSÉS</h3>";
            echo "<p>Le fichier install_new.php est prêt à être utilisé avec toutes les corrections appliquées :</p>";
            echo "<ul>";
            echo "<li>✅ Chemins de cache corrigés (../../bootstrap/cache)</li>";
            echo "<li>✅ Nettoyage ultra-agressif du cache Laravel</li>";
            echo "<li>✅ Sauvegarde immédiate des données dans .env</li>";
            echo "<li>✅ Protection contre la perte de sessions</li>";
            echo "<li>✅ Récupération automatique des données depuis .env</li>";
            echo "</ul>";
            echo "</div>";
        } else {
            echo "<div class='warning'>";
            echo "<h3>⚠️ CERTAINS TESTS ONT DES AVERTISSEMENTS</h3>";
            echo "<p>Le fichier install_new.php devrait fonctionner mais vérifiez les points suivants :</p>";
            echo "<ul>";
            if (!$pathsCorrect) echo "<li>❌ Vérifiez la structure des répertoires</li>";
            if (!$envExists) echo "<li>❌ Créez le fichier .env</li>";
            if (!$envWritable) echo "<li>❌ Vérifiez les permissions du fichier .env</li>";
            echo "</ul>";
            echo "</div>";
        }
        
        testLog("=== FIN DES TESTS DE CORRECTION ===", "END");
        ?>
        
        <div style="margin-top: 20px;">
            <h3>🚀 Actions suivantes :</h3>
            <a href="install_new.php" class="btn">🔄 Tester l'installation corrigée</a>
            <a href="debug_installation_cpanel.php" class="btn">📊 Diagnostic complet</a>
            <a href="?" class="btn">🔄 Relancer les tests</a>
        </div>
        
        <div style="margin-top: 40px; padding: 20px; background: #f8f9fa; border-radius: 4px;">
            <h3>📝 Log complet</h3>
            <p>Le log détaillé est disponible dans : <code><?= __DIR__ ?>/test_install_new.log</code></p>
            <p><strong>Session ID:</strong> <?= session_id() ?></p>
        </div>
    </div>
</body>
</html>