<?php
/**
 * Fonctions essentielles pour l'installateur
 */

/**
 * Crée un fichier de log pour suivre les erreurs
 * 
 * @param string $message Message à logger
 * @param string $type Type de message (INFO, WARNING, ERROR, FATAL)
 * @return void
 */
function writeToLog($message, $type = 'INFO') {
    $logFile = INSTALL_PATH . '/install_log.txt';
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[$timestamp] [$type] $message" . PHP_EOL;
    file_put_contents($logFile, $logMessage, FILE_APPEND);
}

/**
 * Vérifie si Laravel est déjà installé
 * 
 * @return bool True si Laravel est déjà installé, false sinon
 */
function isLaravelInstalled() {
    // Si le paramètre force est présent, considérer que l'application n'est pas installée
    if (isset($_GET['force'])) {
        return false;
    }
    
    try {
        // Vérifier si le fichier .env existe et est lisible
        if (!file_exists(ROOT_PATH . '/.env') || !is_readable(ROOT_PATH . '/.env')) {
            return false;
        }

        // Lire le contenu du fichier .env
        $envContent = file_get_contents(ROOT_PATH . '/.env');
        if ($envContent === false) {
            return false;
        }

        // Vérifier si l'application est marquée comme installée
        if (strpos($envContent, 'APP_INSTALLED=true') === false) {
            return false;
        }

        // Vérifier si la clé d'application est définie
        if (strpos($envContent, 'APP_KEY=') === false) {
            return false;
        }

        // Vérifier la connexion à la base de données et les tables requises
        try {
            // Extraire les informations de connexion du fichier .env
            preg_match('/DB_HOST=(.*)/', $envContent, $hostMatch);
            preg_match('/DB_PORT=(.*)/', $envContent, $portMatch);
            preg_match('/DB_DATABASE=(.*)/', $envContent, $databaseMatch);
            preg_match('/DB_USERNAME=(.*)/', $envContent, $usernameMatch);
            preg_match('/DB_PASSWORD=(.*)/', $envContent, $passwordMatch);
            
            $host = trim($hostMatch[1] ?? 'localhost');
            $port = trim($portMatch[1] ?? '3306');
            $database = trim($databaseMatch[1] ?? '');
            $username = trim($usernameMatch[1] ?? '');
            $password = trim($passwordMatch[1] ?? '');
            
            if (empty($database)) {
                return false;
            }
            
            $dsn = "mysql:host={$host};port={$port};dbname={$database}";
            $pdo = new PDO($dsn, $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);
            
            // Vérifier que les tables essentielles existent
            $stmt = $pdo->query("SHOW TABLES");
            $existingTables = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            $requiredTables = ['users', 'admins', 'projects', 'serial_keys'];
            foreach ($requiredTables as $table) {
                if (!in_array($table, $existingTables)) {
                    return false;
                }
            }
        } catch (Exception $e) {
            writeToLog("Erreur de vérification de la base de données: " . $e->getMessage(), 'ERROR');
            return false;
        }
        
        return true;
        
    } catch (Exception $e) {
        writeToLog("Erreur lors de la vérification de l'installation: " . $e->getMessage(), 'ERROR');
        return false;
    }
}

/**
 * Génère une clé d'application sécurisée
 * 
 * @return string Clé d'application au format base64
 */
function generateAppKey() {
    return 'base64:' . base64_encode(random_bytes(32));
}

/**
 * Vérifie la validité d'une licence
 * 
 * @param string $cleSeriale Clé de licence à vérifier
 * @param string|null $domaine Domaine pour lequel vérifier la licence
 * @param string|null $adresseIP Adresse IP pour laquelle vérifier la licence
 * @return array Résultat de la vérification
 */
