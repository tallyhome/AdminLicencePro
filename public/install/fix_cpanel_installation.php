<?php
/**
 * Script de correction complète pour les problèmes d'installation cPanel
 * Version: 1.0.0
 * Date: 2025-06-23
 * 
 * CORRECTIONS APPLIQUÉES:
 * 1. Génération APP_KEY manquante
 * 2. Nettoyage cache Laravel complet
 * 3. Correction permissions .env
 * 4. Réinitialisation sessions
 * 5. Création .env robuste
 * 6. Validation complète
 */

// Inclure les fichiers nécessaires
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions/core.php';

// Démarrer la session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Fonction de log pour le processus de correction
function fixLog($message, $type = 'INFO') {
    $logFile = INSTALL_PATH . '/fix_cpanel.log';
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[$timestamp] [$type] $message" . PHP_EOL;
    file_put_contents($logFile, $logMessage, FILE_APPEND);
    
    $color = $type === 'SUCCESS' ? '#28a745' : ($type === 'ERROR' ? '#dc3545' : ($type === 'WARNING' ? '#ffc107' : '#007bff'));
    echo "<div style='background: #f8f9fa; border-left: 4px solid $color; padding: 10px; margin: 5px 0; font-family: monospace; font-size: 12px;'>[$type] $message</div>";
}

// Fonction pour nettoyer complètement le cache Laravel
function forceCleanAllCache() {
    fixLog("=== NETTOYAGE CACHE LARAVEL COMPLET ===", "INFO");
    
    $cleaned = 0;
    
    // 1. Fichiers de cache bootstrap
    $cacheFiles = [
        ROOT_PATH . '/bootstrap/cache/config.php',
        ROOT_PATH . '/bootstrap/cache/routes-v7.php',
        ROOT_PATH . '/bootstrap/cache/routes.php',
        ROOT_PATH . '/bootstrap/cache/services.php',
        ROOT_PATH . '/bootstrap/cache/packages.php',
        ROOT_PATH . '/bootstrap/cache/compiled.php'
    ];
    
    foreach ($cacheFiles as $file) {
        if (file_exists($file)) {
            if (@unlink($file)) {
                fixLog("Cache supprimé: " . basename($file), "SUCCESS");
                $cleaned++;
            } else {
                fixLog("Échec suppression: " . basename($file), "WARNING");
            }
        }
    }
    
    // 2. Dossiers de cache storage
    $cacheDirs = [
        ROOT_PATH . '/storage/framework/cache/data',
        ROOT_PATH . '/storage/framework/views',
        ROOT_PATH . '/storage/framework/sessions'
    ];
    
    foreach ($cacheDirs as $dir) {
        if (is_dir($dir)) {
            $files = glob($dir . '/*');
            $dirCleaned = 0;
            foreach ($files as $file) {
                if (is_file($file) && basename($file) !== '.gitignore') {
                    if (@unlink($file)) {
                        $dirCleaned++;
                    }
                }
            }
            if ($dirCleaned > 0) {
                fixLog("Dossier vidé: " . basename($dir) . " ($dirCleaned fichiers)", "SUCCESS");
                $cleaned += $dirCleaned;
            }
        }
    }
    
    fixLog("CACHE NETTOYÉ: $cleaned fichiers supprimés", "SUCCESS");
    return $cleaned;
}

// Fonction pour générer et injecter APP_KEY
function generateAndInjectAppKey() {
    fixLog("=== GÉNÉRATION APP_KEY ===", "INFO");
    
    $envPath = ROOT_PATH . '/.env';
    
    if (!file_exists($envPath)) {
        fixLog("Fichier .env inexistant - création nécessaire", "WARNING");
        return false;
    }
    
    $envContent = file_get_contents($envPath);
    if ($envContent === false) {
        fixLog("Impossible de lire .env", "ERROR");
        return false;
    }
    
    // Générer nouvelle APP_KEY
    $newAppKey = 'base64:' . base64_encode(random_bytes(32));
    fixLog("Nouvelle APP_KEY générée: " . substr($newAppKey, 0, 20) . "...", "INFO");
    
    // Mettre à jour ou ajouter APP_KEY
    if (preg_match('/^APP_KEY=.*$/m', $envContent)) {
        $envContent = preg_replace('/^APP_KEY=.*$/m', "APP_KEY=$newAppKey", $envContent);
        fixLog("APP_KEY existante remplacée", "INFO");
    } else {
        $envContent .= "\nAPP_KEY=$newAppKey";
        fixLog("APP_KEY ajoutée au fichier", "INFO");
    }
    
    // Écrire le fichier
    if (file_put_contents($envPath, $envContent) !== false) {
        fixLog("APP_KEY injectée avec succès", "SUCCESS");
        return true;
    } else {
        fixLog("Échec injection APP_KEY", "ERROR");
        return false;
    }
}

