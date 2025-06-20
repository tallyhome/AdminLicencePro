<?php

echo "=== DIAGNOSTIC SIMPLE DE LICENCE ===\n\n";

// 1. Vérifier la clé dans .env directement
echo "1. Lecture directe du fichier .env:\n";
$envPath = __DIR__ . '/.env';
if (file_exists($envPath)) {
    $envContent = file_get_contents($envPath);
    if (preg_match('/INSTALLATION_LICENSE_KEY=(.+)/', $envContent, $matches)) {
        $envLicenseKey = trim($matches[1]);
        echo "   Clé trouvée dans .env: {$envLicenseKey}\n";
    } else {
        echo "   Clé NON TROUVÉE dans .env\n";
        $envLicenseKey = null;
    }
} else {
    echo "   Fichier .env NON TROUVÉ\n";
    $envLicenseKey = null;
}

// 2. Charger manuellement le .env
echo "\n2. Chargement manuel du .env:\n";
if (file_exists($envPath)) {
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, 'INSTALLATION_LICENSE_KEY=') === 0) {
            $key = substr($line, strlen('INSTALLATION_LICENSE_KEY='));
            echo "   Clé extraite: {$key}\n";
            // Définir la variable d'environnement
            putenv("INSTALLATION_LICENSE_KEY={$key}");
            $_ENV['INSTALLATION_LICENSE_KEY'] = $key;
            $_SERVER['INSTALLATION_LICENSE_KEY'] = $key;
            break;
        }
    }
}

// 3. Test avec getenv
echo "\n3. Test avec getenv():\n";
$getenvKey = getenv('INSTALLATION_LICENSE_KEY');
echo "   getenv(): " . ($getenvKey ?: 'NON TROUVÉE') . "\n";

// 4. Test avec $_ENV
echo "\n4. Test avec \$_ENV:\n";
$envArrayKey = $_ENV['INSTALLATION_LICENSE_KEY'] ?? null;
echo "   \$_ENV: " . ($envArrayKey ?: 'NON TROUVÉE') . "\n";

// 5. Test avec $_SERVER
echo "\n5. Test avec \$_SERVER:\n";
$serverArrayKey = $_SERVER['INSTALLATION_LICENSE_KEY'] ?? null;
echo "   \$_SERVER: " . ($serverArrayKey ?: 'NON TROUVÉE') . "\n";

echo "\n=== FIN DU DIAGNOSTIC ===\n";