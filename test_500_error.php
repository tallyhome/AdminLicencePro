<?php

// Activer l'affichage des erreurs
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "=== Test erreur 500 client ===\n\n";

// Tester une page spécifique avec curl
$url = 'http://127.0.0.1:8000/client/billing';

// D'abord se connecter
$ch = curl_init();

// 1. Récupérer le token CSRF
curl_setopt($ch, CURLOPT_URL, 'http://127.0.0.1:8000/client/login');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookies.txt');
$loginPage = curl_exec($ch);

preg_match('/<meta name="csrf-token" content="([^"]+)"/', $loginPage, $matches);
$csrfToken = $matches[1] ?? null;

if (!$csrfToken) {
    echo "Impossible de récupérer le token CSRF\n";
    exit(1);
}

// 2. Se connecter
curl_setopt($ch, CURLOPT_URL, 'http://127.0.0.1:8000/client/login');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    '_token' => $csrfToken,
    'email' => 'test@client.com',
    'password' => 'password123'
]));
curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookies.txt');
curl_exec($ch);

// 3. Tester la page billing
echo "Test de la page billing...\n";
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, false);
curl_setopt($ch, CURLOPT_HTTPGET, true);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

echo "Code HTTP: $httpCode\n";

if ($httpCode == 500) {
    echo "\nErreur 500 détectée!\n";
    
    // Chercher le message d'erreur
    if (preg_match('/<div class="exception_title"[^>]*>(.*?)<\/div>/s', $response, $matches)) {
        echo "Erreur: " . strip_tags($matches[1]) . "\n";
    }
    
    if (preg_match('/<div class="exception_message"[^>]*>(.*?)<\/div>/s', $response, $matches)) {
        echo "Message: " . strip_tags($matches[1]) . "\n";
    }
    
    // Chercher la trace
    if (preg_match('/<pre[^>]*class="[^"]*trace[^"]*"[^>]*>(.*?)<\/pre>/s', $response, $matches)) {
        $trace = strip_tags($matches[1]);
        $lines = explode("\n", $trace);
        echo "\nPremières lignes de la trace:\n";
        for ($i = 0; $i < min(5, count($lines)); $i++) {
            echo $lines[$i] . "\n";
        }
    }
}

curl_close($ch);
unlink('cookies.txt'); 