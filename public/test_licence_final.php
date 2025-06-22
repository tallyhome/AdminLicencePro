<?php
/**
 * Test final de la fonction de licence
 */

// Configuration stricte des erreurs
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Inclure les fichiers nécessaires
require_once __DIR__ . '/install/functions/core.php';

// Test direct de la fonction verifierLicence
$testKey = "JQUV-QSDM-UT8G-BFHY";
$domain = $_SERVER['HTTP_HOST'] ?? 'localhost';
$ipAddress = $_SERVER['SERVER_ADDR'] ?? $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';

echo "<h1>🧪 Test Final de la Licence</h1>";
echo "<p><strong>Clé testée :</strong> $testKey</p>";
echo "<p><strong>Domaine :</strong> $domain</p>";
echo "<p><strong>IP :</strong> $ipAddress</p>";

echo "<h2>Résultat de verifierLicence() :</h2>";

$result = verifierLicence($testKey, $domain, $ipAddress);

echo "<pre>";
print_r($result);
echo "</pre>";

if ($result['valide']) {
    echo "<div style='padding: 15px; background: #d4edda; color: #155724; border-radius: 4px; margin: 20px 0;'>";
    echo "✅ <strong>SUCCÈS :</strong> " . $result['message'];
    echo "</div>";
} else {
    echo "<div style='padding: 15px; background: #f8d7da; color: #721c24; border-radius: 4px; margin: 20px 0;'>";
    echo "❌ <strong>ERREUR :</strong> " . $result['message'];
    echo "</div>";
}

echo "<h2>Test direct de l'API :</h2>";

// Test direct de l'API
$url = "https://licence.myvcard.fr/api/check-serial.php";
$data = [
    'serial_key' => $testKey,
    'domain' => $domain,
    'ip_address' => $ipAddress
];

$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode($data),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_SSL_VERIFYHOST => false,
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
        'Accept: application/json'
    ]
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

echo "<p><strong>Code HTTP :</strong> $httpCode</p>";
if ($error) {
    echo "<p><strong>Erreur cURL :</strong> $error</p>";
}
echo "<p><strong>Réponse brute :</strong></p>";
echo "<pre>$response</pre>";

if ($response) {
    $decoded = json_decode($response, true);
    if ($decoded) {
        echo "<p><strong>Réponse décodée :</strong></p>";
        echo "<pre>";
        print_r($decoded);
        echo "</pre>";
    }
}

echo "<hr>";
echo "<p><a href='installation_complete_fix.php'>← Retour à l'installation corrigée</a></p>";
echo "<p><a href='test_boutons_final.php'>Test des boutons</a></p>";
?> 