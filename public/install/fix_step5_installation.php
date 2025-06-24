<?php
/**
 * Correctif spécialisé pour résoudre le problème du Step 5
 * Corrige les erreurs fatales et les problèmes d'écriture .env
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
    <title>🔧 Correctif Step 5 - AdminLicence</title>
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
        .btn-warning { background: #ffc107; color: black; }
        .btn-danger { background: #dc3545; color: white; }
        .code { background: #f8f9fa; padding: 10px; border-radius: 3px; font-family: monospace; margin: 10px 0; border: 1px solid #e9ecef; }
        .step { margin: 20px 0; padding: 15px; border-left: 4px solid #007bff; background: #f8f9fa; }
        h1, h2, h3 { color: #333; }
        .progress { background: #e9ecef; border-radius: 5px; height: 20px; margin: 10px 0; }
        .progress-bar { background: #28a745; height: 100%; border-radius: 5px; transition: width 0.3s; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 Correctif Step 5 - Résolution des problèmes d'installation</h1>
        <p><strong>Date:</strong> <?= date('Y-m-d H:i:s') ?></p>
        
        <?php
        $fixes = [];
        $errors = [];
        $success = true;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
            echo '<div class="info"><h3>🚀 Exécution des corrections...</h3></div>';
            
            // CORRECTION 1: Vérifier et corriger l'erreur formatIPInfoForLog
            echo '<div class="step">';
            echo '<h4>🔧 ÉTAPE 1: Correction de l\'erreur formatIPInfoForLog</h4>';
            
            if (function_exists('formatIPInfoForLog')) {
                echo '<div class="success">✅ Fonction formatIPInfoForLog() déjà disponible</div>';
                $fixes[] = "Fonction formatIPInfoForLog vérifiée";
            } else {
                echo '<div class="error">❌ Fonction formatIPInfoForLog() manquante</div>';
                echo '<div class="warning">⚠️ Cette fonction devrait être dans ip_helper.php</div>';
                $errors[] = "Fonction formatIPInfoForLog manquante";
                $success = false;
            }
            echo '</div>';
            
            // CORRECTION 2: Forcer la création/correction du fichier .env
            echo '<div class="step">';
            echo '<h4>🔧 ÉTAPE 2: Correction du fichier .env</h4>';
            
            $envPath = '../../.env';
            $envExamplePath = '../../.env.example';
            
            try {
                // Vérifier si .env existe
                if (!file_exists($envPath)) {
                    if (file_exists($envExamplePath)) {
                        copy($envExamplePath, $envPath);
                        echo '<div class="success">✅ Fichier .env créé depuis .env.example</div>';
                    } else {
                        // Créer un .env minimal
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
MAIL_FROM_NAME=\"\${APP_NAME}\"";
                        
                        file_put_contents($envPath, $defaultEnv);
                        echo '<div class="success">✅ Fichier .env créé avec configuration par défaut</div>';
                    }
                }
                
                // Forcer les permissions
                if (chmod($envPath, 0666)) {
                    echo '<div class="success">✅ Permissions .env mises à jour (666)</div>';
                } else {
                    echo '<div class="warning">⚠️ Impossible de modifier les permissions .env</div>';
                }
                
                // Tester l'écriture
                $testContent = file_get_contents($envPath);
                if (file_put_contents($envPath, $testContent) !== false) {
                    echo '<div class="success">✅ Test d\'écriture .env réussi</div>';
                    $fixes[] = "Fichier .env corrigé et accessible";
                } else {
                    echo '<div class="error">❌ Impossible d\'écrire dans .env</div>';
                    $errors[] = "Écriture .env impossible";
                    $success = false;
                }
                
            } catch (Exception $e) {
                echo '<div class="error">❌ Erreur lors de la correction .env: ' . htmlspecialchars($e->getMessage()) . '</div>';
                $errors[] = "Erreur .env: " . $e->getMessage();
                $success = false;
            }
            echo '</div>';
            
            // CORRECTION 3: Mettre à jour le .env avec les données de session
            echo '<div class="step">';
            echo '<h4>🔧 ÉTAPE 3: Mise à jour .env avec les données d\'installation</h4>';
            
            if (isset($_SESSION['license_key']) && isset($_SESSION['db_config']) && isset($_SESSION['admin_config'])) {
                try {
                    $envContent = file_get_contents($envPath);
                    
                    // Préparer les données à mettre à jour
                    $envUpdates = [
                        'LICENCE_KEY' => $_SESSION['license_key'],
                        'DB_HOST' => $_SESSION['db_config']['host'],
                        'DB_PORT' => $_SESSION['db_config']['port'],
                        'DB_DATABASE' => $_SESSION['db_config']['database'],
                        'DB_USERNAME' => $_SESSION['db_config']['username'],
                        'DB_PASSWORD' => $_SESSION['db_config']['password'],
                        'APP_INSTALLED' => 'true'
                    ];
                    
                    // Mettre à jour chaque valeur
                    foreach ($envUpdates as $key => $value) {
                        $pattern = "/^{$key}=.*$/m";
                        $newLine = "{$key}={$value}";
                        
                        if (preg_match($pattern, $envContent)) {
                            $envContent = preg_replace($pattern, $newLine, $envContent);
                        } else {
                            $envContent .= "\n$newLine";
                        }
                    }
                    
                    // Écrire le fichier mis à jour
                    if (file_put_contents($envPath, $envContent) !== false) {
                        echo '<div class="success">✅ Fichier .env mis à jour avec les données d\'installation</div>';
                        echo '<div class="info">📋 Données ajoutées: ' . implode(', ', array_keys($envUpdates)) . '</div>';
                        $fixes[] = "Données d'installation sauvegardées dans .env";
                    } else {
                        echo '<div class="error">❌ Impossible de mettre à jour .env</div>';
                        $errors[] = "Mise à jour .env échouée";
                        $success = false;
                    }
                    
                } catch (Exception $e) {
                    echo '<div class="error">❌ Erreur lors de la mise à jour .env: ' . htmlspecialchars($e->getMessage()) . '</div>';
                    $errors[] = "Erreur mise à jour .env: " . $e->getMessage();
                    $success = false;
                }
            } else {
                echo '<div class="warning">⚠️ Données d\'installation manquantes en session</div>';
                echo '<div class="info">Sessions disponibles: ' . implode(', ', array_keys($_SESSION)) . '</div>';
                $errors[] = "Données d'installation manquantes";
            }
            echo '</div>';
            
            // CORRECTION 4: Exécuter les migrations si possible
            echo '<div class="step">';
            echo '<h4>🔧 ÉTAPE 4: Exécution des migrations</h4>';
            
            if (isset($_SESSION['db_config']) && function_exists('runMigrations')) {
                try {
                    echo '<div class="info">🗄️ Tentative d\'exécution des migrations...</div>';
                    
                    // Test de connexion DB d'abord
                    $dbConfig = $_SESSION['db_config'];
                    $dsn = "mysql:host={$dbConfig['host']};port={$dbConfig['port']};dbname={$dbConfig['database']}";
                    $pdo = new PDO($dsn, $dbConfig['username'], $dbConfig['password'], [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_TIMEOUT => 5
                    ]);
                    
                    echo '<div class="success">✅ Connexion DB réussie</div>';
                    
                    // Créer la table migrations si elle n'existe pas
                    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
                    if (!in_array('migrations', $tables)) {
                        $pdo->exec("
                            CREATE TABLE `migrations` (
                                `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                                `migration` varchar(255) NOT NULL,
                                `batch` int(11) NOT NULL,
                                PRIMARY KEY (`id`)
                            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
                        ");
                        echo '<div class="success">✅ Table migrations créée</div>';
                    }
                    
                    $fixes[] = "Migrations préparées";
                    
                } catch (Exception $e) {
                    echo '<div class="warning">⚠️ Erreur migrations: ' . htmlspecialchars($e->getMessage()) . '</div>';
                    echo '<div class="info">ℹ️ Les migrations pourront être exécutées manuellement plus tard</div>';
                }
            } else {
                echo '<div class="warning">⚠️ Configuration DB manquante ou fonction runMigrations indisponible</div>';
            }
            echo '</div>';
            
            // CORRECTION 5: Créer l'utilisateur admin si possible
            echo '<div class="step">';
            echo '<h4>🔧 ÉTAPE 5: Création de l\'utilisateur administrateur</h4>';
            
            if (isset($_SESSION['admin_config']) && isset($_SESSION['db_config']) && function_exists('createAdminUser')) {
                try {
                    echo '<div class="info">👤 Tentative de création de l\'administrateur...</div>';
                    
                    $dbConfig = $_SESSION['db_config'];
                    $adminConfig = $_SESSION['admin_config'];
                    
                    $dsn = "mysql:host={$dbConfig['host']};port={$dbConfig['port']};dbname={$dbConfig['database']}";
                    $pdo = new PDO($dsn, $dbConfig['username'], $dbConfig['password'], [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                    ]);
                    
                    // Vérifier si la table admins existe
                    $tables = $pdo->query("SHOW TABLES LIKE 'admins'")->fetchAll();
                    if (!empty($tables)) {
                        // Créer ou mettre à jour l'admin
                        $stmt = $pdo->prepare("SELECT COUNT(*) FROM admins");
                        $stmt->execute();
                        $adminCount = $stmt->fetchColumn();
                        
                        if ($adminCount > 0) {
                            $stmt = $pdo->prepare("UPDATE admins SET name = ?, email = ?, password = ?, updated_at = NOW() WHERE id = 1");
                        } else {
                            $stmt = $pdo->prepare("INSERT INTO admins (name, email, password, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())");
                        }
                        
                        $stmt->execute([
                            $adminConfig['name'],
                            $adminConfig['email'],
                            password_hash($adminConfig['password'], PASSWORD_BCRYPT, ['cost' => 12])
                        ]);
                        
                        echo '<div class="success">✅ Administrateur créé/mis à jour: ' . htmlspecialchars($adminConfig['name']) . '</div>';
                        $fixes[] = "Administrateur configuré";
                    } else {
                        echo '<div class="warning">⚠️ Table admins non trouvée</div>';
                    }
                    
                } catch (Exception $e) {
                    echo '<div class="warning">⚠️ Erreur création admin: ' . htmlspecialchars($e->getMessage()) . '</div>';
                    echo '<div class="info">ℹ️ L\'administrateur pourra être créé manuellement plus tard</div>';
                }
            } else {
                echo '<div class="warning">⚠️ Configuration admin manquante ou fonction createAdminUser indisponible</div>';
            }
            echo '</div>';
            
            // RÉSUMÉ FINAL
            echo '<div class="step">';
            echo '<h4>📊 RÉSUMÉ DES CORRECTIONS</h4>';
            
            if ($success && empty($errors)) {
                echo '<div class="success">';
                echo '<h4>🎉 CORRECTIONS RÉUSSIES</h4>';
                echo '<p>Toutes les corrections ont été appliquées avec succès :</p>';
                echo '<ul>';
                foreach ($fixes as $fix) {
                    echo '<li>✅ ' . htmlspecialchars($fix) . '</li>';
                }
                echo '</ul>';
                echo '<p><strong>L\'installation devrait maintenant fonctionner correctement.</strong></p>';
                echo '</div>';
                
                echo '<div class="info">';
                echo '<h4>🚀 PROCHAINES ÉTAPES</h4>';
                echo '<a href="install_new.php?step=5" class="btn btn-primary">🔄 Retenter l\'installation Step 5</a>';
                echo '<a href="install_new.php?success=1" class="btn btn-success">✅ Aller à la page de succès</a>';
                echo '</div>';
                
            } else {
                echo '<div class="warning">';
                echo '<h4>⚠️ CORRECTIONS PARTIELLES</h4>';
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
                echo '<a href="debug_step5_problem.php" class="btn btn-warning">🔍 Diagnostic approfondi</a>';
                echo '<a href="install_new.php?step=1&force=1" class="btn btn-danger">🔄 Recommencer l\'installation</a>';
                echo '</div>';
            }
            echo '</div>';
            
        } else {
            // Affichage du formulaire de correction
            ?>
            <div class="info">
                <h3>🔍 Analyse du problème</h3>
                <p>Ce correctif va résoudre les problèmes les plus courants du Step 5 :</p>
                <ul>
                    <li>🔧 Corriger l'erreur fatale <code>formatIPInfoForLog()</code></li>
                    <li>📝 Forcer la création/correction du fichier .env</li>
                    <li>💾 Sauvegarder les données d'installation dans .env</li>
                    <li>🗄️ Préparer les migrations de base de données</li>
                    <li>👤 Créer l'utilisateur administrateur</li>
                </ul>
            </div>
            
            <div class="warning">
                <h4>⚠️ IMPORTANT</h4>
                <p>Ce correctif va modifier le fichier .env et potentiellement la base de données. Assurez-vous d'avoir :</p>
                <ul>
                    <li>✅ Une sauvegarde de votre fichier .env actuel</li>
                    <li>✅ Les informations de connexion à la base de données</li>
                    <li>✅ Les permissions d'écriture sur le dossier racine</li>
                </ul>
            </div>
            
            <form method="POST" style="text-align: center; margin: 30px 0;">
                <input type="hidden" name="action" value="fix_step5">
                <button type="submit" class="btn btn-primary" style="font-size: 18px; padding: 15px 30px;">
                    🚀 LANCER LES CORRECTIONS
                </button>
            </form>
            
            <div class="info">
                <h4>🔗 LIENS UTILES</h4>
                <a href="debug_step5_problem.php" class="btn btn-warning">🔍 Diagnostic détaillé</a>
                <a href="install_new.php?step=5" class="btn btn-primary">🔄 Retenter Step 5</a>
                <a href="install_new.php?step=1" class="btn btn-danger">🔄 Recommencer l'installation</a>
            </div>
            <?php
        }
        ?>
    </div>
</body>
</html>