<?php
/**
 * Script de diagnostic avancé pour valider les hypothèses sur le problème d'IP
 * Version avec logs détaillés pour validation des sources du problème
 */

// Inclure les fonctions nécessaires
require_once __DIR__ . '/install/config.php';
require_once __DIR__ . '/install/functions/ip_helper.php';
require_once __DIR__ . '/install/functions/core.php';

// Fonction de logging pour diagnostic
function logDiagnostic($message, $level = 'INFO', $data = []) {
    $timestamp = date('Y-m-d H:i:s');
    $logEntry = "[$timestamp] [$level] $message";
    if (!empty($data)) {
        $logEntry .= " | Data: " . json_encode($data);
    }
    
    // Écrire dans le fichier de log
    $logFile = __DIR__ . '/install/logs/ip_diagnostic.log';
    if (!is_dir(dirname($logFile))) {
        mkdir(dirname($logFile), 0755, true);
    }
    file_put_contents($logFile, $logEntry . PHP_EOL, FILE_APPEND | LOCK_EX);
    
    return $logEntry;
}

echo "<!DOCTYPE html>
<html>
<head>
    <title>Diagnostic IP Avancé - AdminLicence</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; }
        .section { margin: 20px 0; padding: 20px; border: 1px solid #ddd; border-radius: 8px; background: white; }
        .success { background-color: #d4edda; border-color: #c3e6cb; }
        .warning { background-color: #fff3cd; border-color: #ffeaa7; }
        .error { background-color: #f8d7da; border-color: #f5c6cb; }
        .info { background-color: #d1ecf1; border-color: #bee5eb; }
        .code { background-color: #f8f9fa; padding: 15px; border-radius: 5px; font-family: monospace; font-size: 12px; overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .hypothesis { background-color: #e7f3ff; border-left: 4px solid #2196F3; padding: 15px; margin: 10px 0; }
        .validation { background-color: #f0f8f0; border-left: 4px solid #4CAF50; padding: 15px; margin: 10px 0; }
        .log-entry { background-color: #fafafa; border: 1px solid #eee; padding: 10px; margin: 5px 0; border-radius: 3px; }
        h1 { color: #333; text-align: center; }
        h2 { color: #2196F3; border-bottom: 2px solid #2196F3; padding-bottom: 5px; }
        h3 { color: #666; }
        .status-ok { color: #4CAF50; font-weight: bold; }
        .status-warning { color: #FF9800; font-weight: bold; }
        .status-error { color: #f44336; font-weight: bold; }
    </style>
</head>
<body>
    <div class='container'>
        <h1>🔍 Diagnostic IP Avancé - AdminLicence</h1>
        <p style='text-align: center; color: #666;'>Validation des hypothèses sur les sources du problème d'IP</p>";

// SECTION 1: VALIDATION DES HYPOTHÈSES
echo "<div class='section'>
    <h2>🎯 Validation des Hypothèses Identifiées</h2>";

// Hypothèse 1: Variables $_SERVER non définies
echo "<div class='hypothesis'>
    <h3>📊 Hypothèse 1: Variables \$_SERVER non définies</h3>
    <p><strong>Prédiction:</strong> SERVER_ADDR sera null ou non définie</p>";

$serverAddr = $_SERVER['SERVER_ADDR'] ?? null;
$remoteAddr = $_SERVER['REMOTE_ADDR'] ?? null;

if ($serverAddr === null) {
    echo "<div class='validation status-error'>✅ HYPOTHÈSE CONFIRMÉE: SERVER_ADDR est null</div>";
    logDiagnostic("HYPOTHÈSE 1 CONFIRMÉE: SERVER_ADDR non définie", 'VALIDATION', ['server_addr' => $serverAddr]);
} else {
    echo "<div class='validation status-warning'>❌ HYPOTHÈSE INFIRMÉE: SERVER_ADDR = $serverAddr</div>";
    logDiagnostic("HYPOTHÈSE 1 INFIRMÉE: SERVER_ADDR définie", 'VALIDATION', ['server_addr' => $serverAddr]);
}

echo "</div>";

// Hypothèse 2: Configuration serveur web défaillante
echo "<div class='hypothesis'>
    <h3>⚙️ Hypothèse 2: Configuration serveur web défaillante</h3>
    <p><strong>Prédiction:</strong> Le serveur web ne définit pas correctement les variables d'environnement</p>";

$serverSoftware = $_SERVER['SERVER_SOFTWARE'] ?? 'Non défini';
$serverProtocol = $_SERVER['SERVER_PROTOCOL'] ?? 'Non défini';
$serverPort = $_SERVER['SERVER_PORT'] ?? 'Non défini';

echo "<div class='code'>
<strong>Serveur Web:</strong> $serverSoftware<br>
<strong>Protocole:</strong> $serverProtocol<br>
<strong>Port:</strong> $serverPort
</div>";

$webServerIssues = [];
if (strpos($serverSoftware, 'Apache') === false && strpos($serverSoftware, 'nginx') === false) {
    $webServerIssues[] = "Serveur web non standard détecté";
}
if ($serverAddr === null) {
    $webServerIssues[] = "SERVER_ADDR non définie par le serveur web";
}

if (!empty($webServerIssues)) {
    echo "<div class='validation status-error'>✅ HYPOTHÈSE CONFIRMÉE: " . implode(', ', $webServerIssues) . "</div>";
    logDiagnostic("HYPOTHÈSE 2 CONFIRMÉE: Configuration serveur web défaillante", 'VALIDATION', ['issues' => $webServerIssues]);
} else {
    echo "<div class='validation status-ok'>❌ HYPOTHÈSE INFIRMÉE: Configuration serveur web semble correcte</div>";
    logDiagnostic("HYPOTHÈSE 2 INFIRMÉE: Configuration serveur web correcte", 'VALIDATION');
}

echo "</div>";

// Hypothèse 3: Détection IP locale vs publique
echo "<div class='hypothesis'>
    <h3>🌐 Hypothèse 3: Détection IP locale vs publique impossible</h3>
    <p><strong>Prédiction:</strong> Le système détectera 127.0.0.1 au lieu de l'IP publique</p>";

// Utiliser la fonction d'installation
$ipInfo = collectServerIP();
$selectedIp = $ipInfo['ip'];

echo "<div class='code'>
<strong>IP sélectionnée:</strong> {$ipInfo['ip']}<br>
<strong>Raison:</strong> {$ipInfo['reason']}<br>
<strong>Est locale:</strong> " . ($ipInfo['is_local'] ? 'Oui' : 'Non') . "<br>
<strong>Est valide:</strong> " . ($ipInfo['is_valid'] ? 'Oui' : 'Non') . "
</div>";

if ($ipInfo['is_local'] || $selectedIp === '127.0.0.1') {
    echo "<div class='validation status-error'>✅ HYPOTHÈSE CONFIRMÉE: IP locale détectée ($selectedIp)</div>";
    logDiagnostic("HYPOTHÈSE 3 CONFIRMÉE: IP locale détectée", 'VALIDATION', ['ip' => $selectedIp, 'reason' => $ipInfo['reason']]);
} else {
    echo "<div class='validation status-ok'>❌ HYPOTHÈSE INFIRMÉE: IP publique détectée ($selectedIp)</div>";
    logDiagnostic("HYPOTHÈSE 3 INFIRMÉE: IP publique détectée", 'VALIDATION', ['ip' => $selectedIp]);
}

echo "</div>";

// Hypothèse 4: Incohérence entre systèmes
echo "<div class='hypothesis'>
    <h3>🔄 Hypothèse 4: Incohérence entre systèmes d'installation et runtime</h3>
    <p><strong>Prédiction:</strong> Les deux systèmes utilisent des logiques différentes</p>";

// Simuler l'appel Laravel IPHelper (si disponible)
$laravelIpAvailable = class_exists('App\\Helpers\\IPHelper');
if ($laravelIpAvailable) {
    // Simuler l'appel Laravel
    echo "<div class='code'>
    <strong>Système d'installation:</strong> collectServerIP() (fonction)<br>
    <strong>Système runtime:</strong> IPHelper::collectServerIP() (classe Laravel)<br>
    <strong>Classe Laravel disponible:</strong> Oui
    </div>";
    
    echo "<div class='validation status-error'>✅ HYPOTHÈSE CONFIRMÉE: Deux systèmes différents détectés</div>";
    logDiagnostic("HYPOTHÈSE 4 CONFIRMÉE: Incohérence entre systèmes", 'VALIDATION', ['laravel_available' => true]);
} else {
    echo "<div class='code'>
    <strong>Classe Laravel disponible:</strong> Non (contexte d'installation)
    </div>";
    
    echo "<div class='validation status-warning'>⚠️ HYPOTHÈSE PARTIELLEMENT CONFIRMÉE: Contexte d'installation uniquement</div>";
    logDiagnostic("HYPOTHÈSE 4 PARTIELLEMENT CONFIRMÉE: Contexte installation", 'VALIDATION', ['laravel_available' => false]);
}

echo "</div>";

echo "</div>";

// SECTION 2: TESTS DE VALIDATION APPROFONDIS
echo "<div class='section'>
    <h2>🧪 Tests de Validation Approfondis</h2>";

// Test 1: Collecte exhaustive des sources d'IP
echo "<h3>📡 Test 1: Collecte Exhaustive des Sources d'IP</h3>";
echo "<table>
    <tr><th>Source</th><th>Valeur</th><th>Type</th><th>Statut</th></tr>";

$allIpSources = [
    'SERVER_ADDR' => $_SERVER['SERVER_ADDR'] ?? null,
    'REMOTE_ADDR' => $_SERVER['REMOTE_ADDR'] ?? null,
    'HTTP_X_FORWARDED_FOR' => $_SERVER['HTTP_X_FORWARDED_FOR'] ?? null,
    'HTTP_X_REAL_IP' => $_SERVER['HTTP_X_REAL_IP'] ?? null,
    'HTTP_CF_CONNECTING_IP' => $_SERVER['HTTP_CF_CONNECTING_IP'] ?? null,
    'HTTP_X_FORWARDED' => $_SERVER['HTTP_X_FORWARDED'] ?? null,
    'HTTP_FORWARDED_FOR' => $_SERVER['HTTP_FORWARDED_FOR'] ?? null,
    'HTTP_FORWARDED' => $_SERVER['HTTP_FORWARDED'] ?? null,
    'HTTP_CLIENT_IP' => $_SERVER['HTTP_CLIENT_IP'] ?? null,
];

// Ajouter gethostbyname
try {
    $hostname = gethostname();
    $hostnameIp = $hostname ? gethostbyname($hostname) : null;
    if ($hostnameIp === $hostname) $hostnameIp = null;
    $allIpSources['gethostbyname'] = $hostnameIp;
} catch (Exception $e) {
    $allIpSources['gethostbyname'] = null;
}

$validPublicIps = 0;
$localIps = 0;
$nullIps = 0;

foreach ($allIpSources as $source => $value) {
    $type = 'N/A';
    $status = 'null';
    $statusClass = 'status-error';
    
    if ($value !== null) {
        if (filter_var($value, FILTER_VALIDATE_IP)) {
            if (filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                $type = 'IP Publique';
                $status = 'Valide';
                $statusClass = 'status-ok';
                $validPublicIps++;
            } else {
                $type = 'IP Locale/Privée';
                $status = 'Locale';
                $statusClass = 'status-warning';
                $localIps++;
            }
        } else {
            $type = 'Invalide';
            $status = 'Erreur';
            $statusClass = 'status-error';
        }
    } else {
        $nullIps++;
    }
    
    echo "<tr>
        <td><code>$source</code></td>
        <td>" . ($value ?: '<em>null</em>') . "</td>
        <td>$type</td>
        <td class='$statusClass'>$status</td>
    </tr>";
}

echo "</table>";

echo "<div class='code'>
<strong>Résumé de la collecte:</strong><br>
- IPs publiques valides: $validPublicIps<br>
- IPs locales/privées: $localIps<br>
- Sources nulles: $nullIps<br>
- Total des sources: " . count($allIpSources) . "
</div>";

logDiagnostic("Test collecte exhaustive terminé", 'TEST', [
    'public_ips' => $validPublicIps,
    'local_ips' => $localIps,
    'null_sources' => $nullIps,
    'total_sources' => count($allIpSources)
]);

// Test 2: Détection d'environnement
echo "<h3>🏠 Test 2: Détection d'Environnement</h3>";

$envIndicators = [
    'HTTP_HOST' => $_SERVER['HTTP_HOST'] ?? 'Non défini',
    'SERVER_NAME' => $_SERVER['SERVER_NAME'] ?? 'Non défini',
    'SERVER_PORT' => $_SERVER['SERVER_PORT'] ?? 'Non défini',
    'HTTPS' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'Oui' : 'Non',
    'REQUEST_SCHEME' => $_SERVER['REQUEST_SCHEME'] ?? 'Non défini'
];

$isLocalEnv = false;
$localIndicators = [];

// Vérifier les indicateurs d'environnement local
if (in_array($envIndicators['HTTP_HOST'], ['localhost', '127.0.0.1']) ||
    strpos($envIndicators['HTTP_HOST'], ':8000') !== false ||
    strpos($envIndicators['HTTP_HOST'], '.local') !== false) {
    $isLocalEnv = true;
    $localIndicators[] = "Host local détecté: " . $envIndicators['HTTP_HOST'];
}

if ($envIndicators['SERVER_PORT'] === '8000' || $envIndicators['SERVER_PORT'] === '3000') {
    $isLocalEnv = true;
    $localIndicators[] = "Port de développement détecté: " . $envIndicators['SERVER_PORT'];
}

echo "<div class='code'>";
foreach ($envIndicators as $key => $value) {
    echo "<strong>$key:</strong> $value<br>";
}
echo "</div>";

if ($isLocalEnv) {
    echo "<div class='validation status-warning'>⚠️ ENVIRONNEMENT LOCAL DÉTECTÉ<br>Indicateurs: " . implode(', ', $localIndicators) . "</div>";
    logDiagnostic("Environnement local détecté", 'DETECTION', ['indicators' => $localIndicators]);
} else {
    echo "<div class='validation status-ok'>✅ ENVIRONNEMENT PRODUCTION/DISTANT DÉTECTÉ</div>";
    logDiagnostic("Environnement production détecté", 'DETECTION');
}

echo "</div>";

// SECTION 3: RECOMMANDATIONS BASÉES SUR LA VALIDATION
echo "<div class='section'>
    <h2>💡 Recommandations Basées sur la Validation</h2>";

$recommendations = [];

if ($serverAddr === null) {
    $recommendations[] = [
        'priority' => 'HAUTE',
        'issue' => 'SERVER_ADDR non définie',
        'solution' => 'Configurer le serveur web (Apache/Nginx) pour définir SERVER_ADDR',
        'action' => 'Vérifier la configuration du serveur web et les modules chargés'
    ];
}

if ($validPublicIps === 0) {
    $recommendations[] = [
        'priority' => 'HAUTE',
        'issue' => 'Aucune IP publique détectée',
        'solution' => 'Configurer les headers de proxy ou vérifier la configuration réseau',
        'action' => 'Contacter l\'hébergeur ou configurer les headers HTTP_X_FORWARDED_FOR'
    ];
}

if ($isLocalEnv) {
    $recommendations[] = [
        'priority' => 'MOYENNE',
        'issue' => 'Environnement local détecté',
        'solution' => 'Tester en environnement de production pour validation finale',
        'action' => 'Déployer sur un serveur de production pour tests réels'
    ];
}

if ($laravelIpAvailable) {
    $recommendations[] = [
        'priority' => 'MOYENNE',
        'issue' => 'Incohérence entre systèmes d\'installation et runtime',
        'solution' => 'Unifier la logique de détection d\'IP',
        'action' => 'Utiliser la même classe IPHelper dans les deux contextes'
    ];
}

if (empty($recommendations)) {
    echo "<div class='validation status-ok'>✅ Aucun problème critique détecté dans l'environnement actuel</div>";
} else {
    echo "<ol>";
    foreach ($recommendations as $rec) {
        $priorityClass = $rec['priority'] === 'HAUTE' ? 'status-error' : 'status-warning';
        echo "<li>
            <strong class='$priorityClass'>Priorité {$rec['priority']}</strong><br>
            <strong>Problème:</strong> {$rec['issue']}<br>
            <strong>Solution:</strong> {$rec['solution']}<br>
            <strong>Action:</strong> {$rec['action']}
        </li><br>";
    }
    echo "</ol>";
}

logDiagnostic("Diagnostic terminé", 'SUMMARY', [
    'recommendations_count' => count($recommendations),
    'selected_ip' => $selectedIp,
    'is_local_env' => $isLocalEnv,
    'public_ips_available' => $validPublicIps
]);

echo "</div>";

// SECTION 4: LOGS GÉNÉRÉS
echo "<div class='section'>
    <h2>📋 Logs de Diagnostic Générés</h2>
    <p>Les logs détaillés ont été sauvegardés dans: <code>public/install/logs/ip_diagnostic.log</code></p>";

// Afficher les derniers logs
$logFile = __DIR__ . '/install/logs/ip_diagnostic.log';
if (file_exists($logFile)) {
    $logs = file($logFile, FILE_IGNORE_NEW_LINES);
    $recentLogs = array_slice($logs, -10); // 10 dernières entrées
    
    echo "<div class='code'>";
    foreach ($recentLogs as $log) {
        echo "<div class='log-entry'>" . htmlspecialchars($log) . "</div>";
    }
    echo "</div>";
} else {
    echo "<div class='warning'>⚠️ Fichier de log non trouvé</div>";
}

echo "</div>";

// SECTION 5: RÉSUMÉ EXÉCUTIF
echo "<div class='section info'>
    <h2>📊 Résumé Exécutif du Diagnostic</h2>
    <div class='code'>
        <strong>IP qui sera envoyée au serveur de licence:</strong> $selectedIp<br>
        <strong>Type d'IP:</strong> " . ($ipInfo['is_local'] ? 'Locale/Privée' : 'Publique') . "<br>
        <strong>Raison de sélection:</strong> {$ipInfo['reason']}<br>
        <strong>Environnement détecté:</strong> " . ($isLocalEnv ? 'Local/Développement' : 'Production/Distant') . "<br>
        <strong>Nombre d'IPs publiques disponibles:</strong> $validPublicIps<br>
        <strong>Recommandations critiques:</strong> " . count(array_filter($recommendations, function($r) { return $r['priority'] === 'HAUTE'; })) . "<br>
        <strong>Timestamp du diagnostic:</strong> " . date('Y-m-d H:i:s') . "
    </div>
</div>";

echo "</div></body></html>";
?>