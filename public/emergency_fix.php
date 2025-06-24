<?php
/**
 * 🚨 CORRECTION D'URGENCE - AdminLicence
 * Répare l'erreur fatale causée par le patch IP mal placé
 */
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>🚨 Correction d'Urgence</title>
    <style>
        body { font-family: Arial; margin: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 10px; }
        .success { background: #d4edda; border: 1px solid #28a745; padding: 15px; margin: 10px 0; border-radius: 5px; }
        .error { background: #f8d7da; border: 1px solid #dc3545; padding: 15px; margin: 10px 0; border-radius: 5px; }
        .warning { background: #fff3cd; border: 1px solid #ffc107; padding: 15px; margin: 10px 0; border-radius: 5px; }
        .info { background: #d1ecf1; border: 1px solid #17a2b8; padding: 15px; margin: 10px 0; border-radius: 5px; }
        .btn { padding: 10px 20px; margin: 5px; border: none; border-radius: 5px; text-decoration: none; display: inline-block; color: white; }
        .btn-danger { background: #dc3545; }
        .btn-success { background: #28a745; }
        .btn-primary { background: #007bff; }
        code { background: #f8f9fa; padding: 2px 4px; border-radius: 3px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🚨 CORRECTION D'URGENCE</h1>
        <p>Réparation de l'erreur fatale PHP</p>

        <?php
        $action = $_GET['action'] ?? 'menu';
        
        function logMsg($msg, $type = 'info') {
            $time = date('H:i:s');
            $icons = ['success' => '✅', 'error' => '❌', 'warning' => '⚠️', 'info' => 'ℹ️'];
            echo "<div class='$type'>[$time] {$icons[$type]} $msg</div>";
            flush();
        }

        if ($action === 'menu') {
            echo "<div class='error'>";
            echo "<h3>🚨 ERREUR FATALE DÉTECTÉE</h3>";
            echo "<p><strong>Problème :</strong> Patch IP mal appliqué</p>";
            echo "<p><strong>Erreur :</strong> <code>require_once</code> avant <code>namespace</code></p>";
            echo "<p><strong>Résultat :</strong> Erreur 500 sur tout le site</p>";
            echo "</div>";

            echo "<div class='info'>";
            echo "<h3>🚀 SOLUTIONS :</h3>";
            echo "<a href='?action=remove_patch' class='btn btn-danger'>🗑️ SUPPRIMER LE PATCH (Recommandé)</a><br><br>";
            echo "<a href='?action=restore_backups' class='btn btn-primary'>🔄 Restaurer sauvegardes</a>";
            echo "</div>";

        } elseif ($action === 'remove_patch') {
            echo "<h2>🗑️ SUPPRESSION DU PATCH DÉFAILLANT</h2>";
            
            logMsg("Suppression du patch IP problématique...", 'info');
            
            $files = [
                '../app/Http/Controllers/Admin/LicenseController.php',
                '../app/Http/Controllers/Admin/SettingsController.php',
                '../app/Services/LicenseService.php'
            ];
            
            $fixed = 0;
            foreach ($files as $file) {
                if (file_exists($file)) {
                    $content = file_get_contents($file);
                    $original = $content;
                    
                    // Supprimer le require_once problématique
                    $content = str_replace("require_once __DIR__ . '/../../ip_fix_patch.php';\n", '', $content);
                    $content = str_replace("require_once __DIR__ . '/../../ip_fix_patch.php';", '', $content);
                    
                    // Remettre $_SERVER['REMOTE_ADDR'] à la place de getClientIPFixed()
                    $content = str_replace('getClientIPFixed()', '$_SERVER[\'REMOTE_ADDR\']', $content);
                    
                    // Nettoyer les lignes vides
                    $content = preg_replace('/\n\s*\n\s*\n/', "\n\n", $content);
                    
                    if ($content !== $original) {
                        if (file_put_contents($file, $content)) {
                            logMsg("✅ Nettoyé: " . basename($file), 'success');
                            $fixed++;
                        }
                    }
                }
            }
            
            // Supprimer le fichier de patch
            if (file_exists('../ip_fix_patch.php')) {
                @unlink('../ip_fix_patch.php');
                logMsg("✅ Fichier patch supprimé", 'success');
            }
            
            if ($fixed > 0) {
                echo "<div class='success'>";
                echo "<h3>🎉 CORRECTION TERMINÉE !</h3>";
                echo "<p>L'erreur fatale a été corrigée. Le site devrait maintenant fonctionner.</p>";
                echo "<h4>🔗 Testez maintenant :</h4>";
                echo "<p><a href='install_new.php' target='_blank' class='btn btn-success'>🚀 Installateur</a></p>";
                echo "<p><a href='../admin' target='_blank' class='btn btn-primary'>👤 Administration</a></p>";
                echo "</div>";
                
                echo "<div class='warning'>";
                echo "<h4>⚠️ Note importante :</h4>";
                echo "<p>Le problème d'IP de licence reviendra, mais le site fonctionnera.</p>";
                echo "<p>Nous traiterons le problème d'IP séparément une fois le site stable.</p>";
                echo "</div>";
            }

        } elseif ($action === 'restore_backups') {
            echo "<h2>🔄 RESTAURATION DES SAUVEGARDES</h2>";
            
            logMsg("Recherche des sauvegardes...", 'info');
            
            $backups = glob('../app/Http/Controllers/Admin/*.backup.*');
            $serviceBackups = glob('../app/Services/*.backup.*');
            $allBackups = array_merge($backups, $serviceBackups);
            
            if (empty($allBackups)) {
                logMsg("❌ Aucune sauvegarde trouvée", 'error');
                echo "<div class='warning'>";
                echo "<p>Utilisez plutôt l'option 'Supprimer le patch'.</p>";
                echo "</div>";
            } else {
                $restored = 0;
                foreach ($allBackups as $backup) {
                    $original = preg_replace('/\.backup\.\d{8}_\d{6}$/', '', $backup);
                    if (copy($backup, $original)) {
                        logMsg("✅ Restauré: " . basename($original), 'success');
                        $restored++;
                    }
                }
                
                if ($restored > 0) {
                    echo "<div class='success'>";
                    echo "<h3>✅ RESTAURATION TERMINÉE</h3>";
                    echo "<p>$restored fichier(s) restauré(s).</p>";
                    echo "<p><a href='../admin' target='_blank' class='btn btn-primary'>Tester l'admin</a></p>";
                    echo "</div>";
                }
            }
        }

        if ($action !== 'menu') {
            echo "<p><a href='?action=menu' class='btn btn-primary'>← Menu</a></p>";
        }
        ?>

        <hr>
        <div class="error">
            <h4>🔍 DIAGNOSTIC :</h4>
            <p>Le script précédent a placé <code>require_once</code> avant <code>namespace</code>, causant une erreur fatale PHP.</p>
            <p>Cette correction va nettoyer le code et restaurer le fonctionnement normal.</p>
        </div>

        <p><em>Script d'urgence - <?= date('Y-m-d H:i:s') ?></em></p>
    </div>
</body>
</html> 