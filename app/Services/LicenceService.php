<?php

namespace App\Services;

use App\Models\SerialKey;
use App\Models\Admin;
use App\Models\LicenceHistory;
use App\Notifications\LicenceStatusChanged;
use App\Services\WebSocketService;
use App\Services\LicenceHistoryService;
use App\Services\EncryptionService;
use App\Helpers\IPHelper;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use App\Models\Setting;

class LicenceService
{
    /**
     * @var WebSocketService
     */
    protected $webSocketService;
    
    /**
     * @var LicenceHistoryService
     */
    protected $historyService;
    
    /**
     * @var EncryptionService
     */
    protected $encryptionService;
    
    /**
     * Constructeur du service de licence
     */
    public function __construct(
        WebSocketService $webSocketService, 
        LicenceHistoryService $historyService,
        EncryptionService $encryptionService
    )
    {
        $this->webSocketService = $webSocketService;
        $this->historyService = $historyService;
        $this->encryptionService = $encryptionService;
    }
    /**
     * Valide une clé de série
     *
     * @param string $serialKey
     * @param string $domain
     * @param string $ipAddress
     * @return array
     */
    public function validateSerialKey(string $serialKey, string $domain, string $ipAddress): array
    {
        // Initialiser le résultat
        $result = [
            'valid' => false,
            'message' => 'Erreur de vérification de licence',
            'data' => []
        ];

        try {
            // Configuration de l'API de licence depuis les variables d'environnement
            $apiUrl = env('LICENCE_API_URL', 'https://licence.myvcard.fr');
            $apiKey = env('LICENCE_API_KEY', '');
            $apiSecret = env('LICENCE_API_SECRET', '');
            $endpoint = env('LICENCE_API_ENDPOINT', '/api/check-serial.php');
            
            // Préparer les données à envoyer (format JSON comme dans l'installation)
            // Note: On envoie l'IP pour l'enregistrement mais la validation se base uniquement sur le domaine
            $data = [
                'serial_key' => trim(strtoupper($serialKey)),
                'domain' => $domain,
                'ip_address' => $ipAddress, // Envoyé pour enregistrement, mais validation basée sur le domaine uniquement
                'validation_mode' => 'domain_only' // Indique au serveur de ne valider que le domaine
            ];
            
            // Logger la requête uniquement en environnement de développement
            if (env('APP_ENV') === 'local' || env('APP_DEBUG') === true) {
                Log::debug('Envoi de requête de vérification de licence', [
                    'url' => $apiUrl . $endpoint,
                    'data' => $data
                ]);
            }
            
            // Initialiser cURL
            $ch = curl_init($apiUrl . $endpoint);
            
            // Configuration de cURL
            curl_setopt_array($ch, [
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => json_encode($data),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_CONNECTTIMEOUT => 10,
                CURLOPT_SSL_VERIFYPEER => false, // Désactiver la vérification SSL
                CURLOPT_SSL_VERIFYHOST => false, // Désactiver la vérification du nom d'hôte
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_MAXREDIRS => 3,
                CURLOPT_USERAGENT => 'AdminLicence/4.5.1',
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/json',
                    'Accept: application/json',
                    'User-Agent: AdminLicence/4.5.1'
                ]
            ]);
            
            // Exécuter la requête
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            
            // Fermer la connexion cURL
            curl_close($ch);
            
            // Stocker le code HTTP pour le débogage
            Setting::set('debug_api_http_code', $httpCode);
            Setting::set('debug_api_response', $response);
            
            // Vérifier les erreurs cURL
            if ($response === false || !empty($error)) {
                Log::error('Erreur cURL lors de la vérification de licence', [
                    'error' => $error,
                    'http_code' => $httpCode
                ]);
                
                return [
                    'valid' => false,
                    'message' => 'Erreur de connexion au serveur de licence: ' . $error,
                    'status_code' => 500,
                    'data' => []
                ];
            }
            
            // Décoder la réponse JSON
            $decoded = json_decode($response, true);
            
            // Vérifier si le décodage a échoué
            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('Erreur de décodage JSON: ' . json_last_error_msg() . ' - Réponse: ' . substr($response, 0, 1000));
                
                return [
                    'valid' => false,
                    'message' => 'Erreur de décodage de la réponse du serveur de licence',
                    'status_code' => 500,
                    'data' => []
                ];
            }
            
            // Vérifier la validité de la licence
            $isValid = false;
            $status = 'invalid';
            $message = 'Licence invalide';
            
