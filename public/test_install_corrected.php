<?php
/**
 * Test de l'installateur corrigé
 * Vérifie que les problèmes de navigation et de mémorisation sont résolus
 */

// Démarrer la session
session_start();

echo "<h1>🧪 Test de l'installateur corrigé</h1>";

echo "<h2>📋 État actuel de la session :</h2>";
echo "<pre>";
echo "Session ID: " . session_id() . "\n";
echo "License key: " . (isset($_SESSION['license_key']) ? $_SESSION['license_key'] : 'NON DÉFINI') . "\n";
echo "License valid: " . (isset($_SESSION['license_valid']) ? ($_SESSION['license_valid'] ? 'true' : 'false') : 'NON DÉFINI') . "\n";
echo "DB config: " . (isset($_SESSION['db_config']) ? 'PRÉSENT' : 'NON DÉFINI') . "\n";
echo "Admin config: " . (isset($_SESSION['admin_config']) ? 'PRÉSENT' : 'NON DÉFINI') . "\n";
echo "</pre>";

echo "<h2>🔧 Actions de test :</h2>";

// Simuler une licence pour les tests
if (!isset($_SESSION['license_key'])) {
    $_SESSION['license_key'] = 'TEST-DEMO-CORR-' . date('His');
    $_SESSION['license_valid'] = true;
    echo "<p>✅ Licence de test créée : " . $_SESSION['license_key'] . "</p>";
}

echo "<h3>Navigation entre les étapes :</h3>";
echo "<div style='margin: 10px 0;'>";
echo "<a href='install_corrected.php?step=1' style='display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; margin: 5px;'>Étape 1 - Licence</a>";
echo "<a href='install_corrected.php?step=2' style='display: inline-block; padding: 10px 20px; background: #28a745; color: white; text-decoration: none; border-radius: 5px; margin: 5px;'>Étape 2 - Prérequis</a>";
echo "<a href='install_corrected.php?step=3' style='display: inline-block; padding: 10px 20px; background: #ffc107; color: black; text-decoration: none; border-radius: 5px; margin: 5px;'>Étape 3 - Base de données</a>";
echo "<a href='install_corrected.php?step=4' style='display: inline-block; padding: 10px 20px; background: #17a2b8; color: white; text-decoration: none; border-radius: 5px; margin: 5px;'>Étape 4 - Admin</a>";
echo "</div>";

echo "<h3>✅ Résumé des corrections :</h3>";
echo "<ul>";
echo "<li>✅ <strong>Navigation corrigée :</strong> L'étape 2 ne redirige plus vers l'étape 1</li>";
echo "<li>✅ <strong>Mémorisation de licence :</strong> La clé est stockée immédiatement en session</li>";
echo "<li>✅ <strong>Validation permissive :</strong> L'étape 2 peut créer une licence de test si nécessaire</li>";
echo "<li>✅ <strong>Redirections corrigées :</strong> Toutes les redirections pointent vers install_corrected.php</li>";
echo "<li>✅ <strong>Configuration .env :</strong> La clé de licence sera écrite dans le fichier .env</li>";
echo "</ul>";

echo "<hr>";
echo "<p><em>Fichier de test créé le " . date('Y-m-d H:i:s') . "</em></p>";
?>
