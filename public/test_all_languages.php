<?php
/**
 * Test de toutes les langues de l'installateur
 */

// Inclure la configuration et les fonctions
require_once 'install/config.php';
require_once 'install/functions/language.php';

echo "<h1>Test de toutes les langues de l'installateur</h1>";
echo "<style>body{font-family:Arial,sans-serif;margin:20px;} ul{margin:10px 0;} li{margin:5px 0;}</style>";

// Tester chaque langue disponible
foreach (AVAILABLE_LANGUAGES as $code => $name) {
    echo "<h2>$name ($code)</h2>";
    
    // Simuler le changement de langue
    $_SESSION['installer_language'] = $code;
    
    echo "<h3>Titres des étapes :</h3>";
    echo "<ul>";
    for ($step = 1; $step <= 4; $step++) {
        $title = getStepTitle($step);
        echo "<li>Étape $step : $title</li>";
    }
    echo "</ul>";
    
    echo "<h3>Descriptions des étapes :</h3>";
    echo "<ul>";
    for ($step = 1; $step <= 4; $step++) {
        $description = getStepDescription($step);
        echo "<li>Étape $step : $description</li>";
    }
    echo "</ul>";
    
    echo "<h3>Traductions courantes :</h3>";
    echo "<ul>";
    $testKeys = ['next', 'back', 'license_key', 'database_configuration', 'admin_setup', 'installation_complete'];
    foreach ($testKeys as $key) {
        $translation = t($key);
        echo "<li>$key : $translation</li>";
    }
    echo "</ul>";
    
    echo "<hr>";
}

echo "<h2>Test du sélecteur de langue</h2>";
echo "<p>Liens de changement de langue :</p>";
echo getLanguageLinks();
?> 