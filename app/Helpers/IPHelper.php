<?php

namespace App\Helpers;

/**
 * Classe d'aide pour la collecte d'IP du serveur
 * Version améliorée avec cohérence entre installation et runtime
 */
class IPHelper
{
    /**
     * Collecte l'adresse IP réelle du serveur de manière robuste
     * 
     * @return array Tableau avec l'IP sélectionnée et les détails de diagnostic
     */
    public static function collectServerIP()
    {
        // Utiliser la version robuste par défaut
        return self::collectServerIPRobust(false);
    }

    /**
     * Collecte l'adresse IP réelle du serveur avec stratégies multiples (version robuste)
     * 
     * @param bool $forceExternal Force la détection via services externes
     * @return array Tableau avec l'IP sélectionnée et les détails de diagnostic
     */
    public static function collectServerIPRobust($forceExternal = false)
    {
        // Essayer d'utiliser la fonction d'installation si disponible pour la cohérence
        $installHelperPath = base_path('public/install/functions/ip_helper.php');
        if (file_exists($installHelperPath)) {
            try {
                require_once $installHelperPath;
                if (function_exists('collectServerIPRobust')) {
                    return collectServerIPRobust($forceExternal);
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::warning('Erreur lors du chargement de ip_helper.php: ' . $e->getMessage());
            }
        }
        
        // Fallback vers l'implémentation Laravel native
        return self::collectServerIPNative($forceExternal);
    }
    
    /**
     * Implémentation native Laravel de la collecte d'IP robuste
     * 
     * @param bool $forceExternal Force la détection via services externes
     * @return array Tableau avec l'IP sélectionnée et les détails de diagnostic
     */
    public static function collectServerIPNative($forceExternal = false)
    {
        $result = [
            'ip' => '127.0.0.1',
            'reason' => 'Fallback par défaut',
            'sources' => [],
            'strategies' => [],
            'is_local' => true,
            'is_valid' => true,
            'confidence' => 0
        ];
        
        // STRATÉGIE 1: Variables serveur standard
        $serverSources = [
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
        
        $result['sources'] = $serverSources;
        $result['strategies'][] = 'Variables serveur collectées';
        
        // STRATÉGIE 2: Résolution DNS
        try {
            $hostname = gethostname();
            if ($hostname !== false) {
                $hostnameIP = gethostbyname($hostname);
                if ($hostnameIP !== $hostname && filter_var($hostnameIP, FILTER_VALIDATE_IP)) {
                    $serverSources['gethostbyname'] = $hostnameIP;
                    $result['strategies'][] = 'Résolution DNS effectuée';
                }
            }
        } catch (\Exception $e) {
            $result['strategies'][] = 'Résolution DNS échouée: ' . $e->getMessage();
        }
        
        // STRATÉGIE 3: Services externes (si forcé ou aucune IP publique trouvée)
        $externalIP = null;
        if ($forceExternal || !self::hasPublicIP($serverSources)) {
            $externalIP = self::getExternalIP();
            if ($externalIP) {
                $serverSources['external_service'] = $externalIP;
                $result['strategies'][] = 'IP externe récupérée via service tiers';
            }
        }
        
        // LOGIQUE DE SÉLECTION PRIORITAIRE
        $selectedIP = null;
        $selectionReason = '';
        $confidence = 0;
        
        // 1. Priorité maximale: IP externe si disponible et publique
        if ($externalIP && self::isPublicIP($externalIP)) {
            $selectedIP = $externalIP;
            $selectionReason = 'Service externe (IP publique confirmée)';
            $confidence = 95;
        }
        // 2. SERVER_ADDR si publique
        elseif (!empty($serverSources['SERVER_ADDR']) && self::isPublicIP($serverSources['SERVER_ADDR'])) {
            $selectedIP = $serverSources['SERVER_ADDR'];
            $selectionReason = 'SERVER_ADDR (IP publique)';
            $confidence = 90;
        }
        // 3. HTTP_X_REAL_IP si publique
        elseif (!empty($serverSources['HTTP_X_REAL_IP']) && self::isPublicIP($serverSources['HTTP_X_REAL_IP'])) {
            $selectedIP = $serverSources['HTTP_X_REAL_IP'];
            $selectionReason = 'HTTP_X_REAL_IP (proxy)';
            $confidence = 85;
        }
        // 4. HTTP_CF_CONNECTING_IP (Cloudflare)
        elseif (!empty($serverSources['HTTP_CF_CONNECTING_IP']) && self::isPublicIP($serverSources['HTTP_CF_CONNECTING_IP'])) {
            $selectedIP = $serverSources['HTTP_CF_CONNECTING_IP'];
            $selectionReason = 'HTTP_CF_CONNECTING_IP (Cloudflare)';
            $confidence = 85;
        }
        // 5. Première IP publique de HTTP_X_FORWARDED_FOR
        elseif (!empty($serverSources['HTTP_X_FORWARDED_FOR'])) {
            $forwardedIPs = explode(',', $serverSources['HTTP_X_FORWARDED_FOR']);
            foreach ($forwardedIPs as $ip) {
                $ip = trim($ip);
                if (!empty($ip) && self::isPublicIP($ip)) {
                    $selectedIP = $ip;
                    $selectionReason = 'HTTP_X_FORWARDED_FOR (première IP publique)';
                    $confidence = 80;
                    break;
                }
            }
        }
        // 6. REMOTE_ADDR si publique
        elseif (!empty($serverSources['REMOTE_ADDR']) && self::isPublicIP($serverSources['REMOTE_ADDR'])) {
            $selectedIP = $serverSources['REMOTE_ADDR'];
            $selectionReason = 'REMOTE_ADDR (IP publique)';
            $confidence = 75;
        }
        // 7. gethostbyname si publique
        elseif (!empty($serverSources['gethostbyname']) && self::isPublicIP($serverSources['gethostbyname'])) {
            $selectedIP = $serverSources['gethostbyname'];
            $selectionReason = 'gethostbyname (résolution DNS)';
            $confidence = 70;
        }
        // 8. Fallbacks vers IPs locales si nécessaire
        elseif (!empty($serverSources['SERVER_ADDR'])) {
            $selectedIP = $serverSources['SERVER_ADDR'];
            $selectionReason = 'SERVER_ADDR (fallback local)';
            $confidence = 40;
        }
        elseif (!empty($serverSources['REMOTE_ADDR'])) {
            $selectedIP = $serverSources['REMOTE_ADDR'];
            $selectionReason = 'REMOTE_ADDR (fallback local)';
            $confidence = 35;
        }
        elseif (!empty($serverSources['gethostbyname'])) {
            $selectedIP = $serverSources['gethostbyname'];
            $selectionReason = 'gethostbyname (fallback local)';
            $confidence = 30;
        }
        // 9. Dernier recours
        else {
            $selectedIP = '127.0.0.1';
            $selectionReason = 'Fallback final (localhost)';
            $confidence = 10;
        }
        
        $result['ip'] = $selectedIP;
        $result['reason'] = $selectionReason;
        $result['is_local'] = !self::isPublicIP($selectedIP);
        $result['is_valid'] = filter_var($selectedIP, FILTER_VALIDATE_IP) !== false;
        $result['confidence'] = $confidence;
        $result['sources'] = $serverSources;
        
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
     * @param string $ip L'adresse IP à vérifier
     * @return bool True si l'IP est locale, false sinon
     */
    public static function isLocalIP($ip)
    {
        if (empty($ip)) {
            return true;
        }
        
        // IPs explicitement locales
        $localIPs = ['127.0.0.1', '::1', 'localhost', '0.0.0.0'];
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
     * Récupère l'IP externe via des services tiers
     */
    public static function getExternalIP()
    {
        $services = [
            'https://api.ipify.org',
            'https://icanhazip.com',
            'https://ipecho.net/plain',
            'https://myexternalip.com/raw'
        ];
        
        foreach ($services as $service) {
            try {
                $context = stream_context_create([
                    'http' => [
                        'timeout' => 5,
                        'user_agent' => 'AdminLicence-IPDetection/1.0'
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
     * Formate les informations d'IP pour les logs
     * 
     * @param array $ipInfo Résultat de collectServerIP()
     * @return string Message formaté pour les logs
     */
    public static function formatIPInfoForLog($ipInfo)
    {
        $sources = [];
        foreach ($ipInfo['sources'] as $key => $value) {
            $sources[] = "$key: " . ($value ?: 'null');
        }
        
        $confidence = isset($ipInfo['confidence']) ? $ipInfo['confidence'] . '%' : 'N/A';
        $strategies = isset($ipInfo['strategies']) ? implode(' + ', $ipInfo['strategies']) : 'N/A';
        
        return "IP sélectionnée: {$ipInfo['ip']} ({$ipInfo['reason']}) | " . 
               "Confiance: $confidence | " .
               "Local: " . ($ipInfo['is_local'] ? 'oui' : 'non') . " | " .
               "Valide: " . ($ipInfo['is_valid'] ? 'oui' : 'non') . " | " .
               "Stratégies: $strategies | " .
               "Sources: " . implode(' | ', array_slice($sources, 0, 5));
    }
}