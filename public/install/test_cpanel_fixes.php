<?php
/**
 * Script de test pour valider les corrections cPanel
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

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test des corrections cPanel - AdminLicence</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; line-height: 1.6; }
        .container { max-width: 1000px; margin: 0 auto; }
        h1 { color: #333; border-bottom: 2px solid #007bff; padding-bottom: 10px; }
        .test-section { margin: 20px 0; padding: 20px; border: 1px solid #ddd; border-radius: 8px; }
        .test-result { padding: 10px; margin: 10px 0; border-radius: 4px; }
        .success { background: #d4edda; border: 1px solid #c3e6cb; color: #155724; }
        .error { background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; }
        .warning { background: #fff3cd; border: 1px solid #ffeaa7; color: #856404; }
        .info { background: #d1ecf1; border: 1px solid #bee5eb; color: #0c5460; }
        .btn { display: inline-block; padding: 10px 20px; margin: 5px; text-decoration: none; border-radius: 4px; border: none; cursor: pointer; }
        .btn-primary { background: #007bff; color: white; }
        .btn-success { background: #28a745; color: white; }
        .btn-warning { background: #ffc107; color: black; }
        .btn-danger { background: #dc3545; color: white; }
        .code { background: #f4f4f4; padding: 10px; border-radius: 4px; font-family: monospace; margin: 10px 0; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🧪 Test des corrections cPanel</h1>
        <p><strong>Date:</strong> <?= date('Y-m-d H:i:s') ?></p>
        
        <?php
        $allTestsPassed = true;
        $testResults = [];
        
        // TEST 1: Vérification du nettoyage du cache
        echo "<div class='test-section'>";
        echo "<h2>1. 🧹 Test du nettoyage du cache Laravel</h2>";
        
        $cacheFiles = [
            '../bootstrap/cache/config.php',
            '../bootstrap/cache/routes-v7.php',
            '../bootstrap/cache/services.php',
            '../bootstrap/cache/packages.php',
            '../bootstrap/cache/compiled.php'
        ];
        
        $cachePresent = 0;
        foreach ($cacheFiles as $file) {
            if (file_exists($file)) {
                $cachePresent++;
            }
        }
        
        if ($cachePresent === 0) {
            echo "<div class='test-result success'>✅ SUCCÈS: Aucun fichier de cache Laravel détecté</div>";
            $testResults['cache'] = true;
        } else {
            echo "<div class='test-result error'>❌ ÉCHEC: $cachePresent fichier(s) de cache détecté(s)</div>";
            echo "<div class='test-result info'>💡 Solution: Exécuter le nettoyage automatique dans l'installateur</div>";
            $testResults['cache'] = false;
            $allTestsPassed = false;
        }
        echo "</div>";
        
        // TEST 2: Vérification des permissions .env
        echo "<div class='test-section'>";
        echo "<h2>2. 🔐 Test des permissions .env</h2>";
        
        $envPath = '../.env';
        if (file_exists($envPath)) {
            $readable = is_readable($envPath);
            $writable = is_writable($envPath);
            $perms = fileperms($envPath) & 0777;
            
            if ($readable && $writable) {
                echo "<div class='test-result success'>✅ SUCCÈS: Fichier .env accessible en lecture/écriture</div>";
                echo "<div class='test-result info'>📋 Permissions actuelles: " . sprintf('%o', $perms) . "</div>";
                $testResults['env_permissions'] = true;
            } else {
                echo "<div class='test-result error'>❌ ÉCHEC: Permissions .env insuffisantes</div>";
                echo "<div class='test-result info'>📋 Permissions actuelles: " . sprintf('%o', $perms) . " | Lecture: " . ($readable ? 'OK' : 'NON') . " | Écriture: " . ($writable ? 'OK' : 'NON') . "</div>";
                $testResults['env_permissions'] = false;
                $allTestsPassed = false;
            }
        } else {
            echo "<div class='test-result warning'>⚠️ ATTENTION: Fichier .env n'existe pas encore</div>";
            echo "<div class='test-result info'>💡 Normal si l'installation n'a pas encore été lancée</div>";
            $testResults['env_permissions'] = true; // Pas bloquant
        }
        echo "</div>";
        
        // TEST 3: Test des sessions
        echo "<div class='test-section'>";
        echo "<h2>3. 📝 Test de la gestion des sessions</h2>";
        
        // Créer des données de test en session
        $_SESSION['test_cpanel'] = [
            'timestamp' => time(),
            'data' => 'test_data_cpanel'
        ];
        
        if (isset($_SESSION['test_cpanel']) && $_SESSION['test_cpanel']['data'] === 'test_data_cpanel') {
            echo "<div class='test-result success'>✅ SUCCÈS: Sessions fonctionnelles</div>";
            echo "<div class='test-result info'>📋 Session ID: " . session_id() . "</div>";
            $testResults['sessions'] = true;
        } else {
            echo "<div class='test-result error'>❌ ÉCHEC: Problème avec les sessions</div>";
            $testResults['sessions'] = false;
            $allTestsPassed = false;
        }
        
        // Nettoyer la session de test
        unset($_SESSION['test_cpanel']);
        echo "</div>";
        
        // TEST 4: Test des extensions PHP
        echo "<div class='test-section'>";
        echo "<h2>4. 🔧 Test des extensions PHP critiques</h2>";
        
        $requiredExtensions = ['pdo', 'pdo_mysql', 'curl', 'json', 'mbstring'];
        $missingExtensions = [];
        
        foreach ($requiredExtensions as $ext) {
            if (extension_loaded($ext)) {
                echo "<div class='test-result success'>✅ Extension $ext: Disponible</div>";
            } else {
                echo "<div class='test-result error'>❌ Extension $ext: Manquante</div>";
                $missingExtensions[] = $ext;
            }
        }
        
        if (empty($missingExtensions)) {
            echo "<div class='test-result success'>✅ SUCCÈS: Toutes les extensions critiques sont disponibles</div>";
            $testResults['extensions'] = true;
        } else {
            echo "<div class='test-result error'>❌ ÉCHEC: Extensions manquantes: " . implode(', ', $missingExtensions) . "</div>";
            $testResults['extensions'] = false;
            $allTestsPassed = false;
        }
        echo "</div>";
        
        // TEST 5: Test de création de fichier .env
        echo "<div class='test-section'>";
        echo "<h2>5. 📄 Test de création du fichier .env</h2>";
        
        try {
            $testEnvPath = '../.env.test';
            $testContent = "# Test cPanel\nTEST_VAR=test_value\n";
            
            if (file_put_contents($testEnvPath, $testContent) !== false) {
                $readContent = file_get_contents($testEnvPath);
                if ($readContent === $testContent) {
                    echo "<div class='test-result success'>✅ SUCCÈS: Création et lecture de fichier .env fonctionnelles</div>";
                    $testResults['env_creation'] = true;
                } else {
                    echo "<div class='test-result error'>❌ ÉCHEC: Problème de lecture du fichier .env</div>";
                    $testResults['env_creation'] = false;
                    $allTestsPassed = false;
                }
                
                // Nettoyer le fichier de test
                @unlink($testEnvPath);
            } else {
                echo "<div class='test-result error'>❌ ÉCHEC: Impossible de créer le fichier .env</div>";
                $testResults['env_creation'] = false;
                $allTestsPassed = false;
            }
        } catch (Exception $e) {
            echo "<div class='test-result error'>❌ ÉCHEC: Erreur lors du test .env: " . $e->getMessage() . "</div>";
            $testResults['env_creation'] = false;
            $allTestsPassed = false;
        }
        echo "</div>";
        
        // TEST 6: Test de connexion MySQL (si paramètres fournis)
        echo "<div class='test-section'>";
        echo "<h2>6. 🗄️ Test de connexion MySQL</h2>";
        
        if (isset($_POST['test_mysql'])) {
            try {
                $dsn = "mysql:host={$_POST['db_host']};port={$_POST['db_port']}";
                $pdo = new PDO($dsn, $_POST['db_user'], $_POST['db_password'], [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_TIMEOUT => 5
                ]);
                
                echo "<div class='test-result success'>✅ SUCCÈS: Connexion MySQL réussie</div>";
                echo "<div class='test-result info'>📋 Serveur: {$_POST['db_host']}:{$_POST['db_port']}</div>";
                $testResults['mysql'] = true;
                
            } catch (PDOException $e) {
                echo "<div class='test-result error'>❌ ÉCHEC: Erreur MySQL: " . $e->getMessage() . "</div>";
                $testResults['mysql'] = false;
                $allTestsPassed = false;
            }
        } else {
            echo "<div class='test-result info'>💡 Test MySQL non effectué - Paramètres non fournis</div>";
            echo "<form method='post' style='margin: 10px 0;'>
                <input type='hidden' name='test_mysql' value='1'>
                <input type='text' name='db_host' placeholder='Host MySQL' value='localhost' style='margin: 5px; padding: 5px;'>
                <input type='text' name='db_port' placeholder='Port' value='3306' style='margin: 5px; padding: 5px;'>
                <input type='text' name='db_user' placeholder='Utilisateur' style='margin: 5px; padding: 5px;'>
                <input type='password' name='db_password' placeholder='Mot de passe' style='margin: 5px; padding: 5px;'>
                <button type='submit' class='btn btn-primary'>Tester MySQL</button>
            </form>";
            $testResults['mysql'] = null; // Non testé
        }
        echo "</div>";
        
        // RÉSUMÉ FINAL
        echo "<div class='test-section'>";
        echo "<h2>📊 Résumé des tests</h2>";
        
        $passedTests = array_filter($testResults, function($result) { return $result === true; });
        $failedTests = array_filter($testResults, function($result) { return $result === false; });
        $skippedTests = array_filter($testResults, function($result) { return $result === null; });
        
        echo "<div class='test-result " . ($allTestsPassed ? 'success' : 'warning') . "'>";
        echo "<strong>Résultats:</strong><br>";
        echo "✅ Tests réussis: " . count($passedTests) . "<br>";
        echo "❌ Tests échoués: " . count($failedTests) . "<br>";
        echo "⏭️ Tests ignorés: " . count($skippedTests) . "<br>";
        echo "</div>";
        
        if ($allTestsPassed && count($failedTests) === 0) {
            echo "<div class='test-result success'>";
            echo "<strong>🎉 EXCELLENT!</strong> Tous les tests critiques sont passés.<br>";
            echo "L'environnement cPanel est prêt pour l'installation AdminLicence.";
            echo "</div>";
        } else {
            echo "<div class='test-result error'>";
            echo "<strong>⚠️ ATTENTION!</strong> Certains tests ont échoué.<br>";
            echo "Veuillez corriger les problèmes avant de procéder à l'installation.";
            echo "</div>";
        }
        
        // Actions recommandées
        echo "<h3>🔧 Actions recommandées</h3>";
        
        if (!$testResults['cache']) {
            echo "<div class='test-result warning'>🧹 Vider le cache Laravel avant l'installation</div>";
        }
        
        if (!$testResults['env_permissions']) {
            echo "<div class='test-result warning'>🔐 Corriger les permissions du fichier .env (chmod 644)</div>";
        }
        
        if (!$testResults['extensions']) {
            echo "<div class='test-result warning'>🔧 Installer les extensions PHP manquantes</div>";
        }
        
        echo "</div>";
        
        // Actions rapides
        echo "<div class='test-section'>";
        echo "<h2>🚀 Actions rapides</h2>";
        echo "<a href='diagnostic_cpanel.php' class='btn btn-primary'>Diagnostic complet</a>";
        echo "<a href='install_cpanel_fixed.php' class='btn btn-success'>Installer AdminLicence (cPanel)</a>";
        echo "<a href='?action=clear_cache' class='btn btn-warning'>Vider le cache</a>";
        echo "<a href='?action=test_env' class='btn btn-info'>Tester .env</a>";
        echo "</div>";
        
        // Traitement des actions
        if (isset($_GET['action'])) {
            echo "<div class='test-section'>";
            echo "<h2>🔄 Résultat de l'action</h2>";
            
            switch ($_GET['action']) {
                case 'clear_cache':
                    $cleared = 0;
                    foreach ($cacheFiles as $file) {
                        if (file_exists($file) && @unlink($file)) {
                            $cleared++;
                        }
                    }
                    echo "<div class='test-result success'>✅ $cleared fichier(s) de cache supprimé(s)</div>";
                    break;
                    
                case 'test_env':
                    try {
                        $testPath = '../.env.test.action';
                        $testData = "TEST_ACTION=success\nTIMESTAMP=" . time();
                        
                        if (file_put_contents($testPath, $testData) !== false) {
                            $readData = file_get_contents($testPath);
                            if (strpos($readData, 'TEST_ACTION=success') !== false) {
                                echo "<div class='test-result success'>✅ Test .env réussi</div>";
                            } else {
                                echo "<div class='test-result error'>❌ Problème de lecture .env</div>";
                            }
                            @unlink($testPath);
                        } else {
                            echo "<div class='test-result error'>❌ Impossible de créer le fichier .env</div>";
                        }
                    } catch (Exception $e) {
                        echo "<div class='test-result error'>❌ Erreur: " . $e->getMessage() . "</div>";
                    }
                    break;
            }
            
            echo "<p><a href='test_cpanel_fixes.php' class='btn btn-primary'>Relancer les tests</a></p>";
            echo "</div>";
        }
        
        // Informations système
        echo "<div class='test-section'>";
        echo "<h2>ℹ️ Informations système</h2>";
        echo "<div class='code'>";
        echo "<strong>PHP Version:</strong> " . PHP_VERSION . "<br>";
        echo "<strong>Serveur:</strong> " . ($_SERVER['SERVER_SOFTWARE'] ?? 'Inconnu') . "<br>";
        echo "<strong>OS:</strong> " . PHP_OS . "<br>";
        echo "<strong>Document Root:</strong> " . ($_SERVER['DOCUMENT_ROOT'] ?? 'Inconnu') . "<br>";
        echo "<strong>Script Path:</strong> " . __FILE__ . "<br>";
        echo "<strong>Session ID:</strong> " . session_id() . "<br>";
        echo "<strong>Memory Limit:</strong> " . ini_get('memory_limit') . "<br>";
        echo "<strong>Max Execution Time:</strong> " . ini_get('max_execution_time') . "s<br>";
        echo "<strong>Upload Max Size:</strong> " . ini_get('upload_max_filesize') . "<br>";
        echo "</div>";
        echo "</div>";
        
        // Logger les résultats
        if (function_exists('writeToLog')) {
            $logMessage = "TEST CPANEL - Réussis: " . count($passedTests) . " | Échoués: " . count($failedTests) . " | Ignorés: " . count($skippedTests);
            writeToLog($logMessage, $allTestsPassed && count($failedTests) === 0 ? 'SUCCESS' : 'WARNING');
        }
        ?>
        
    </div>
</body>
</html>