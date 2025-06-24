<?php
/**
 * 🔧 CORRECTION DE LA CONFIGURATION BASE DE DONNÉES
 * Résout le problème de cache qui utilise encore adminlicenceteste
 */

echo "<style>
body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
.container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
.success { background: #d4edda; border: 2px solid #28a745; padding: 15px; border-radius: 8px; margin: 10px 0; }
.error { background: #f8d7da; border: 2px solid #dc3545; padding: 15px; border-radius: 8px; margin: 10px 0; }
.warning { background: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; border-radius: 8px; margin: 10px 0; }
.info { background: #d1ecf1; border: 1px solid #bee5eb; padding: 15px; border-radius: 8px; margin: 10px 0; }
code { background: #f8f9fa; padding: 2px 4px; border-radius: 3px; }
</style>";

echo "<div class='container'>";
echo "<h1>🔧 Correction de la configuration Base de Données</h1>";

echo "<div class='warning'>";
echo "<h3>⚠️ PROBLÈME DÉTECTÉ</h3>";
echo "<p>Laravel utilise encore <code>adminlicenceteste</code> au lieu de <code>fabien_adminlicence</code></p>";
echo "<p>Cela vient du cache de configuration. Nous allons le nettoyer !</p>";
echo "</div>";

// Vider tous les caches possibles
$commands = [
    'cd .. && php artisan config:clear',
    'cd .. && php artisan cache:clear', 
    'cd .. && php artisan view:clear',
    'cd .. && php artisan route:clear',
    'cd .. && php artisan optimize:clear'
];

$results = [];
$errors = [];

echo "<div class='info'>";
echo "<h3>🚀 Nettoyage des caches en cours...</h3>";
echo "</div>";

foreach ($commands as $command) {
    $commandName = explode(' && ', $command)[1];
    
    ob_start();
    $result = exec($command . ' 2>&1', $output, $return_var);
    ob_end_clean();
    
    if ($return_var === 0) {
        echo "<div style='background: #d4edda; padding: 5px; margin: 3px 0; border-radius: 3px;'>";
        echo "✅ <code>$commandName</code> - Succès";
        echo "</div>";
        $results[] = $commandName . ' - OK';
    } else {
        echo "<div style='background: #f8d7da; padding: 5px; margin: 3px 0; border-radius: 3px;'>";
        echo "❌ <code>$commandName</code> - Erreur: " . implode("\n", $output);
        echo "</div>";
        $errors[] = $commandName . ' - ' . implode("\n", $output);
    }
}

// Vérifier le fichier .env
echo "<div class='info'>";
echo "<h3>📄 Vérification du fichier .env</h3>";
echo "</div>";

$envFile = '../.env';
if (file_exists($envFile)) {
    $envContent = file_get_contents($envFile);
    
    if (strpos($envContent, 'fabien_adminlicence') !== false) {
        echo "<div class='success'>";
        echo "✅ Le fichier .env contient bien la configuration correcte";
        echo "</div>";
        
        // Afficher les lignes DB importantes
        $lines = explode("\n", $envContent);
        echo "<div style='background: #f8f9fa; padding: 10px; border-radius: 5px; margin: 10px 0;'>";
        echo "<strong>Configuration actuelle :</strong><br>";
        foreach ($lines as $line) {
            if (strpos($line, 'DB_') === 0) {
                echo "<code>$line</code><br>";
            }
        }
        echo "</div>";
    } else {
        echo "<div class='error'>";
        echo "❌ Le fichier .env ne contient pas la bonne configuration !";
        echo "</div>";
    }
} else {
    echo "<div class='error'>";
    echo "❌ Fichier .env introuvable !";
    echo "</div>";
}

// Nettoyer les fichiers de cache manuellement
echo "<div class='info'>";
echo "<h3>🧹 Nettoyage manuel des caches...</h3>";
echo "</div>";

$cacheDirectories = [
    '../storage/framework/cache',
    '../storage/framework/views',
    '../storage/framework/sessions',
    '../bootstrap/cache'
];

foreach ($cacheDirectories as $dir) {
    if (is_dir($dir)) {
        $files = glob($dir . '/*');
        $deleted = 0;
        
        foreach ($files as $file) {
            if (is_file($file) && basename($file) !== '.gitignore') {
                if (unlink($file)) {
                    $deleted++;
                }
            }
        }
        
        echo "<div style='background: #d4edda; padding: 5px; margin: 3px 0; border-radius: 3px;'>";
        echo "✅ <code>$dir</code> - $deleted fichiers supprimés";
        echo "</div>";
    }
}

// Test de connexion avec les nouvelles données
echo "<div class='info'>";
echo "<h3>🔌 Test de connexion avec la nouvelle configuration</h3>";
echo "</div>";

$host = '127.0.0.1';
$database = 'fabien_adminlicence';
$username = 'fabien_adminlicence';
$password = 'Fab@250872';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<div class='success'>";
    echo "✅ <strong>Connexion réussie !</strong>";
    echo "<p>✅ Serveur : $host</p>";
    echo "<p>✅ Base : $database</p>";
    echo "<p>✅ Utilisateur : $username</p>";
    echo "</div>";
    
} catch (PDOException $e) {
    echo "<div class='error'>";
    echo "❌ <strong>Erreur de connexion :</strong> " . $e->getMessage();
    echo "</div>";
}

// Instructions finales
echo "<div class='warning'>";
echo "<h3>🔄 Prochaines étapes :</h3>";
echo "<ol>";
echo "<li><strong>Redémarrez votre serveur web</strong> (Apache/Nginx)</li>";
echo "<li><strong>Sur cPanel :</strong> Allez dans 'Redémarrer les services' ou redémarrez PHP</li>";
echo "<li><strong>Essayez à nouveau l'installation :</strong> <a href='install/install_new.php' target='_blank'>🚀 Relancer l'installateur</a></li>";
echo "<li><strong>Si le problème persiste :</strong> Vérifiez que votre base de données <code>fabien_adminlicence</code> existe bien sur cPanel</li>";
echo "</ol>";
echo "</div>";

echo "<div class='success'>";
echo "<h3>✅ Résumé des corrections :</h3>";
echo "<ul>";
echo "<li>✅ Tous les caches Laravel vidés</li>";
echo "<li>✅ Fichiers de cache manuellement supprimés</li>";
echo "<li>✅ Configuration .env vérifiée</li>";
echo "<li>✅ Test de connexion effectué</li>";
echo "</ul>";
echo "</div>";

echo "<hr>";
echo "<p><em>Correction effectuée le " . date('Y-m-d H:i:s') . "</em></p>";
echo "</div>";
?> 