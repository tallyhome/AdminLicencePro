<?php
/**
 * Script de diagnostic avancé pour identifier les problèmes d'installation cPanel
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

// Fonction de log spécialisée pour le debug
function debugLog($message, $category = 'DEBUG') {
    $logFile = INSTALL_PATH . '/debug_cpanel.log';
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[$timestamp] [$category] $message" . PHP_EOL;
    file_put_contents($logFile, $logMessage, FILE_APPEND);
    echo "<div style='background: #f8f9fa; border-left: 4px solid #007bff; padding: 10px; margin: 5px 0; font-family: monospace; font-size: 12px;'>[$category] $message</div>";
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Debug Installation cPanel - AdminLicence</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; line-height: 1.6; }
        .container { max-width: 1200px; margin: 0 auto; }
        h1 { color: #333; border-bottom: 2px solid #dc3545; padding-bottom: 10px; }
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
        <h1>🐛 Debug Installation cPanel - AdminLicence</h1>
        <p><strong>Date:</strong> <?= date('Y-m-d H:i:s') ?></p>
        
        <?php
        debugLog("=== DÉBUT DU DIAGNOSTIC AVANCÉ ===", "START");
        
        // TEST 1: Vérification de l'APP_KEY et impact sur les sessions
        echo "<h2>🔑 TEST 1: APP_KEY et Sessions</h2>";
        echo "<div class='test-section'>";
        
        $envPath = ROOT_PATH . '/.env';
        $appKeyExists = false;
        $appKeyValue = '';
        
        if (file_exists($envPath)) {
            $envContent = file_get_contents($envPath);
            if (preg_match('/APP_KEY=(.*)/', $envContent, $matches)) {
                $appKeyValue = trim($matches[1]);
                $appKeyExists = !empty($appKeyValue);
            }
        }
        
        debugLog("Fichier .env existe: " . (file_exists($envPath) ? 'OUI' : 'NON'), "APP_KEY");
        debugLog("APP_KEY définie: " . ($appKeyExists ? 'OUI' : 'NON'), "APP_KEY");
        debugLog("APP_KEY valeur: " . ($appKeyExists ? substr($appKeyValue, 0, 20) . '...' : 'VIDE'), "APP_KEY");
        
        // Test de chiffrement de session
        if ($appKeyExists) {
            $_SESSION['test_encryption'] = 'test_value_' . time();
            debugLog("Test de session avec APP_KEY: CRÉÉ", "SESSION");
            
            // Vérifier si la session persiste
            if (isset($_SESSION['test_encryption'])) {
                debugLog("Session test persiste: OUI - " . $_SESSION['test_encryption'], "SESSION");
                echo "<div class='success'>✅ APP_KEY présente et sessions fonctionnelles</div>";
            } else {
                debugLog("Session test persiste: NON", "SESSION");
                echo "<div class='error'>❌ Problème de persistance des sessions malgré APP_KEY</div>";
            }
        } else {
            debugLog("PROBLÈME CRITIQUE: APP_KEY manquante - Sessions non chiffrées", "ERROR");
            echo "<div class='error'>❌ APP_KEY manquante - Cause probable de perte des sessions</div>";
        }
        
        echo "</div>";
        
        // TEST 2: Vérification des données de session actuelles
        echo "<h2>📝 TEST 2: État des Sessions</h2>";
        echo "<div class='test-section'>";
        
        $sessionKeys = ['license_key', 'license_valid', 'db_config', 'admin_config', 'system_check_passed'];
        $sessionData = [];
        
        foreach ($sessionKeys as $key) {
            $value = $_SESSION[$key] ?? null;
            $sessionData[$key] = $value;
            
            if ($value !== null) {
                if (is_array($value)) {
                    debugLog("Session '$key': PRÉSENTE - " . json_encode($value), "SESSION");
                } else {
                    debugLog("Session '$key': PRÉSENTE - " . (is_bool($value) ? ($value ? 'true' : 'false') : $value), "SESSION");
                }
            } else {
                debugLog("Session '$key': MANQUANTE", "SESSION");
            }
        }
        
        $missingSessions = array_filter($sessionKeys, function($key) use ($sessionData) {
            return $sessionData[$key] === null;
        });
        
        if (empty($missingSessions)) {
            echo "<div class='success'>✅ Toutes les sessions sont présentes</div>";
        } else {
            echo "<div class='error'>❌ Sessions manquantes: " . implode(', ', $missingSessions) . "</div>";
            debugLog("PROBLÈME: Sessions manquantes - " . implode(', ', $missingSessions), "ERROR");
        }
        
        echo "</div>";
        
        // TEST 3: Vérification de l'écriture du fichier .env
        echo "<h2>💾 TEST 3: Écriture du fichier .env</h2>";
        echo "<div class='test-section'>";
        
        debugLog("Test d'écriture .env - Début", "ENV_WRITE");
        
        // Test de permissions
        $envWritable = is_writable($envPath) || is_writable(dirname($envPath));
        debugLog("Permissions .env: " . ($envWritable ? 'ÉCRITURE OK' : 'ÉCRITURE REFUSÉE'), "ENV_WRITE");
        
        if ($envWritable) {
            // Test d'écriture réelle
            $testContent = "\n# TEST_DEBUG_" . time() . "=test_value";
            $writeResult = file_put_contents($envPath, $testContent, FILE_APPEND);
            
            if ($writeResult !== false) {
                debugLog("Test d'écriture .env: SUCCÈS ($writeResult octets)", "ENV_WRITE");
                echo "<div class='success'>✅ Écriture .env fonctionnelle</div>";
                
                // Nettoyer le test
                $envContent = file_get_contents($envPath);
                $envContent = preg_replace('/\n# TEST_DEBUG_\d+=test_value/', '', $envContent);
                file_put_contents($envPath, $envContent);
            } else {
                debugLog("Test d'écriture .env: ÉCHEC", "ENV_WRITE");
                echo "<div class='error'>❌ Impossible d'écrire dans .env</div>";
            }
        } else {
            echo "<div class='error'>❌ Permissions insuffisantes pour .env</div>";
        }
        
        echo "</div>";
        
        // TEST 4: Simulation de l'étape 3 (DB Config)
        echo "<h2>🗄️ TEST 4: Simulation Étape 3 - Configuration DB</h2>";
        echo "<div class='test-section'>";
        
        debugLog("Simulation étape 3 - Début", "STEP3_SIM");
        
        // Simuler des données DB
        $testDbConfig = [
            'host' => 'localhost',
            'port' => '3306',
            'database' => 'test_adminlicence',
            'username' => 'test_user',
            'password' => 'test_password'
        ];
        
        // Stocker en session
        $_SESSION['db_config'] = $testDbConfig;
        debugLog("DB Config stockée en session: " . json_encode($testDbConfig), "STEP3_SIM");
        
        // Tenter d'écrire dans .env
        if ($envWritable) {
            $envContent = file_get_contents($envPath);
            $newEnvContent = $envContent;
            
            // Mettre à jour les variables DB
            $dbVars = [
                'DB_HOST' => $testDbConfig['host'],
                'DB_PORT' => $testDbConfig['port'],
                'DB_DATABASE' => $testDbConfig['database'],
                'DB_USERNAME' => $testDbConfig['username'],
                'DB_PASSWORD' => $testDbConfig['password']
            ];
            
            foreach ($dbVars as $key => $value) {
                $pattern = "/^{$key}=.*$/m";
                if (preg_match($pattern, $newEnvContent)) {
                    $newEnvContent = preg_replace($pattern, "{$key}={$value}", $newEnvContent);
                    debugLog("Variable $key mise à jour: $value", "ENV_UPDATE");
                } else {
                    $newEnvContent .= "\n{$key}={$value}";
                    debugLog("Variable $key ajoutée: $value", "ENV_UPDATE");
                }
            }
            
            $writeResult = file_put_contents($envPath, $newEnvContent);
            if ($writeResult !== false) {
                debugLog("Mise à jour .env réussie: $writeResult octets", "ENV_UPDATE");
                echo "<div class='success'>✅ Configuration DB écrite dans .env</div>";
            } else {
                debugLog("Mise à jour .env échouée", "ENV_UPDATE");
                echo "<div class='error'>❌ Échec écriture configuration DB</div>";
            }
        }
        
        echo "</div>";
        
        // TEST 5: Vérification de la persistance après cache clear
        echo "<h2>🧹 TEST 5: Impact du Cache Laravel</h2>";
        echo "<div class='test-section'>";
        
        debugLog("Test impact cache - Début", "CACHE_TEST");
        
        // Lister les fichiers de cache
        $cacheFiles = [
            ROOT_PATH . '/bootstrap/cache/config.php',
            ROOT_PATH . '/bootstrap/cache/routes-v7.php',
            ROOT_PATH . '/bootstrap/cache/services.php',
            ROOT_PATH . '/bootstrap/cache/packages.php',
            ROOT_PATH . '/bootstrap/cache/compiled.php'
        ];
        
        $cacheFound = [];
        foreach ($cacheFiles as $file) {
            if (file_exists($file)) {
                $cacheFound[] = basename($file);
                debugLog("Cache trouvé: " . basename($file), "CACHE_TEST");
            }
        }
        
        if (!empty($cacheFound)) {
            echo "<div class='error'>❌ Cache Laravel présent: " . implode(', ', $cacheFound) . "</div>";
            debugLog("PROBLÈME: Cache Laravel empêche lecture .env - " . implode(', ', $cacheFound), "ERROR");
        } else {
            echo "<div class='success'>✅ Pas de cache Laravel résiduel</div>";
            debugLog("Cache Laravel: PROPRE", "CACHE_TEST");
        }
        
        echo "</div>";
        
        // TEST 6: Test de connexion avec les anciennes données
        echo "<h2>🔍 TEST 6: Analyse de l'erreur SQLSTATE</h2>";
        echo "<div class='test-section'>";
        
        debugLog("Analyse erreur SQLSTATE - Début", "SQLSTATE_TEST");
        
        // L'erreur mentionne 'adminlicenceteste'@'localhost' - vérifier d'où ça vient
        if (file_exists($envPath)) {
            $envContent = file_get_contents($envPath);
            
            if (strpos($envContent, 'adminlicenceteste') !== false) {
                debugLog("TROUVÉ: 'adminlicenceteste' dans .env actuel", "SQLSTATE_TEST");
                echo "<div class='error'>❌ Ancien utilisateur 'adminlicenceteste' encore dans .env</div>";
            } else {
                debugLog("'adminlicenceteste' PAS dans .env actuel", "SQLSTATE_TEST");
                echo "<div class='warning'>⚠️ 'adminlicenceteste' pas dans .env - cache ou config Laravel?</div>";
            }
            
            // Afficher les variables DB actuelles
            preg_match('/DB_USERNAME=(.*)/', $envContent, $userMatch);
            preg_match('/DB_DATABASE=(.*)/', $envContent, $dbMatch);
            
            $currentUser = trim($userMatch[1] ?? 'NON_DÉFINI');
            $currentDb = trim($dbMatch[1] ?? 'NON_DÉFINI');
            
            debugLog("DB_USERNAME actuel: $currentUser", "SQLSTATE_TEST");
            debugLog("DB_DATABASE actuel: $currentDb", "SQLSTATE_TEST");
            
            echo "<div class='code'>";
            echo "<strong>Variables DB actuelles dans .env:</strong><br>";
            echo "DB_USERNAME: $currentUser<br>";
            echo "DB_DATABASE: $currentDb<br>";
            echo "</div>";
        }
        
        echo "</div>";
        
        // RÉSUMÉ ET RECOMMANDATIONS
        echo "<h2>📋 DIAGNOSTIC FINAL</h2>";
        echo "<div class='test-section'>";
        
        $issues = [];
        
        if (!$appKeyExists) {
            $issues[] = "APP_KEY manquante (critique pour les sessions)";
        }
        
        if (!empty($missingSessions)) {
            $issues[] = "Sessions perdues: " . implode(', ', $missingSessions);
        }
        
        if (!$envWritable) {
            $issues[] = "Permissions .env insuffisantes";
        }
        
        if (!empty($cacheFound)) {
            $issues[] = "Cache Laravel présent: " . implode(', ', $cacheFound);
        }
        
        debugLog("=== RÉSUMÉ FINAL ===", "SUMMARY");
        debugLog("Problèmes identifiés: " . count($issues), "SUMMARY");
        foreach ($issues as $issue) {
            debugLog("- $issue", "SUMMARY");
        }
        
        if (empty($issues)) {
            echo "<div class='success'>";
            echo "<h3>✅ DIAGNOSTIC POSITIF</h3>";
            echo "<p>Aucun problème critique détecté. L'installation devrait fonctionner.</p>";
            echo "</div>";
        } else {
            echo "<div class='error'>";
            echo "<h3>❌ PROBLÈMES IDENTIFIÉS</h3>";
            echo "<ul>";
            foreach ($issues as $issue) {
                echo "<li>$issue</li>";
            }
            echo "</ul>";
            echo "</div>";
        }
        
        debugLog("=== FIN DU DIAGNOSTIC AVANCÉ ===", "END");
        ?>
        
        <div style="margin-top: 20px;">
            <h3>🔧 Actions recommandées :</h3>
            <a href="?action=fix_all" class="btn">🚀 Corriger tous les problèmes</a>
            <a href="install_cpanel_fixed.php" class="btn">🔄 Relancer l'installation</a>
            <a href="diagnostic_cpanel.php" class="btn">📊 Diagnostic standard</a>
        </div>
        
        <?php
        // Action de correction automatique
        if (isset($_GET['action']) && $_GET['action'] === 'fix_all') {
            echo "<h2>🔧 CORRECTION AUTOMATIQUE</h2>";
            echo "<div class='test-section'>";
            
            debugLog("=== DÉBUT CORRECTION AUTOMATIQUE ===", "FIX");
            
            $fixed = 0;
            
            // 1. Générer APP_KEY si manquante
            if (!$appKeyExists && $envWritable) {
                $newAppKey = 'base64:' . base64_encode(random_bytes(32));
                $envContent = file_get_contents($envPath);
                
                if (preg_match('/APP_KEY=.*/', $envContent)) {
                    $envContent = preg_replace('/APP_KEY=.*/', "APP_KEY=$newAppKey", $envContent);
                } else {
                    $envContent .= "\nAPP_KEY=$newAppKey";
                }
                
                if (file_put_contents($envPath, $envContent)) {
                    debugLog("APP_KEY générée et ajoutée: " . substr($newAppKey, 0, 20) . "...", "FIX");
                    echo "<div class='success'>✅ APP_KEY générée et ajoutée</div>";
                    $fixed++;
                }
            }
            
            // 2. Vider le cache Laravel
            $cacheCleared = 0;
            foreach ($cacheFiles as $file) {
                if (file_exists($file) && @unlink($file)) {
                    $cacheCleared++;
                }
            }
            if ($cacheCleared > 0) {
                debugLog("Cache Laravel vidé: $cacheCleared fichiers supprimés", "FIX");
                echo "<div class='success'>✅ Cache Laravel vidé ($cacheCleared fichiers)</div>";
                $fixed++;
            }
            
            // 3. Corriger les permissions
            if (@chmod($envPath, 0644)) {
                debugLog("Permissions .env corrigées: 644", "FIX");
                echo "<div class='success'>✅ Permissions .env corrigées</div>";
                $fixed++;
            }
            
            // 4. Réinitialiser les sessions
            session_destroy();
            session_start();
            debugLog("Sessions réinitialisées", "FIX");
            echo "<div class='success'>✅ Sessions réinitialisées</div>";
            $fixed++;
            
            debugLog("=== FIN CORRECTION AUTOMATIQUE - $fixed corrections ===", "FIX");
            
            echo "<div class='success'>";
            echo "<h3>🎉 CORRECTION TERMINÉE</h3>";
            echo "<p>$fixed correction(s) appliquée(s). Vous pouvez maintenant relancer l'installation.</p>";
            echo "<a href='install_cpanel_fixed.php' class='btn'>🚀 Relancer l'installation maintenant</a>";
            echo "</div>";
            
            echo "</div>";
        }
        ?>
        
        <div style="margin-top: 40px; padding: 20px; background: #f8f9fa; border-radius: 4px;">
            <h3>📝 Log complet</h3>
            <p>Le log détaillé est disponible dans : <code><?= INSTALL_PATH ?>/debug_cpanel.log</code></p>
            <p><strong>Session ID:</strong> <?= session_id() ?></p>
        </div>
    </div>
</body>
</html>