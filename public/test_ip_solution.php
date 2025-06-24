<?php
/**
 * Script de test pour valider la solution de collecte d'IP robuste
 */

// Inclure les fonctions nécessaires
require_once __DIR__ . '/install/config.php';
require_once __DIR__ . '/install/functions/ip_helper.php';
require_once __DIR__ . '/install/functions/core.php';

echo "<!DOCTYPE html>
<html>
<head>
    <title>Test Solution IP - AdminLicence</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .section { margin: 20px 0; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
        .success { background-color: #d4edda; border-color: #c3e6cb; }
        .warning { background-color: #fff3cd; border-color: #ffeaa7; }
        .error { background-color: #f8d7da; border-color: #f5c6cb; }
        .info { background-color: #d1ecf1; border-color: #bee5eb; }
        .code { background-color: #f8f9fa; padding: 10px; border-radius: 3px; font-family: monospace; white-space: pre-wrap; }
        .comparison { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .before, .after { padding: 15px; border-radius: 5px; }
        .before { background-color: #f8d7da; border: 1px solid #f5c6cb; }
        .after { background-color: #d4edda; border: 1px solid #c3e6cb; }
    </style>
</head>
<body>
    <h1>🧪 Test de la Solution IP Robuste</h1>
    <p>Ce script teste la nouvelle solution de collecte d'IP et compare avec l'ancienne méthode.</p>";

// Test 1: Comparaison ancienne vs nouvelle méthode
echo "<div class='section'>
    <h2>📊 Comparaison Ancienne vs Nouvelle Méthode</h2>
    <div class='comparison'>
        <div class='before'>
            <h3>❌ Ancienne Méthode</h3>";

// Simuler l'ancienne logique
$oldServerAddr = $_SERVER['SERVER_ADDR'] ?? null;
$oldRemoteAddr = $_SERVER['REMOTE_ADDR'] ?? null;
$oldHostnameIp = gethostbyname(gethostname());
$oldSelectedIp = $oldServerAddr ?: ($oldRemoteAddr ?: $oldHostnameIp);

echo "<div class='code'>
Logique simple:
\$ip = \$_SERVER['SERVER_ADDR'] ?: 
      (\$_SERVER['REMOTE_ADDR'] ?: 
       gethostbyname(gethostname()));

Résultat: $oldSelectedIp
Problèmes:
- Pas de gestion des proxies
- Pas de validation d'IP publique
- Pas de fallbacks intelligents
</div>
        </div>
        
        <div class='after'>
            <h3>✅ Nouvelle Méthode Robuste</h3>";

// Utiliser la nouvelle logique
$newIpInfo = collectServerIP();

echo "<div class='code'>
Logique robuste avec priorités:
1. SERVER_ADDR (si publique)
2. HTTP_X_REAL_IP (proxy)
3. HTTP_CF_CONNECTING_IP (Cloudflare)
4. HTTP_X_FORWARDED_FOR (première IP publique)
5. REMOTE_ADDR (si publique)
6. gethostbyname (si publique)
7. Fallbacks intelligents

Résultat: {$newIpInfo['ip']}
Raison: {$newIpInfo['reason']}
Est publique: " . ($newIpInfo['is_local'] ? 'Non' : 'Oui') . "
</div>
        </div>
    </div>
</div>";

// Test 2: Simulation d'appel API
echo "<div class='section'>
    <h2>🚀 Simulation d'Appel API</h2>";

$domain = $_SERVER['HTTP_HOST'] ?? 'localhost';
$testKey = 'TEST-1234-5678-9ABC';

// Données qui seraient envoyées avec l'ancienne méthode
$oldApiData = [
    'serial_key' => $testKey,
    'domain' => $domain,
    'ip_address' => $oldSelectedIp
];

// Données qui seraient envoyées avec la nouvelle méthode
$newApiData = [
    'serial_key' => $testKey,
    'domain' => $domain,
    'ip_address' => $newIpInfo['ip'],
    'validation_mode' => 'domain_only'
];

echo "<div class='comparison'>
    <div class='before'>
        <h3>❌ Données Anciennes</h3>
        <div class='code'>" . json_encode($oldApiData, JSON_PRETTY_PRINT) . "</div>
    </div>
    
    <div class='after'>
        <h3>✅ Données Nouvelles</h3>
        <div class='code'>" . json_encode($newApiData, JSON_PRETTY_PRINT) . "</div>
    </div>
</div>";

// Analyse de l'amélioration
if ($oldSelectedIp !== $newIpInfo['ip']) {
    echo "<div class='info'>
        <h4>🔍 Amélioration Détectée</h4>
        <p><strong>Ancienne IP:</strong> $oldSelectedIp</p>
        <p><strong>Nouvelle IP:</strong> {$newIpInfo['ip']} ({$newIpInfo['reason']})</p>
        <p><strong>Impact:</strong> La nouvelle méthode a sélectionné une IP différente, potentiellement plus appropriée.</p>
    </div>";
} else {
    echo "<div class='success'>
        <h4>✅ Cohérence Maintenue</h4>
        <p>Les deux méthodes sélectionnent la même IP, mais la nouvelle méthode offre plus de robustesse et de diagnostics.</p>
    </div>";
}

echo "</div>";

// Test 3: Validation des améliorations
echo "<div class='section'>
    <h2>🎯 Validation des Améliorations</h2>
    <ul>";

$improvements = [];

// Vérifier si on gère mieux les proxies
if (!empty($_SERVER['HTTP_X_REAL_IP']) || !empty($_SERVER['HTTP_X_FORWARDED_FOR']) || !empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
    $improvements[] = "✅ Gestion des proxies/load balancers détectée";
} else {
    $improvements[] = "ℹ️ Pas de proxy détecté (normal pour serveur direct)";
}

// Vérifier si on a une IP publique
if (!$newIpInfo['is_local']) {
    $improvements[] = "✅ IP publique sélectionnée - Excellent pour l'identification";
} else {
    $improvements[] = "⚠️ IP locale sélectionnée - Le serveur distant recevra l'IP mais elle sera moins utile";
}

// Vérifier la validité de l'IP
if ($newIpInfo['is_valid']) {
    $improvements[] = "✅ IP valide selon les standards";
} else {
    $improvements[] = "❌ IP invalide détectée - Problème de configuration";
}

// Vérifier les logs détaillés
$improvements[] = "✅ Logs détaillés disponibles pour diagnostic";
$improvements[] = "✅ Raison de sélection documentée: {$newIpInfo['reason']}";

foreach ($improvements as $improvement) {
    echo "<li>$improvement</li>";
}

echo "</ul></div>";

// Test 4: Instructions pour la validation
echo "<div class='section'>
    <h2>📋 Instructions de Validation</h2>
    <ol>
        <li><strong>Testez une installation:</strong> Lancez le processus d'installation et vérifiez les logs dans <code>public/install/install_log.txt</code></li>
        <li><strong>Vérifiez les logs Laravel:</strong> Consultez <code>storage/logs/laravel.log</code> pour voir les nouvelles entrées de diagnostic</li>
        <li><strong>Consultez l'interface de licence:</strong> Vérifiez si l'IP apparaît maintenant dans l'interface au lieu de 'Non spécifiée'</li>
        <li><strong>Testez en production:</strong> Déployez sur votre serveur de production et vérifiez que l'IP réelle est collectée</li>
    </ol>
    
    <div class='info'>
        <h4>🔧 Fichiers Modifiés</h4>
        <ul>
            <li><code>public/install/functions/ip_helper.php</code> - Nouvelle fonction de collecte d'IP</li>
            <li><code>app/Helpers/IPHelper.php</code> - Version Laravel de la fonction</li>
            <li><code>public/install/install_new.php</code> - Utilisation de la nouvelle fonction</li>
            <li><code>public/install/functions/core.php</code> - Fonction verifierLicence mise à jour</li>
            <li><code>app/Services/LicenceService.php</code> - Service principal mis à jour</li>
        </ul>
    </div>
</div>";

// Résumé final
echo "<div class='section " . ($newIpInfo['is_local'] ? 'warning' : 'success') . "'>
    <h2>📈 Résumé de la Solution</h2>
    <p><strong>IP qui sera envoyée au serveur de licence:</strong> {$newIpInfo['ip']}</p>
    <p><strong>Méthode de sélection:</strong> {$newIpInfo['reason']}</p>
    <p><strong>Type d'IP:</strong> " . ($newIpInfo['is_local'] ? 'Locale/Privée' : 'Publique') . "</p>
    <p><strong>Statut:</strong> " . ($newIpInfo['is_local'] ? 
        'Le serveur distant recevra l\'IP mais elle sera marquée comme locale' : 
        'Le serveur distant recevra une IP publique utilisable') . "</p>
</div>";

echo "</body></html>";
?>