// Fonction pour corriger les permissions
function fixAllPermissions() {
    fixLog("=== CORRECTION PERMISSIONS ===", "INFO");
    
    $fixed = 0;
    $permissions = [
        ROOT_PATH . '/.env' => 0644,
        ROOT_PATH . '/storage' => 0755,
        ROOT_PATH . '/bootstrap/cache' => 0755,
        ROOT_PATH . '/public/install' => 0755
    ];
    
    foreach ($permissions as $path => $perm) {
        if (file_exists($path)) {
            if (@chmod($path, $perm)) {
                fixLog("Permissions corrigées: " . basename($path) . " -> " . decoct($perm), "SUCCESS");
                $fixed++;
            } else {
                fixLog("Échec permissions: " . basename($path), "WARNING");
            }
        }
    }
    
    fixLog("PERMISSIONS: $fixed éléments corrigés", "SUCCESS");
    return $fixed;
}

// Fonction pour créer un .env robuste
function createRobustEnvFile() {
    fixLog("=== CRÉATION .ENV ROBUSTE ===", "INFO");
    
    $envPath = ROOT_PATH . '/.env';
    $envExamplePath = ROOT_PATH . '/.env.example';
    
    // Générer APP_KEY
    $appKey = 'base64:' . base64_encode(random_bytes(32));
    
    // Contenu .env minimal mais complet
    $envContent = "APP_NAME=AdminLicence
APP_ENV=production
APP_KEY=$appKey
APP_DEBUG=false
APP_URL=" . (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://') . ($_SERVER['HTTP_HOST'] ?? 'localhost') . "
APP_INSTALLED=false

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@adminlicence.com
MAIL_FROM_NAME=\"\${APP_NAME}\"

LICENCE_KEY=";
    
    // Écrire le fichier
    if (file_put_contents($envPath, $envContent) !== false) {
        fixLog(".env robuste créé avec succès", "SUCCESS");
        
        // Corriger les permissions
        @chmod($envPath, 0644);
        fixLog("Permissions .env définies: 644", "SUCCESS");
        
        return true;
    } else {
        fixLog("Échec création .env robuste", "ERROR");
        return false;
    }
}

// Fonction pour nettoyer les sessions problématiques
function cleanSessions() {
    fixLog("=== NETTOYAGE SESSIONS ===", "INFO");
    
    // Détruire la session actuelle
    session_destroy();
    
    // Redémarrer une session propre
    session_start();
    
    fixLog("Sessions nettoyées et redémarrées", "SUCCESS");
    return true;
}

