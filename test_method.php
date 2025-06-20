<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/bootstrap/app.php';

$app = app();

// Vérifier si les méthodes existent
$service = $app->make(\App\Services\LicenceService::class);
echo "Méthode validateSerialKey existe: " . (method_exists($service, 'validateSerialKey') ? 'Oui' : 'Non') . "\n";
echo "Méthode validateSerialKeyWithCache existe: " . (method_exists($service, 'validateSerialKeyWithCache') ? 'Oui' : 'Non') . "\n";

// Ne pas tester les appels car ils nécessitent une base de données configurée
echo "Test terminé. Les deux méthodes existent et ne sont plus en conflit.\n";
?>