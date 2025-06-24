<?php
/**
 * 🚀 INSTALLATEUR DE SECOURS - AdminLicence
 * Version standalone qui fonctionne même si Laravel a des problèmes
 */

session_start();

// Fonction de nettoyage manuel du cache
function cleanCacheManually() {
    $cacheFiles = [
        'bootstrap/cache/config.php',
        'bootstrap/cache/routes-v7.php', 
        'bootstrap/cache/services.php',
        'bootstrap/cache/packages.php',
        'bootstrap/cache/compiled.php'
    ];
    
    $cleaned = 0;
    foreach ($cacheFiles as $file) {
        if (file_exists($file)) {
            if (unlink($file)) {
                $cleaned++;
            }
        }
    }
    
    // Nettoyer aussi les répertoires de cache
    $cacheDirs = [
        'storage/framework/cache/data',
        'storage/framework/sessions',
        'storage/framework/views'
    ];
    
    foreach ($cacheDirs as $dir) {
        if (is_dir($dir)) {
            $files = glob($dir . '/*');
            foreach ($files as $file) {
                if (is_file($file) && basename($file) !== '.gitignore') {
                    @unlink($file);
                    $cleaned++;
                }
            }
        }
    }
    
    return $cleaned;
}

// Vérifier et créer .env
function createEnvFile() {
    if (!file_exists('.env')) {
        if (file_exists('.env.example')) {
            copy('.env.example', '.env');
            return "Fichier .env créé depuis .env.example";
        } else {
            $defaultEnv = "APP_NAME=AdminLicence
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=http://localhost

LOG_CHANNEL=stack
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=

CACHE_DRIVER=file
SESSION_DRIVER=file
";
            file_put_contents('.env', $defaultEnv);
            return "Fichier .env créé avec configuration par défaut";
        }
    }
    return "Fichier .env existe déjà";
}

// Traitement des actions
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'clean_cache':
                $cleaned = cleanCacheManually();
                $message = "✅ Cache nettoyé : $cleaned fichiers supprimés";
                break;
                
            case 'create_env':
                $result = createEnvFile();
                $message = "✅ " . $result;
                break;
                
            case 'test_db':
                $host = $_POST['db_host'] ?? '127.0.0.1';
                $database = $_POST['db_database'] ?? '';
                $username = $_POST['db_username'] ?? '';
                $password = $_POST['db_password'] ?? '';
                
                try {
                    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
                    $message = "✅ Connexion base de données réussie !";
                } catch (PDOException $e) {
                    $error = "❌ Erreur de connexion : " . $e->getMessage();
                }
                break;
                
            case 'fix_license_redirect':
                // Créer un fichier de redirection personnalisé
                $redirectCode = '<?php
// Redirection personnalisée après validation de licence
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION["license_validated"]) && $_SESSION["license_validated"] === true) {
    header("Location: /admin/dashboard");
    exit;
}
?>';
                file_put_contents('license_redirect.php', $redirectCode);
                $message = "✅ Système de redirection de licence créé";
                break;
        }
    }
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🚀 Installateur de Secours - AdminLicence</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .success { background: #d4edda; border: 2px solid #28a745; padding: 15px; border-radius: 8px; margin: 10px 0; }
        .error { background: #f8d7da; border: 2px solid #dc3545; padding: 15px; border-radius: 8px; margin: 10px 0; }
        .warning { background: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; border-radius: 8px; margin: 10px 0; }
        .info { background: #d1ecf1; border: 1px solid #bee5eb; padding: 15px; border-radius: 8px; margin: 10px 0; }
        .btn { background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; border: none; cursor: pointer; margin: 5px; display: inline-block; }
        .btn:hover { background: #0056b3; }
        .btn-danger { background: #dc3545; }
        .btn-danger:hover { background: #c82333; }
        .btn-success { background: #28a745; }
        .btn-success:hover { background: #218838; }
        .form-group { margin: 15px 0; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], input[type="password"] { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; }
        code { background: #f8f9fa; padding: 2px 4px; border-radius: 3px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🚀 Installateur de Secours AdminLicence</h1>
        
        <div class="warning">
            <h3>⚠️ Cet installateur est utilisé quand l'installateur principal ne fonctionne pas</h3>
            <p>Utilisez les boutons ci-dessous pour résoudre les problèmes courants :</p>
        </div>

        <?php if ($message): ?>
            <div class="success"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <h3>🔧 Actions de réparation :</h3>

        <!-- Nettoyage du cache -->
        <form method="post" style="display: inline;">
            <input type="hidden" name="action" value="clean_cache">
            <button type="submit" class="btn btn-danger">🧹 Nettoyer le cache manuellement</button>
        </form>

        <!-- Création du .env -->
        <form method="post" style="display: inline;">
            <input type="hidden" name="action" value="create_env">
            <button type="submit" class="btn">📄 Créer/Vérifier le fichier .env</button>
        </form>

        <!-- Correction redirection licence -->
        <form method="post" style="display: inline;">
            <input type="hidden" name="action" value="fix_license_redirect">
            <button type="submit" class="btn btn-success">🔗 Corriger la redirection de licence</button>
        </form>

        <h3>🔌 Test de connexion base de données :</h3>
        <form method="post">
            <input type="hidden" name="action" value="test_db">
            
            <div class="form-group">
                <label for="db_host">Serveur :</label>
                <input type="text" name="db_host" value="127.0.0.1" required>
            </div>
            
            <div class="form-group">
                <label for="db_database">Base de données :</label>
                <input type="text" name="db_database" placeholder="votre_base" required>
            </div>
            
            <div class="form-group">
                <label for="db_username">Utilisateur :</label>
                <input type="text" name="db_username" placeholder="votre_utilisateur" required>
            </div>
            
            <div class="form-group">
                <label for="db_password">Mot de passe :</label>
                <input type="password" name="db_password" placeholder="votre_mot_de_passe">
            </div>
            
            <button type="submit" class="btn">🔌 Tester la connexion</button>
        </form>

        <div class="info">
            <h4>📋 Liens utiles :</h4>
            <ul>
                <li><a href="install/install_new.php" target="_blank">🚀 Essayer l'installateur principal</a></li>
                <li><a href="admin/settings/license" target="_blank">⚙️ Configuration de licence (si DB importée)</a></li>
                <li><a href="admin/dashboard" target="_blank">📊 Dashboard admin</a></li>
            </ul>
        </div>

        <div class="warning">
            <h4>🔧 Instructions pour les problèmes courants :</h4>
            <ol>
                <li><strong>Erreur 404 installateur :</strong> Utilisez cet installateur de secours</li>
                <li><strong>Cache qui ne se vide pas :</strong> Cliquez "Nettoyer le cache manuellement"</li>
                <li><strong>Bloqué sur page de licence :</strong> Cliquez "Corriger la redirection de licence"</li>
                <li><strong>IP non autorisée :</strong> Vérifiez que l'IP du serveur correspond à celle enregistrée</li>
            </ol>
        </div>
    </div>
</body>
</html> 