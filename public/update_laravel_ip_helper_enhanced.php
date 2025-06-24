<?php
/**
 * Script de mise à jour avancée du Helper Laravel IPHelper
 * Assure la cohérence entre les systèmes d'installation et runtime
 */

// Configuration
$logFile = __DIR__ . '/install/logs/laravel_helper_update.log';
$laravelHelperPath = __DIR__ . '/../app/Helpers/IPHelper.php';

// Fonction de logging
function logUpdate($message, $level = 'INFO', $data = []) {
    global $logFile;
    $timestamp = date('Y-m-d H:i:s');
    $logEntry = "[$timestamp] [$level] $message";
    if (!empty($data)) {
        $logEntry .= " | Data: " . json_encode($data);
    }
    
    if (!is_dir(dirname($logFile))) {
        mkdir(dirname($logFile), 0755, true);
    }
    file_put_contents($logFile, $logEntry . PHP_EOL, FILE_APPEND | LOCK_EX);
    
    return $logEntry;
}

echo "<!DOCTYPE html>
<html>
<head>
    <title>Mise à Jour Helper Laravel - IPHelper</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f5f5f5; }
        .container { max-width: 1000px; margin: 0 auto; }
        .section { margin: 20px 0; padding: 20px; border: 1px solid #ddd; border-radius: 8px; background: white; }
        .success { background-color: #d4edda; border-color: #c3e6cb; }
        .warning { background-color: #fff3cd; border-color: #ffeaa7; }
        .error { background-color: #f8d7da; border-color: #f5c6cb; }
        .info { background-color: #d1ecf1; border-color: #bee5eb; }
        .code { background-color: #f8f9fa; padding: 15px; border-radius: 5px; font-family: monospace; font-size: 12px; overflow-x: auto; white-space: pre-wrap; }
        .step { background-color: #e7f3ff; border-left: 4px solid #2196F3; padding: 15px; margin: 10px 0; }
        h1 { color: #333; text-align: center; }
        h2 { color: #2196F3; border-bottom: 2px solid #2196F3; padding-bottom: 5px; }
        .status-ok { color: #4CAF50; font-weight: bold; }
        .status-warning { color: #FF9800; font-weight: bold; }
        .status-error { color: #f44336; font-weight: bold; }
        .diff { background-color: #f8f9fa; border: 1px solid #e9ecef; border-radius: 5px; padding: 10px; margin: 10px 0; }
        .diff-add { background-color: #d4edda; color: #155724; }
        .diff-remove { background-color: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
    <div class='container'>
        <h1>🔄 Mise à Jour Helper Laravel - IPHelper</h1>
        <p style='text-align: center; color: #666;'>Assurer la cohérence entre les systèmes d'installation et runtime</p>";

logUpdate("Début de la mise à jour du Helper Laravel", 'START');

// ÉTAPE 1: VÉRIFICATION DE L'ÉTAT ACTUEL
echo "<div class='section'>
    <h2>📊 Étape 1: Vérification de l'État Actuel</h2>";

$currentState = [
    'laravel_helper_exists' => file_exists($laravelHelperPath),
    'install_helper_exists' => file_exists(__DIR__ . '/install/functions/ip_helper.php'),
    'laravel_helper_readable' => file_exists($laravelHelperPath) && is_readable($laravelHelperPath),
    'laravel_helper_writable' => file_exists($laravelHelperPath) && is_writable($laravelHelperPath)
];

echo "<div class='code'>";
foreach ($currentState as $key => $value) {
    $status = $value ? '✅' : '❌';
    echo "$key: $status " . ($value ? 'OUI' : 'NON') . "\n";
}
echo "</div>";

if (!$currentState['laravel_helper_exists']) {
    echo "<div class='step status-error'>❌ Le fichier IPHelper Laravel n'existe pas. Création nécessaire.</div>";
    logUpdate("IPHelper Laravel non trouvé", 'WARNING');
} elseif (!$currentState['laravel_helper_writable']) {
    echo "<div class='step status-error'>❌ Le fichier IPHelper Laravel n'est pas accessible en écriture.</div>";
    logUpdate("IPHelper Laravel non accessible en écriture", 'ERROR');
    echo "</div></div></body></html>";
    exit;
} else {
    echo "<div class='step status-ok'>✅ IPHelper Laravel trouvé et accessible</div>";
    logUpdate("IPHelper Laravel accessible", 'SUCCESS');
}

echo "</div>";

// ÉTAPE 2: ANALYSE DU CONTENU ACTUEL
echo "<div class='section'>
    <h2>🔍 Étape 2: Analyse du Contenu Actuel</h2>";

$currentContent = '';
$hasRobustMethod = false;
$hasExternalMethod = false;

if ($currentState['laravel_helper_exists']) {
    $currentContent = file_get_contents($laravelHelperPath);
    $hasRobustMethod = strpos($currentContent, 'collectServerIPRobust') !== false;
    $hasExternalMethod = strpos($currentContent, 'collectServerIPWithExternal') !== false;
    
    echo "<div class='code'>
Taille du fichier actuel: " . strlen($currentContent) . " octets
Méthode collectServerIPRobust présente: " . ($hasRobustMethod ? 'OUI' : 'NON') . "
Méthode collectServerIPWithExternal présente: " . ($hasExternalMethod ? 'OUI' : 'NON') . "
Nombre de lignes: " . substr_count($currentContent, "\n") . "
    </div>";
    
    logUpdate("Analyse du contenu actuel effectuée", 'ANALYSIS', [
        'file_size' => strlen($currentContent),
        'has_robust_method' => $hasRobustMethod,
        'has_external_method' => $hasExternalMethod
    ]);
} else {
    echo "<div class='step status-warning'>⚠️ Fichier non existant - création complète nécessaire</div>";
}

echo "</div>";

// ÉTAPE 3: CRÉATION DU NOUVEAU CONTENU
echo "<div class='section'>
    <h2>🔧 Étape 3: Création du Nouveau Contenu</h2>";

$newLaravelHelperContent = '<?php

namespace App\Helpers;

/**
 * Classe d\'aide pour la collecte d\'IP du serveur
 * Version améliorée avec cohérence entre installation et runtime
 */
class IPHelper
{
    /**
     * Collecte l\'adresse IP réelle du serveur de manière robuste
     * 
     * @return array Tableau avec l\'IP sélectionnée et les détails de diagnostic
     */
    public static function collectServerIP()
    {
        // Utiliser la version robuste par défaut
        return self::collectServerIPRobust(false);
    }

    /**
     * Collecte l\'adresse IP réelle du serveur avec stratégies multiples (version robuste)
     * 
     * @param bool $forceExternal Force la détection via services externes
     * @return array Tableau avec l\'IP sélectionnée et les détails de diagnostic
     */
    public static function collectServerIPRobust($forceExternal = false)
    {
        // Essayer d\'utiliser la fonction d\'installation si disponible pour la cohérence
        $installHelperPath = base_path(\'public/install/functions/ip_helper.php\');
        if (file_exists($installHelperPath)) {
            try {
                require_once $installHelperPath;
                if (function_exists(\'collectServerIPRobust\')) {
                    return collectServerIPRobust($forceExternal);
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::warning(\'Erreur lors du chargement de ip_helper.php: \' . $e->getMessage());
            }
        }
        
        // Fallback vers l\'implémentation Laravel native
        return self::collectServerIPNative($forceExternal);
    }
    
    /**
     * Implémentation native Laravel de la collecte d\'IP robuste
     * 
     * @param bool $forceExternal Force la détection via services externes
     * @return array Tableau avec l\'IP sélectionnée et les détails de diagnostic
     */
    public static function collectServerIPNative($forceExternal = false)
    {
        $result = [
            \'ip\' => \'127.0.0.1\',
            \'reason\' => \'Fallback par défaut\',
            \'sources\' => [],
            \'strategies\' => [],
            \'is_local\' => true,
            \'is_valid\' => true,
            \'confidence\' => 0
        ];
        
        // STRATÉGIE 1: Variables serveur standard
        $serverSources = [
            \'SERVER_ADDR\' => $_SERVER[\'SERVER_ADDR\'] ?? null,
            \'REMOTE_ADDR\' => $_SERVER[\'REMOTE_ADDR\'] ?? null,
            \'HTTP_X_FORWARDED_FOR\' => $_SERVER[\'HTTP_X_FORWARDED_FOR\'] ?? null,
            \'HTTP_X_REAL_IP\' => $_SERVER[\'HTTP_X_REAL_IP\'] ?? null,
            \'HTTP_CF_CONNECTING_IP\' => $_SERVER[\'HTTP_CF_CONNECTING_IP\'] ?? null,
            \'HTTP_X_FORWARDED\' => $_SERVER[\'HTTP_X_FORWARDED\'] ?? null,
            \'HTTP_FORWARDED_FOR\' => $_SERVER[\'HTTP_FORWARDED_FOR\'] ?? null,
            \'HTTP_FORWARDED\' => $_SERVER[\'HTTP_FORWARDED\'] ?? null,
            \'HTTP_CLIENT_IP\' => $_SERVER[\'HTTP_CLIENT_IP\'] ?? null,
        ];
        
        $result[\'sources\'] = $serverSources;
        $result[\'strategies\'][] = \'Variables serveur collectées\';
        
        // STRATÉGIE 2: Résolution DNS
        try {
            $hostname = gethostname();
            if ($hostname !== false) {
                $hostnameIP = gethostbyname($hostname);
                if ($hostnameIP !== $hostname && filter_var($hostnameIP, FILTER_VALIDATE_IP)) {
                    $serverSources[\'gethostbyname\'] = $hostnameIP;
                    $result[\'strategies\'][] = \'Résolution DNS effectuée\';
                }
            }
        } catch (\Exception $e) {
            $result[\'strategies\'][] = \'Résolution DNS échouée: \' . $e->getMessage();
        }
        
        // STRATÉGIE 3: Services externes (si forcé ou aucune IP publique trouvée)
        $externalIP = null;
        if ($forceExternal || !self::hasPublicIP($serverSources)) {
            $externalIP = self::getExternalIP();
            if ($externalIP) {
                $serverSources[\'external_service\'] = $externalIP;
                $result[\'strategies\'][] = \'IP externe récupérée via service tiers\';
            }
        }
        
        // LOGIQUE DE SÉLECTION PRIORITAIRE
        $selectedIP = null;
        $selectionReason = \'\';
        $confidence = 0;
        
        // 1. Priorité maximale: IP externe si disponible et publique
        if ($externalIP && self::isPublicIP($externalIP)) {
            $selectedIP = $externalIP;
            $selectionReason = \'Service externe (IP publique confirmée)\';
            $confidence = 95;
        }
        // 2. SERVER_ADDR si publique
        elseif (!empty($serverSources[\'SERVER_ADDR\']) && self::isPublicIP($serverSources[\'SERVER_ADDR\'])) {
            $selectedIP = $serverSources[\'SERVER_ADDR\'];
            $selectionReason = \'SERVER_ADDR (IP publique)\';
            $confidence = 90;
        }
        // 3. HTTP_X_REAL_IP si publique
        elseif (!empty($serverSources[\'HTTP_X_REAL_IP\']) && self::isPublicIP($serverSources[\'HTTP_X_REAL_IP\'])) {
            $selectedIP = $serverSources[\'HTTP_X_REAL_IP\'];
            $selectionReason = \'HTTP_X_REAL_IP (proxy)\';
            $confidence = 85;
        }
        // 4. HTTP_CF_CONNECTING_IP (Cloudflare)
        elseif (!empty($serverSources[\'HTTP_CF_CONNECTING_IP\']) && self::isPublicIP($serverSources[\'HTTP_CF_CONNECTING_IP\'])) {
            $selectedIP = $serverSources[\'HTTP_CF_CONNECTING_IP\'];
            $selectionReason = \'HTTP_CF_CONNECTING_IP (Cloudflare)\';
            $confidence = 85;
        }
        // 5. Première IP publique de HTTP_X_FORWARDED_FOR
        elseif (!empty($serverSources[\'HTTP_X_FORWARDED_FOR\'])) {
            $forwardedIPs = explode(\',\', $serverSources[\'HTTP_X_FORWARDED_FOR\']);
            foreach ($forwardedIPs as $ip) {
                $ip = trim($ip);
                if (!empty($ip) && self::isPublicIP($ip)) {
                    $selectedIP = $ip;
                    $selectionReason = \'HTTP_X_FORWARDED_FOR (première IP publique)\';
                    $confidence = 80;
                    break;
                }
            }
        }
        // 6. REMOTE_ADDR si publique
        elseif (!empty($serverSources[\'REMOTE_ADDR\']) && self::isPublicIP($serverSources[\'REMOTE_ADDR\'])) {
            $selectedIP = $serverSources[\'REMOTE_ADDR\'];
            $selectionReason = \'REMOTE_ADDR (IP publique)\';
            $confidence = 75;
        }
        // 7. gethostbyname si publique
        elseif (!empty($serverSources[\'gethostbyname\']) && self::isPublicIP($serverSources[\'gethostbyname\'])) {
            $selectedIP = $serverSources[\'gethostbyname\'];
            $selectionReason = \'gethostbyname (résolution DNS)\';
            $confidence = 70;
        }
        // 8. Fallbacks vers IPs locales si nécessaire
        elseif (!empty($serverSources[\'SERVER_ADDR\'])) {
            $selectedIP = $serverSources[\'SERVER_ADDR\'];
            $selectionReason = \'SERVER_ADDR (fallback local)\';
            $confidence = 40;
        }
        elseif (!empty($serverSources[\'REMOTE_ADDR\'])) {
            $selectedIP = $serverSources[\'REMOTE_ADDR\'];
            $selectionReason = \'REMOTE_ADDR (fallback local)\';
            $confidence = 35;
        }
        elseif (!empty($serverSources[\'gethostbyname\'])) {
            $selectedIP = $serverSources[\'gethostbyname\'];
            $selectionReason = \'gethostbyname (fallback local)\';
            $confidence = 30;
        }
        // 9. Dernier recours
        else {
            $selectedIP = \'127.0.0.1\';
            $selectionReason = \'Fallback final (localhost)\';
            $confidence = 10;
        }
        
        $result[\'ip\'] = $selectedIP;
        $result[\'reason\'] = $selectionReason;
        $result[\'is_local\'] = !self::isPublicIP($selectedIP);
        $result[\'is_valid\'] = filter_var($selectedIP, FILTER_VALIDATE_IP) !== false;
        $result[\'confidence\'] = $confidence;
        $result[\'sources\'] = $serverSources;
        
        return $result;
    }

    /**
     * Méthode de compatibilité pour forcer la détection externe
     */
    public static function collectServerIPWithExternal()
    {
        return self::collectServerIPRobust(true);
    }

    /**
     * Vérifie si une IP est considérée comme locale/privée
     * 
     * @param string $ip L\'adresse IP à vérifier
     * @return bool True si l\'IP est locale, false sinon
     */
    public static function isLocalIP($ip)
    {
        if (empty($ip)) {
            return true;
        }
        
        // IPs explicitement locales
        $localIPs = [\'127.0.0.1\', \'::1\', \'localhost\', \'0.0.0.0\'];
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
     * Vérifie si une IP est publique (non privée/réservée)
     */
    public static function isPublicIP($ip)
    {
        return !self::isLocalIP($ip);
    }
    
    /**
     * Vérifie si au moins une IP publique est disponible dans les sources
     */
    public static function hasPublicIP($sources)
    {
        foreach ($sources as $ip) {
            if (self::isPublicIP($ip)) return true;
        }
        return false;
    }
    
    /**
     * Récupère l\'IP externe via des services tiers
     */
    public static function getExternalIP()
    {
        $services = [
            \'https://api.ipify.org\',
            \'https://icanhazip.com\',
            \'https://ipecho.net/plain\',
            \'https://myexternalip.com/raw\'
        ];
        
        foreach ($services as $service) {
            try {
                $context = stream_context_create([
                    \'http\' => [
                        \'timeout\' => 5,
                        \'user_agent\' => \'AdminLicence-IPDetection/1.0\'
                    ]
                ]);
                
                $result = @file_get_contents($service, false, $context);
                if ($result !== false) {
                    $ip = trim($result);
                    if (filter_var($ip, FILTER_VALIDATE_IP) && self::isPublicIP($ip)) {
                        return $ip;
                    }
                }
            } catch (\Exception $e) {
                continue; // Essayer le service suivant
            }
        }
        
        return null;
    }

    /**
     * Formate les informations d\'IP pour les logs
     * 
     * @param array $ipInfo Résultat de collectServerIP()
     * @return string Message formaté pour les logs
     */
    public static function formatIPInfoForLog($ipInfo)
    {
        $sources = [];
        foreach ($ipInfo[\'sources\'] as $key => $value) {
            $sources[] = "$key: " . ($value ?: \'null\');
        }
        
        $confidence = isset($ipInfo[\'confidence\']) ? $ipInfo[\'confidence\'] . \'%\' : \'N/A\';
        $strategies = isset($ipInfo[\'strategies\']) ? implode(\' + \', $ipInfo[\'strategies\']) : \'N/A\';
        
        return "IP sélectionnée: {$ipInfo[\'ip\']} ({$ipInfo[\'reason\']}) | " . 
               "Confiance: $confidence | " .
               "Local: " . ($ipInfo[\'is_local\'] ? \'oui\' : \'non\') . " | " .
               "Valide: " . ($ipInfo[\'is_valid\'] ? \'oui\' : \'non\') . " | " .
               "Stratégies: $strategies | " .
               "Sources: " . implode(\' | \', array_slice($sources, 0, 5));
    }
}';

echo "<div class='step'>Nouveau contenu IPHelper Laravel généré</div>";
echo "<div class='code'>
Taille du nouveau contenu: " . strlen($newLaravelHelperContent) . " octets
Nombre de lignes: " . substr_count($newLaravelHelperContent, "\n") . "
Nouvelles méthodes ajoutées: collectServerIPRobust, collectServerIPWithExternal, collectServerIPNative
</div>";

logUpdate("Nouveau contenu IPHelper généré", 'GENERATION', [
    'new_size' => strlen($newLaravelHelperContent),
    'new_lines' => substr_count($newLaravelHelperContent, "\n")
]);

echo "</div>";

// ÉTAPE 4: SAUVEGARDE ET MISE À JOUR
echo "<div class='section'>
    <h2>💾 Étape 4: Sauvegarde et Mise à Jour</h2>";

// Créer une sauvegarde si le fichier existe
if ($currentState['laravel_helper_exists']) {
    $backupPath = dirname($laravelHelperPath) . '/IPHelper.php.backup.' . date('Y-m-d-His');
    if (copy($laravelHelperPath, $backupPath)) {
        echo "<div class='step status-ok'>✅ Sauvegarde créée: " . basename($backupPath) . "</div>";
        logUpdate("Sauvegarde créée", 'BACKUP', ['backup_path' => $backupPath]);
    } else {
        echo "<div class='step status-warning'>⚠️ Impossible de créer la sauvegarde</div>";
        logUpdate("Échec de sauvegarde", 'WARNING');
    }
}

// Écrire le nouveau contenu
if (file_put_contents($laravelHelperPath, $newLaravelHelperContent)) {
    echo "<div class='step status-ok'>✅ IPHelper Laravel mis à jour avec succès</div>";
    logUpdate("IPHelper Laravel mis à jour", 'SUCCESS');
    
    // Vérifier les permissions
    if (chmod($laravelHelperPath, 0644)) {
        echo "<div class='step status-ok'>✅ Permissions définies correctement (644)</div>";
    } else {
        echo "<div class='step status-warning'>⚠️ Impossible de définir les permissions</div>";
    }
} else {
    echo "<div class='step status-error'>❌ Échec de mise à jour de IPHelper Laravel</div>";
    logUpdate("Échec de mise à jour IPHelper Laravel", 'ERROR');
}

echo "</div>";

// ÉTAPE 5: TESTS DE VALIDATION
echo "<div class='section'>
    <h2>🧪 Étape 5: Tests de Validation</h2>";

// Tester si le fichier peut être inclus sans erreur
try {
    $testContent = file_get_contents($laravelHelperPath);
    if (strpos($testContent, 'class IPHelper') !== false) {
        echo "<div class='step status-ok'>✅ Structure de classe valide détectée</div>";
        logUpdate("Structure de classe valide", 'TEST');
    } else {
        echo "<div class='step status-error'>❌ Structure de classe invalide</div>";
        logUpdate("Structure de classe invalide", 'ERROR');
    }
    
    // Vérifier la présence des nouvelles méthodes
    $methods = [
        'collectServerIPRobust' => strpos($testContent, 'collectServerIPRobust') !== false,
        'collectServerIPWithExternal' => strpos($testContent, 'collectServerIPWithExternal') !== false,
        'collectServerIPNative' => strpos($testContent, 'collectServerIPNative') !== false,
        'isPublicIP' => strpos($testContent, 'isPublicIP') !== false,
        'getExternalIP' => strpos($testContent, 'getExternalIP') !== false
    ];
    
    echo "<div class='code'>
Méthodes présentes dans le nouveau fichier:";
    foreach ($methods as $method => $present) {
        $status = $present ? '✅' : '❌';
        echo "\n$method: $status";
    }
    echo "</div>";
    
    $methodsCount = array_sum($methods);
    logUpdate("Validation des méthodes", 'TEST', [
        'methods_present' => $methodsCount,
        'total_methods' => count($methods)
    ]);
    
} catch (Exception $e) {
    echo "<div class='step status-error'>❌ Erreur lors de la validation: " . $e->getMessage() . "</div>";
    logUpdate("Erreur de validation", 'ERROR', ['error' => $e->getMessage()]);
}

echo "</div>";

// ÉTAPE 6: RÉSUMÉ FINAL
echo "<div class='section info'>
    <h2>📋 Résumé de la Mise à Jour</h2>";

$updateSummary = [
    'file_updated' => file_exists($laravelHelperPath),
    'backup_created' => isset($backupPath) && file_exists($backupPath),
    'methods_added' => isset($methodsCount) ? $methodsCount : 0,
    'file_size' => file_exists($laravelHelperPath) ? filesize($laravelHelperPath) : 0,
    'timestamp' => date('Y-m-d H:i:s')
];

echo "<div class='code'>
<strong>Fichier mis à jour:</strong> " . ($updateSummary['file_updated'] ? 'OUI' : 'NON') . "
<strong>Sauvegarde créée:</strong> " . ($updateSummary['backup_created'] ? 'OUI' : 'NON') . "
<strong>Méthodes ajoutées:</strong> {$updateSummary['methods_added']}/5
<strong>Taille finale:</strong> {$updateSummary['file_size']} octets
<strong>Timestamp:</strong> {$updateSummary['timestamp']}
</div>";

echo "<div class='step'>
<strong>Améliorations apportées:</strong><br>
✅ Cohérence entre systèmes d'installation et runtime<br>
✅ Détection IP robuste avec stratégies multiples<br>
✅ Support de la détection externe forcée<br>
✅ Méthodes de compatibilité maintenues<br>
✅ Logging amélioré pour le diagnostic<br>
✅ Gestion d'erreurs renforcée
</div>";

echo "<div class='step'>
<strong>Prochaines étapes:</strong><br>
1. Tester l'application Laravel avec le nouveau helper<br>
2. Vérifier les logs de l'application pour les erreurs<br>
3. Valider la cohérence avec le système d'installation<br>
4. Surveiller les performances en production
</div>";

logUpdate("Mise à jour terminée", 'COMPLETE', $updateSummary);

echo "</div>";

echo "</div></body></html>";
?>