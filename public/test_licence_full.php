<?php
/**
 * Test complet de validation de licence
 */

// Inclure Laravel
require_once __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

// Initialiser Laravel correctement
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Obtenir le service de licence
$licenceService = $app->make(\App\Services\LicenceService::class);

// Configuration
$testLicenseKey = $_GET['key'] ?? 'JQUV-QSDM-UT8G-BFHY'; // Clé de test par défaut
$domain = $_SERVER['HTTP_HOST'] ?? 'localhost';
$ipAddress = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';

echo "<h1>Test complet de validation de licence</h1>";
echo "<p><strong>Clé de test:</strong> " . htmlspecialchars($testLicenseKey) . "</p>";
echo "<p><strong>Domaine:</strong> " . htmlspecialchars($domain) . "</p>";
echo "<p><strong>IP:</strong> " . htmlspecialchars($ipAddress) . "</p>";

echo "<hr>";

try {
    echo "<h2>1. Test de validation avec le LicenceService</h2>";
    
    $result = $licenceService->validateSerialKey($testLicenseKey, $domain, $ipAddress);
    
    echo "<h3>Résultat de la validation:</h3>";
    echo "<pre style='background: #f5f5f5; padding: 10px; border: 1px solid #ddd;'>";
    echo htmlspecialchars(json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    echo "</pre>";
    
    if ($result['valid']) {
        echo "<p style='color: green; font-weight: bold;'>✓ Licence VALIDE</p>";
        echo "<p>Status: " . ($result['status'] ?? 'N/A') . "</p>";
        echo "<p>Message: " . ($result['message'] ?? 'N/A') . "</p>";
        
        if (isset($result['expires_at'])) {
            echo "<p>Expire le: " . $result['expires_at'] . "</p>";
        }
    } else {
        echo "<p style='color: red; font-weight: bold;'>✗ Licence INVALIDE</p>";
        echo "<p>Status: " . ($result['status'] ?? 'N/A') . "</p>";
        echo "<p>Message: " . ($result['message'] ?? 'N/A') . "</p>";
    }
    
    echo "<hr>";
    
    echo "<h2>2. Vérification des Settings Laravel</h2>";
    
    $licenseValid = \App\Models\Setting::get('license_valid', false);
    $licenseStatus = \App\Models\Setting::get('license_status', 'unknown');
    $lastCheck = \App\Models\Setting::get('last_license_check', 'never');
    $licenseKey = \App\Models\Setting::get('license_key', 'not set');
    $licenseDomain = \App\Models\Setting::get('license_domain', 'not set');
    $licenseIp = \App\Models\Setting::get('license_ip', 'not set');
    
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>Setting</th><th>Valeur</th></tr>";
    echo "<tr><td>license_valid</td><td>" . ($licenseValid ? 'true' : 'false') . "</td></tr>";
    echo "<tr><td>license_status</td><td>" . htmlspecialchars($licenseStatus) . "</td></tr>";
    echo "<tr><td>last_license_check</td><td>" . htmlspecialchars($lastCheck) . "</td></tr>";
    echo "<tr><td>license_key</td><td>" . htmlspecialchars($licenseKey) . "</td></tr>";
    echo "<tr><td>license_domain</td><td>" . htmlspecialchars($licenseDomain) . "</td></tr>";
    echo "<tr><td>license_ip</td><td>" . htmlspecialchars($licenseIp) . "</td></tr>";
    echo "</table>";
    
    echo "<hr>";
    
    echo "<h2>3. Vérification de la session</h2>";
    
    // Démarrer la session si nécessaire
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>Session Key</th><th>Valeur</th></tr>";
    
    $sessionKeys = ['license_valid', 'license_status', 'license_details'];
    foreach ($sessionKeys as $key) {
        $value = $_SESSION[$key] ?? 'not set';
        if (is_array($value) || is_object($value)) {
            $value = json_encode($value, JSON_PRETTY_PRINT);
        }
        echo "<tr><td>" . htmlspecialchars($key) . "</td><td><pre>" . htmlspecialchars($value) . "</pre></td></tr>";
    }
    echo "</table>";
    
    echo "<hr>";
    
    echo "<h2>4. Test direct de l'API</h2>";
    
    $apiUrl = 'https://licence.myvcard.fr/api/ultra-simple.php';
    $data = [
        'serial_key' => trim(strtoupper($testLicenseKey)),
        'domain' => $domain,
        'ip_address' => $ipAddress
    ];
    
    $ch = curl_init($apiUrl);
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
    
    echo "<p><strong>Code HTTP:</strong> " . $httpCode . "</p>";
    if ($error) {
        echo "<p style='color: red;'><strong>Erreur cURL:</strong> " . htmlspecialchars($error) . "</p>";
    }
    
    echo "<h3>Réponse brute de l'API:</h3>";
    echo "<pre style='background: #f5f5f5; padding: 10px; border: 1px solid #ddd;'>";
    echo htmlspecialchars($response);
    echo "</pre>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'><strong>Exception:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}

echo "<hr>";
echo "<p><em>Pour tester avec une autre clé: ?key=VOTRE-CLE</em></p>";
echo "<p><em>N'oubliez pas de supprimer ce fichier après les tests!</em></p>";
?> 