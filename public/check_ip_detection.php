<?php
/**
 * 🔍 DIAGNOSTIC IP - AdminLicence
 * Vérifie comment l'IP est détectée côté client vs serveur
 */

echo "<style>
body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
.container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
.info { background: #d1ecf1; border: 1px solid #bee5eb; padding: 15px; border-radius: 8px; margin: 10px 0; }
.warning { background: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; border-radius: 8px; margin: 10px 0; }
code { background: #f8f9fa; padding: 2px 4px; border-radius: 3px; }
table { width: 100%; border-collapse: collapse; margin: 10px 0; }
th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
th { background: #f8f9fa; }
</style>";

echo "<div class='container'>";
echo "<h1>🔍 Diagnostic de détection d'IP</h1>";

echo "<div class='warning'>";
echo "<h3>⚠️ Problème détecté</h3>";
echo "<p>Serveur distant dit : <strong>IP 82.66.185.78</strong></p>";
echo "<p>Client dit : <strong>Adresse IP non autorisée</strong></p>";
echo "</div>";

echo "<h3>🌐 Informations IP côté serveur :</h3>";
echo "<table>";
echo "<tr><th>Variable</th><th>Valeur</th><th>Description</th></tr>";

$ipVariables = [
    'REMOTE_ADDR' => [$_SERVER['REMOTE_ADDR'] ?? 'N/A', 'IP du client (peut être proxy)'],
    'HTTP_X_FORWARDED_FOR' => [$_SERVER['HTTP_X_FORWARDED_FOR'] ?? 'N/A', 'IP réelle si behind proxy'],
    'HTTP_X_REAL_IP' => [$_SERVER['HTTP_X_REAL_IP'] ?? 'N/A', 'IP réelle (certains proxies)'],
    'HTTP_CLIENT_IP' => [$_SERVER['HTTP_CLIENT_IP'] ?? 'N/A', 'IP client (shared internet)'],
    'SERVER_ADDR' => [$_SERVER['SERVER_ADDR'] ?? 'N/A', 'IP du serveur local'],
    'HTTP_CF_CONNECTING_IP' => [$_SERVER['HTTP_CF_CONNECTING_IP'] ?? 'N/A', 'IP Cloudflare'],
    'HTTP_X_CLUSTER_CLIENT_IP' => [$_SERVER['HTTP_X_CLUSTER_CLIENT_IP'] ?? 'N/A', 'IP cluster'],
];

foreach ($ipVariables as $var => $info) {
    $class = $info[0] !== 'N/A' ? '' : 'style="opacity: 0.5;"';
    echo "<tr $class><td><code>$var</code></td><td><strong>{$info[0]}</strong></td><td>{$info[1]}</td></tr>";
}
echo "</table>";

echo "<h3>🔧 IP détectée par différentes méthodes :</h3>";

// Méthode 1 : Simple
$ip1 = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';

// Méthode 2 : Avec proxy
function getRealIpAddress() {
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        return trim($ips[0]);
    } elseif (!empty($_SERVER['HTTP_X_REAL_IP'])) {
        return $_SERVER['HTTP_X_REAL_IP'];
    } elseif (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } else {
        return $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
    }
}
$ip2 = getRealIpAddress();

// Méthode 3 : Serveur externe
$ip3 = 'N/A';
try {
    $context = stream_context_create(['http' => ['timeout' => 5]]);
    $ip3 = trim(file_get_contents('https://ipinfo.io/ip', false, $context));
} catch (Exception $e) {
    $ip3 = 'Erreur: ' . $e->getMessage();
}

echo "<table>";
echo "<tr><th>Méthode</th><th>IP détectée</th><th>Match serveur distant</th></tr>";
echo "<tr><td>Simple REMOTE_ADDR</td><td><code>$ip1</code></td><td>" . ($ip1 === '82.66.185.78' ? '✅ MATCH' : '❌ Différent') . "</td></tr>";
echo "<tr><td>Avec gestion proxy</td><td><code>$ip2</code></td><td>" . ($ip2 === '82.66.185.78' ? '✅ MATCH' : '❌ Différent') . "</td></tr>";
echo "<tr><td>Service externe</td><td><code>$ip3</code></td><td>" . ($ip3 === '82.66.185.78' ? '✅ MATCH' : '❌ Différent') . "</td></tr>";
echo "</table>";

echo "<h3>🔍 Analyse du problème :</h3>";

if ($ip1 !== '82.66.185.78' && $ip2 !== '82.66.185.78' && $ip3 !== '82.66.185.78') {
    echo "<div class='warning'>";
    echo "<h4>❌ Problème détecté :</h4>";
    echo "<p>Aucune méthode ne trouve l'IP <code>82.66.185.78</code> enregistrée sur le serveur distant.</p>";
    echo "<p><strong>Causes possibles :</strong></p>";
    echo "<ul>";
    echo "<li>🔥 <strong>Cloudflare/Proxy</strong> : L'IP réelle est masquée</li>";
    echo "<li>🔄 <strong>Load Balancer</strong> : Répartition de charge</li>";
    echo "<li>🌐 <strong>CDN</strong> : Content Delivery Network</li>";
    echo "<li>🛡️ <strong>Firewall</strong> : Modification des headers</li>";
    echo "</ul>";
    echo "</div>";
} else {
    echo "<div class='info'>";
    echo "<h4>✅ IP correcte trouvée !</h4>";
    echo "</div>";
}

echo "<h3>🛠️ Solutions recommandées :</h3>";
echo "<div class='info'>";
echo "<ol>";
echo "<li><strong>Utiliser plusieurs méthodes de détection</strong> dans le code de vérification de licence</li>";
echo "<li><strong>Autoriser une plage d'IP</strong> pour le même serveur</li>";
echo "<li><strong>Mettre à jour l'IP sur le serveur distant</strong> avec l'IP réelle détectée</li>";
echo "<li><strong>Utiliser le domaine</strong> comme identification principale plutôt que l'IP</li>";
echo "</ol>";
echo "</div>";

echo "<h3>📝 Headers HTTP complets :</h3>";
echo "<div style='background: #f8f9fa; padding: 10px; border-radius: 5px; max-height: 300px; overflow-y: auto;'>";
foreach ($_SERVER as $key => $value) {
    if (strpos($key, 'HTTP_') === 0 || in_array($key, ['REMOTE_ADDR', 'SERVER_ADDR'])) {
        echo "<code>$key</code> = <strong>" . htmlspecialchars($value) . "</strong><br>";
    }
}
echo "</div>";

echo "<hr>";
echo "<p><em>Diagnostic généré le " . date('Y-m-d H:i:s') . "</em></p>";
echo "</div>";
?> 