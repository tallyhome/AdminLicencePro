<?php
/**
 * 🧹 SCRIPT DE NETTOYAGE AUTOMATIQUE - AdminLicence
 * Supprime automatiquement tous les fichiers temporaires et de test
 */

echo "<style>
body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
.container { max-width: 1000px; margin: 0 auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
.success { background: #d4edda; border: 2px solid #28a745; padding: 15px; border-radius: 8px; margin: 10px 0; }
.error { background: #f8d7da; border: 2px solid #dc3545; padding: 15px; border-radius: 8px; margin: 10px 0; }
.warning { background: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; border-radius: 8px; margin: 10px 0; }
.info { background: #d1ecf1; border: 1px solid #bee5eb; padding: 15px; border-radius: 8px; margin: 10px 0; }
code { background: #f8f9fa; padding: 2px 4px; border-radius: 3px; }
</style>";

echo "<div class='container'>";
echo "<h1>🧹 Script de nettoyage automatique</h1>";

// Vérification de sécurité
if (!isset($_GET['confirm']) || $_GET['confirm'] !== 'yes') {
    echo "<div class='warning'>";
    echo "<h3>⚠️ CONFIRMATION REQUISE</h3>";
    echo "<p>Ce script va supprimer définitivement tous les fichiers de test et temporaires.</p>";
    echo "<p><strong>Êtes-vous sûr de vouloir continuer ?</strong></p>";
    echo "<p><a href='?confirm=yes' style='background: #dc3545; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>🗑️ OUI, SUPPRIMER LES FICHIERS</a></p>";
    echo "<p><a href='cleanup_analysis.php' style='background: #6c757d; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>📋 Retour à l'analyse</a></p>";
    echo "</div>";
    echo "</div>";
    exit;
}

echo "<div class='info'>";
echo "<h3>🚀 Nettoyage en cours...</h3>";
echo "<p>Suppression des fichiers temporaires et de test...</p>";
echo "</div>";

// Liste des fichiers à supprimer (même liste que dans l'analyse)
$filesToDelete = [
    '🧪 TESTS DE NAVIGATION (nos créations)' => [
        'test_all_steps_complete.php',
        'test_step4_navigation_fix.php',
        'test_step3_complete_fix.php', 
        'test_step2_navigation_fix.php',
        'test_install_corrected.php',
    ],
    
    '🔧 VERSIONS INSTALLATION TEMPORAIRES' => [
        'install_corrected.php',
        'install_solution_finale.php',
        'install_step2_fixed.php',
        'install_fixed_final.php',
        'install_ultra_simple.php',
        'install_standalone.php',
        'install_fixed.php',
        'install_simple.php',
        'installation_complete_fix.php',
    ],
    
    '🐛 DEBUG ÉTAPE 2' => [
        'test_step2_fix.php',
        'fix_step2_problem.php',
        'test_step2_session.php',
        'test_session_fix.php',
        'debug_step2.php',
        'test_steps_reorganized.php',
        'test_corrections_step2.php',
    ],
    
    '🌐 TESTS TRADUCTIONS' => [
        'test_interface_translations.php',
        'test_all_languages.php',
        'test_debug_install.php',
        'test_translations_fix.php',
        'recapitulatif_menu_langues.php',
        'test_menu_langues.php',
        'check_missing_translations.php',
        'fix_missing_translations.php',
    ],
    
    '🔑 TESTS API/LICENSE' => [
        'test-api-key.php',
        'test-api-route.php',
        'test-api-simple.php',
        'test-api-with-key.php',
        'test-cle-api.php',
        'debug_install_licence.php',
        'test_licence_final.php',
        'test_licence_full.php',
    ],
    
    '📊 STATUS/DIAGNOSTICS TEMPORAIRES' => [
        'status_final_corrections.php',
        'status_installation.php',
        'test_install_corrections.php',
        'test_install_system_requirements.php',
        'test_system_requirements.php',
        'test_install_final_check.php',
        'test_corrections_final.php',
        'test_corrections_finales.php',
        'test_boutons_final.php',
        'test_installation_ui.php',
        'test_install_direct.php',
    ],
    
    '🛠️ FICHIERS CURL/BATCH' => [
        'curl-test.bat',
        'curl-test.ps1',
    ],
    
    '📁 FICHIERS RACINE TEMPORAIRES' => [
        '../test_bouton.html',
        '../test_simple.php',
        '../test_method.php',
        '../test-google2fa.php',
        '../test_favicon.txt',
        '../force_license_refresh2.php',
        '../simple_diagnose.php',
        '../check_license.php',
        '../update_license_key.php',
        '../check_env_var.php',
        '../update_licence_key.php',
        '../teste-install.env',
        '../php.ini',
        '../serve.php',
    ],
    
    '💾 BACKUPS COMPOSER' => [
        '../composer.json.backup-2025-06-01-042020',
        '../composer.json.backup-2025-06-01-044041',
        '../composer.json.backup-2025-06-01-044201',
    ],
    
    '⚙️ SCRIPTS MAINTENANCE TEMPORAIRES' => [
        '../check_env.php',
        '../check_migrations.php', 
        '../check_table_structure.php',
        '../clear-cache.php',
        '../disable_ssl_verify.php',
        '../enable_debug_mode.php',
        '../fix_config.php',
        '../fix_ssl_error.php',
        '../generate_key.php',
        '../mark_migrations_as_completed.php',
        '../update_composer_packages.php',
        '../update_dependencies.php',
        '../update_env.php',
        '../update_env_security.php',
        '../update_license_controller_translations.php',
        '../update_translations.php',
        '../cleanup.ps1',
    ],
    
    '📄 DOCUMENTATION TEMPORAIRE' => [
        '../security_improvements.md',
        '../security_improvements_summary.md', 
        '../security_recommendations.md',
    ],
];

$totalDeleted = 0;
$totalSize = 0;
$errors = [];

// Suppression des fichiers
foreach ($filesToDelete as $category => $files) {
    echo "<h4>$category</h4>";
    
    foreach ($files as $file) {
        if (file_exists($file)) {
            $size = filesize($file);
            $totalSize += $size;
            
            if (unlink($file)) {
                $totalDeleted++;
                echo "<div style='background: #d4edda; padding: 5px; margin: 3px 0; border-radius: 3px;'>";
                echo "✅ Supprimé : <code>$file</code> (" . formatBytes($size) . ")";
                echo "</div>";
            } else {
                $errors[] = $file;
                echo "<div style='background: #f8d7da; padding: 5px; margin: 3px 0; border-radius: 3px;'>";
                echo "❌ Erreur : <code>$file</code>";
                echo "</div>";
            }
        } else {
            echo "<div style='background: #e2e3e5; padding: 5px; margin: 3px 0; border-radius: 3px; opacity: 0.7;'>";
            echo "⚪ Inexistant : <code>$file</code>";
            echo "</div>";
        }
    }
    echo "<br>";
}

// Suppression de l'archive ZIP si elle existe
$zipFile = '../AdminLicence-4.5.2.5.zip';
if (file_exists($zipFile)) {
    $size = filesize($zipFile);
    if (unlink($zipFile)) {
        $totalDeleted++;
        $totalSize += $size;
        echo "<div class='success'>";
        echo "✅ Archive supprimée : <code>$zipFile</code> (" . formatBytes($size) . ")";
        echo "</div>";
    }
}

// Nettoyage des fichiers de cette série de scripts aussi
$scriptsToCleanup = ['../cleanup_analysis.php', '../cleanup_files.php'];
foreach ($scriptsToCleanup as $script) {
    if (file_exists($script)) {
        $size = filesize($script);
        if (unlink($script)) {
            $totalDeleted++;
            $totalSize += $size;
            echo "<div style='background: #d4edda; padding: 5px; margin: 3px 0; border-radius: 3px;'>";
            echo "✅ Script nettoyé : <code>$script</code> (" . formatBytes($size) . ")";
            echo "</div>";
        }
    }
}

// Résumé final
echo "<div class='success'>";
echo "<h3>🎉 Nettoyage terminé !</h3>";
echo "<ul>";
echo "<li><strong>Fichiers supprimés :</strong> $totalDeleted</li>";
echo "<li><strong>Espace libéré :</strong> " . formatBytes($totalSize) . "</li>";
echo "<li><strong>Erreurs :</strong> " . count($errors) . "</li>";
echo "</ul>";
echo "</div>";

if (!empty($errors)) {
    echo "<div class='error'>";
    echo "<h4>❌ Erreurs rencontrées :</h4>";
    foreach ($errors as $error) {
        echo "<p>• <code>$error</code></p>";
    }
    echo "</div>";
}

echo "<div class='info'>";
echo "<h4>📋 Fichiers conservés (IMPORTANTS) :</h4>";
echo "<ul>";
echo "<li>✅ <code>install/install_new.php</code> - Installateur corrigé</li>";
echo "<li>✅ <code>install/assets/js/install.js</code> - JavaScript corrigé</li>";
echo "<li>✅ <code>install/functions/ui.php</code> - Interface corrigée</li>";
echo "<li>✅ Tous les répertoires <code>app/</code>, <code>config/</code>, <code>resources/</code>, etc.</li>";
echo "</ul>";
echo "</div>";

echo "<div class='warning'>";
echo "<h4>🔄 Prochaines étapes recommandées :</h4>";
echo "<ol>";
echo "<li>Testez que l'installation fonctionne : <a href='install/install_new.php' target='_blank'>🚀 Tester l'installateur</a></li>";
echo "<li>Vérifiez que l'application Laravel fonctionne normalement</li>";
echo "<li>Ce script va se supprimer automatiquement dans 10 secondes...</li>";
echo "</ol>";
echo "</div>";

function formatBytes($bytes, $precision = 2) {
    $units = array('B', 'KB', 'MB', 'GB');
    for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
        $bytes /= 1024;
    }
    return round($bytes, $precision) . ' ' . $units[$i];
}

echo "<hr>";
echo "<p><em>Nettoyage terminé le " . date('Y-m-d H:i:s') . "</em></p>";

// Auto-suppression de ce script après 10 secondes
echo "<script>
setTimeout(function() {
    window.location.href = 'cleanup_self_destruct.php';
}, 10000);
</script>";
echo "</div>";
?> 