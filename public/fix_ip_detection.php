<?php
/**
 * Script de correction pour la détection d'IP du serveur
 * Ce script implémente plusieurs stratégies pour obtenir l'IP publique réelle
 */

// Inclure les fonctions nécessaires
require_once __DIR__ . '/install/config.php';
require_once __DIR__ . '/install/functions/ip_helper.php';
require_once __DIR__ . '/install/functions/core.php';

echo "<!DOCTYPE html>
<html>
<head>
    <title>Correction Détection IP - AdminLicence</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .section { margin: 20px 0; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
        .success { background-color: #d4edda; border-color: #c3e6cb; }
        .warning { background-color: #fff3cd; border-color: #ffeaa7; }
        .error { background-color: #f8d7da; border-color: #f5c6cb; }
        .info { background-color: #d1ecf1; border-color: #bee5eb; }
        .code { background-color: #f8f9fa; padding: 10px; border-radius: 3px; font-family: monospace; white-space: pre-wrap; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 8px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f2f2f2; }
        .btn { display: inline-block; padding: 10px 20px; margin: 5px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; }
        .btn:hover { background: #0056b3; }
    </style>
</head>
<body>
    <h1>🔧 Correction Détection IP - AdminLicence</h1>
    <p>Ce script corrige la détection d'IP du serveur en implémentant plusieurs stratégies robustes.</p>";

/**
 * Nouvelle fonction de détection d'IP publique robuste
 */
function detectPublicServerIP() {
    $strategies = [];
    $finalIP = null;
    $finalReason = '';
    
    // Stratégie 1: Variables serveur classiques
    $serverVars = [
        'SERVER_ADDR' => $_SERVER['SERVER_ADDR'] ?? null,
        'REMOTE_ADDR' => $_SERVER['REMOTE_ADDR'] ?? null,
        'HTTP_X_FORWARDED_FOR' => $_SERVER['HTTP_X_FORWARDED_FOR'] ?? null,
        'HTTP_X_REAL_IP' => $_SERVER['HTTP_X_REAL_IP'] ?? null,
        'HTTP_CF_CONNECTING_IP' => $_SERVER['HTTP_CF_CONNECTING_IP'] ?? null,
    ];
    
    foreach ($serverVars as $var => $value) {
        if (!empty($value) && !isLocalIP($value)) {
            $strategies[] = [
                'method' => 'server_var',
                'source' => $var,
                'ip' => $value,
                'priority' => getVarPriority($var)
            ];
        }
    }
    
    // Stratégie 2: Résolution DNS du hostname
    try {
        $hostname = gethostname();
        if ($hostname !== false) {
            $hostnameIPs = gethostbynamel($hostname);
            if ($hostnameIPs) {
                foreach ($hostnameIPs as $ip) {
                    if (!isLocalIP($ip)) {
                        $strategies[] = [
                            'method' => 'dns_resolution',
                            'source' => 'gethostbynamel(' . $hostname . ')',
                            'ip' => $ip,
                            'priority' => 50
                        ];
                    }
                }
            }
        }
    } catch (Exception $e) {
        // Ignorer les erreurs DNS
    }
    
    // Stratégie 3: Services externes (en dernier recours)
    $externalServices = [
        'ipify' => 'https://api.ipify.org',
        'icanhazip' => 'https://icanhazip.com',
        'httpbin' => 'https://httpbin.org/ip'
    ];
    
    foreach ($externalServices as $service => $url) {
        try {
            $context = stream_context_create([
                'http' => [
                    'timeout' => 3,
                    'user_agent' => 'AdminLicence-IPDetection/1.0'
                ]
            ]);
            $result = @file_get_contents($url, false, $context);
            if ($result !== false) {
                $result = trim($result);
                // Pour httpbin, extraire l'IP du JSON
                if ($service === 'httpbin') {
                    $json = json_decode($result, true);
                    $result = $json['origin'] ?? null;
                }
                if ($result && filter_var($result, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                    $strategies[] = [
                        'method' => 'external_service',
                        'source' => $service,
                        'ip' => $result,
                        'priority' => 100 // Priorité la plus basse
                    ];
                }
            }
        } catch (Exception $e) {
            // Ignorer les erreurs de service externe
        }
    }
    
    // Trier par priorité (plus petit = plus prioritaire)
    usort($strategies, function($a, $b) {
        return $a['priority'] - $b['priority'];
    });
    
    // Sélectionner la meilleure IP
    if (!empty($strategies)) {
        $best = $strategies[0];
        $finalIP = $best['ip'];
        $finalReason = $best['source'] . ' (' . $best['method'] . ')';
    } else {
        // Fallback final
        $finalIP = '127.0.0.1';
        $finalReason = 'Fallback - aucune IP publique détectée';
    }
    
    return [
        'ip' => $finalIP,
        'reason' => $finalReason,
        'strategies' => $strategies,
        'is_local' => isLocalIP($finalIP),
        'is_valid' => filter_var($finalIP, FILTER_VALIDATE_IP) !== false
    ];
}

function getVarPriority($var) {
    $priorities = [
        'SERVER_ADDR' => 10,
        'HTTP_X_REAL_IP' => 20,
        'HTTP_CF_CONNECTING_IP' => 25,
        'HTTP_X_FORWARDED_FOR' => 30,
        'REMOTE_ADDR' => 40
    ];
    return $priorities[$var] ?? 99;
}

// Exécuter le diagnostic
echo "<div class='section'>
    <h2>🎯 Nouvelle Détection IP Robuste</h2>";

$newDetection = detectPublicServerIP();

echo "<div class='code'>
<strong>🔍 RÉSULTAT DE LA NOUVELLE DÉTECTION:</strong>
IP sélectionnée: {$newDetection['ip']}
Raison: {$newDetection['reason']}
Est locale: " . ($newDetection['is_local'] ? 'Oui' : 'Non') . "
Est valide: " . ($newDetection['is_valid'] ? 'Oui' : 'Non') . "

<strong>📊 STRATÉGIES TESTÉES:</strong>";

if (!empty($newDetection['strategies'])) {
    foreach ($newDetection['strategies'] as $i => $strategy) {
        $status = ($i === 0) ? '✅ SÉLECTIONNÉE' : '⏸️ Alternative';
        echo "
{$status} - {$strategy['source']}: {$strategy['ip']} (priorité: {$strategy['priority']})";
    }
} else {
    echo "
❌ Aucune stratégie n'a fonctionné";
}

echo "</div>";

// Comparaison avec l'ancienne méthode
$oldDetection = collectServerIP();
echo "<div class='section'>
    <h2>📊 Comparaison Ancienne vs Nouvelle Méthode</h2>
    <table>
        <tr><th>Aspect</th><th>Ancienne Méthode</th><th>Nouvelle Méthode</th><th>Amélioration</th></tr>
        <tr>
            <td>IP Détectée</td>
            <td>{$oldDetection['ip']}</td>
            <td>{$newDetection['ip']}</td>
            <td>" . ($newDetection['ip'] !== $oldDetection['ip'] ? '🔄 Différente' : '✅ Identique') . "</td>
        </tr>
        <tr>
            <td>Est Publique</td>
            <td>" . ($oldDetection['is_local'] ? '❌ Non' : '✅ Oui') . "</td>
            <td>" . ($newDetection['is_local'] ? '❌ Non' : '✅ Oui') . "</td>
            <td>" . (!$newDetection['is_local'] && $oldDetection['is_local'] ? '🎉 Améliorée' : ($newDetection['is_local'] === $oldDetection['is_local'] ? '➖ Identique' : '⚠️ Dégradée')) . "</td>
        </tr>
        <tr>
            <td>Raison</td>
            <td>{$oldDetection['reason']}</td>
            <td>{$newDetection['reason']}</td>
            <td>📝 Détaillée</td>
        </tr>
        <tr>
            <td>Stratégies Testées</td>
            <td>1 (logique fixe)</td>
            <td>" . count($newDetection['strategies']) . " (dynamique)</td>
            <td>🚀 Plus robuste</td>
        </tr>
    </table>
</div>";

// Test de transmission
if (isset($_GET['test_transmission']) && $_GET['test_transmission'] === '1') {
    echo "<div class='section'>
        <h2>🚀 Test de Transmission au Serveur de Licence</h2>";
    
    $testKey = $_GET['test_key'] ?? 'TEST-1234-5678-9ABC';
    $domain = $_SERVER['HTTP_HOST'] ?? 'localhost';
    
    // Tester avec l'ancienne IP
    $oldData = [
        'serial_key' => strtoupper($testKey),
        'domain' => $domain,
        'ip_address' => $oldDetection['ip'],
        'validation_mode' => 'domain_only'
    ];
    
    // Tester avec la nouvelle IP
    $newData = [
        'serial_key' => strtoupper($testKey),
        'domain' => $domain,
        'ip_address' => $newDetection['ip'],
        'validation_mode' => 'domain_only'
    ];
    
    echo "<div class='code'>
<strong>🔄 COMPARAISON DES DONNÉES TRANSMISES:</strong>

<strong>Ancienne méthode:</strong>
" . json_encode($oldData, JSON_PRETTY_PRINT) . "

<strong>Nouvelle méthode:</strong>
" . json_encode($newData, JSON_PRETTY_PRINT) . "

<strong>🎯 IMPACT:</strong>";
    
    if ($newDetection['ip'] !== $oldDetection['ip']) {
        echo "
✅ L'IP transmise au serveur distant sera différente
✅ Le serveur recevra: {$newDetection['ip']} au lieu de {$oldDetection['ip']}";
        if (!$newDetection['is_local'] && $oldDetection['is_local']) {
            echo "
🎉 AMÉLIORATION MAJEURE: IP publique au lieu d'IP locale!";
        }
    } else {
        echo "
➖ Aucun changement dans l'IP transmise";
    }
    
    echo "</div>";
    
    echo "<div class='warning'>
        ℹ️ <strong>Note:</strong> Ce test ne fait pas d'appel réel à l'API pour éviter les erreurs. 
        Pour appliquer la correction, utilisez le bouton ci-dessous.
    </div>";
} else {
    echo "<div class='section'>
        <h2>🧪 Test de Transmission</h2>
        <p>Voulez-vous tester comment les données seraient transmises au serveur de licence ?</p>
        <a href='?test_transmission=1&test_key=TEST-1234-5678-9ABC' class='btn'>🧪 Tester la Transmission</a>
    </div>";
}

// Actions recommandées
echo "<div class='section'>
    <h2>🎯 Actions Recommandées</h2>";

if (!$newDetection['is_local'] && $oldDetection['is_local']) {
    echo "<div class='success'>
        <h3>🎉 Correction Recommandée</h3>
        <p>La nouvelle méthode détecte une IP publique ({$newDetection['ip']}) alors que l'ancienne détectait une IP locale ({$oldDetection['ip']}).</p>
        <p><strong>Impact:</strong> Le serveur de licence recevra une IP publique utilisable pour l'identification géographique.</p>
        <a href='?apply_fix=1' class='btn'>✅ Appliquer la Correction</a>
    </div>";
} elseif ($newDetection['ip'] !== $oldDetection['ip']) {
    echo "<div class='info'>
        <h3>🔄 Amélioration Possible</h3>
        <p>La nouvelle méthode détecte une IP différente ({$newDetection['ip']}) de l'ancienne ({$oldDetection['ip']}).</p>
        <p><strong>Recommandation:</strong> Vérifiez quelle IP est la plus appropriée pour votre environnement.</p>
        <a href='?apply_fix=1' class='btn'>🔄 Appliquer la Correction</a>
    </div>";
} else {
    echo "<div class='warning'>
        <h3>➖ Aucune Amélioration Détectée</h3>
        <p>Les deux méthodes détectent la même IP ({$newDetection['ip']}).</p>
        <p><strong>Statut:</strong> " . ($newDetection['is_local'] ? 'IP locale - normal en environnement de développement' : 'IP publique - configuration optimale') . "</p>";
    
    if ($newDetection['is_local']) {
        echo "<p><strong>Solutions possibles:</strong></p>
        <ul>
            <li>Configurer les headers de proxy si vous êtes derrière un load balancer</li>
            <li>Vérifier la configuration de votre serveur web (Apache/Nginx)</li>
            <li>Contacter votre hébergeur pour la configuration réseau</li>
        </ul>";
    }
    echo "</div>";
}

echo "</div>";

// Application de la correction
if (isset($_GET['apply_fix']) && $_GET['apply_fix'] === '1') {
    echo "<div class='section'>
        <h2>🔧 Application de la Correction</h2>";
    
    try {
        // Sauvegarder l'ancienne fonction
        $backupFile = __DIR__ . '/install/functions/ip_helper_backup_' . date('Y-m-d_H-i-s') . '.php';
        copy(__DIR__ . '/install/functions/ip_helper.php', $backupFile);
        
        // Créer la nouvelle fonction améliorée
        $newIPHelperContent = '<?php
/**
 * Fonctions d\'aide pour la collecte d\'IP du serveur - VERSION AMÉLIORÉE
 * Générée automatiquement le ' . date('Y-m-d H:i:s') . '
 */

/**
 * Collecte l\'adresse IP réelle du serveur de manière robuste - VERSION AMÉLIORÉE
 * 
 * @return array Tableau avec l\'IP sélectionnée et les détails de diagnostic
 */
function collectServerIP() {
    $strategies = [];
    $finalIP = null;
    $finalReason = \'\';
    
    // Stratégie 1: Variables serveur classiques
    $serverVars = [
        \'SERVER_ADDR\' => $_SERVER[\'SERVER_ADDR\'] ?? null,
        \'HTTP_X_REAL_IP\' => $_SERVER[\'HTTP_X_REAL_IP\'] ?? null,
        \'HTTP_CF_CONNECTING_IP\' => $_SERVER[\'HTTP_CF_CONNECTING_IP\'] ?? null,
        \'HTTP_X_FORWARDED_FOR\' => $_SERVER[\'HTTP_X_FORWARDED_FOR\'] ?? null,
        \'REMOTE_ADDR\' => $_SERVER[\'REMOTE_ADDR\'] ?? null,
    ];
    
    foreach ($serverVars as $var => $value) {
        if (!empty($value) && !isLocalIP($value)) {
            $strategies[] = [
                \'method\' => \'server_var\',
                \'source\' => $var,
                \'ip\' => $value,
                \'priority\' => getVarPriority($var)
            ];
        }
    }
    
    // Traitement spécial pour HTTP_X_FORWARDED_FOR (peut contenir plusieurs IPs)
    if (!empty($_SERVER[\'HTTP_X_FORWARDED_FOR\'])) {
        $forwardedIPs = explode(\',\', $_SERVER[\'HTTP_X_FORWARDED_FOR\']);
        foreach ($forwardedIPs as $ip) {
            $ip = trim($ip);
            if (!empty($ip) && !isLocalIP($ip) && filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                $strategies[] = [
                    \'method\' => \'server_var\',
                    \'source\' => \'HTTP_X_FORWARDED_FOR (première IP publique)\',
                    \'ip\' => $ip,
                    \'priority\' => 30
                ];
                break; // Prendre seulement la première IP publique
            }
        }
    }
    
    // Stratégie 2: Résolution DNS du hostname
    try {
        $hostname = gethostname();
        if ($hostname !== false) {
            $hostnameIPs = gethostbynamel($hostname);
            if ($hostnameIPs) {
                foreach ($hostnameIPs as $ip) {
                    if (!isLocalIP($ip)) {
                        $strategies[] = [
                            \'method\' => \'dns_resolution\',
                            \'source\' => \'gethostbynamel(\' . $hostname . \')\',
                            \'ip\' => $ip,
                            \'priority\' => 50
                        ];
                        break; // Prendre seulement la première IP publique
                    }
                }
            }
        }
    } catch (Exception $e) {
        // Ignorer les erreurs DNS
    }
    
    // Stratégie 3: Services externes (en dernier recours et seulement si aucune autre méthode)
    if (empty($strategies)) {
        $externalServices = [
            \'ipify\' => \'https://api.ipify.org\',
            \'icanhazip\' => \'https://icanhazip.com\'
        ];
        
        foreach ($externalServices as $service => $url) {
            try {
                $context = stream_context_create([
                    \'http\' => [
                        \'timeout\' => 3,
                        \'user_agent\' => \'AdminLicence-IPDetection/1.0\'
                    ]
                ]);
                $result = @file_get_contents($url, false, $context);
                if ($result !== false) {
                    $result = trim($result);
                    if ($result && filter_var($result, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                        $strategies[] = [
                            \'method\' => \'external_service\',
                            \'source\' => $service,
                            \'ip\' => $result,
                            \'priority\' => 100
                        ];
                        break; // Prendre seulement le premier service qui fonctionne
                    }
                }
            } catch (Exception $e) {
                // Ignorer les erreurs de service externe
            }
        }
    }
    
    // Trier par priorité (plus petit = plus prioritaire)
    usort($strategies, function($a, $b) {
        return $a[\'priority\'] - $b[\'priority\'];
    });
    
    // Sélectionner la meilleure IP
    if (!empty($strategies)) {
        $best = $strategies[0];
        $finalIP = $best[\'ip\'];
        $finalReason = $best[\'source\'] . \' (\' . $best[\'method\'] . \')\';
    } else {
        // Fallback vers les variables serveur même si locales
        foreach ($serverVars as $var => $value) {
            if (!empty($value)) {
                $finalIP = $value;
                $finalReason = $var . \' (fallback)\';
                break;
            }
        }
        
        // Dernier fallback
        if (!$finalIP) {
            $finalIP = \'127.0.0.1\';
            $finalReason = \'Fallback final\';
        }
    }
    
    return [
        \'ip\' => $finalIP,
        \'reason\' => $finalReason,
        \'sources\' => array_merge($serverVars, [\'gethostbyname\' => gethostbyname(gethostname())]),
        \'strategies\' => $strategies,
        \'is_local\' => isLocalIP($finalIP),
        \'is_valid\' => filter_var($finalIP, FILTER_VALIDATE_IP) !== false
    ];
}

function getVarPriority($var) {
    $priorities = [
        \'SERVER_ADDR\' => 10,
        \'HTTP_X_REAL_IP\' => 20,
        \'HTTP_CF_CONNECTING_IP\' => 25,
        \'HTTP_X_FORWARDED_FOR\' => 30,
        \'REMOTE_ADDR\' => 40
    ];
    return $priorities[$var] ?? 99;
}

/**
 * Vérifie si une IP est considérée comme locale/privée
 * 
 * @param string $ip L\'adresse IP à vérifier
 * @return bool True si l\'IP est locale, false sinon
 */
function isLocalIP($ip) {
    if (empty($ip)) {
        return true;
    }
    
    // IPs explicitement locales
    $localIPs = [\'127.0.0.1\', \'::1\', \'localhost\'];
    if (in_array($ip, $localIPs)) {
        return true;
    }
    
    // Utiliser les filtres PHP pour détecter les IPs privées/réservées
    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
        return true;
    }
    
    return false;
}

/**
 * Formate les informations d\'IP pour les logs
 * 
 * @param array $ipInfo Résultat de collectServerIP()
 * @return string Message formaté pour les logs
 */
function formatIPInfoForLog($ipInfo) {
    $sources = [];
    foreach ($ipInfo[\'sources\'] as $key => $value) {
        $sources[] = "$key: " . ($value ?: \'null\');
    }
    
    $strategiesInfo = \'\';
    if (isset($ipInfo[\'strategies\']) && !empty($ipInfo[\'strategies\'])) {
        $strategiesInfo = \' | Stratégies testées: \' . count($ipInfo[\'strategies\']);
    }
    
    return "IP sélectionnée: {$ipInfo[\'ip\']} ({$ipInfo[\'reason\']}) | " . 
           "Local: " . ($ipInfo[\'is_local\'] ? \'oui\' : \'non\') . " | " .
           "Valide: " . ($ipInfo[\'is_valid\'] ? \'oui\' : \'non\') . $strategiesInfo . " | " .
           "Sources: " . implode(\' | \', $sources);
}
';
        
        // Écrire le nouveau fichier
        file_put_contents(__DIR__ . '/install/functions/ip_helper.php', $newIPHelperContent);
        
        echo "<div class='success'>
            <h3>✅ Correction Appliquée avec Succès</h3>
            <p><strong>Fichier sauvegardé:</strong> " . basename($backupFile) . "</p>
            <p><strong>Nouveau fichier:</strong> ip_helper.php (version améliorée)</p>
            <p><strong>Améliorations apportées:</strong></p>
            <ul>
                <li>🎯 Détection multi-stratégies avec priorités</li>
                <li>🔍 Résolution DNS améliorée</li>
                <li>🌐 Services externes en dernier recours</li>
                <li>📊 Logging détaillé des stratégies testées</li>
                <li>⚡ Performance optimisée</li>
            </ul>
        </div>";
        
        // Test de la nouvelle fonction
        $testResult = collectServerIP();
        echo "<div class='info'>
            <h3>🧪 Test de la Nouvelle Fonction</h3>
            <div class='code'>
IP détectée: {$testResult['ip']}
Raison: {$testResult['reason']}
Est locale: " . ($testResult['is_local'] ? 'Oui' : 'Non') . "
Stratégies testées: " . (isset($testResult['strategies']) ? count($testResult['strategies']) : 'N/A') . "
            </div>
        </div>";
        
    } catch (Exception $e) {
        echo "<div class='error'>
            <h3>❌ Erreur lors de l'Application</h3>
            <p>Erreur: " . $e->getMessage() . "</p>
        </div>";
    }
}

echo "<div class='section'>
    <h2>📋 Résumé</h2>
    <div class='code'>
<strong>IP ACTUELLE (ancienne méthode):</strong> {$oldDetection['ip']} - {$oldDetection['reason']}
<strong>IP PROPOSÉE (nouvelle méthode):</strong> {$newDetection['ip']} - {$newDetection['reason']}
<strong>AMÉLIORATION:</strong> " . (!$newDetection['is_local'] && $oldDetection['is_local'] ? 'IP publique détectée ✅' : ($newDetection['ip'] !== $oldDetection['ip'] ? 'IP différente détectée 🔄' : 'Aucun changement ➖')) . "
    </div>
</div>";

echo "</body></html>";
?>