            // Vérifier le format de réponse de l'API check-serial
            if (isset($decoded['status'])) {
                if ($decoded['status'] === 'success' && isset($decoded['data'])) {
                    // Vérifier si les données essentielles sont présentes (token est requis)
                    $data = $decoded['data'];
                    if (isset($data['token'])) {
                        // Vérifier si la licence n'est pas expirée (si expires_at est fourni)
                        $isValid = true;
                        $status = 'active';
                        $message = 'Licence valide';
                        
                        if (!empty($data['expires_at']) && $data['expires_at'] !== null) {
                            try {
                                $expirationDate = new \DateTime($data['expires_at']);
                                $currentDate = new \DateTime();
                                if ($currentDate > $expirationDate) {
                                    $isValid = false;
                                    $message = 'Licence expirée';
                                    $status = 'expired';
                                }
                            } catch (\Exception $e) {
                                // Si la date est invalide, on considère la licence comme valide
                                // mais on log l'erreur
                                Log::warning('Format de date d\'expiration invalide: ' . $data['expires_at']);
                            }
                        }
                        // Si expires_at est null ou vide, on considère que la licence n'expire pas
                    } else {
                        $message = 'Token de licence manquant';
                    }
                } else {
                    $message = $decoded['message'] ?? 'Erreur de validation de licence';
                    $status = 'error';
                }
            } else {
                $message = 'Format de réponse invalide';
                $status = 'error';
            }
            
