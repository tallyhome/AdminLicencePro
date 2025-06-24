<?php
/**
 * Script de diagnostic pour tester la collecte et l'envoi d'IP au serveur de licence
 */

// Inclure les fonctions nécessaires
require_once __DIR__ . '/install/config.php';
require_once __DIR__ . '/install/functions/ip_helper.php';
require_once __DIR__ . '/install/functions/core.php';

echo "<!DOCTYPE html>
<html>
<head>
    <title>Diagnostic IP - AdminLicence</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .section { margin: 20px 0; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
        .success { background-color: #d4edda; border-color: #c3e6cb; }
        .warning { background-color: #fff3cd; border-color: #ffeaa7; }
        .error { background-color: #f8d7da; border-color: #f5c6cb; }
        .code { background-color: #f8f9fa; padding: 10px; border-radius: 3px; font-family: monospace; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 8px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>🔍 Diagnostic IP - AdminLicence</h1>
    <p>Ce script diagnostique la collecte d'IP du serveur et sa transmission au serveur de licence.</p>";

// Section 1: Variables serveur disponibles
echo "<div class='section'>
    <h2>📊 Variables Serveur Disponibles</h2>
    <table>
        <tr><th>Variable</th><th>Valeur</th><th>Statut</th></tr>";

$serverVars = [
    'SERVER_ADDR' => $_SERVER['SERVER_ADDR'] ?? null,
    'REMOTE_ADDR' => $_SERVER['REMOTE_ADDR'] ?? null,
    'HTTP_X_FORWARDED_FOR' => $_SERVER['HTTP_X_FORWARDED_FOR'] ?? null,
    'HTTP_X_REAL_IP' => $_SERVER['HTTP_X_REAL_IP'] ?? null,
    'HTTP_HOST' => $_SERVER['HTTP_HOST'] ?? null,
    'SERVER_NAME' => $_SERVER['SERVER_NAME'] ?? null
];

foreach ($serverVars as $var => $value) {
    $status = $value ? 'success' : 'warning';
    $statusText = $value ? '✅ Disponible' : '⚠️ Non définie';
    echo "<tr>
        <td><code>\$_SERVER['$var']</code></td>
        <td>" . ($value ?: '<em>null</em>') . "</td>
        <td class='$status'>$statusText</td>
    </tr>";
}

// Tester gethostbyname
$hostnameIp = gethostbyname(gethostname());
echo "<tr>
    <td><code>gethostbyname(gethostname())</code></td>
    <td>$hostnameIp</td>
    <td class='success'>✅ Calculée</td>
</tr>";

echo "</table></div>";

// Section 2: Logique de sélection d'IP ROBUSTE
echo "<div class='section'>
    <h2>🎯 Logique de Sélection d'IP (NOUVELLE VERSION ROBUSTE)</h2>";

// Utiliser la nouvelle fonction robuste
$ipInfo = collectServerIP();
$selectedIp = $ipInfo['ip'];

echo "<div class='code'>
<strong>Nouvelle logique robuste utilisée:</strong><br>
1. SERVER_ADDR (si publique)<br>
2. HTTP_X_REAL_IP (proxy)<br>
3. HTTP_CF_CONNECTING_IP (Cloudflare)<br>
4. HTTP_X_FORWARDED_FOR (première IP publique)<br>
5. REMOTE_ADDR (si publique)<br>
6. gethostbyname(gethostname()) (si publique)<br>
7. Fallbacks vers IPs locales si nécessaire<br><br>
<strong>Résultat:</strong><br>
- <strong>IP sélectionnée: {$ipInfo['ip']}</strong><br>
- <strong>Raison: {$ipInfo['reason']}</strong><br>
- <strong>Est locale: " . ($ipInfo['is_local'] ? 'Oui' : 'Non') . "</strong><br>
- <strong>Est valide: " . ($ipInfo['is_valid'] ? 'Oui' : 'Non') . "</strong>
</div>";

if ($ipInfo['is_local']) {
    echo "<div class='warning'>
        ⚠️ <strong>Attention:</strong> L'IP sélectionnée ({$selectedIp}) est locale/privée.
        Cela peut indiquer que le serveur est en environnement local ou derrière un proxy/NAT.
        <br><strong>Impact:</strong> Le serveur distant recevra cette IP mais elle ne sera pas utile pour l'identification géographique.
    </div>";
} else {
    echo "<div class='success'>
        ✅ <strong>Excellent:</strong> Une IP publique a été détectée ({$selectedIp}).
        <br><strong>Avantage:</strong> Le serveur distant pourra utiliser cette IP pour l'identification.
    </div>";
}

echo "</div>";

// Section 3: Test de transmission à l'API (si une clé de test est fournie)
if (isset($_GET['test_key']) && !empty($_GET['test_key'])) {
    echo "<div class='section'>
        <h2>🚀 Test de Transmission à l'API</h2>";
    
    $testKey = $_GET['test_key'];
    $domain = $_SERVER['HTTP_HOST'] ?? 'localhost';
    
    echo "<p>Test avec la clé: <code>$testKey</code></p>
          <p>Domaine: <code>$domain</code></p>
          <p>IP: <code>$selectedIp</code></p>";
    
    // Simuler l'appel API (sans vraiment l'exécuter pour éviter les erreurs)
    $apiData = [
        'serial_key' => strtoupper($testKey),
        'domain' => $domain,
        'ip_address' => $selectedIp,
        'validation_mode' => 'domain_only'
    ];
    
    echo "<div class='code'>
        <strong>Données qui seraient envoyées à l'API:</strong><br>
        " . json_encode($apiData, JSON_PRETTY_PRINT) . "
    </div>";
    
    echo "<div class='warning'>
        ℹ️ <strong>Note:</strong> Ce diagnostic ne fait pas d'appel réel à l'API pour éviter les erreurs. 
        Pour tester réellement, utilisez le processus d'installation normal.
    </div>";
    
} else {
    echo "<div class='section'>
        <h2>🚀 Test de Transmission à l'API</h2>
        <p>Pour tester la transmission à l'API, ajoutez <code>?test_key=XXXX-XXXX-XXXX-XXXX</code> à l'URL.</p>
        <p><a href='?test_key=TEST-1234-5678-9ABC'>Tester avec une clé fictive</a></p>
    </div>";
}

// Section 4: Logs de Diagnostic Détaillés
echo "<div class='section'>
    <h2>🔍 Logs de Diagnostic Détaillés</h2>";

// Log 1: Vérifier les variables d'environnement serveur
echo "<h3>📊 Variables d'Environnement Serveur</h3>";
echo "<div class='code'>";
echo "<strong>DOCUMENT_ROOT:</strong> " . ($_SERVER['DOCUMENT_ROOT'] ?? 'Non définie') . "<br>";
echo "<strong>SERVER_SOFTWARE:</strong> " . ($_SERVER['SERVER_SOFTWARE'] ?? 'Non définie') . "<br>";
echo "<strong>SERVER_PROTOCOL:</strong> " . ($_SERVER['SERVER_PROTOCOL'] ?? 'Non définie') . "<br>";
echo "<strong>REQUEST_METHOD:</strong> " . ($_SERVER['REQUEST_METHOD'] ?? 'Non définie') . "<br>";
echo "<strong>HTTP_USER_AGENT:</strong> " . (isset($_SERVER['HTTP_USER_AGENT']) ? substr($_SERVER['HTTP_USER_AGENT'], 0, 100) . '...' : 'Non définie') . "<br>";
echo "</div>";

// Log 2: Test de détection d'IP externe via services tiers
echo "<h3>🌐 Test de Détection d'IP Externe</h3>";
echo "<div class='code'>";

$externalIPServices = [
    'ipify' => 'https://api.ipify.org',
    'httpbin' => 'https://httpbin.org/ip',
    'icanhazip' => 'https://icanhazip.com'
];

foreach ($externalIPServices as $service => $url) {
    try {
        $context = stream_context_create([
            'http' => [
                'timeout' => 5,
                'user_agent' => 'AdminLicence-Diagnostic/1.0'
            ]
        ]);
        $result = @file_get_contents($url, false, $context);
        if ($result !== false) {
            $result = trim($result);
            // Pour httpbin, extraire l'IP du JSON
            if ($service === 'httpbin') {
                $json = json_decode($result, true);
                $result = $json['origin'] ?? 'Erreur JSON';
            }
            echo "<strong>$service:</strong> $result<br>";
        } else {
            echo "<strong>$service:</strong> ❌ Échec de connexion<br>";
        }
    } catch (Exception $e) {
        echo "<strong>$service:</strong> ❌ Erreur: " . $e->getMessage() . "<br>";
    }
}
echo "</div>";

// Log 3: Test de résolution DNS
echo "<h3>🔍 Test de Résolution DNS</h3>";
echo "<div class='code'>";
$hostname = gethostname();
echo "<strong>Hostname du serveur:</strong> " . ($hostname ?: 'Non disponible') . "<br>";
if ($hostname) {
    $dnsIPs = gethostbynamel($hostname);
    if ($dnsIPs) {
        echo "<strong>IPs résolues par DNS:</strong> " . implode(', ', $dnsIPs) . "<br>";
    } else {
        echo "<strong>IPs résolues par DNS:</strong> ❌ Aucune résolution<br>";
    }
}

// Test de résolution inverse
if ($selectedIp && $selectedIp !== '127.0.0.1') {
    $reverseDNS = gethostbyaddr($selectedIp);
    echo "<strong>DNS inverse pour {$selectedIp}:</strong> " . ($reverseDNS !== $selectedIp ? $reverseDNS : 'Pas de résolution inverse') . "<br>";
}
echo "</div>";

// Log 4: Configuration réseau détectée
echo "<h3>⚙️ Configuration Réseau Détectée</h3>";
echo "<div class='code'>";
$isLocalEnv = in_array($_SERVER['HTTP_HOST'] ?? '', ['localhost', '127.0.0.1']) ||
              strpos($_SERVER['HTTP_HOST'] ?? '', ':8000') !== false ||
              strpos($_SERVER['HTTP_HOST'] ?? '', '.local') !== false;
echo "<strong>Environnement détecté:</strong> " . ($isLocalEnv ? '🏠 Local/Développement' : '🌐 Production/Distant') . "<br>";
echo "<strong>Port utilisé:</strong> " . ($_SERVER['SERVER_PORT'] ?? 'Non défini') . "<br>";
echo "<strong>HTTPS actif:</strong> " . (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? '✅ Oui' : '❌ Non') . "<br>";

// Détecter si derrière un proxy/load balancer
$proxyHeaders = ['HTTP_X_FORWARDED_FOR', 'HTTP_X_REAL_IP', 'HTTP_CF_CONNECTING_IP', 'HTTP_X_FORWARDED_PROTO'];
$proxyDetected = false;
foreach ($proxyHeaders as $header) {
    if (!empty($_SERVER[$header])) {
        $proxyDetected = true;
        break;
    }
}
echo "<strong>Proxy/Load Balancer détecté:</strong> " . ($proxyDetected ? '✅ Oui' : '❌ Non') . "<br>";
echo "</div>";

// Section 5: Recommandations
echo "</div><div class='section'>
    <h2>💡 Recommandations</h2>
    <ul>";

if (!$serverVars['SERVER_ADDR']) {
    echo "<li>⚠️ <strong>SERVER_ADDR non définie:</strong> Vérifiez la configuration de votre serveur web (Apache/Nginx).</li>";
}

if (!$serverVars['REMOTE_ADDR'] || $serverVars['REMOTE_ADDR'] === '127.0.0.1') {
    echo "<li>⚠️ <strong>REMOTE_ADDR non définie:</strong> Cela peut indiquer un problème de configuration réseau.</li>";
}

if ($selectedIp === '127.0.0.1') {
    echo "<li>🔧 <strong>IP localhost détectée:</strong> 
        <ul>
            <li>Si vous êtes en production, vérifiez la configuration de votre serveur</li>
            <li>Si vous êtes derrière un proxy/load balancer, vérifiez les headers HTTP_X_FORWARDED_FOR ou HTTP_X_REAL_IP</li>
            <li>Contactez votre hébergeur si le problème persiste</li>
        </ul>
    </li>";
}

if ($serverVars['HTTP_X_FORWARDED_FOR'] || $serverVars['HTTP_X_REAL_IP']) {
    echo "<li>✅ <strong>Headers de proxy détectés:</strong> Votre serveur semble être derrière un proxy. L'IP réelle pourrait être dans ces headers.</li>";
}

echo "</ul></div>";

// Section 5: Actions à effectuer
echo "<div class='section'>
    <h2>🎯 Actions à Effectuer</h2>
    <ol>
        <li><strong>Vérifiez les logs d'installation:</strong> Consultez <code>public/install/install_log.txt</code> pour voir les IPs collectées pendant l'installation.</li>
        <li><strong>Vérifiez les logs Laravel:</strong> Consultez <code>storage/logs/laravel.log</code> pour voir les IPs collectées pendant le runtime.</li>
        <li><strong>Testez une installation:</strong> Lancez une nouvelle installation et vérifiez si l'IP est correctement transmise.</li>
        <li><strong>Contactez le support:</strong> Si l'IP est toujours 'Non spécifiée', partagez ce diagnostic avec l'équipe technique.</li>
    </ol>
</div>";

echo "<div class='section'>
    <h2>📋 Résumé du Diagnostic</h2>
    <div class='code'>
        <strong>IP qui sera envoyée au serveur de licence:</strong> $selectedIp<br>
        <strong>Domaine:</strong> " . ($_SERVER['HTTP_HOST'] ?? 'localhost') . "<br>
        <strong>Timestamp:</strong> " . date('Y-m-d H:i:s') . "
    </div>
</div>";

echo "</body></html>";
?>