<?php
/**
 * 🔍 DIAGNOSTIC INSTALLATEUR
 * Trouve pourquoi install_new.php retourne 404
 */
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>🔍 Diagnostic Installateur</title>
    <style>
        body { font-family: Arial; margin: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 10px; }
        .success { background: #d4edda; border: 1px solid #28a745; padding: 10px; margin: 10px 0; border-radius: 5px; }
        .error { background: #f8d7da; border: 1px solid #dc3545; padding: 10px; margin: 10px 0; border-radius: 5px; }
        .warning { background: #fff3cd; border: 1px solid #ffc107; padding: 10px; margin: 10px 0; border-radius: 5px; }
        .info { background: #d1ecf1; border: 1px solid #17a2b8; padding: 10px; margin: 10px 0; border-radius: 5px; }
        code { background: #f8f9fa; padding: 2px 4px; border-radius: 3px; font-family: monospace; }
        .file-list { background: #f8f9fa; padding: 10px; border-radius: 5px; font-family: monospace; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 DIAGNOSTIC INSTALLATEUR</h1>
        <p>Analyse pourquoi l'installateur retourne une erreur 404</p>

        <?php
        echo "<div class='info'>";
        echo "<h3>📍 Emplacement actuel :</h3>";
        echo "<p><strong>Répertoire :</strong> <code>" . __DIR__ . "</code></p>";
        echo "<p><strong>URL testée :</strong> <code>https://adminlicence.eu/install/install_new.php</code></p>";
        echo "</div>";

        // 1. Vérifier l'existence des fichiers d'installation
        echo "<h3>📁 VÉRIFICATION DES FICHIERS :</h3>";
        
        $installFiles = [
            'install_new.php' => 'Installateur principal',
            'install/install_new.php' => 'Installateur dans sous-dossier install',
            'install_standalone.php' => 'Installateur autonome',
            'index.php' => 'Index principal'
        ];
        
        foreach ($installFiles as $file => $description) {
            if (file_exists($file)) {
                echo "<div class='success'>✅ <strong>$file</strong> - $description (" . filesize($file) . " bytes)</div>";
            } else {
                echo "<div class='error'>❌ <strong>$file</strong> - $description (MANQUANT)</div>";
            }
        }

        // 2. Lister tous les fichiers du répertoire public
        echo "<h3>📂 CONTENU DU RÉPERTOIRE PUBLIC :</h3>";
        echo "<div class='file-list'>";
        
        $files = scandir('.');
        $phpFiles = [];
        $directories = [];
        
        foreach ($files as $file) {
            if ($file === '.' || $file === '..') continue;
            
            if (is_dir($file)) {
                $directories[] = $file;
            } elseif (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
                $phpFiles[] = $file;
            }
        }
        
        echo "<strong>📁 Dossiers :</strong><br>";
        foreach ($directories as $dir) {
            echo "📁 $dir/<br>";
            
            // Lister le contenu du dossier install s'il existe
            if ($dir === 'install' && is_dir('install')) {
                $installFiles = scandir('install');
                foreach ($installFiles as $installFile) {
                    if ($installFile !== '.' && $installFile !== '..') {
                        echo "&nbsp;&nbsp;&nbsp;📄 install/$installFile<br>";
                    }
                }
            }
        }
        
        echo "<br><strong>🐘 Fichiers PHP :</strong><br>";
        foreach ($phpFiles as $phpFile) {
            echo "🐘 $phpFile<br>";
        }
        echo "</div>";

        // 3. Vérifier la configuration du serveur web
        echo "<h3>🌐 CONFIGURATION SERVEUR :</h3>";
        
        echo "<div class='info'>";
        echo "<p><strong>Serveur :</strong> " . ($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown') . "</p>";
        echo "<p><strong>Document Root :</strong> " . ($_SERVER['DOCUMENT_ROOT'] ?? 'Unknown') . "</p>";
        echo "<p><strong>Script Name :</strong> " . ($_SERVER['SCRIPT_NAME'] ?? 'Unknown') . "</p>";
        echo "<p><strong>Request URI :</strong> " . ($_SERVER['REQUEST_URI'] ?? 'Unknown') . "</p>";
        echo "</div>";

        // 4. Tester l'accessibilité des fichiers
        echo "<h3>🔗 TESTS D'ACCESSIBILITÉ :</h3>";
        
        $baseUrl = 'https://adminlicence.eu';
        $testFiles = [
            '/install_debug.php' => 'Ce fichier de diagnostic',
            '/production_fix.php' => 'Script de correction',
            '/install/install_new.php' => 'Installateur dans /install/',
            '/install_new.php' => 'Installateur à la racine',
            '/install_standalone.php' => 'Installateur autonome'
        ];
        
        foreach ($testFiles as $path => $description) {
            $fullUrl = $baseUrl . $path;
            $localFile = ltrim($path, '/');
            
            if (file_exists($localFile)) {
                echo "<div class='success'>";
                echo "✅ <strong>$description</strong><br>";
                echo "📄 Fichier local : <code>$localFile</code> existe<br>";
                echo "🌐 URL : <a href='$fullUrl' target='_blank'>$fullUrl</a>";
                echo "</div>";
            } else {
                echo "<div class='error'>";
                echo "❌ <strong>$description</strong><br>";
                echo "📄 Fichier local : <code>$localFile</code> MANQUANT";
                echo "</div>";
            }
        }

        // 5. Vérification des permissions
        echo "<h3>🔐 PERMISSIONS :</h3>";
        
        $checkPermissions = [
            '.' => 'Répertoire actuel',
            'install' => 'Dossier install',
            'install_new.php' => 'Fichier installateur (racine)',
            'install/install_new.php' => 'Fichier installateur (install/)'
        ];
        
        foreach ($checkPermissions as $path => $description) {
            if (file_exists($path)) {
                $perms = fileperms($path);
                $readablePerms = substr(sprintf('%o', $perms), -4);
                
                if (is_readable($path)) {
                    echo "<div class='success'>✅ <strong>$description</strong> - Permissions: $readablePerms (LISIBLE)</div>";
                } else {
                    echo "<div class='error'>❌ <strong>$description</strong> - Permissions: $readablePerms (NON LISIBLE)</div>";
                }
            }
        }

        // 6. Analyse du problème et solutions
        echo "<h3>💡 DIAGNOSTIC ET SOLUTIONS :</h3>";
        
        $installInSubdir = file_exists('install/install_new.php');
        $installInRoot = file_exists('install_new.php');
        
        if ($installInSubdir && !$installInRoot) {
            echo "<div class='warning'>";
            echo "<h4>🎯 PROBLÈME IDENTIFIÉ :</h4>";
            echo "<p>L'installateur existe dans <code>/install/install_new.php</code> mais pas à la racine.</p>";
            echo "<p>Vous essayez d'accéder à : <code>https://adminlicence.eu/install/install_new.php</code></p>";
            echo "<p>Le fichier est dans : <code>public/install/install_new.php</code></p>";
            
            echo "<h4>✅ SOLUTIONS :</h4>";
            echo "<ol>";
            echo "<li><strong>Utiliser l'URL correcte :</strong> <a href='install/install_new.php' class='btn btn-success' target='_blank'>https://adminlicence.eu/install/install_new.php</a></li>";
            echo "<li><strong>Ou copier le fichier à la racine</strong> (voir bouton ci-dessous)</li>";
            echo "</ol>";
            echo "</div>";
            
        } elseif (!$installInSubdir && !$installInRoot) {
            echo "<div class='error'>";
            echo "<h4>❌ PROBLÈME MAJEUR :</h4>";
            echo "<p>Aucun fichier <code>install_new.php</code> trouvé !</p>";
            echo "<p>Le fichier d'installation est manquant.</p>";
            echo "</div>";
            
        } elseif ($installInRoot) {
            echo "<div class='success'>";
            echo "<h4>✅ INSTALLATEUR TROUVÉ :</h4>";
            echo "<p>L'installateur existe à la racine : <a href='install_new.php' target='_blank'>install_new.php</a></p>";
            echo "</div>";
        }

        // 7. Actions de correction automatique
        echo "<h3>🔧 ACTIONS CORRECTIVES :</h3>";
        
        if (isset($_GET['action'])) {
            $action = $_GET['action'];
            
            if ($action === 'copy_installer' && file_exists('install/install_new.php')) {
                if (copy('install/install_new.php', 'install_new.php')) {
                    echo "<div class='success'>✅ Installateur copié vers la racine avec succès !</div>";
                    echo "<p><a href='install_new.php' target='_blank'>🚀 Tester l'installateur</a></p>";
                } else {
                    echo "<div class='error'>❌ Impossible de copier l'installateur</div>";
                }
            }
        } else {
            echo "<div class='info'>";
            if (file_exists('install/install_new.php') && !file_exists('install_new.php')) {
                echo "<p><a href='?action=copy_installer' class='btn btn-primary'>📋 Copier l'installateur vers la racine</a></p>";
            }
            echo "<p><a href='production_fix.php' class='btn btn-warning'>🚀 Outil de correction complet</a></p>";
            echo "</div>";
        }

        // 8. Résumé final
        echo "<hr>";
        echo "<div class='info'>";
        echo "<h4>📋 RÉSUMÉ :</h4>";
        echo "<ul>";
        echo "<li>Diagnostic effectué le " . date('Y-m-d H:i:s') . "</li>";
        echo "<li>Serveur : " . ($_SERVER['HTTP_HOST'] ?? 'Unknown') . "</li>";
        echo "<li>Répertoire : " . __DIR__ . "</li>";
        echo "</ul>";
        echo "</div>";
        ?>
    </div>
</body>
</html> 