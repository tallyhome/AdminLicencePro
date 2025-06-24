<?php
/**
 * Correctif final pour les permissions .env
 * Résout définitivement le problème de permissions au step 2
 */

// Inclure les fichiers nécessaires
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions/core.php';

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🔧 Correctif Permissions .env - AdminLicence</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .success { background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .warning { background: #fff3cd; border: 1px solid #ffeaa7; color: #856404; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .error { background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .info { background: #d1ecf1; border: 1px solid #bee5eb; color: #0c5460; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .btn { padding: 10px 20px; margin: 5px; border: none; border-radius: 5px; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn-primary { background: #007bff; color: white; }
        .btn-success { background: #28a745; color: white; }
        .code { background: #f8f9fa; padding: 10px; border-radius: 3px; font-family: monospace; margin: 10px 0; border: 1px solid #e9ecef; }
        h1, h2, h3 { color: #333; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 Correctif Final - Permissions .env</h1>
        <p><strong>Date:</strong> <?= date('Y-m-d H:i:s') ?></p>
        
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
            echo '<div class="info"><h3>🚀 Application du correctif permissions .env...</h3></div>';
            
            $envPath = '../../.env';
            $envExamplePath = '../../.env.example';
            $fixes = [];
            $errors = [];
            
            // ÉTAPE 1: Créer ou recréer le fichier .env
            echo '<h4>📝 ÉTAPE 1: Création/Recréation du fichier .env</h4>';
            
            try {
                // Supprimer l'ancien .env s'il existe et pose problème
                if (file_exists($envPath)) {
                    $oldPerms = fileperms($envPath);
                    echo '<div class="info">📋 Ancien fichier .env trouvé (permissions: ' . substr(sprintf('%o', $oldPerms), -4) . ')</div>';
                    
                    // Sauvegarder le contenu existant
                    $existingContent = @file_get_contents($envPath);
                    if ($existingContent !== false) {
                        $backupPath = $envPath . '.backup.' . date('Y-m-d-His');
                        file_put_contents($backupPath, $existingContent);
                        echo '<div class="success">✅ Sauvegarde créée: ' . basename($backupPath) . '</div>';
                    }
                    
                    // Forcer la suppression
                    @chmod($envPath, 0777);
                    if (@unlink($envPath)) {
                        echo '<div class="success">✅ Ancien fichier .env supprimé</div>';
                    } else {
                        echo '<div class="warning">⚠️ Impossible de supprimer l\'ancien .env</div>';
                    }
                }
                
                // Créer un nouveau .env
                if (file_exists($envExamplePath)) {
                    copy($envExamplePath, $envPath);
                    echo '<div class="success">✅ Nouveau .env créé depuis .env.example</div>';
                } else {
                    // Créer un .env minimal complet
                    $defaultEnv = "APP_NAME=AdminLicence
APP_ENV=production
APP_KEY=base64:" . base64_encode(random_bytes(32)) . "
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
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=\"noreply@adminlicence.com\"
MAIL_FROM_NAME=\"\${APP_NAME}\"

# Configuration de licence
LICENCE_KEY=

# Configuration personnalisée
CACHE_DRIVER=file
SESSION_DRIVER=file";
                    
                    file_put_contents($envPath, $defaultEnv);
                    echo '<div class="success">✅ Nouveau .env créé avec configuration complète</div>';
                }
                
                $fixes[] = "Fichier .env recréé";
                
            } catch (Exception $e) {
                echo '<div class="error">❌ Erreur lors de la création .env: ' . htmlspecialchars($e->getMessage()) . '</div>';
                $errors[] = "Création .env échouée: " . $e->getMessage();
            }
            
            // ÉTAPE 2: Forcer les permissions optimales
            echo '<h4>🔐 ÉTAPE 2: Configuration des permissions optimales</h4>';
            
            if (file_exists($envPath)) {
                // Essayer différentes permissions
                $permissionsToTry = [0666, 0664, 0644, 0777];
                $permissionSuccess = false;
                
                foreach ($permissionsToTry as $perm) {
                    if (@chmod($envPath, $perm)) {
                        // Tester l'écriture
                        $testContent = file_get_contents($envPath);
                        if (@file_put_contents($envPath, $testContent) !== false) {
                            echo '<div class="success">✅ Permissions ' . sprintf('%o', $perm) . ' appliquées et testées avec succès</div>';
                            $permissionSuccess = true;
                            $fixes[] = "Permissions " . sprintf('%o', $perm) . " appliquées";
                            break;
                        }
                    }
                }
                
                if (!$permissionSuccess) {
                    echo '<div class="warning">⚠️ Aucune permission standard ne fonctionne</div>';
                    $errors[] = "Permissions non configurables";
                }
                
                // Afficher les informations finales
                $finalPerms = fileperms($envPath);
                $owner = fileowner($envPath);
                $group = filegroup($envPath);
                
                echo '<div class="info">';
                echo '<strong>Informations finales du fichier .env:</strong><br>';
                echo '📋 Permissions: ' . substr(sprintf('%o', $finalPerms), -4) . '<br>';
                echo '👤 Propriétaire UID: ' . $owner . '<br>';
                echo '👥 Groupe GID: ' . $group . '<br>';
                echo '📏 Taille: ' . filesize($envPath) . ' octets';
                echo '</div>';
                
            } else {
                echo '<div class="error">❌ Fichier .env non trouvé après création</div>';
                $errors[] = "Fichier .env introuvable";
            }
            
            // ÉTAPE 3: Test d'écriture complet
            echo '<h4>🧪 ÉTAPE 3: Test d\'écriture complet</h4>';
            
            if (file_exists($envPath)) {
                try {
                    // Test 1: Lecture
                    $content = file_get_contents($envPath);
                    if ($content !== false) {
                        echo '<div class="success">✅ Test de lecture réussi (' . strlen($content) . ' caractères)</div>';
                    } else {
                        echo '<div class="error">❌ Test de lecture échoué</div>';
                        $errors[] = "Lecture .env impossible";
                    }
                    
                    // Test 2: Écriture simple
                    $testWrite = file_put_contents($envPath, $content);
                    if ($testWrite !== false) {
                        echo '<div class="success">✅ Test d\'écriture simple réussi (' . $testWrite . ' octets)</div>';
                    } else {
                        echo '<div class="error">❌ Test d\'écriture simple échoué</div>';
                        $errors[] = "Écriture .env impossible";
                    }
                    
                    // Test 3: Écriture avec verrou
                    $testWriteLock = file_put_contents($envPath, $content, LOCK_EX);
                    if ($testWriteLock !== false) {
                        echo '<div class="success">✅ Test d\'écriture avec verrou réussi</div>';
                        $fixes[] = "Écriture avec verrou fonctionnelle";
                    } else {
                        echo '<div class="warning">⚠️ Test d\'écriture avec verrou échoué</div>';
                    }
                    
                    // Test 4: Modification de contenu
                    $testContent = $content . "\n# Test d'écriture " . date('Y-m-d H:i:s');
                    $testModify = file_put_contents($envPath, $testContent);
                    if ($testModify !== false) {
                        echo '<div class="success">✅ Test de modification de contenu réussi</div>';
                        // Restaurer le contenu original
                        file_put_contents($envPath, $content);
                        $fixes[] = "Modification de contenu fonctionnelle";
                    } else {
                        echo '<div class="error">❌ Test de modification de contenu échoué</div>';
                        $errors[] = "Modification .env impossible";
                    }
                    
                } catch (Exception $e) {
                    echo '<div class="error">❌ Erreur lors des tests: ' . htmlspecialchars($e->getMessage()) . '</div>';
                    $errors[] = "Tests échoués: " . $e->getMessage();
                }
            }
            
            // RÉSUMÉ FINAL
            echo '<h4>📊 RÉSUMÉ DU CORRECTIF</h4>';
            
            if (empty($errors)) {
                echo '<div class="success">';
                echo '<h4>🎉 CORRECTIF RÉUSSI</h4>';
                echo '<p>Le fichier .env a été corrigé avec succès :</p>';
                echo '<ul>';
                foreach ($fixes as $fix) {
                    echo '<li>✅ ' . htmlspecialchars($fix) . '</li>';
                }
                echo '</ul>';
                echo '<p><strong>Le step 2 devrait maintenant afficher "OK" pour les permissions .env</strong></p>';
                echo '</div>';
                
                echo '<div class="info">';
                echo '<h4>🚀 PROCHAINES ÉTAPES</h4>';
                echo '<a href="install_new.php?step=2" class="btn btn-primary">🔄 Retester le Step 2</a>';
                echo '<a href="install_new.php?step=1" class="btn btn-success">🔄 Recommencer l\'installation</a>';
                echo '</div>';
                
            } else {
                echo '<div class="warning">';
                echo '<h4>⚠️ CORRECTIF PARTIEL</h4>';
                echo '<p>Certaines corrections ont été appliquées, mais des problèmes persistent :</p>';
                
                if (!empty($fixes)) {
                    echo '<p><strong>Corrections réussies :</strong></p>';
                    echo '<ul>';
                    foreach ($fixes as $fix) {
                        echo '<li>✅ ' . htmlspecialchars($fix) . '</li>';
                    }
                    echo '</ul>';
                }
                
                if (!empty($errors)) {
                    echo '<p><strong>Problèmes restants :</strong></p>';
                    echo '<ul>';
                    foreach ($errors as $error) {
                        echo '<li>❌ ' . htmlspecialchars($error) . '</li>';
                    }
                    echo '</ul>';
                }
                echo '</div>';
                
                echo '<div class="info">';
                echo '<h4>🔧 ACTIONS SUPPLÉMENTAIRES</h4>';
                echo '<p>Si le problème persiste, contactez votre hébergeur pour :</p>';
                echo '<ul>';
                echo '<li>Vérifier les permissions du dossier racine</li>';
                echo '<li>Vérifier les restrictions de sécurité (SELinux, etc.)</li>';
                echo '<li>Vérifier les quotas d\'espace disque</li>';
                echo '</ul>';
                echo '<a href="debug_step5_problem.php" class="btn btn-warning">🔍 Diagnostic complet</a>';
                echo '</div>';
            }
            
        } else {
            // Affichage du formulaire de correction
            ?>
            <div class="info">
                <h3>🔍 Problème détecté</h3>
                <p>Le step 2 affiche une erreur pour les permissions du fichier .env :</p>
                <div class="code">❌ .env (Écriture) Erreur</div>
                <p>Ce correctif va :</p>
                <ul>
                    <li>🔄 Recréer complètement le fichier .env</li>
                    <li>🔐 Appliquer les permissions optimales</li>
                    <li>🧪 Tester toutes les opérations d'écriture</li>
                    <li>💾 Sauvegarder l'ancien fichier si nécessaire</li>
                </ul>
            </div>
            
            <div class="warning">
                <h4>⚠️ IMPORTANT</h4>
                <p>Ce correctif va recréer le fichier .env. Une sauvegarde sera créée automatiquement.</p>
            </div>
            
            <form method="POST" style="text-align: center; margin: 30px 0;">
                <input type="hidden" name="action" value="fix_env_permissions">
                <button type="submit" class="btn btn-primary" style="font-size: 18px; padding: 15px 30px;">
                    🔧 CORRIGER LES PERMISSIONS .env
                </button>
            </form>
            
            <div class="info">
                <h4>🔗 LIENS UTILES</h4>
                <a href="install_new.php?step=2" class="btn btn-warning">🔄 Retester Step 2</a>
                <a href="debug_step5_problem.php" class="btn btn-primary">🔍 Diagnostic complet</a>
            </div>
            <?php
        }
        ?>
    </div>
</body>
</html>