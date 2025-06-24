<?php
/**
 * Diagnostic spécialisé pour les problèmes cPanel
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

// Fonction pour afficher les résultats
function displayResult($test, $status, $message, $details = null) {
    $icon = $status === 'OK' ? '✅' : ($status === 'WARNING' ? '⚠️' : '❌');
    echo "<div style='margin: 10px 0; padding: 10px; border-left: 4px solid " . 
         ($status === 'OK' ? '#28a745' : ($status === 'WARNING' ? '#ffc107' : '#dc3545')) . 
         "; background: #f8f9fa;'>";
    echo "<strong>$icon $test:</strong> $message";
    if ($details) {
        echo "<br><small style='color: #666;'>$details</small>";
    }
    echo "</div>";
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diagnostic cPanel - AdminLicence</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; line-height: 1.6; }
        .container { max-width: 1000px; margin: 0 auto; }
        h1 { color: #333; border-bottom: 2px solid #007bff; padding-bottom: 10px; }
        h2 { color: #555; margin-top: 30px; }
        .section { margin: 20px 0; }
        .code { background: #f4f4f4; padding: 10px; border-radius: 4px; font-family: monospace; }
        .actions { margin: 20px 0; }
        .btn { display: inline-block; padding: 10px 20px; margin: 5px; text-decoration: none; border-radius: 4px; }
        .btn-primary { background: #007bff; color: white; }
        .btn-success { background: #28a745; color: white; }
        .btn-warning { background: #ffc107; color: black; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Diagnostic cPanel - AdminLicence</h1>
        <p><strong>Date:</strong> <?= date('Y-m-d H:i:s') ?></p>
        
        <?php
        // DIAGNOSTIC 1: Vérification du cache Laravel
        echo "<h2>1. 🧹 Cache Laravel</h2>";
        
        $cacheFiles = [
            '../bootstrap/cache/config.php' => 'Configuration',
            '../bootstrap/cache/routes-v7.php' => 'Routes',
            '../bootstrap/cache/services.php' => 'Services',
            '../bootstrap/cache/packages.php' => 'Packages',
            '../bootstrap/cache/compiled.php' => 'Compiled'
        ];
        
        $cacheIssues = 0;
        foreach ($cacheFiles as $file => $name) {
            if (file_exists($file)) {
                displayResult("Cache $name", 'ERROR', "Fichier de cache présent", "Fichier: $file");
                $cacheIssues++;
            } else {
                displayResult("Cache $name", 'OK', "Pas de cache résiduel");
            }
        }
        
        if ($cacheIssues > 0) {
            echo "<div style='background: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; border-radius: 4px; margin: 10px 0;'>";
            echo "<strong>⚠️ PROBLÈME DÉTECTÉ:</strong> $cacheIssues fichier(s) de cache Laravel détecté(s). ";
            echo "Ces fichiers empêchent la lecture du nouveau .env.";
            echo "</div>";
        }
        
        // DIAGNOSTIC 2: Permissions des fichiers
        echo "<h2>2. 🔐 Permissions des fichiers</h2>";
        
        $permissionChecks = [
            '../.env' => 'Fichier .env',
            '../storage' => 'Dossier storage',
            '../bootstrap/cache' => 'Dossier bootstrap/cache',
            '../' => 'Dossier racine'
        ];
        
        $permissionIssues = 0;
        foreach ($permissionChecks as $path => $name) {
            if (file_exists($path)) {
                $perms = fileperms($path);
                $readable = is_readable($path);
                $writable = is_writable($path);
                
                if ($readable && $writable) {
                    displayResult("$name", 'OK', "Permissions correctes", sprintf("Permissions: %o", $perms & 0777));
                } else {
                    displayResult("$name", 'ERROR', "Permissions insuffisantes", 
                        sprintf("Permissions: %o | Lecture: %s | Écriture: %s", 
                            $perms & 0777, 
                            $readable ? 'OK' : 'NON', 
                            $writable ? 'OK' : 'NON'
                        )
                    );
                    $permissionIssues++;
                }
            } else {
                displayResult("$name", 'WARNING', "Fichier/dossier inexistant", "Chemin: $path");
            }
        }
        
        // DIAGNOSTIC 3: Gestion des sessions
        echo "<h2>3. 📝 Gestion des sessions</h2>";
        
        $sessionData = [
            'license_key' => $_SESSION['license_key'] ?? null,
            'license_valid' => $_SESSION['license_valid'] ?? null,
            'db_config' => $_SESSION['db_config'] ?? null,
            'admin_config' => $_SESSION['admin_config'] ?? null,
            'system_check_passed' => $_SESSION['system_check_passed'] ?? null
        ];
        
        $sessionIssues = 0;
        foreach ($sessionData as $key => $value) {
            if ($value !== null) {
                if (is_array($value)) {
                    displayResult("Session $key", 'OK', "Données présentes", "Clés: " . implode(', ', array_keys($value)));
                } else {
                    displayResult("Session $key", 'OK', "Valeur: " . (is_bool($value) ? ($value ? 'true' : 'false') : $value));
                }
            } else {
                displayResult("Session $key", 'WARNING', "Données manquantes");
                $sessionIssues++;
            }
        }
        
        // DIAGNOSTIC 4: Fichier .env
        echo "<h2>4. ⚙️ Fichier .env</h2>";
        
        $envPath = '../.env';
        if (file_exists($envPath)) {
            $envContent = file_get_contents($envPath);
            if ($envContent !== false) {
                $envLines = explode("\n", $envContent);
                $envVars = [];
                foreach ($envLines as $line) {
                    if (strpos($line, '=') !== false && !str_starts_with(trim($line), '#')) {
                        list($key, $value) = explode('=', $line, 2);
                        $envVars[trim($key)] = trim($value);
                    }
                }
                
                $criticalVars = ['APP_KEY', 'DB_HOST', 'DB_DATABASE', 'DB_USERNAME', 'LICENCE_KEY'];
                foreach ($criticalVars as $var) {
                    if (isset($envVars[$var]) && !empty($envVars[$var])) {
                        displayResult("Variable $var", 'OK', "Définie", 
                            $var === 'LICENCE_KEY' ? "Valeur: " . $envVars[$var] : "Valeur présente"
                        );
                    } else {
                        displayResult("Variable $var", 'ERROR', "Manquante ou vide");
                    }
                }
            } else {
                displayResult("Lecture .env", 'ERROR', "Impossible de lire le fichier .env");
            }
        } else {
            displayResult("Fichier .env", 'ERROR', "Fichier .env inexistant");
        }
        
        // DIAGNOSTIC 5: Environnement cPanel
        echo "<h2>5. 🖥️ Environnement cPanel</h2>";
        
        // Vérifier les fonctions désactivées
        $disabledFunctions = explode(',', ini_get('disable_functions'));
        $criticalFunctions = ['exec', 'shell_exec', 'system', 'passthru'];
        
        foreach ($criticalFunctions as $func) {
            if (in_array($func, $disabledFunctions) || !function_exists($func)) {
                displayResult("Fonction $func", 'WARNING', "Désactivée", "Normale sur cPanel pour la sécurité");
            } else {
                displayResult("Fonction $func", 'OK', "Disponible");
            }
        }
        
        // Vérifier les extensions PHP
        $requiredExtensions = ['pdo', 'pdo_mysql', 'curl', 'json', 'mbstring'];
        foreach ($requiredExtensions as $ext) {
            if (extension_loaded($ext)) {
                displayResult("Extension $ext", 'OK', "Chargée");
            } else {
                displayResult("Extension $ext", 'ERROR', "Manquante");
            }
        }
        
        // DIAGNOSTIC 6: Test de connexion base de données
        echo "<h2>6. 🗄️ Base de données</h2>";
        
        if (isset($_SESSION['db_config'])) {
            $dbConfig = $_SESSION['db_config'];
            try {
                $dsn = "mysql:host={$dbConfig['host']};port={$dbConfig['port']};dbname={$dbConfig['database']}";
                $pdo = new PDO($dsn, $dbConfig['username'], $dbConfig['password'], [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_TIMEOUT => 5
                ]);
                
                displayResult("Connexion DB", 'OK', "Connexion réussie", 
                    "Host: {$dbConfig['host']}:{$dbConfig['port']} | DB: {$dbConfig['database']}"
                );
                
                // Vérifier les tables
                $stmt = $pdo->query("SHOW TABLES");
                $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
                displayResult("Tables DB", count($tables) > 0 ? 'OK' : 'WARNING', 
                    count($tables) . " table(s) trouvée(s)", 
                    count($tables) > 0 ? implode(', ', array_slice($tables, 0, 5)) . (count($tables) > 5 ? '...' : '') : 'Aucune table'
                );
                
            } catch (Exception $e) {
                displayResult("Connexion DB", 'ERROR', "Échec de connexion", $e->getMessage());
            }
        } else {
            displayResult("Configuration DB", 'WARNING', "Pas de configuration en session");
        }
        
        // RÉSUMÉ ET RECOMMANDATIONS
        echo "<h2>📋 Résumé et Recommandations</h2>";
        
        $totalIssues = $cacheIssues + $permissionIssues + $sessionIssues;
        
        if ($totalIssues === 0) {
            echo "<div style='background: #d4edda; border: 1px solid #c3e6cb; padding: 15px; border-radius: 4px;'>";
            echo "<strong>✅ DIAGNOSTIC POSITIF:</strong> Aucun problème critique détecté.";
            echo "</div>";
        } else {
            echo "<div style='background: #f8d7da; border: 1px solid #f5c6cb; padding: 15px; border-radius: 4px;'>";
            echo "<strong>❌ PROBLÈMES DÉTECTÉS:</strong> $totalIssues problème(s) identifié(s).";
            echo "</div>";
            
            echo "<h3>🔧 Actions recommandées :</h3>";
            echo "<ol>";
            
            if ($cacheIssues > 0) {
                echo "<li><strong>Vider le cache Laravel :</strong> Supprimer tous les fichiers dans bootstrap/cache/</li>";
            }
            
            if ($permissionIssues > 0) {
                echo "<li><strong>Corriger les permissions :</strong> Définir 644 pour les fichiers et 755 pour les dossiers</li>";
            }
            
            if ($sessionIssues > 0) {
                echo "<li><strong>Reprendre l'installation :</strong> Recommencer depuis l'étape 1 pour recréer les sessions</li>";
            }
            
            echo "</ol>";
        }
        
        // Actions rapides
        echo "<div class='actions'>";
        echo "<h3>🚀 Actions rapides :</h3>";
        echo "<a href='?action=clear_cache' class='btn btn-warning'>Vider le cache</a>";
        echo "<a href='?action=fix_permissions' class='btn btn-warning'>Corriger les permissions</a>";
        echo "<a href='?action=reset_sessions' class='btn btn-warning'>Réinitialiser les sessions</a>";
        echo "<a href='install_new.php' class='btn btn-primary'>Relancer l'installation</a>";
        echo "</div>";
        
        // Traitement des actions
        if (isset($_GET['action'])) {
            echo "<h2>🔄 Exécution de l'action</h2>";
            
            switch ($_GET['action']) {
                case 'clear_cache':
                    $cleared = 0;
                    foreach ($cacheFiles as $file => $name) {
                        if (file_exists($file) && @unlink($file)) {
                            $cleared++;
                        }
                    }
                    echo "<div style='background: #d4edda; padding: 10px; border-radius: 4px;'>";
                    echo "✅ $cleared fichier(s) de cache supprimé(s).";
                    echo "</div>";
                    break;
                    
                case 'fix_permissions':
                    $fixed = 0;
                    if (file_exists('../.env') && @chmod('../.env', 0644)) $fixed++;
                    if (file_exists('../storage') && @chmod('../storage', 0755)) $fixed++;
                    if (file_exists('../bootstrap/cache') && @chmod('../bootstrap/cache', 0755)) $fixed++;
                    
                    echo "<div style='background: #d4edda; padding: 10px; border-radius: 4px;'>";
                    echo "✅ Tentative de correction des permissions ($fixed éléments traités).";
                    echo "</div>";
                    break;
                    
                case 'reset_sessions':
                    session_destroy();
                    session_start();
                    echo "<div style='background: #d4edda; padding: 10px; border-radius: 4px;'>";
                    echo "✅ Sessions réinitialisées.";
                    echo "</div>";
                    break;
            }
            
            echo "<p><a href='diagnostic_cpanel.php' class='btn btn-primary'>Relancer le diagnostic</a></p>";
        }
        
        // Log du diagnostic
        writeToLog("DIAGNOSTIC CPANEL - Cache: $cacheIssues | Permissions: $permissionIssues | Sessions: $sessionIssues", 'INFO');
        ?>
        
        <div style="margin-top: 40px; padding: 20px; background: #f8f9fa; border-radius: 4px;">
            <h3>📝 Informations techniques</h3>
            <div class="code">
                <strong>PHP Version:</strong> <?= PHP_VERSION ?><br>
                <strong>Serveur:</strong> <?= $_SERVER['SERVER_SOFTWARE'] ?? 'Inconnu' ?><br>
                <strong>Document Root:</strong> <?= $_SERVER['DOCUMENT_ROOT'] ?? 'Inconnu' ?><br>
                <strong>Script Path:</strong> <?= __FILE__ ?><br>
                <strong>Session ID:</strong> <?= session_id() ?><br>
                <strong>Timestamp:</strong> <?= date('c') ?>
            </div>
        </div>
    </div>
</body>
</html>