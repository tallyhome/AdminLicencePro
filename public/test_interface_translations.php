<?php
/**
 * Test des traductions de l'interface pour toutes les langues
 */

// Inclure la configuration et les fonctions
require_once 'install/config.php';
require_once 'install/functions/language.php';

echo "<h1>Test des traductions d'interface AdminLicence</h1>";
echo "<style>body{font-family:Arial,sans-serif;margin:20px;} .lang-section{border:1px solid #ddd;margin:10px 0;padding:15px;border-radius:5px;} .test-key{margin:5px 0;padding:5px;background:#f9f9f9;}</style>";

// Clés à tester
$testKeys = [
    'installation_title',
    'installation_assistant', 
    'license_api',
    'required_format',
    'all_rights_reserved',
    'support',
    'documentation'
];

// Tester chaque langue
foreach (AVAILABLE_LANGUAGES as $code => $name) {
    echo "<div class='lang-section'>";
    echo "<h2>$name ($code)</h2>";
    
    // Simuler le changement de langue
    $_SESSION['installer_language'] = $code;
    
    echo "<h3>Traductions de l'interface :</h3>";
    foreach ($testKeys as $key) {
        $translation = t($key);
        $status = ($translation === $key) ? "❌ MANQUANT" : "✅ OK";
        echo "<div class='test-key'><strong>$key:</strong> $translation $status</div>";
    }
    
    echo "<h3>Labels des étapes :</h3>";
    for ($step = 1; $step <= 4; $step++) {
        $label = getStepLabel($step);
        echo "<div class='test-key'><strong>Étape $step:</strong> $label</div>";
    }
    
    echo "</div>";
}

// Test du titre et sous-titre
echo "<h2>Test en contexte (Français) :</h2>";
$_SESSION['installer_language'] = 'fr';
echo "<div style='border:2px solid #3b82f6;padding:20px;margin:10px 0;'>";
echo "<h1>" . t('installation_title') . "</h1>";
echo "<p>" . t('installation_assistant') . "</p>";
echo "<p><small>&copy; " . date('Y') . " AdminLicence. " . t('all_rights_reserved') . "</small></p>";
echo "<p><a href='#'>" . t('support') . "</a> | <a href='#'>" . t('documentation') . "</a></p>";
echo "</div>";

echo "<h2>Test en contexte (English) :</h2>";
$_SESSION['installer_language'] = 'en';
echo "<div style='border:2px solid #3b82f6;padding:20px;margin:10px 0;'>";
echo "<h1>" . t('installation_title') . "</h1>";
echo "<p>" . t('installation_assistant') . "</p>";
echo "<p><small>&copy; " . date('Y') . " AdminLicence. " . t('all_rights_reserved') . "</small></p>";
echo "<p><a href='#'>" . t('support') . "</a> | <a href='#'>" . t('documentation') . "</a></p>";
echo "</div>";
?> 