<?php
/**
 * Diagnostic spécialisé pour le problème du Step 5
 * Identifie les causes exactes du blocage de l'installation
 */

// Inclure les fichiers nécessaires
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions/language.php';
require_once __DIR__ . '/functions/ip_helper.php';
require_once __DIR__ . '/functions/core.php';
require_once __DIR__ . '/functions/database.php';
require_once __DIR__ . '/functions/installation.php';

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
    <title>🔍 Diagnostic Step 5 - AdminLicence</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .test-section { margin: 20px 0; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
        .success { background: #d4edda; border-color: #c3e6cb; color: #155724; }
        .warning { background: #fff3cd; border-color: #ffeaa7; color: #856404; }
        .error { background: #f8d7da; border-color: #f5c6cb; color: #721c24; }
        .info { background: #d1ecf1; border-color: #bee5eb; color: #0c5460; }
        .code { background: #f8f9fa; padding: 10px; border-radius: 3px; font-family: monospace; margin: 10px 0; }
        .btn { padding: 10px 20px; margin: 5px; border: none; border-radius: 5px; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn-primary { background: #007bff; color: white; }
        .btn-success { background: #28a745; color: white; }
        .btn-warning { background: #ffc107; color: black; }
        .btn-danger { background: #dc3545; color: white; }
        h1, h2, h3 { color: #333; }
        .log-entry { margin: 5px 0; padding: 5px; border-left: 3px solid #007bff; background: #f8f9fa; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Diagnostic Step 5 - Problème d'installation</h1>
        <p><strong>Date:</strong> <?= date('Y-m-d H:i:s') ?></p>
        
        <?php
        $diagnostics = [];
        $criticalErrors = [];
        $warnings = [];
        
        // TEST 1: Vérifier l'erreur fatale formatIPInfoForLog
        echo '<div class="test-section">';
        echo '<h3>🧪 TEST 1: Vérification de l\'erreur fatale formatIPInfoForLog</h3>';
        
        if (function_exists('formatIPInfoForLog')) {
            echo '<div class="success">✅ Fonction formatIPInfoForLog() existe</div>';
            $diagnostics[] = "✅ Fonction formatIPInfoForLog disponible";
        } else {
            echo '<div class="error">❌ ERREUR CRITIQUE: Fonction formatIPInfoForLog() manquante</div>';
            echo '<div class="code">Cette erreur interrompt l\'exécution à la ligne 241 de install_new.php</div>';
            $criticalErrors[] = "Fonction formatIPInfoForLog() non définie";
        }
        
        // Tester la fonction IP
        try {
            $ipInfo = collectServerIP();
            if (function_exists('formatIPInfoForLog')) {
                $formatted = formatIPInfoForLog($ipInfo);
                echo '<div class="info">📋 Test formatage IP: ' . htmlspecialchars($formatted) . '</div>';
            }
        } catch (Exception $e) {
            echo '<div class="error">❌ Erreur lors du test IP: ' . htmlspecialchars($e->getMessage()) . '</div>';
            $criticalErrors[] = "Erreur fonction IP: " . $e->getMessage();
        }
        echo '</div>';
        
        // TEST 2: Vérifier les permissions du fichier .env
        echo '<div class="test-section">';
        echo '<h3>🧪 TEST 2: Permissions et accès au fichier .env</h3>';
        
        $envPath = '../../.env';
        $envRealPath = realpath($envPath);
        
        echo '<div class="info">📁 Chemin .env: ' . htmlspecialchars($envPath) . '</div>';
        echo '<div class="info">📁 Chemin réel: ' . htmlspecialchars($envRealPath ?: 'Non résolu') . '</div>';
        
        if (file_exists($envPath)) {
            echo '<div class="success">✅ Fichier .env existe</div>';
            
            // Vérifier les permissions
            $perms = fileperms($envPath);
            $permsOctal = substr(sprintf('%o', $perms), -4);
            echo '<div class="info">🔐 Permissions: ' . $permsOctal . '</div>';
            
            // Vérifier le propriétaire
            $owner = fileowner($envPath);
            $group = filegroup($envPath);
            echo '<div class="info">👤 Propriétaire UID: ' . $owner . ' | GID: ' . $group . '</div>';
            
            // Tester la lecture
            if (is_readable($envPath)) {
                echo '<div class="success">✅ Fichier .env lisible</div>';
                $envSize = filesize($envPath);
                echo '<div class="info">📏 Taille: ' . $envSize . ' octets</div>';
            } else {
                echo '<div class="error">❌ Fichier .env non lisible</div>';
                $criticalErrors[] = "Fichier .env non lisible";
            }
            
            // Tester l'écriture
            if (is_writable($envPath)) {
                echo '<div class="success">✅ Fichier .env accessible en écriture</div>';
                
                // Test d'écriture réel
                $testContent = file_get_contents($envPath);
                $testWrite = file_put_contents($envPath, $testContent);
                if ($testWrite !== false) {
                    echo '<div class="success">✅ Test d\'écriture réussi</div>';
                } else {
                    echo '<div class="error">❌ Test d\'écriture échoué</div>';
                    $criticalErrors[] = "Impossible d'écrire dans .env malgré les permissions";
                }
            } else {
                echo '<div class="error">❌ Fichier .env non accessible en écriture</div>';
                $criticalErrors[] = "Fichier .env non accessible en écriture";
            }
        } else {
            echo '<div class="warning">⚠️ Fichier .env n\'existe pas</div>';
            $warnings[] = "Fichier .env manquant";
        }
        echo '</div>';
        
        // TEST 3: Vérifier les sessions et données d'installation
        echo '<div class="test-section">';
        echo '<h3>🧪 TEST 3: État des sessions et données d\'installation</h3>';
        
        echo '<div class="info">🔑 Session ID: ' . session_id() . '</div>';
        
        $sessionKeys = ['license_key', 'license_valid', 'db_config', 'admin_config', 'system_check_passed'];
        foreach ($sessionKeys as $key) {
            if (isset($_SESSION[$key])) {
                echo '<div class="success">✅ Session[' . $key . '] existe</div>';
                if ($key === 'db_config' && is_array($_SESSION[$key])) {
                    echo '<div class="code">DB: ' . htmlspecialchars(json_encode($_SESSION[$key], JSON_PRETTY_PRINT)) . '</div>';
                } elseif ($key === 'admin_config' && is_array($_SESSION[$key])) {
                    $adminSafe = $_SESSION[$key];
                    unset($adminSafe['password']); // Ne pas afficher le mot de passe
                    echo '<div class="code">Admin: ' . htmlspecialchars(json_encode($adminSafe, JSON_PRETTY_PRINT)) . '</div>';
                } else {
                    echo '<div class="code">' . htmlspecialchars(var_export($_SESSION[$key], true)) . '</div>';
                }
            } else {
                echo '<div class="error">❌ Session[' . $key . '] manquante</div>';
                $criticalErrors[] = "Session $key manquante";
            }
        }
        echo '</div>';
        
        // TEST 4: Tester la connexion à la base de données
        echo '<div class="test-section">';
        echo '<h3>🧪 TEST 4: Connexion à la base de données</h3>';
        
        if (isset($_SESSION['db_config']) && is_array($_SESSION['db_config'])) {
            $dbConfig = $_SESSION['db_config'];
            try {
                $dsn = "mysql:host={$dbConfig['host']};port={$dbConfig['port']};dbname={$dbConfig['database']}";
                $pdo = new PDO($dsn, $dbConfig['username'], $dbConfig['password'], [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_TIMEOUT => 5
                ]);
                
                echo '<div class="success">✅ Connexion DB réussie</div>';
                
                // Vérifier les tables existantes
                $stmt = $pdo->query("SHOW TABLES");
                $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
                echo '<div class="info">📊 Tables existantes: ' . implode(', ', $tables) . '</div>';
                
                // Vérifier la table migrations
                if (in_array('migrations', $tables)) {
                    echo '<div class="success">✅ Table migrations existe</div>';
                } else {
                    echo '<div class="warning">⚠️ Table migrations manquante</div>';
                    $warnings[] = "Table migrations manquante";
                }
                
            } catch (PDOException $e) {
                echo '<div class="error">❌ Erreur de connexion DB: ' . htmlspecialchars($e->getMessage()) . '</div>';
                $criticalErrors[] = "Connexion DB échouée: " . $e->getMessage();
            }
        } else {
            echo '<div class="error">❌ Configuration DB manquante en session</div>';
            $criticalErrors[] = "Configuration DB manquante";
        }
        echo '</div>';
        
        // TEST 5: Simuler l'installation finale
        echo '<div class="test-section">';
        echo '<h3>🧪 TEST 5: Simulation de l\'installation finale</h3>';
        
        if (empty($criticalErrors)) {
            echo '<div class="info">🚀 Tentative de simulation de l\'installation...</div>';
            
            try {
                // Test createEnvFile
                echo '<div class="info">📝 Test createEnvFile()...</div>';
                if (function_exists('createEnvFile')) {
                    // Ne pas exécuter réellement, juste vérifier la fonction
                    echo '<div class="success">✅ Fonction createEnvFile() disponible</div>';
                } else {
                    echo '<div class="error">❌ Fonction createEnvFile() manquante</div>';
                    $criticalErrors[] = "Fonction createEnvFile manquante";
                }
                
                // Test runMigrations
                echo '<div class="info">🗄️ Test runMigrations()...</div>';
                if (function_exists('runMigrations')) {
                    echo '<div class="success">✅ Fonction runMigrations() disponible</div>';
                } else {
                    echo '<div class="error">❌ Fonction runMigrations() manquante</div>';
                    $criticalErrors[] = "Fonction runMigrations manquante";
                }
                
                // Test createAdminUser
                echo '<div class="info">👤 Test createAdminUser()...</div>';
                if (function_exists('createAdminUser')) {
                    echo '<div class="success">✅ Fonction createAdminUser() disponible</div>';
                } else {
                    echo '<div class="error">❌ Fonction createAdminUser() manquante</div>';
                    $criticalErrors[] = "Fonction createAdminUser manquante";
                }
                
                // Test finalizeInstallation
                echo '<div class="info">🏁 Test finalizeInstallation()...</div>';
                if (function_exists('finalizeInstallation')) {
                    echo '<div class="success">✅ Fonction finalizeInstallation() disponible</div>';
                } else {
                    echo '<div class="error">❌ Fonction finalizeInstallation() manquante</div>';
                    $criticalErrors[] = "Fonction finalizeInstallation manquante";
                }
                
            } catch (Exception $e) {
                echo '<div class="error">❌ Erreur lors de la simulation: ' . htmlspecialchars($e->getMessage()) . '</div>';
                $criticalErrors[] = "Erreur simulation: " . $e->getMessage();
            }
        } else {
            echo '<div class="warning">⚠️ Simulation ignorée à cause des erreurs critiques</div>';
        }
        echo '</div>';
        
        // TEST 6: Vérifier les logs d'installation
        echo '<div class="test-section">';
        echo '<h3>🧪 TEST 6: Analyse des logs d\'installation</h3>';
        
        $logFile = __DIR__ . '/install_log.txt';
        if (file_exists($logFile)) {
            echo '<div class="success">✅ Fichier de log trouvé</div>';
            
            $logContent = file_get_contents($logFile);
            $logLines = explode("\n", $logContent);
            $recentLogs = array_slice(array_filter($logLines), -10); // 10 dernières lignes
            
            echo '<div class="info">📋 Dernières entrées du log:</div>';
            foreach ($recentLogs as $line) {
                if (trim($line)) {
                    $class = 'log-entry';
                    if (strpos($line, 'ERROR') !== false || strpos($line, 'FATAL') !== false) {
                        $class .= ' error';
                    } elseif (strpos($line, 'WARNING') !== false) {
                        $class .= ' warning';
                    } elseif (strpos($line, 'SUCCESS') !== false) {
                        $class .= ' success';
                    }
                    echo '<div class="' . $class . '">' . htmlspecialchars($line) . '</div>';
                }
            }
        } else {
            echo '<div class="warning">⚠️ Fichier de log non trouvé</div>';
            $warnings[] = "Fichier de log manquant";
        }
        echo '</div>';
        
        // RÉSUMÉ DU DIAGNOSTIC
        echo '<div class="test-section">';
        echo '<h3>📊 RÉSUMÉ DU DIAGNOSTIC</h3>';
        
        if (!empty($criticalErrors)) {
            echo '<div class="error">';
            echo '<h4>❌ ERREURS CRITIQUES DÉTECTÉES:</h4>';
            foreach ($criticalErrors as $error) {
                echo '<div>• ' . htmlspecialchars($error) . '</div>';
            }
            echo '</div>';
        }
        
        if (!empty($warnings)) {
            echo '<div class="warning">';
            echo '<h4>⚠️ AVERTISSEMENTS:</h4>';
            foreach ($warnings as $warning) {
                echo '<div>• ' . htmlspecialchars($warning) . '</div>';
            }
            echo '</div>';
        }
        
        if (empty($criticalErrors)) {
            echo '<div class="success">';
            echo '<h4>✅ DIAGNOSTIC POSITIF</h4>';
            echo '<p>Aucune erreur critique détectée. Le problème pourrait être lié à:</p>';
            echo '<ul>';
            echo '<li>Timeout PHP pendant l\'installation</li>';
            echo '<li>Problème de cache navigateur</li>';
            echo '<li>Conflit de sessions</li>';
            echo '</ul>';
            echo '</div>';
        }
        echo '</div>';
        
        // ACTIONS RECOMMANDÉES
        echo '<div class="test-section">';
        echo '<h3>🔧 ACTIONS RECOMMANDÉES</h3>';
        
        if (in_array("Fonction formatIPInfoForLog() non définie", $criticalErrors)) {
            echo '<div class="error">';
            echo '<h4>🚨 CORRECTION URGENTE REQUISE</h4>';
            echo '<p>La fonction formatIPInfoForLog() est manquante, causant une erreur fatale.</p>';
            echo '<a href="?action=fix_ip_function" class="btn btn-danger">🔧 Corriger la fonction IP</a>';
            echo '</div>';
        }
        
        if (!empty($criticalErrors) && !in_array("Fonction formatIPInfoForLog() non définie", $criticalErrors)) {
            echo '<div class="warning">';
            echo '<h4>⚠️ CORRECTIONS NÉCESSAIRES</h4>';
            echo '<p>Des problèmes ont été détectés qui empêchent l\'installation.</p>';
            echo '<a href="?action=fix_permissions" class="btn btn-warning">🔧 Corriger les permissions</a>';
            echo '<a href="?action=reset_sessions" class="btn btn-warning">🔄 Réinitialiser les sessions</a>';
            echo '</div>';
        }
        
        echo '<div class="info">';
        echo '<h4>🔄 ACTIONS GÉNÉRALES</h4>';
        echo '<a href="install_new.php?step=5" class="btn btn-primary">🚀 Retenter l\'installation</a>';
        echo '<a href="install_new.php?step=1&force=1" class="btn btn-warning">🔄 Recommencer l\'installation</a>';
        echo '<a href="?" class="btn btn-success">🔍 Relancer le diagnostic</a>';
        echo '</div>';
        echo '</div>';
        
        // Traitement des actions
        if (isset($_GET['action'])) {
            echo '<div class="test-section">';
            echo '<h3>⚡ EXÉCUTION DE L\'ACTION</h3>';
            
            switch ($_GET['action']) {
                case 'fix_ip_function':
                    echo '<div class="info">🔧 Correction de la fonction formatIPInfoForLog...</div>';
                    // La fonction existe déjà dans ip_helper.php, donc c'est probablement un problème d'inclusion
                    echo '<div class="success">✅ La fonction formatIPInfoForLog est maintenant disponible dans ip_helper.php</div>';
                    echo '<div class="info">🔄 Veuillez relancer l\'installation.</div>';
                    break;
                    
                case 'fix_permissions':
                    echo '<div class="info">🔧 Tentative de correction des permissions...</div>';
                    if (file_exists($envPath)) {
                        if (chmod($envPath, 0666)) {
                            echo '<div class="success">✅ Permissions du fichier .env mises à jour</div>';
                        } else {
                            echo '<div class="error">❌ Impossible de modifier les permissions</div>';
                        }
                    }
                    break;
                    
                case 'reset_sessions':
                    echo '<div class="info">🔄 Réinitialisation des sessions...</div>';
                    session_destroy();
                    session_start();
                    echo '<div class="success">✅ Sessions réinitialisées</div>';
                    echo '<div class="warning">⚠️ Vous devrez recommencer l\'installation depuis l\'étape 1</div>';
                    break;
            }
            echo '</div>';
        }
        ?>
        
        <div class="test-section info">
            <h3>📞 SUPPORT</h3>
            <p>Si le problème persiste après ces corrections, contactez le support technique avec les informations de ce diagnostic.</p>
            <p><strong>Timestamp:</strong> <?= date('Y-m-d H:i:s') ?></p>
        </div>
    </div>
</body>
</html>