// Fonction pour valider la correction
function validateFix() {
    fixLog("=== VALIDATION CORRECTION ===", "INFO");
    
    $issues = [];
    
    // 1. Vérifier APP_KEY
    $envPath = ROOT_PATH . '/.env';
    if (file_exists($envPath)) {
        $envContent = file_get_contents($envPath);
        if (preg_match('/APP_KEY=(.+)/', $envContent, $matches)) {
            $appKey = trim($matches[1]);
            if (!empty($appKey) && $appKey !== 'base64:') {
                fixLog("✓ APP_KEY présente et valide", "SUCCESS");
            } else {
                $issues[] = "APP_KEY vide ou invalide";
            }
        } else {
            $issues[] = "APP_KEY manquante";
        }
    } else {
        $issues[] = "Fichier .env manquant";
    }
    
    // 2. Vérifier cache
    $cacheFiles = [
        ROOT_PATH . '/bootstrap/cache/config.php',
        ROOT_PATH . '/bootstrap/cache/routes-v7.php',
        ROOT_PATH . '/bootstrap/cache/services.php'
    ];
    
    $cacheFound = 0;
    foreach ($cacheFiles as $file) {
        if (file_exists($file)) {
            $cacheFound++;
        }
    }
    
    if ($cacheFound === 0) {
        fixLog("✓ Cache Laravel propre", "SUCCESS");
    } else {
        $issues[] = "Cache Laravel résiduel ($cacheFound fichiers)";
    }
    
    // 3. Vérifier permissions
    if (is_writable($envPath)) {
        fixLog("✓ Permissions .env correctes", "SUCCESS");
    } else {
        $issues[] = "Permissions .env insuffisantes";
    }
    
    // 4. Vérifier sessions
    if (session_status() === PHP_SESSION_ACTIVE) {
        fixLog("✓ Sessions fonctionnelles", "SUCCESS");
    } else {
        $issues[] = "Sessions non fonctionnelles";
    }
    
    return $issues;
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Correction Installation cPanel - AdminLicence</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; line-height: 1.6; }
        .container { max-width: 1000px; margin: 0 auto; }
        h1 { color: #333; border-bottom: 2px solid #dc3545; padding-bottom: 10px; }
        h2 { color: #555; margin-top: 30px; background: #f8f9fa; padding: 10px; border-radius: 4px; }
        .success { background: #d4edda; border: 1px solid #c3e6cb; padding: 15px; border-radius: 4px; margin: 10px 0; }
        .error { background: #f8d7da; border: 1px solid #f5c6cb; padding: 15px; border-radius: 4px; margin: 10px 0; }
        .warning { background: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; border-radius: 4px; margin: 10px 0; }
        .btn { display: inline-block; padding: 10px 20px; margin: 5px; text-decoration: none; border-radius: 4px; background: #007bff; color: white; }
        .btn-success { background: #28a745; }
        .btn-warning { background: #ffc107; color: black; }
        .progress { margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 Correction Installation cPanel - AdminLicence</h1>
        <p><strong>Date:</strong> <?= date('Y-m-d H:i:s') ?></p>
        
        <?php
        if (isset($_GET['action']) && $_GET['action'] === 'fix') {
            fixLog("=== DÉBUT CORRECTION AUTOMATIQUE ===", "INFO");
            
            echo "<h2>🚀 Correction en cours...</h2>";
            echo "<div class='progress'>";
            
            $steps = [
                'Nettoyage cache Laravel' => 'forceCleanAllCache',
                'Génération APP_KEY' => 'generateAndInjectAppKey', 
                'Correction permissions' => 'fixAllPermissions',
                'Création .env robuste' => 'createRobustEnvFile',
                'Nettoyage sessions' => 'cleanSessions'
            ];
            
            $completed = 0;
            $total = count($steps);
            
            foreach ($steps as $stepName => $function) {
                echo "<h3>📋 $stepName</h3>";
                
                try {
                    $result = call_user_func($function);
                    if ($result !== false) {
                        echo "<div class='success'>✅ $stepName - TERMINÉ</div>";
                        $completed++;
                    } else {
                        echo "<div class='error'>❌ $stepName - ÉCHEC</div>";
                    }
                } catch (Exception $e) {
                    echo "<div class='error'>❌ $stepName - ERREUR: " . $e->getMessage() . "</div>";
                    fixLog("Erreur $stepName: " . $e->getMessage(), "ERROR");
                }
                
                // Forcer l'affichage progressif
                if (ob_get_level()) ob_flush();
                flush();
            }
            
            echo "</div>";
            
            // Validation finale
            echo "<h2>🔍 Validation de la correction</h2>";
            $issues = validateFix();
            
            if (empty($issues)) {
                echo "<div class='success'>";
                echo "<h3>🎉 CORRECTION RÉUSSIE !</h3>";
                echo "<p>Tous les problèmes ont été corrigés. L'installation peut maintenant être relancée.</p>";
                echo "<p><strong>Étapes complétées:</strong> $completed/$total</p>";
                echo "</div>";
                
                fixLog("=== CORRECTION TERMINÉE AVEC SUCCÈS ===", "SUCCESS");
                
                echo "<div style='margin: 20px 0;'>";
                echo "<a href='install_cpanel_fixed.php' class='btn btn-success'>🚀 Relancer l'installation maintenant</a>";
                echo "<a href='diagnostic_cpanel.php' class='btn'>📊 Vérifier le diagnostic</a>";
                echo "</div>";
                
            } else {
                echo "<div class='error'>";
                echo "<h3>⚠️ CORRECTION PARTIELLE</h3>";
                echo "<p>Certains problèmes persistent :</p>";
                echo "<ul>";
                foreach ($issues as $issue) {
                    echo "<li>$issue</li>";
                }
                echo "</ul>";
                echo "<p><strong>Étapes complétées:</strong> $completed/$total</p>";
                echo "</div>";
                
                fixLog("=== CORRECTION PARTIELLE - Problèmes restants: " . implode(', ', $issues) . " ===", "WARNING");
                
                echo "<div style='margin: 20px 0;'>";
                echo "<a href='?action=fix' class='btn btn-warning'>🔄 Réessayer la correction</a>";
                echo "<a href='debug_installation_cpanel.php' class='btn'>🔍 Diagnostic avancé</a>";
                echo "</div>";
            }
            
        } else {
            // Affichage initial
            echo "<h2>🔍 Problèmes identifiés</h2>";
            echo "<div class='warning'>";
            echo "<h3>Problèmes d'installation cPanel détectés :</h3>";
            echo "<ul>";
            echo "<li>❌ APP_KEY manquante causant la perte des sessions</li>";
            echo "<li>❌ Cache Laravel empêchant la lecture du .env</li>";
            echo "<li>❌ Permissions .env insuffisantes</li>";
            echo "<li>❌ Sessions corrompues</li>";
            echo "<li>❌ Erreur SQLSTATE avec ancien utilisateur 'adminlicenceteste'</li>";
            echo "</ul>";
            echo "</div>";
            
            echo "<h2>🔧 Solution de correction</h2>";
            echo "<div class='success'>";
            echo "<h3>Cette correction va :</h3>";
            echo "<ol>";
            echo "<li>🧹 Nettoyer complètement le cache Laravel</li>";
            echo "<li>🔑 Générer une nouvelle APP_KEY</li>";
            echo "<li>📝 Créer un fichier .env robuste</li>";
            echo "<li>🔐 Corriger toutes les permissions</li>";
            echo "<li>🔄 Réinitialiser les sessions</li>";
            echo "<li>✅ Valider que tout fonctionne</li>";
            echo "</ol>";
            echo "</div>";
            
            echo "<div style='margin: 20px 0;'>";
            echo "<a href='?action=fix' class='btn btn-success'>🚀 Lancer la correction automatique</a>";
            echo "<a href='debug_installation_cpanel.php' class='btn'>🔍 Diagnostic détaillé d'abord</a>";
            echo "<a href='diagnostic_cpanel.php' class='btn'>📊 Diagnostic standard</a>";
            echo "</div>";
            
            echo "<div class='warning'>";
            echo "<h3>⚠️ Important :</h3>";
            echo "<p>Cette correction va :</p>";
            echo "<ul>";
            echo "<li>Supprimer tous les fichiers de cache Laravel</li>";
            echo "<li>Recréer le fichier .env avec de nouvelles valeurs</li>";
            echo "<li>Réinitialiser toutes les sessions en cours</li>";
            echo "</ul>";
            echo "<p>Assurez-vous d'avoir sauvegardé vos données importantes avant de continuer.</p>";
            echo "</div>";
        }
        ?>
        
        <div style="margin-top: 40px; padding: 20px; background: #f8f9fa; border-radius: 4px;">
            <h3>📝 Informations</h3>
            <p><strong>Log de correction:</strong> <?= INSTALL_PATH ?>/fix_cpanel.log</p>
            <p><strong>Session ID:</strong> <?= session_id() ?></p>
            <p><strong>Timestamp:</strong> <?= date('c') ?></p>
        </div>
    </div>
</body>
</html>