function verifierLicence($cleSeriale, $domaine = null, $adresseIP = null) {
    writeToLog("Début de la vérification de licence - Clé: " . $cleSeriale);
    
    if (empty($cleSeriale)) {
        writeToLog("Erreur: Clé de licence vide");
        return [
            'valide' => false,
            'message' => 'Clé de licence requise',
            'donnees' => null
        ];
    }

    // Valider le format de la clé de licence (exemple: XXXX-XXXX-XXXX-XXXX)
    if (!preg_match('/^[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}$/', strtoupper($cleSeriale))) {
        writeToLog("Erreur: Format de clé invalide");
        return [
            'valide' => false,
            'message' => 'Format de clé de licence invalide (XXXX-XXXX-XXXX-XXXX)',
            'donnees' => null
        ];
    }

    // VÉRIFICATION API RÉELLE - Utilisation du même endpoint que le système principal
    // URL de l'API de vérification (utilisation de l'API check-serial pour la production)
    $url = "https://licence.myvcard.fr/api/check-serial.php";
    
    // SOLUTION ROBUSTE: Utiliser la nouvelle fonction de collecte d'IP si pas fournie
    if (!$adresseIP) {
        $ipInfo = collectServerIP();
        $adresseIP = $ipInfo['ip'];
        
        if (function_exists('formatIPInfoForLog')) {
            writeToLog("COLLECTE IP CORE - " . formatIPInfoForLog($ipInfo), 'INFO');
        } else {
            writeToLog("COLLECTE IP CORE - IP: {$ipInfo['ip']} | Raison: {$ipInfo['reason']}", 'INFO');
        }
        
        if ($ipInfo['is_local']) {
            writeToLog("ATTENTION CORE: IP locale détectée ({$adresseIP}). Le serveur distant recevra cette IP.", 'WARNING');
        }
    }
    
    // Données à envoyer (même format que le LicenceService)
    $donnees = [
        'serial_key' => trim(strtoupper($cleSeriale)),
        'domain' => $domaine ?: (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost'),
        'ip_address' => $adresseIP,
        'validation_mode' => 'domain_only' // Indique au serveur de ne valider que le domaine mais d'enregistrer l'IP
    ];
    
    writeToLog("TRANSMISSION API - Données envoyées: " . json_encode($donnees), 'INFO');
     
    try {
        // Initialiser cURL
        $ch = curl_init($url);
        if ($ch === false) {
            throw new Exception('Erreur lors de l\'initialisation de la connexion');
        }
        
        // Configurer cURL (même configuration que le LicenceService)
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($donnees),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_SSL_VERIFYPEER => false, // Désactiver la vérification SSL
            CURLOPT_SSL_VERIFYHOST => false, // Désactiver la vérification du nom d'hôte
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 3,
            CURLOPT_USERAGENT => 'AdminLicence/4.5.1 Installation',
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Accept: application/json',
                'User-Agent: AdminLicence/4.5.1 Installation'
            ]
        ]);
        
        // Exécuter la requête
        $reponse = curl_exec($ch);
        $erreur = curl_error($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        curl_close($ch);
        
        writeToLog("Réponse de l'API - Code HTTP: " . $httpCode);
        writeToLog("Corps de la réponse: " . $reponse);
        
        if ($erreur) {
            throw new Exception("Erreur cURL: " . $erreur);
        }
        
        if (empty($reponse)) {
            throw new Exception("Réponse vide du serveur de licences");
        }
        
        // Décoder la réponse JSON
        $resultat = json_decode($reponse, true);
        if (json_last_error() !== JSON_ERROR_NONE || !is_array($resultat)) {
            throw new Exception("Erreur de décodage JSON: " . json_last_error_msg());
        }
        
        // Vérifier le statut de la réponse
        if (!isset($resultat['status'])) {
            throw new Exception("Réponse sans statut");
        }
        
        if ($resultat['status'] === 'error') {
            writeToLog("Erreur API: " . ($resultat['message'] ?? 'non spécifiée'));
            return [
                'valide' => false,
                'message' => $resultat['message'] ?? 'Clé de licence invalide',
                'donnees' => null
            ];
        }
        
        // Vérification du statut success et des données (même logique que le LicenceService)
        if ($resultat['status'] === 'success' && isset($resultat['data'])) {
            $data = $resultat['data'];
            
            // Vérifier si les données essentielles sont présentes (token est requis)
            if (isset($data['token'])) {
                $isValid = true;
                $message = 'Licence valide';
                
                // Vérifier si la licence n'est pas expirée (si expires_at est fourni)
                if (!empty($data['expires_at']) && $data['expires_at'] !== null) {
                    try {
                        $expirationDate = new DateTime($data['expires_at']);
                        $currentDate = new DateTime();
                        if ($currentDate > $expirationDate) {
                            $isValid = false;
                            $message = 'Licence expirée';
                            writeToLog("Licence expirée - Date d'expiration: " . $data['expires_at']);
                        }
                    } catch (Exception $e) {
                        // Si la date est invalide, on considère la licence comme valide
                        // mais on log l'erreur
                        writeToLog("Format de date d'expiration invalide: " . $data['expires_at']);
                    }
                }
                // Si expires_at est null ou vide, on considère que la licence n'expire pas
                
                if ($isValid) {
                    writeToLog("Licence valide - Token: " . substr($data['token'], 0, 10) . "...");
                    return [
                        'valide' => true,
                        'message' => $message,
                        'donnees' => $data
                    ];
                } else {
                    return [
                        'valide' => false,
                        'message' => $message,
                        'donnees' => null
                    ];
                }
            } else {
                writeToLog("Erreur: Token de licence manquant");
                return [
                    'valide' => false,
                    'message' => 'Clé de licence invalide',
                    'donnees' => null
                ];
            }
        } else {
            writeToLog("Erreur: Statut de réponse invalide ou données manquantes");
            return [
                'valide' => false,
                'message' => $resultat['message'] ?? 'Clé de licence invalide',
                'donnees' => null
            ];
        }
        
    } catch (Exception $e) {
        writeToLog("Erreur lors de la vérification de licence: " . $e->getMessage());
        return [
            'valide' => false,
            'message' => "Erreur de connexion au serveur de licence: " . $e->getMessage(),
            'donnees' => null
        ];
    }
}
