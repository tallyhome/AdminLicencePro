<?php
/**
 * 🧹 ANALYSE DE NETTOYAGE - AdminLicence
 * Identifie tous les fichiers temporaires, tests et debug à supprimer
 */

echo "<style>
body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
.container { max-width: 1200px; margin: 0 auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
.category { background: #f8f9fa; margin: 15px 0; padding: 15px; border-radius: 8px; border-left: 4px solid #007bff; }
.file-list { background: white; padding: 10px; border-radius: 5px; margin: 10px 0; }
.file-item { padding: 5px; margin: 3px 0; border-radius: 3px; }
.exists { background: #fff3cd; }
.missing { background: #e2e3e5; opacity: 0.6; }
.stats { background: linear-gradient(135deg, #d4edda, #c3e6cb); border: 2px solid #28a745; padding: 20px; border-radius: 10px; }
.warning { background: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; border-radius: 8px; }
code { background: #f8f9fa; padding: 2px 4px; border-radius: 3px; }
</style>";

echo "<div class='container'>";
echo "<h1>🧹 Analyse de nettoyage - AdminLicence</h1>";

// Liste complète des fichiers à supprimer
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

$totalFiles = 0;
$totalSize = 0;
$totalCategories = count($filesToDelete);

foreach ($filesToDelete as $category => $files) {
    echo "<div class='category'>";
    echo "<h3>$category</h3>";
    echo "<div class='file-list'>";
    
    $categoryCount = 0;
    $categorySize = 0;
    
    foreach ($files as $file) {
        if (file_exists($file)) {
            $size = filesize($file);
            $sizeFormatted = formatBytes($size);
            $categorySize += $size;
            $categoryCount++;
            $totalFiles++;
            $totalSize += $size;
            
            echo "<div class='file-item exists'>";
            echo "🗑️ <code>$file</code> <small>($sizeFormatted)</small>";
            echo "</div>";
        } else {
            echo "<div class='file-item missing'>";
            echo "❌ <code>$file</code> <small>(introuvable)</small>";
            echo "</div>";
        }
    }
    
    echo "</div>";
    echo "<p><strong>$category :</strong> $categoryCount fichiers trouvés (" . formatBytes($categorySize) . ")</p>";
    echo "</div>";
}

echo "<div class='stats'>";
echo "<h2>📊 Statistiques de nettoyage</h2>";
echo "<div style='font-size: 18px;'>";
echo "<strong>📁 Total fichiers à supprimer : $totalFiles</strong><br>";
echo "<strong>💾 Espace disque libéré : " . formatBytes($totalSize) . "</strong><br>";
echo "<strong>📂 Catégories analysées : $totalCategories</strong>";
echo "</div>";
echo "</div>";

echo "<div class='warning'>";
echo "<h3>⚠️ FICHIERS À CONSERVER ABSOLUMENT</h3>";
echo "<ul>";
echo "<li><strong>install/install_new.php</strong> - Installateur principal corrigé ✅</li>";
echo "<li><strong>install/assets/js/install.js</strong> - JavaScript corrigé ✅</li>";
echo "<li><strong>install/functions/ui.php</strong> - Interface corrigée ✅</li>";
echo "<li><strong>Répertoires app/, config/, resources/, routes/</strong> - Core Laravel ✅</li>";
echo "<li><strong>composer.json, package.json</strong> - Dépendances ✅</li>";
echo "<li><strong>.htaccess, index.php</strong> - Fichiers essentiels ✅</li>";
echo "</ul>";
echo "</div>";

echo "<h3>🎯 Plan de nettoyage recommandé :</h3>";
echo "<ol>";
echo "<li><strong>Phase 1 :</strong> Créer une sauvegarde complète du projet</li>";
echo "<li><strong>Phase 2 :</strong> Supprimer tous les fichiers test_* (nos créations de debug)</li>";
echo "<li><strong>Phase 3 :</strong> Supprimer les versions d'installation obsolètes</li>";
echo "<li><strong>Phase 4 :</strong> Nettoyer les scripts de maintenance temporaires</li>";
echo "<li><strong>Phase 5 :</strong> Supprimer les backups et fichiers de configuration temporaires</li>";
echo "<li><strong>Phase 6 :</strong> Vérifier que l'installation fonctionne toujours correctement</li>";
echo "</ol>";

echo "<div style='text-align: center; margin: 20px 0;'>";
echo "<a href='cleanup_files.php' style='background: #dc3545; color: white; padding: 15px 30px; text-decoration: none; border-radius: 8px; font-size: 18px; display: inline-block;'>";
echo "🗑️ LANCER LE NETTOYAGE AUTOMATIQUE";
echo "</a>";
echo "</div>";

function formatBytes($bytes, $precision = 2) {
    $units = array('B', 'KB', 'MB', 'GB');
    for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
        $bytes /= 1024;
    }
    return round($bytes, $precision) . ' ' . $units[$i];
}

echo "<hr>";
echo "<p><em>Analyse générée le " . date('Y-m-d H:i:s') . "</em></p>";
echo "<p style='background: #d4edda; padding: 10px; border-radius: 5px;'>";
echo "<strong>✅ Résumé :</strong> $totalFiles fichiers temporaires détectés, représentant " . formatBytes($totalSize) . " d'espace disque à libérer.";
echo "</p>";
echo "</div>";
?> 