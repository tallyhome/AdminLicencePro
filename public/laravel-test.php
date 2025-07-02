<?php
// Afficher toutes les erreurs
ini_set(\"display_errors\", 1);
ini_set(\"display_startup_errors\", 1);
error_reporting(E_ALL);

// Informations de base sur PHP
echo "<h1>Test PHP</h1>";
echo "<p>PHP version: " . phpversion() . "</p>";

// Test de Laravel
echo "<h2>Test Laravel</h2>";
try {
    require __DIR__ . \"/../vendor/autoload.php\";
    echo "<p style=\"color: green;\">Autoloader loaded successfully</p>";
    
    $app = require_once __DIR__ . \"/../bootstrap/app.php\";
    echo "<p style=\"color: green;\">Laravel app loaded successfully</p>";
    
    $kernel = $app->make(Illuminate\\Contracts\\Http\\Kernel::class);
    echo "<p style=\"color: green;\">HTTP Kernel created successfully</p>";
    
    echo "<h3>Registered Service Providers</h3>";
    echo "<ul>";
    foreach ($app->getLoadedProviders() as $provider => $loaded) {
        echo "<li>$provider</li>";
    }
    echo "</ul>";
    
} catch (Exception $e) {
    echo "<p style=\"color: red;\">Laravel error: " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

// Fin du test
echo "<h2>Test terminé</h2>";
echo "<p>Si vous voyez cette page, PHP fonctionne correctement.</p>";
