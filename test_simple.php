<?php

// Test simple de la fonction verifierLicence
define('INSTALL_PATH', __DIR__ . '/public/install');

// Fonction de traduction simple pour les tests
function t($key) {
    $translations = [
        'license_key_required' => 'Clé de licence requise',
        'license_key_invalid_format' => 'Format de clé invalide',
        'license_key_invalid' => 'Clé de licence invalide',
        'license_valid' => 'Licence valide'
    ];
    return $translations[$key] ?? $key;
}

require_once 'public/install/functions/core.php';

echo "=== Test de la fonction verifierLicence ===\n";

$testKey = 'JZPR-9QJQ-ZRNW-S60B';
echo "Test avec la clé: $testKey\n";

$result = verifierLicence($testKey);
echo "Résultat:\n";
print_r($result);

echo "\n=== Test avec une clé invalide ===\n";
$invalidKey = 'INVALID-KEY-TEST';
echo "Test avec la clé: $invalidKey\n";

$result2 = verifierLicence($invalidKey);
echo "Résultat:\n";
print_r($result2);

echo "\n=== Fin du test ===\n";