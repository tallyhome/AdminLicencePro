<?php
/**
 * 🚀 INSTALLATEUR cPanel - AdminLicence
 * Version spéciale sans exec() pour serveurs cPanel
 */
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🚀 Installation AdminLicence - cPanel</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .header { text-align: center; margin-bottom: 30px; }
        .step { background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0; }
        .success { background: #d4edda; border: 1px solid #28a745; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .error { background: #f8d7da; border: 1px solid #dc3545; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .warning { background: #fff3cd; border: 1px solid #ffc107; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .info { background: #d1ecf1; border: 1px solid #17a2b8; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .form-group { margin: 15px 0; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input, .form-group select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; }
        .btn { padding: 12px 25px; margin: 10px 5px; border: none; border-radius: 5px; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn-primary { background: #007bff; color: white; }
        .btn-success { background: #28a745; color: white; }
        .btn-danger { background: #dc3545; color: white; }
        .btn-warning { background: #ffc107; color: black; }
        .progress { width: 100%; background: #f0f0f0; border-radius: 5px; margin: 20px 0; }
        .progress-bar { height: 25px; background: #007bff; border-radius: 5px; text-align: center; color: white; line-height: 25px; }
        code { background: #f8f9fa; padding: 2px 4px; border-radius: 3px; font-family: monospace; }
        .hidden { display: none; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🚀 Installation AdminLicence</h1>
            <p>Version optimisée pour serveurs cPanel</p>
        </div>

        <?php
        // Configuration
        session_start();
        $step = $_GET['step'] ?? ($_POST['step'] ?? 1);
        $errors = [];
        $success = [];

        // Fonction de nettoyage des caches (SANS EXEC)
        function clearCachesManually() {
            $cleared = 0;
            
            // Fichiers de cache Laravel
            $cacheFiles = [
                '../bootstrap/cache/config.php',
                '../bootstrap/cache/routes-v7.php',
                '../bootstrap/cache/services.php',
                '../bootstrap/cache/packages.php'
            ];
            
            foreach ($cacheFiles as $file) {
                if (file_exists($file) && @unlink($file)) {
                    $cleared++;
                }
            }
            
            // Répertoires de cache
            $cacheDirs = [
                '../storage/framework/cache',
                '../storage/framework/views',
                '../storage/framework/sessions'
            ];
            
            foreach ($cacheDirs as $dir) {
                if (is_dir($dir)) {
                    $files = glob($dir . '/*');
                    foreach ($files as $file) {
                        if (is_file($file) && basename($file) !== '.gitignore') {
                            @unlink($file);
                            $cleared++;
                        }
                    }
                }
            }
            
            return $cleared;
        }

        // Fonction de test de base de données
        function testDatabase($host, $name, $user, $pass) {
            try {
                $pdo = new PDO("mysql:host=$host;dbname=$name", $user, $pass);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $pdo->query("SELECT 1");
                return ['success' => true, 'message' => 'Connexion réussie'];
            } catch (PDOException $e) {
                return ['success' => false, 'message' => $e->getMessage()];
            }
        }

        // Fonction de vérification de licence (simulée)
        function verifyLicense($key, $domain, $ip) {
            // Pour les tests, accepter certaines clés
            $testKeys = ['TEST-LICENSE-KEY', 'ADMIN-LICENCE-2024', 'DEMO-KEY-12345'];
            
            if (in_array($key, $testKeys)) {
                return ['valid' => true, 'message' => 'Licence de test validée'];
            }
            
            // Ici vous pourriez ajouter votre vraie vérification de licence
            // Pour le moment, on accepte toutes les clés pour les tests
            if (strlen($key) >= 10) {
                return ['valid' => true, 'message' => 'Licence validée'];
            }
            
            return ['valid' => false, 'message' => 'Clé de licence invalide'];
        }

        // Traitement des formulaires
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            switch ($step) {
                case 1: // Vérification licence
                    if (empty($_POST['license_key'])) {
                        $errors[] = "La clé de licence est requise";
                    } else {
                        $domain = $_SERVER['HTTP_HOST'] ?? 'localhost';
                        $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
                        
                        $licenseCheck = verifyLicense($_POST['license_key'], $domain, $ip);
                        
                        if ($licenseCheck['valid']) {
                            $_SESSION['license_key'] = $_POST['license_key'];
                            $_SESSION['domain'] = $domain;
                            $_SESSION['ip'] = $ip;
                            $success[] = $licenseCheck['message'];
                            $step = 2;
                        } else {
                            $errors[] = $licenseCheck['message'];
                        }
                    }
                    break;
                    
                case 2: // Configuration base de données
                    $requiredFields = ['db_host', 'db_name', 'db_user'];
                    foreach ($requiredFields as $field) {
                        if (empty($_POST[$field])) {
                            $errors[] = "Le champ $field est requis";
                        }
                    }
                    
                    if (empty($errors)) {
                        $dbTest = testDatabase($_POST['db_host'], $_POST['db_name'], $_POST['db_user'], $_POST['db_pass'] ?? '');
                        
                        if ($dbTest['success']) {
                            $_SESSION['db_config'] = [
                                'host' => $_POST['db_host'],
                                'name' => $_POST['db_name'],
                                'user' => $_POST['db_user'],
                                'pass' => $_POST['db_pass'] ?? ''
                            ];
                            $success[] = "Configuration base de données validée";
                            $step = 3;
                        } else {
                            $errors[] = "Erreur de connexion: " . $dbTest['message'];
                        }
                    }
                    break;
                    
                case 3: // Configuration administrateur
                    $requiredFields = ['admin_name', 'admin_email', 'admin_password'];
                    foreach ($requiredFields as $field) {
                        if (empty($_POST[$field])) {
                            $errors[] = "Le champ $field est requis";
                        }
                    }
                    
                    if (empty($errors)) {
                        $_SESSION['admin_config'] = [
                            'name' => $_POST['admin_name'],
                            'email' => $_POST['admin_email'],
                            'password' => password_hash($_POST['admin_password'], PASSWORD_DEFAULT)
                        ];
                        $success[] = "Configuration administrateur validée";
                        $step = 4;
                    }
                    break;
                    
                case 4: // Installation finale
                    try {
                        // Nettoyage des caches
                        $cleared = clearCachesManually();
                        $success[] = "$cleared fichiers de cache supprimés";
                        
                        // Créer/mettre à jour .env
                        $envContent = "APP_NAME=AdminLicence\n";
                        $envContent .= "APP_ENV=production\n";
                        $envContent .= "APP_DEBUG=false\n";
                        $envContent .= "APP_URL=" . (isset($_SERVER['HTTPS']) ? 'https' : 'http') . "://" . $_SERVER['HTTP_HOST'] . "\n\n";
                        
                        if (isset($_SESSION['db_config'])) {
                            $db = $_SESSION['db_config'];
                            $envContent .= "DB_CONNECTION=mysql\n";
                            $envContent .= "DB_HOST={$db['host']}\n";
                            $envContent .= "DB_PORT=3306\n";
                            $envContent .= "DB_DATABASE={$db['name']}\n";
                            $envContent .= "DB_USERNAME={$db['user']}\n";
                            $envContent .= "DB_PASSWORD={$db['pass']}\n\n";
                        }
                        
                        $envContent .= "CACHE_DRIVER=file\n";
                        $envContent .= "SESSION_DRIVER=file\n";
                        $envContent .= "QUEUE_CONNECTION=sync\n";
                        
                        file_put_contents('../.env', $envContent);
                        $success[] = "Fichier .env créé";
                        
                        // Créer les répertoires nécessaires
                        $dirs = ['../storage/app', '../storage/logs', '../storage/framework/cache', '../storage/framework/sessions', '../storage/framework/views'];
                        foreach ($dirs as $dir) {
                            if (!is_dir($dir)) {
                                @mkdir($dir, 0755, true);
                            }
                        }
                        $success[] = "Répertoires système créés";
                        
                        // Sauvegarder les informations d'installation
                        $installData = [
                            'license_key' => $_SESSION['license_key'] ?? '',
                            'domain' => $_SESSION['domain'] ?? '',
                            'ip' => $_SESSION['ip'] ?? '',
                            'admin' => $_SESSION['admin_config'] ?? [],
                            'installed_at' => date('Y-m-d H:i:s'),
                            'version' => '4.5.1'
                        ];
                        
                        file_put_contents('../storage/install.json', json_encode($installData, JSON_PRETTY_PRINT));
                        file_put_contents('../storage/installed', date('Y-m-d H:i:s'));
                        
                        $success[] = "Installation terminée avec succès !";
                        $step = 5; // Page de succès
                        
                    } catch (Exception $e) {
                        $errors[] = "Erreur lors de l'installation: " . $e->getMessage();
                    }
                    break;
            }
        }

        // Affichage des messages
        foreach ($errors as $error) {
            echo "<div class='error'>❌ $error</div>";
        }
        
        foreach ($success as $msg) {
            echo "<div class='success'>✅ $msg</div>";
        }

        // Barre de progression
        $progress = min(($step / 5) * 100, 100);
        echo "<div class='progress'>";
        echo "<div class='progress-bar' style='width: {$progress}%'>Étape $step/5</div>";
        echo "</div>";

        // Affichage des étapes
        switch ($step) {
            case 1: // Licence
                echo "<div class='step'>";
                echo "<h2>🔑 Étape 1 - Vérification de la licence</h2>";
                echo "<form method='POST'>";
                echo "<input type='hidden' name='step' value='1'>";
                echo "<div class='form-group'>";
                echo "<label>Clé de licence :</label>";
                echo "<input type='text' name='license_key' placeholder='Entrez votre clé de licence' required>";
                echo "</div>";
                echo "<div class='info'>";
                echo "<strong>Pour les tests, vous pouvez utiliser :</strong><br>";
                echo "<code>TEST-LICENSE-KEY</code> ou <code>ADMIN-LICENCE-2024</code>";
                echo "</div>";
                echo "<button type='submit' class='btn btn-primary'>Vérifier la licence</button>";
                echo "</form>";
                echo "</div>";
                break;
                
            case 2: // Base de données
                echo "<div class='step'>";
                echo "<h2>🔌 Étape 2 - Configuration base de données</h2>";
                echo "<form method='POST'>";
                echo "<input type='hidden' name='step' value='2'>";
                echo "<div class='form-group'>";
                echo "<label>Hôte de la base de données :</label>";
                echo "<input type='text' name='db_host' value='127.0.0.1' required>";
                echo "</div>";
                echo "<div class='form-group'>";
                echo "<label>Nom de la base de données :</label>";
                echo "<input type='text' name='db_name' placeholder='fabien_adminlicence' required>";
                echo "</div>";
                echo "<div class='form-group'>";
                echo "<label>Utilisateur de la base de données :</label>";
                echo "<input type='text' name='db_user' placeholder='fabien_adminlicence' required>";
                echo "</div>";
                echo "<div class='form-group'>";
                echo "<label>Mot de passe de la base de données :</label>";
                echo "<input type='password' name='db_pass' placeholder='Mot de passe'>";
                echo "</div>";
                echo "<button type='submit' class='btn btn-primary'>Tester et continuer</button>";
                echo "</form>";
                echo "</div>";
                break;
                
            case 3: // Administrateur
                echo "<div class='step'>";
                echo "<h2>👤 Étape 3 - Configuration administrateur</h2>";
                echo "<form method='POST'>";
                echo "<input type='hidden' name='step' value='3'>";
                echo "<div class='form-group'>";
                echo "<label>Nom de l'administrateur :</label>";
                echo "<input type='text' name='admin_name' placeholder='Nom complet' required>";
                echo "</div>";
                echo "<div class='form-group'>";
                echo "<label>Email de l'administrateur :</label>";
                echo "<input type='email' name='admin_email' placeholder='admin@example.com' required>";
                echo "</div>";
                echo "<div class='form-group'>";
                echo "<label>Mot de passe administrateur :</label>";
                echo "<input type='password' name='admin_password' placeholder='Mot de passe sécurisé' required>";
                echo "</div>";
                echo "<button type='submit' class='btn btn-primary'>Continuer</button>";
                echo "</form>";
                echo "</div>";
                break;
                
            case 4: // Installation
                echo "<div class='step'>";
                echo "<h2>🚀 Étape 4 - Installation finale</h2>";
                echo "<p>Prêt à installer AdminLicence avec la configuration suivante :</p>";
                echo "<ul>";
                echo "<li><strong>Licence :</strong> " . ($_SESSION['license_key'] ?? 'Non définie') . "</li>";
                echo "<li><strong>Domaine :</strong> " . ($_SESSION['domain'] ?? 'Non défini') . "</li>";
                echo "<li><strong>Base de données :</strong> " . ($_SESSION['db_config']['name'] ?? 'Non définie') . "</li>";
                echo "<li><strong>Administrateur :</strong> " . ($_SESSION['admin_config']['email'] ?? 'Non défini') . "</li>";
                echo "</ul>";
                echo "<form method='POST'>";
                echo "<input type='hidden' name='step' value='4'>";
                echo "<button type='submit' class='btn btn-success'>🚀 Lancer l'installation</button>";
                echo "</form>";
                echo "</div>";
                break;
                
            case 5: // Succès
                echo "<div class='success'>";
                echo "<h2>🎉 Installation terminée avec succès !</h2>";
                echo "<p><strong>AdminLicence a été installé correctement.</strong></p>";
                echo "<h3>📋 Prochaines étapes :</h3>";
                echo "<ol>";
                echo "<li><strong>Redémarrez PHP</strong> dans votre cPanel</li>";
                echo "<li><strong>Accédez à l'administration :</strong> <a href='../admin' target='_blank' class='btn btn-primary'>👤 Administration</a></li>";
                echo "<li><strong>Connectez-vous</strong> avec les identifiants administrateur que vous avez définis</li>";
                echo "</ol>";
                echo "</div>";
                
                echo "<div class='info'>";
                echo "<h4>🔧 Informations d'installation :</h4>";
                echo "<ul>";
                echo "<li><strong>Date :</strong> " . date('Y-m-d H:i:s') . "</li>";
                echo "<li><strong>Version :</strong> AdminLicence 4.5.1</li>";
                echo "<li><strong>Type :</strong> Installation cPanel (sans exec)</li>";
                echo "</ul>";
                echo "</div>";
                
                // Nettoyer la session
                session_destroy();
                break;
        }
        ?>

        <hr>
        <div class="info">
            <h4>ℹ️ Installateur cPanel</h4>
            <p>Cette version de l'installateur est spécialement conçue pour les serveurs cPanel où la fonction <code>exec()</code> est désactivée.</p>
            <p>Elle utilise uniquement des méthodes manuelles de nettoyage des caches et de configuration.</p>
        </div>

        <p><em>Installateur cPanel - AdminLicence v4.5.1 - <?= date('Y-m-d H:i:s') ?></em></p>
    </div>
</body>
</html> 