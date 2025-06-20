<?php

require __DIR__ . '/vendor/autoload.php';

// Charger les variables d'environnement
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Vérifier la clé de licence dans différents contextes
echo "=== Vérification de la clé de licence ===\n";

// Vérifier via getenv()
$licenseKey = getenv('INSTALLATION_LICENSE_KEY');
echo "Clé via getenv(): " . ($licenseKey ?: 'Non définie') . "\n";

// Vérifier via $_ENV
echo "Clé via _ENV: " . ($_ENV['INSTALLATION_LICENSE_KEY'] ?? 'Non définie') . "\n";

// Vérifier via $_SERVER
echo "Clé via _SERVER: " . ($_SERVER['INSTALLATION_LICENSE_KEY'] ?? 'Non définie') . "\n";

// Afficher le contenu du fichier .env
echo "\n=== Contenu du fichier .env ===\n";
$envContent = file_get_contents(__DIR__ . '/.env');
preg_match('/INSTALLATION_LICENSE_KEY=([^\n]*)/', $envContent, $matches);
echo "Clé dans .env: " . ($matches[1] ?? 'Non trouvée') . "\n";

echo "\n=== Fin de la vérification ===\n";