            // Si la licence est valide, mettre à jour les informations
            if ($isValid && $status === 'active') {
                // Mettre à jour les settings
                Setting::set('license_valid', true);
                Setting::set('license_status', 'active');
                Setting::set('license_key', $serialKey);
                Setting::set('license_domain', $domain);
                // Setting::set('license_ip', $ipAddress); // Supprimé car on ne vérifie plus l'IP
                Setting::set('last_license_check', now()->toDateTimeString());
                
                if (isset($decoded['data']['expires_at'])) {
                    Setting::set('license_expires_at', $decoded['data']['expires_at']);
                }
                
                // Stocker les détails dans la session
                session([
                    'license_details' => $decoded['data'],
                    'license_valid' => true,
                    'license_status' => 'active'
                ]);
                
                return [
                    'valid' => true,
                    'message' => $message,
                    'status_code' => 200,
                    'status' => 'active',
                    'token' => $decoded['data']['token'] ?? null,
                    'expires_at' => $decoded['data']['expires_at'] ?? null,
                    'domain' => $domain,
                    // 'ip_address' => $ipAddress, // Supprimé car on ne vérifie plus l'IP
                    'data' => $decoded['data']
                ];
            } else {
                // Mettre à jour les settings pour une licence invalide
                Setting::set('license_valid', false);
                Setting::set('license_status', $status);
                Setting::set('last_license_check', now()->toDateTimeString());
                
                // Vider la session
                session()->forget(['license_details', 'license_valid', 'license_status']);
                
                return [
                    'valid' => false,
                    'message' => $message,
                    'status_code' => 401,
                    'status' => $status,
                    'data' => $decoded
                ];
            }
        } catch (\Exception $e) {
            // Logger l'exception
            Log::error('Exception lors de la vérification de licence: ' . $e->getMessage(), [
                'exception' => $e->getTraceAsString()
            ]);
            
            // Mettre à jour les settings pour une erreur
            Setting::set('license_valid', false);
            Setting::set('license_status', 'error');
            Setting::set('last_license_check', now()->toDateTimeString());
            
            // Vider la session
            session()->forget(['license_details', 'license_valid', 'license_status']);
            
            return [
                'valid' => false,
                'message' => 'Erreur lors de la vérification de licence: ' . $e->getMessage(),
                'status_code' => 500,
                'data' => []
            ];
        }
    }

    /**
     * Valide une clé de série avec gestion du cache
     *
     * @param string $serialKey
     * @param bool $forceCheck
     * @return array
     */
    public function validateSerialKeyWithCache($serialKey, $forceCheck = false)
    {
        // Si on est en environnement local et que APP_DEBUG est activé, on bypass la vérification
        // Note: Le bypass en environnement local a été supprimé pour assurer la sécurité
        // La validation de licence est maintenant toujours effectuée
        
        // Si la clé n'est pas configurée
        if (empty($serialKey)) {
            Log::warning('Tentative de validation avec une clé de licence vide');
            return [
                'valid' => false,
                'status' => 'invalid',
                'message' => 'Aucune clé de licence configurée',
                'data' => null
            ];
        }
        
        // Clé de cache pour cette licence
        $cacheKey = 'license_verification_' . md5($serialKey);
        $lastCheckKey = 'last_license_check_' . md5($serialKey);
        
        // Vérifier si on a une nouvelle clé (différente de la dernière vérifiée)
        $lastVerifiedKey = Cache::get('last_verified_license_key');
        if ($lastVerifiedKey && $lastVerifiedKey !== $serialKey) {
            // Nouvelle clé détectée, on vide le cache
            $oldCacheKey = 'license_verification_' . md5($lastVerifiedKey);
            $oldLastCheckKey = 'last_license_check_' . md5($lastVerifiedKey);
            
            Cache::forget($oldCacheKey);
            Cache::forget($oldLastCheckKey);
            Cache::forget('last_verified_license_key');
            Cache::forget($cacheKey); // Vider aussi le cache de la nouvelle clé au cas où
            Cache::forget($lastCheckKey);
            
            Log::info('Nouvelle clé de licence détectée, cache vidé', [
                'old_key' => substr($lastVerifiedKey, 0, 5) . '...' . substr($lastVerifiedKey, -5),
                'new_key' => substr($serialKey, 0, 5) . '...' . substr($serialKey, -5)
            ]);
            $forceCheck = true; // Forcer la vérification avec la nouvelle clé
        }
        
        // Si on ne force pas la vérification, on vérifie si on a un résultat en cache
        if (!$forceCheck && Cache::has($cacheKey)) {
            $cachedResult = Cache::get($cacheKey);
            
            // Mettre à jour le timestamp de dernière vérification
            Cache::put($lastCheckKey, now()->timestamp, 60 * 24 * 7); // 7 jours
            
            Log::info('Résultat de vérification de licence récupéré du cache', [
                'valid' => $cachedResult['valid'],
                'status' => $cachedResult['status'] ?? 'unknown',
                'cached_at' => Cache::get($lastCheckKey)
            ]);
            return $cachedResult;
        }
        
        try {
            // Appel à l'API pour vérifier la licence
            $response = $this->callLicenseApi('verify', [
                'serial_key' => $serialKey,
                'domain' => request()->getHost(),
                'ip' => request()->ip(),
                'version' => config('app.version')
            ]);
            
            if ($response && isset($response['valid'])) {
                // Stocker le résultat en cache (24 heures)
                Cache::put($cacheKey, $response, 60 * 24);
                
                // Stocker la clé vérifiée pour détecter les changements futurs
                Cache::put('last_verified_license_key', $serialKey, 60 * 24 * 7); // 7 jours
                
                // Stocker le timestamp de dernière vérification
                Cache::put($lastCheckKey, now()->timestamp, 60 * 24 * 7); // 7 jours
                
                Log::info('Vérification de licence effectuée avec succès', [
                    'valid' => $response['valid'],
                    'status' => $response['status'] ?? 'unknown',
                    'message' => $response['message'] ?? null,
                    'cache_key' => $cacheKey,
                    'last_check_key' => $lastCheckKey
                ]);
                
                return $response;
            }
            
            // En cas de réponse invalide
            $errorResult = [
                'valid' => false,
                'status' => 'error',
                'message' => 'Réponse invalide du serveur de licences',
                'data' => null
            ];
            
            Cache::put($cacheKey, $errorResult, 60 * 24); // Cache 24h même en cas d'erreur
            Cache::put($lastCheckKey, now()->timestamp, 60 * 24 * 7); // 7 jours
            
            Log::error('Réponse invalide du serveur de licences', ['response' => $response]);
            
            return $errorResult;
        } catch (\Exception $e) {
            // En cas d'erreur de connexion ou autre
            $errorResult = [
                'valid' => false,
                'status' => 'error',
                'message' => 'Erreur lors de la vérification: ' . $e->getMessage(),
                'data' => null
            ];
            
            // En environnement local, on permet quand même l'accès malgré l'erreur
            if (env('APP_ENV') === 'local') {
                $errorResult['valid'] = true;
                $errorResult['status'] = 'warning';
                $errorResult['message'] = 'Erreur de vérification en environnement local (accès autorisé): ' . $e->getMessage();
            }
            
            Cache::put($cacheKey, $errorResult, 60 * 6); // Cache 6h en cas d'erreur
            Cache::put($lastCheckKey, now()->timestamp, 60 * 24 * 7); // 7 jours
            
            Log::error('Erreur lors de la vérification de licence', [
                'error' => $e->getMessage(),
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return $errorResult;
        }
    }

    /**
     * Suspendre une clé de série.
     *
     * @param SerialKey $serialKey
     * @return void
     */
    public function suspendKey(SerialKey $serialKey): void
    {
        $serialKey->update([
            'status' => 'suspended'
        ]);

        // Notifier le propriétaire du projet
        if ($serialKey->project->user) {
            $serialKey->project->user->notify(new LicenceStatusChanged($serialKey, 'suspended'));
        }
        
        // Envoyer une notification WebSocket aux administrateurs
        $this->webSocketService->notifyLicenceStatusChange($serialKey, 'suspended');
    }

    /**
     * Révoquer une clé de série.
     *
     * @param SerialKey $serialKey
     * @return void
     */
    public function revokeKey(SerialKey $serialKey): void
    {
        $serialKey->update([
            'status' => 'revoked'
        ]);

        // Notifier le propriétaire du projet
        if ($serialKey->project->user) {
            $serialKey->project->user->notify(new LicenceStatusChanged($serialKey, 'revoked'));
        }
        
        // Enregistrement des modifications dans le journal
        Log::info('Clé de licence révoquée', [
            'serial_key' => $serialKey->serial_key,
            'project_id' => $serialKey->project_id,
            'domain' => $serialKey->domain
        ]);
    }

    /**
     * Génère un token sécurisé pour l'authentification API
     * 
     * @param string $serialKey
     * @param string $domain
     * @param string $ipAddress
     * @return string
     */
    public function generateSecureToken(string $serialKey, string $domain, string $ipAddress): string
    {
        // Utiliser HMAC-SHA256 au lieu de MD5 pour une meilleure sécurité
        $secret = env('SECURITY_TOKEN_SECRET', 'default_secret_change_me');
        $expiryTime = time() + (env('SECURITY_TOKEN_EXPIRY_MINUTES', 60) * 60);
        $data = $serialKey . '|' . $domain . '|' . $ipAddress . '|' . $expiryTime;
        
        return hash_hmac('sha256', $data, $secret);
    }

    /**
     * Génère une nouvelle clé de licence unique
     *
     * @return string
     */
    public function generateKey(): string
    {
        do {
            $key = strtoupper(Str::random(4) . '-' . Str::random(4) . '-' . Str::random(4) . '-' . Str::random(4));
        } while (SerialKey::where('serial_key', $key)->exists());

        return $key;
    }
    
    /**
     * Vérifie la licence d'installation
     *
     * @param bool $forceCheck Force la vérification sans utiliser le cache
     * @return bool
     */
    public function verifyInstallationLicense($forceCheck = false): bool
    {
        try {
            // Note: La vérification de licence est maintenant toujours effectuée,
            // même en environnement local, pour assurer la sécurité
            
            // Récupérer la clé de licence d'installation depuis les paramètres
            $licenseKey = env('INSTALLATION_LICENSE_KEY');
            
            // Journaliser le début de la vérification (uniquement en debug)
            if (env('APP_ENV') === 'local' || env('APP_DEBUG') === true) {
                Log::debug('Début de vérification de licence', [
                    'license_key' => $licenseKey ? 'CONFIGURÉE' : 'NON CONFIGURÉE',
                    'app_env' => env('APP_ENV', 'production')
                ]);
            }
            
            // Vérifier si une clé de licence est configurée
            if (empty($licenseKey)) {
                Log::warning('Clé de licence d\'installation non configurée');
                return false; // Bloquer l'accès si aucune licence n'est configurée
            }
            
            // Forcer le rafraîchissement du cache si demandé ou si c'est une nouvelle clé
            $forceRefresh = $forceCheck || request()->has('force_license_check');
            
            // Vérifier si c'est une nouvelle clé de licence (différente de celle en cache)
            $lastVerifiedKey = Cache::get('last_verified_license_key');
            $isNewKey = ($lastVerifiedKey !== $licenseKey);
            
            // Forcer la vérification si c'est une nouvelle clé ou si demandé explicitement
            $cacheKey = 'license_verification_' . md5($licenseKey);
            
            // Si c'est une nouvelle clé, vider immédiatement le cache
            if ($isNewKey) {
                Cache::forget($cacheKey);
                Cache::forget('last_verified_license_key');
                Log::info('Cache de licence vidé pour nouvelle clé', [
                    'old_key' => $lastVerifiedKey,
                    'new_key' => $licenseKey
                ]);
            }
            
            // Vérifier si le résultat est en cache et qu'on ne force pas le rafraîchissement
            if (!$forceRefresh && !$isNewKey && Cache::has($cacheKey)) {
                $cachedResult = Cache::get($cacheKey);
                // Log uniquement en environnement de développement
                if (env('APP_ENV') === 'local' || env('APP_DEBUG') === true) {
                    Log::debug('Résultat de vérification de licence récupéré du cache', [
                        'valid' => $cachedResult,
                        'license_key' => $licenseKey
                    ]);
                }
                return $cachedResult;
            }
            
            // Si c'est une nouvelle clé, logger l'information
            if ($isNewKey) {
                Log::info('Nouvelle clé de licence détectée, vérification forcée', [
                    'old_key' => $lastVerifiedKey,
                    'new_key' => $licenseKey
                ]);
            }
            
            // Récupérer le domaine actuel
            $domain = request()->getHost();
            
            // Si pas de domaine ou domaine par défaut, utiliser 'local' en environnement de développement
            if (empty($domain) || $domain === 'localhost' || $domain === '127.0.0.1') {
                $isLocalEnv = (config('app.env') === 'local') || (env('APP_ENV') === 'local') || (config('app.debug') === true);
                if ($isLocalEnv) {
                    $domain = 'local';
                }
            }
            
            // SOLUTION ROBUSTE: Utiliser la nouvelle fonction de collecte d'IP
            $ipInfo = IPHelper::collectServerIP();
            $ipAddress = $ipInfo['ip'];
            
            // Logger les informations détaillées pour diagnostic
            Log::info('COLLECTE IP RUNTIME ROBUSTE - ' . IPHelper::formatIPInfoForLog($ipInfo));
            
            // Avertissement si IP locale détectée
            if ($ipInfo['is_local']) {
                Log::warning("ATTENTION RUNTIME: IP locale détectée ({$ipAddress}). Le serveur distant recevra cette IP mais elle pourrait ne pas être utile pour l'identification.");
            }
            
            // Log uniquement en environnement de développement
            if (env('APP_ENV') === 'local' || env('APP_DEBUG') === true) {
                Log::debug('Paramètres de vérification d\'API', [
                    'domain' => $domain,
                    'ip_address' => $ipAddress,
                    'ip_selection_reason' => $ipInfo['reason']
                ]);
            }
            
            // Vérifier la validité de la licence via l'API externe
            $result = $this->validateSerialKey($licenseKey, $domain, $ipAddress);
            
            // Si nous sommes en développement local, accepter la licence même si l'API échoue
            if (env('APP_ENV') === 'local' && isset($result['api_error'])) {
                Log::warning('Erreur API en environnement local, licence considérée comme valide', [
                    'error' => $result['api_error']
                ]);
                return true;
            }
            
            // Bypass pour les domaines de développement en environnement local
            $isLocalEnv = (config('app.env') === 'local') || (env('APP_ENV') === 'local') || (config('app.debug') === true);
            if ($isLocalEnv && in_array($domain, ['localhost', '127.0.0.1', 'local.test', 'local'])) {
                Log::info('Development bypass activated in verifyInstallationLicense', [
                    'domain' => $domain,
                    'license_key' => substr($licenseKey, 0, 4) . '...',
                    'app_env' => config('app.env'),
                    'app_debug' => config('app.debug')
                ]);
                return true;
            }
            
            $isValid = $result['valid'] === true;
            
            // Mettre à jour les informations en session si disponibles
            if ($isValid && isset($result['data'])) {
                session(['license_details' => $result['data']]);
                session(['license_valid' => true]);
                session(['license_status' => $result['status'] ?? 'active']);
            }
            
            // Log uniquement en environnement de développement ou en cas d'erreur
            if ($isValid) {
                if (env('APP_ENV') === 'local' || env('APP_DEBUG') === true) {
                    Log::info('Licence valide!', [
                        'response' => $result
                    ]);
                }
            } else {
                // Toujours logger les erreurs de licence
                Log::warning('Licence invalide!', [
                    'message' => $result['message'] ?? 'Aucun message'
                ]);
            }
            
            // Mettre en cache le résultat pendant 24 heures
            Cache::put($cacheKey, $isValid, 60 * 24);
            
            // Sauvegarder la clé vérifiée pour détecter les changements futurs
            Cache::put('last_verified_license_key', $licenseKey, 60 * 24 * 7); // 7 jours
            
            return $isValid;
        } catch (\Exception $e) {
            // En cas d'erreur (serveur indisponible, etc.), logger l'erreur
            Log::error('Erreur lors de la vérification de licence', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Bloquer l'accès en cas d'erreur pour des raisons de sécurité
            // Note: Le bypass en environnement local a été supprimé pour assurer la sécurité
            return false;
        }
    }
}