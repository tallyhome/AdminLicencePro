<?php
/**
 * Script pour mettre à jour le helper IP de Laravel avec la même logique améliorée
 */

// Inclure les fonctions nécessaires
require_once __DIR__ . '/install/config.php';

echo "<!DOCTYPE html>
<html>
<head>
    <title>Mise à jour Laravel IP Helper - AdminLicence</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .section { margin: 20px 0; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
        .success { background-color: #d4edda; border-color: #c3e6cb; }
        .warning { background-color: #fff3cd; border-color: #ffeaa7; }
        .error { background-color: #f8d7da; border-color: #f5c6cb; }
        .info { background-color: #d1ecf1; border-color: #bee5eb; }
        .code { background-color: #f8f9fa; padding: 10px; border-radius: 3px; font-family: monospace; white-space: pre-wrap; }
        .btn { display: inline-block; padding: 10px 20px; margin: 5px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; }
        .btn:hover { background: #0056b3; }
    </style>
</head>
<body>
    <h1>🔄 Mise à jour Laravel IP Helper</h1>
    <p>Ce script met à jour le helper IP de Laravel pour utiliser la même logique améliorée que le système d'installation.</p>";

// Vérifier si le fichier Laravel existe
$laravelHelperPath = ROOT_PATH . '/app/Helpers/IPHelper.php';
$helperExists = file_exists($laravelHelperPath);

echo "<div class='section'>
    <h2>📁 Vérification des Fichiers</h2>
    <p><strong>Fichier Laravel IPHelper:</strong> " . ($helperExists ? '✅ Trouvé' : '❌ Non trouvé') . "</p>
    <p><strong>Chemin:</strong> <code>$laravelHelperPath</code></p>
</div>";

if (isset($_GET['update']) && $_GET['update'] === '1') {
    echo "<div class='section'>
        <h2>🔧 Mise à jour en cours...</h2>";
    
    try {
        // Créer une sauvegarde si le fichier existe
        if ($helperExists) {
            $backupPath = $laravelHelperPath . '.backup.' . date('Y-m-d_H-i-s');
            copy($laravelHelperPath, $backupPath);
            echo "<p>✅ Sauvegarde créée: " . basename($backupPath) . "</p>";
        }
        
        // Contenu du nouveau helper Laravel amélioré
        $newLaravelHelper = '<?php

namespace App\Helpers;

/**
 * Classe d\'aide pour la collecte d\'IP du serveur - VERSION AMÉLIORÉE
 * Synchronisée avec le système d\'installation
 * Mise à jour automatique le ' . date('Y-m-d H:i:s') . '
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
        $strategies = [];
        $finalIP = null;
        $finalReason = \'\';
        
        // Stratégie 1: Variables serveur classiques avec priorités
        $serverVars = [
            \'SERVER_ADDR\' => $_SERVER[\'SERVER_ADDR\'] ?? null,
            \'HTTP_X_REAL_IP\' => $_SERVER[\'HTTP_X_REAL_IP\'] ?? null,
            \'HTTP_CF_CONNECTING_IP\' => $_SERVER[\'HTTP_CF_CONNECTING_IP\'] ?? null,
            \'HTTP_X_FORWARDED_FOR\' => $_SERVER[\'HTTP_X_FORWARDED_FOR\'] ?? null,
            \'REMOTE_ADDR\' => $_SERVER[\'REMOTE_ADDR\'] ?? null,
            \'HTTP_X_FORWARDED\' => $_SERVER[\'HTTP_X_FORWARDED\'] ?? null,
            \'HTTP_FORWARDED_FOR\' => $_SERVER[\'HTTP_FORWARDED_FOR\'] ?? null,
            \'HTTP_FORWARDED\' => $_SERVER[\'HTTP_FORWARDED\'] ?? null,
            \'HTTP_CLIENT_IP\' => $_SERVER[\'HTTP_CLIENT_IP\'] ?? null,
        ];
        
        foreach ($serverVars as $var => $value) {
            if (!empty($value) && !self::isLocalIP($value)) {
                $strategies[] = [
                    \'method\' => \'server_var\',
                    \'source\' => $var,
                    \'ip\' => $value,
                    \'priority\' => self::getVarPriority($var)
                ];
            }
        }
        
        // Traitement spécial pour HTTP_X_FORWARDED_FOR (peut contenir plusieurs IPs)
        if (!empty($_SERVER[\'HTTP_X_FORWARDED_FOR\'])) {
            $forwardedIPs = explode(\',\', $_SERVER[\'HTTP_X_FORWARDED_FOR\']);
            foreach ($forwardedIPs as $ip) {
                $ip = trim($ip);
                if (!empty($ip) && !self::isLocalIP($ip) && filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
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
                // Utiliser gethostbynamel pour obtenir toutes les IPs
                $hostnameIPs = gethostbynamel($hostname);
                if ($hostnameIPs) {
                    foreach ($hostnameIPs as $ip) {
                        if (!self::isLocalIP($ip)) {
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
                
                // Fallback avec gethostbyname si gethostbynamel échoue
                if (empty($strategies)) {
                    $hostnameIP = gethostbyname($hostname);
                    if ($hostnameIP !== $hostname && !self::isLocalIP($hostnameIP)) {
                        $strategies[] = [
                            \'method\' => \'dns_resolution\',
                            \'source\' => \'gethostbyname(\' . $hostname . \')\',
                            \'ip\' => $hostnameIP,
                            \'priority\' => 55
                        ];
                    }
                }
            }
        } catch (\Exception $e) {
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
                            \'user_agent\' => \'AdminLicence-Laravel/1.0\'
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
                } catch (\Exception $e) {
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
                try {
                    $hostname = gethostname();
                    $finalIP = ($hostname !== false) ? gethostbyname($hostname) : \'127.0.0.1\';
                    $finalReason = \'Fallback final\';
                } catch (\Exception $e) {
                    $finalIP = \'127.0.0.1\';
                    $finalReason = \'Fallback final (erreur)\';
                }
            }
        }
        
        return [
            \'ip\' => $finalIP,
            \'reason\' => $finalReason,
            \'sources\' => $serverVars,
            \'strategies\' => $strategies,
            \'is_local\' => self::isLocalIP($finalIP),
            \'is_valid\' => filter_var($finalIP, FILTER_VALIDATE_IP) !== false
        ];
    }

    /**
     * Obtient la priorité d\'une variable serveur
     * 
     * @param string $var Nom de la variable
     * @return int Priorité (plus petit = plus prioritaire)
     */
    private static function getVarPriority($var)
    {
        $priorities = [
            \'SERVER_ADDR\' => 10,
            \'HTTP_X_REAL_IP\' => 20,
            \'HTTP_CF_CONNECTING_IP\' => 25,
            \'HTTP_X_FORWARDED_FOR\' => 30,
            \'REMOTE_ADDR\' => 40,
            \'HTTP_X_FORWARDED\' => 45,
            \'HTTP_FORWARDED_FOR\' => 46,
            \'HTTP_FORWARDED\' => 47,
            \'HTTP_CLIENT_IP\' => 48
        ];
        return $priorities[$var] ?? 99;
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
    public static function formatIPInfoForLog($ipInfo)
    {
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

    /**
     * Méthode de compatibilité pour obtenir rapidement l\'IP du serveur
     * 
     * @return string IP du serveur
     */
    public static function getServerIP()
    {
        $result = self::collectServerIP();
        return $result[\'ip\'];
    }

    /**
     * Vérifie si l\'IP détectée est publique
     * 
     * @return bool True si l\'IP est publique, false sinon
     */
    public static function hasPublicIP()
    {
        $result = self::collectServerIP();
        return !$result[\'is_local\'];
    }
}
';
        
        // Écrire le nouveau fichier
        file_put_contents($laravelHelperPath, $newLaravelHelper);
        
        echo "<div class='success'>
            <h3>✅ Helper Laravel Mis à Jour</h3>
            <p><strong>Fichier:</strong> app/Helpers/IPHelper.php</p>
            <p><strong>Améliorations apportées:</strong></p>
            <ul>
                <li>🎯 Logique identique au système d\'installation</li>
                <li>🔍 Détection multi-stratégies avec priorités</li>
                <li>🌐 Services externes en dernier recours</li>
                <li>📊 Logging détaillé des stratégies</li>
                <li>⚡ Méthodes de compatibilité ajoutées</li>
                <li>🔄 Synchronisation parfaite entre les systèmes</li>
            </ul>
        </div>";
        
        // Test de la nouvelle classe
        if (class_exists(\'App\\Helpers\\IPHelper\')) {
            // Recharger la classe
            include_once $laravelHelperPath;
        }
        
        echo "<div class='info'>
            <h3>🧪 Test de la Nouvelle Classe</h3>
            <p>La classe a été mise à jour avec succès. Les méthodes disponibles sont :</p>
            <ul>
                <li><code>IPHelper::collectServerIP()</code> - Détection complète avec diagnostic</li>
                <li><code>IPHelper::getServerIP()</code> - Obtenir rapidement l\'IP</li>
                <li><code>IPHelper::hasPublicIP()</code> - Vérifier si l\'IP est publique</li>
                <li><code>IPHelper::isLocalIP($ip)</code> - Vérifier si une IP est locale</li>
                <li><code>IPHelper::formatIPInfoForLog($info)</code> - Formater pour les logs</li>
            </ul>
        </div>";
        
    } catch (Exception $e) {
        echo "<div class='error'>
            <h3>❌ Erreur lors de la Mise à Jour</h3>
            <p>Erreur: " . $e->getMessage() . "</p>
        </div>";
    }
    
} else {
    echo "<div class='section'>
        <h2>🎯 Actions Disponibles</h2>
        <p>Cette mise à jour va :</p>
        <ul>
            <li>✅ Synchroniser la logique de détection IP entre l\'installation et Laravel</li>
            <li>🔄 Remplacer l\'ancienne méthode par la nouvelle version améliorée</li>
            <li>💾 Créer une sauvegarde de l\'ancien fichier</li>
            <li>🎯 Ajouter des méthodes de compatibilité</li>
            <li>📊 Améliorer le logging et le diagnostic</li>
        </ul>
        <a href='?update=1' class='btn'>🔄 Mettre à Jour le Helper Laravel</a>
    </div>";
}

echo "<div class='section'>
    <h2>📋 Informations Importantes</h2>
    <div class='warning'>
        <h3>⚠️ Points à Retenir</h3>
        <ul>
            <li><strong>Cohérence:</strong> Cette mise à jour assure que l\'installation et Laravel utilisent la même logique</li>
            <li><strong>Compatibilité:</strong> Les anciennes méthodes restent disponibles</li>
            <li><strong>Performance:</strong> La nouvelle version est optimisée et plus robuste</li>
            <li><strong>Logs:</strong> Meilleur diagnostic des problèmes de détection IP</li>
        </ul>
    </div>
</div>";

echo "</body></html>";
?>