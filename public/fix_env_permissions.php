<?php
/**
 * 🔧 Correcteur de permissions .env pour cPanel
 */
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>🔧 Correction permissions .env</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; }
        .success { background: #d4edda; border: 1px solid #28a745; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .error { background: #f8d7da; border: 1px solid #dc3545; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .info { background: #d1ecf1; border: 1px solid #17a2b8; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .btn { padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; }
        code { background: #f8f9fa; padding: 2px 4px; border-radius: 3px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 Correction permissions .env</h1>
        
        <?php
        $envPath = '../.env';
        $results = [];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // 1. Vérifier si .env existe
            if (!file_exists($envPath)) {
                // Créer le fichier .env
                $defaultEnv = "APP_NAME=AdminLicence
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=" . (isset($_SERVER['HTTPS']) ? 'https' : 'http') . "://" . $_SERVER['HTTP_HOST'] . "

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync";
                
                if (file_put_contents($envPath, $defaultEnv)) {
                    $results[] = ['type' => 'success', 'msg' => '✅ Fichier .env créé'];
                } else {
                    $results[] = ['type' => 'error', 'msg' => '❌ Impossible de créer .env'];
                }
            }
            
            // 2. Corriger les permissions
            if (file_exists($envPath)) {
                // Essayer différentes permissions
                $permissions = [0666, 0664, 0644, 0755];
                $success = false;
                
                foreach ($permissions as $perm) {
                    if (@chmod($envPath, $perm)) {
                        $results[] = ['type' => 'success', 'msg' => "✅ Permissions définies à " . decoct($perm)];
                        $success = true;
                        break;
                    }
                }
                
                if (!$success) {
                    $results[] = ['type' => 'error', 'msg' => '❌ Impossible de modifier les permissions'];
                }
                
                // 3. Test d'écriture
                $testContent = file_get_contents($envPath);
                if (file_put_contents($envPath, $testContent)) {
                    $results[] = ['type' => 'success', 'msg' => '✅ Test d\'écriture réussi'];
                } else {
                    $results[] = ['type' => 'error', 'msg' => '❌ Test d\'écriture échoué'];
                }
            }
            
            // 4. Vérifier les permissions des répertoires parents
            $parentDir = dirname($envPath);
            if (is_writable($parentDir)) {
                $results[] = ['type' => 'success', 'msg' => '✅ Répertoire parent accessible en écriture'];
            } else {
                $results[] = ['type' => 'error', 'msg' => '❌ Répertoire parent non accessible en écriture'];
                
                // Essayer de corriger le répertoire parent
                if (@chmod($parentDir, 0755)) {
                    $results[] = ['type' => 'success', 'msg' => '✅ Permissions du répertoire parent corrigées'];
                }
            }
        }
        
        // Afficher les résultats
        foreach ($results as $result) {
            echo "<div class='{$result['type']}'>{$result['msg']}</div>";
        }
        
        // État actuel
        echo "<div class='info'>";
        echo "<h3>📋 État actuel :</h3>";
        echo "<ul>";
        echo "<li><strong>Fichier .env existe :</strong> " . (file_exists($envPath) ? '✅ Oui' : '❌ Non') . "</li>";
        echo "<li><strong>Lisible :</strong> " . (is_readable($envPath) ? '✅ Oui' : '❌ Non') . "</li>";
        echo "<li><strong>Accessible en écriture :</strong> " . (is_writable($envPath) ? '✅ Oui' : '❌ Non') . "</li>";
        if (file_exists($envPath)) {
            $perms = fileperms($envPath);
            echo "<li><strong>Permissions actuelles :</strong> " . decoct($perms & 0777) . "</li>";
        }
        echo "</ul>";
        echo "</div>";
        ?>
        
        <form method="POST">
            <button type="submit" class="btn">🔧 Corriger les permissions</button>
        </form>
        
        <div class="info">
            <h4>ℹ️ Instructions manuelles (si le script échoue) :</h4>
            <ol>
                <li>Connectez-vous au <strong>Gestionnaire de fichiers cPanel</strong></li>
                <li>Naviguez vers le répertoire racine de votre site</li>
                <li>Clic droit sur le fichier <code>.env</code></li>
                <li>Sélectionnez <strong>"Permissions"</strong></li>
                <li>Définissez les permissions à <code>644</code> ou <code>666</code></li>
                <li>Cliquez sur <strong>"Modifier les permissions"</strong></li>
            </ol>
        </div>
        
        <p><a href="install/install_new.php">🔙 Retour à l'installateur</a></p>
    </div>
</body>
</html> 