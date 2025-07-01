<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SerialKey;
use App\Models\Setting;
use App\Services\LicenceService;
use App\Helpers\IPHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

class LicenseController extends Controller
{
    /**
     * Service de licence
     *
     * @var LicenceService
     */
    protected $licenceService;

    /**
     * Constructeur
     *
     * @param LicenceService $licenceService
     */
    public function __construct(LicenceService $licenceService)
    {
        $this->licenceService = $licenceService;
    }

    /**
     * Afficher la page de gestion des licences
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        // Récupérer la clé de licence actuelle depuis le .env
        $licenseKey = env('INSTALLATION_LICENSE_KEY', '');
        
        // Si aucune clé n'est trouvée dans l'environnement, essayer de la récupérer depuis les settings
        if (empty($licenseKey)) {
            $licenseKey = Setting::get('license_key', '');
        }
        
        // Si une clé est trouvée dans le .env mais pas dans les settings, la sauvegarder
        if (!empty($licenseKey) && empty(Setting::get('license_key'))) {
            Setting::set('license_key', $licenseKey);
        }
        
        // Récupérer les paramètres liés à la licence
        $licenseValid = (bool) Setting::get('license_valid', false);
        $licenseStatus = Setting::get('license_status', 'unknown');
        $expiresAt = Setting::get('license_expires_at');
        $checkFrequency = (int) Setting::get('license_check_frequency', 7); // défaut: 7 jours
        $lastCheck = Setting::get('last_license_check');
        
        // Vérifier si la licence doit être re-vérifiée
        $forceCheck = false;
        
        if (!empty($licenseKey)) {
            // Vérifier la date de dernière vérification
            if (empty($lastCheck)) {
                // Aucune vérification antérieure
                $forceCheck = true;
            } else {
                // Calculer quand la prochaine vérification est due
                $lastCheckDate = new \DateTime($lastCheck);
                $nextCheckDue = $lastCheckDate->add(new \DateInterval('P' . $checkFrequency . 'D'));
                $now = new \DateTime();
                
                // Si la date de prochaine vérification est dépassée
                if ($now >= $nextCheckDue) {
                    $forceCheck = true;
                }
            }
            
            // Si une vérification est nécessaire, procéder
            if ($forceCheck) {
                try {
                    $licenseDetails = $this->verifyLicenseWithServer($licenseKey);
                    
                    // Mettre à jour les propriétés locales
                    $licenseValid = $licenseDetails['valid'] ?? false;
                    
                    // Forcer le statut en fonction de la validité
                    if ($licenseValid) {
                        $licenseStatus = 'active';
                        $licenseDetails['status'] = 'active';
                        Setting::set('license_status', 'active');
                        
                        // S'assurer que la variable de session est bien mise à jour
                        session(['license_status' => 'active']);
                    } else {
                        $licenseStatus = 'invalid';
                        $licenseDetails['status'] = 'invalid';
                        Setting::set('license_status', 'invalid');
                        
                        // S'assurer que la variable de session est bien mise à jour
                        session(['license_status' => 'invalid']);
                        session(['license_valid' => false]);
                    }
                    
                    $expiresAt = $licenseDetails['expires_at'] ?? null;
                    
                    // Mettre à jour les sessions avec les détails de licence
                    session(['license_details' => $licenseDetails]);
                    session(['license_valid' => $licenseValid]);
                    session(['license_status' => $licenseStatus]);
                    
                    // Gérer le bypass pour la navigation
                    if ($licenseValid) {
                        session(['bypass_license_check' => true]);
                        Log::info('Licence valide : navigation autorisée');
                    } else {
                        // Licence invalide - bloquer navigation sauf dans la page de licence
                        session(['bypass_license_check' => false]);
                        Log::warning('Licence invalide : navigation bloquée');
                    }
                } catch (\Exception $e) {
                    // En cas d'erreur, bloquer l'accès pour des raisons de sécurité
                    Log::error('Erreur lors de la vérification de licence: ' . $e->getMessage());
                    
                    // Note: Le bypass en environnement de développement a été supprimé pour assurer la sécurité
                    session(['bypass_license_check' => false]);
                    Log::warning('Accès bloqué suite à l\'erreur de vérification de licence');
                }
            } else {
                // Utiliser les valeurs existantes dans la session/settings
                if ($licenseValid) {
                    // Si la licence est valide, autoriser la navigation
                    session(['bypass_license_check' => true]);
                    Log::info('Licence valide : navigation autorisée');
                    
                    // Forcer le statut à 'active' dans la session
                    session(['license_status' => 'active']);
                    session(['license_valid' => true]);
                    Setting::set('license_status', 'active');
                    Setting::set('license_valid', true);
                } else {
                    // Licence invalide - bloquer TOUTE navigation (y compris page de licence)
                    session(['bypass_license_check' => false]);
                    Log::info('Licence invalide : navigation TOTALEMENT bloquée');
                    
                    // S'assurer que le statut est cohérent avec l'invalidité
                    $licenseStatus = 'invalid';
                    Setting::set('license_status', 'invalid');
                    Setting::set('license_valid', false);
                    session(['license_status' => 'invalid']);
                    session(['license_valid' => false]);
                }
            }
        }
        
        // Vérifier si le fichier .env existe et est accessible
        $envExists = file_exists(base_path('.env')) && is_writable(base_path('.env'));
        
        // Déterminer si on doit bloquer la navigation (sauf si bypass activé)
        $bypassCheck = session('bypass_license_check', false);
        $blockNavigation = !$licenseValid && !$bypassCheck;
        
        Log::info('Blocage de navigation: ' . ($blockNavigation ? 'OUI' : 'NON') . 
                  ', Licence valide: ' . ($licenseValid ? 'OUI' : 'NON') . 
                  ', Bypass: ' . ($bypassCheck ? 'ACTIF' : 'INACTIF'));
        
        return view('admin.settings.license', [
            'licenseKey' => $licenseKey,
            'licenseValid' => $licenseValid,
            'licenseStatus' => $licenseStatus,
            'expiresAt' => $expiresAt,
            'checkFrequency' => $checkFrequency,
            'lastCheck' => $lastCheck,
            'envExists' => $envExists,
            'blockNavigation' => $blockNavigation
        ]);
    }

    /**
     * Mettre à jour les paramètres de licence
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateSettings(Request $request)
    {
        // Valider les données - License key uniquement car c'est tout ce que le formulaire envoie
        $validated = $request->validate([
            'license_key' => 'nullable|string|max:100'
        ]);
        
        // Récupérer la clé de licence
        $licenseKey = $validated['license_key'] ?? '';
        
        try {
            // Si la clé est vide, bloquer la navigation et afficher une erreur
            if (empty($licenseKey)) {
                // Mettre à jour les settings
                Setting::set('license_valid', false);
                Setting::set('license_status', 'invalid');
                
                // Mettre à jour la session
                session(['bypass_license_check' => false]);
                session(['license_valid' => false]);
                session(['license_status' => 'invalid']);
                
                return redirect()->route('admin.settings.license')
                    ->with('error', 'Aucune clé de licence n\'a été fournie.');
            }
            
            // Mettre à jour le fichier .env avec la nouvelle clé
            $this->updateEnvFile('INSTALLATION_LICENSE_KEY', $licenseKey);
            
            // Vérifier la licence avec le serveur distant
            $domain = request()->getHost();
            
            // CORRECTION: Utiliser le nouveau système de détection d'IP robuste
            $ipInfo = IPHelper::collectServerIPRobust(false);
            $ipAddress = $ipInfo['ip'];
            
            // Logger les informations d'IP pour diagnostic
            Log::info('LICENCE CONTROLLER - Détection IP: ' . IPHelper::formatIPInfoForLog($ipInfo));
            
            // Utiliser le LicenceService pour valider la licence (domaine + IP robuste)
            $result = $this->licenceService->validateSerialKey($licenseKey, $domain, $ipAddress);
            $isValid = $result['valid'] ?? false;
            
            // Mettre à jour les settings selon la validité
            if ($isValid) {
                // ✅ Licence valide - Forcer la mise à jour des Settings
                Setting::set('license_valid', true);
                Setting::set('license_status', 'active');
                Setting::set('last_license_check', now()->toDateTimeString());
                
                Log::info('🎉 Licence sauvegardée avec succès et validée');
                
                // Message flash de succès
                return redirect()->route('admin.settings.license')
                    ->with('success', 'La clé de licence a été validée avec succès et les informations ont été envoyées au serveur distant.');
            } else {
                // ❌ Licence invalide - Forcer la mise à jour des Settings
                Setting::set('license_valid', false);
                Setting::set('license_status', 'invalid');
                Setting::set('last_license_check', now()->toDateTimeString());
                
                Log::warning('🔒 Licence invalide sauvegardée');
                
                // Message flash d'erreur
                $message = $result['message'] ?? 'Clé de licence non valide';
                return redirect()->route('admin.settings.license')
                    ->with('error', 'La clé de licence est invalide: ' . $message);
            }
        } catch (\Exception $e) {
            // Journaliser l'erreur
            Log::error('Erreur lors de la validation de la licence: ' . $e->getMessage());
            
            // En cas d'erreur, définir la licence comme invalide
            Setting::set('license_valid', false);
            Setting::set('license_status', 'error');
            Setting::set('last_license_check', now()->toDateTimeString());
            
            Log::warning('🔒 Erreur de validation de licence');
            
            // Message flash d'erreur
            return redirect()->route('admin.settings.license')
                ->with('error', 'Une erreur est survenue: ' . $e->getMessage());
        }
    }
    
    /**
     * Basculer l'état du bypass de licence
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleBypass(Request $request)
    {
        $enable = $request->input('enable', true);
        
        // Activer ou désactiver le bypass
        session(['bypass_license_check' => (bool)$enable]);
        
        return response()->json([
            'success' => true,
            'bypass_enabled' => (bool)$enable
        ]);
    }
    
    /**
     * Forcer la vérification de licence
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function forceCheck()
    {
        try {
            // Récupérer la clé de licence actuelle
            $licenseKey = env('INSTALLATION_LICENSE_KEY');
            
            if (empty($licenseKey)) {
                // Mettre à jour les settings
                Setting::set('license_valid', false);
                Setting::set('license_status', 'invalid');
                
                // Mettre à jour la session
                session(['bypass_license_check' => false]);
                session(['license_valid' => false]);
                session(['license_status' => 'invalid']);
                
                // Message d'erreur
                return redirect()->route('admin.settings.license')
                    ->with('error', 'Aucune clé de licence n\'est configurée.');
            }
            
            // Valider avec le serveur distant
            $domain = request()->getHost();
            
            // CORRECTION: Utiliser le nouveau système de détection d'IP robuste
            $ipInfo = IPHelper::collectServerIPRobust(false);
            $ipAddress = $ipInfo['ip'];
            
            // Logger les informations d'IP pour diagnostic
            Log::info('LICENCE CONTROLLER FORCE CHECK - Détection IP: ' . IPHelper::formatIPInfoForLog($ipInfo));
            
            // Forcer une validation fraîche (domaine + IP robuste)
            $result = $this->licenceService->validateSerialKey($licenseKey, $domain, $ipAddress);
            $isValid = isset($result['valid']) && $result['valid'] === true;
            
            // Mettre à jour selon la validité
            if ($isValid) {
                // Licence valide
                Setting::set('license_valid', true);
                Setting::set('license_status', 'active'); // Forcer le statut à active
                Setting::set('license_expires_at', $result['expires_at'] ?? null);
                Setting::set('last_license_check', now()->toDateTimeString());
                
                // Mettre à jour la session
                session(['bypass_license_check' => true]);
                session(['license_valid' => true]);
                session(['license_status' => 'active']);
                
                // Détails de licence
                $licenseDetails = [
                    'key' => $licenseKey,
                    'status' => 'active', // Forcer le statut à active
                    'expires_at' => $result['expires_at'] ?? null,
                    'domain' => $result['domain'] ?? $domain,
                    // 'ip_address' => $result['ip_address'] ?? $ipAddress, // Supprimé car on ne vérifie plus l'IP
                    'valid' => true,
                    'last_check' => now()->toDateTimeString()
                ];
                session(['license_details' => $licenseDetails]);
                
                // Message de succès
                return redirect()->route('admin.settings.license')
                    ->with('success', 'La licence a été vérifiée avec succès et est valide.');
            } else {
                // Licence invalide
                Setting::set('license_valid', false);
                Setting::set('license_status', 'invalid');
                Setting::set('last_license_check', now()->toDateTimeString());
                
                // 🔒 SÉCURITÉ: DÉSACTIVER TOUS LES BYPASS si licence invalide
                session(['emergency_license_bypass' => false]);
                session(['bypass_license_check' => false]);
                session(['license_valid' => false]);
                session(['license_status' => 'invalid']);
                
                // Détails de licence
                $licenseDetails = [
                    'key' => $licenseKey,
                    'status' => 'invalid',
                    'expires_at' => $result['expires_at'] ?? null,
                    'domain' => $result['domain'] ?? $domain,
                    // 'ip_address' => $result['ip_address'] ?? $ipAddress, // Supprimé car on ne vérifie plus l'IP
                    'valid' => false,
                    'message' => $result['message'] ?? 'Clé de licence non valide',
                    'last_check' => now()->toDateTimeString()
                ];
                session(['license_details' => $licenseDetails]);
                
                // Message d'erreur
                $message = $result['message'] ?? 'Clé de licence non valide';
                return redirect()->route('admin.settings.license')
                    ->with('error', 'La licence a été vérifiée mais n\'est pas valide: ' . $message);
            }
        } catch (\Exception $e) {
            // Journaliser l'erreur
            Log::error('Erreur lors de la vérification de licence: ' . $e->getMessage());
            
            // En cas d'erreur, définir la licence comme invalide
            Setting::set('license_valid', false);
            Setting::set('license_status', 'error');
            
            // 🔒 SÉCURITÉ: DÉSACTIVER TOUS LES BYPASS en cas d'erreur
            session(['emergency_license_bypass' => false]);
            session(['bypass_license_check' => false]);
            session(['license_valid' => false]);
            session(['license_status' => 'error']);
            
            // Message d'erreur
            return redirect()->route('admin.settings.license')
                ->with('error', 'Une erreur est survenue lors de la vérification de la licence: ' . $e->getMessage());
        }
    }
    
    /**
     * Mettre à jour le fichier .env
     *
     * @param string $key   Clé à mettre à jour
     * @param string $value Valeur à affecter
     *
     * @return bool
     */
    private function updateEnvFile($key, $value)
    {
        try {
            $envFile = base_path('.env');
            
            // Vérifier si le fichier existe et est accessible en écriture
            if (!file_exists($envFile)) {
                Log::error('Fichier .env introuvable');
                return false;
            }
            
            if (!is_writable($envFile)) {
                // Tenter de rendre le fichier accessible en écriture
                chmod($envFile, 0666);
                
                if (!is_writable($envFile)) {
                    Log::error('Fichier .env non accessible en écriture');
                    return false;
                }
            }
            
            // Lecture du fichier .env
            $content = file_get_contents($envFile);
            
            // Préparer la valeur en ajoutant des guillemets si nécessaire
            $quotedValue = $value;
            if (strpos($value, ' ') !== false || preg_match('/[^A-Za-z0-9_.-]/', $value)) {
                $quotedValue = '"' . str_replace('"', '\\"', $value) . '"';
            }

            // Traiter la clé pour les expressions régulières
            $escapedKey = preg_quote($key, '/');
            
            // Vérifier si la clé existe déjà
            $pattern = "/^{$escapedKey}=.*/m";
            if (preg_match($pattern, $content)) {
                // Mettre à jour la valeur existante
                $content = preg_replace(
                    $pattern,
                    "{$key}={$quotedValue}",
                    $content
                );
                
                Log::info("Clé $key mise à jour dans .env");
            } else {
                // Ajouter une nouvelle clé
                $content .= "\n{$key}={$quotedValue}\n";
                
                Log::info("Clé $key ajoutée dans .env");
            }
            
            // Sauvegarder le fichier
            $bytesWritten = file_put_contents($envFile, $content);
            
            if ($bytesWritten === false) {
                Log::error('Impossible d\'enregistrer le fichier .env');
                return false;
            }
            
            // Vider le cache de configuration pour forcer la relecture des variables d'environnement
            Artisan::call('config:clear');
            
            // Vérifier que la clé est bien écrite dans le fichier
            clearstatcache(); // Nettoyer le cache de stat pour s'assurer d'avoir le bon contenu
            $newContent = file_get_contents($envFile);
            $patternCheck = "/^{$escapedKey}=/m";
            
            if (!preg_match($patternCheck, $newContent)) {
                Log::error("La clé $key n'a pas été correctement écrite dans .env");
                return false;
            }
            
            Log::info("Clé de licence correctement mise à jour dans .env");
            return true;
            
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour du fichier .env: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Vider les caches spécifiques à la licence sans affecter les traductions
     * pour forcer la prise en compte des changements de licence
     *
     * @return void
     */
    private function clearCache()
    {
        // Vider le cache de configuration (nécessaire pour les variables d'environnement)
        Artisan::call('config:clear');
        Log::info("Cache configuration vidé");
        
        // Vider le cache de route (nécessaire pour les middlewares)
        Artisan::call('route:clear');
        Log::info("Cache routes vidé");
        
        // Vider le cache de vue (nécessaire pour les vues compilées)
        Artisan::call('view:clear');
        Log::info("Cache vues vidé");
        
        // Vider le cache OPcache si disponible
        if (function_exists('opcache_reset')) {
            opcache_reset();
            Log::info("OPCache réinitialisé");
        }
        
        // Vider uniquement les caches spécifiques à la licence (SANS toucher aux traductions)
        $licenseCacheKeys = [
            'license_validation',
            'license_check_session_' . session()->getId(),
            'license_verification_' . md5(Setting::get('license_key', '')),
            'last_license_check_' . md5(Setting::get('license_key', '')),
            'last_verified_license_key'
        ];
        
        foreach ($licenseCacheKeys as $key) {
            Cache::forget($key);
            Log::info("Cache de licence vidé: {$key}");
        }
        
        // Vider les caches de configuration et d'application SANS vider les traductions
        try {
            // Vider seulement le cache de l'application Laravel (pas Redis/Memcached global)
            Artisan::call('cache:clear');
            Log::info("Cache de l'application vidé (traductions préservées)");
        } catch (\Exception $e) {
            Log::warning("Erreur lors du vidage du cache de l'application: " . $e->getMessage());
        }
        
        Log::info("Caches de licence vidés avec succès (traductions préservées)");
    }
    
    /**
     * Forcer le rechargement des variables d'environnement
     *
     * @return void
     */
    private function reloadEnv()
    {
        // Clear config cache
        Artisan::call('config:clear');
        
        // Reload environment variables
        $dotenv = \Dotenv\Dotenv::createImmutable(base_path());
        $dotenv->load();
    }
    
    /**
     * Récupérer les détails d'une licence depuis le serveur distant
     * 
     * @param string $licenseKey
     * @return array
     * @throws \Exception Si une erreur inattendue survient pendant la vérification
     */
    private function getLicenseDetails($licenseKey)
    {
        // Par défaut, on initialise un résultat vide
        $result = [
            'valid' => false,
            'status' => 'invalid',
            'message' => 'Données de licence invalides'
        ];
        
        // Vérification de base de la clé
        if (empty($licenseKey) || !is_string($licenseKey)) {
            Log::warning('Tentative de validation avec une clé vide ou invalide');
            $result['message'] = 'Clé de licence vide ou invalide';
            return $result;
        }

        try {
            // Récupérer les détails de licence depuis le service
            Log::info('Appel au service de validation de licence', ['key' => substr($licenseKey, 0, 5) . '...']);
            
            // Récupérer le domaine pour la validation
            $domain = request()->getHost();
            
            // CORRECTION: Utiliser le nouveau système de détection d'IP robuste
            $ipInfo = IPHelper::collectServerIPRobust(false);
            $ipAddress = $ipInfo['ip'];
            
            Log::info('Paramètres de validation de licence', [
                'domain' => $domain,
                'ip_address' => $ipAddress,
                'ip_confidence' => $ipInfo['confidence'] ?? 0,
                'ip_reason' => $ipInfo['reason'] ?? 'unknown',
                'validation_method' => 'domain_and_ip_robust'
            ]);
            
            // Logger les informations d'IP pour diagnostic
            Log::info('LICENCE CONTROLLER GET DETAILS - Détection IP: ' . IPHelper::formatIPInfoForLog($ipInfo));
            
            // Vérifier la validité (domaine + IP robuste)
            $result = $this->licenceService->validateSerialKey($licenseKey, $domain, $ipAddress);
            
            // S'assurer que tous les champs nécessaires sont présents
            if (!isset($result['valid'])) {
                $result['valid'] = false;
                Log::warning('Champ valid manquant dans la réponse de validation');
            }
            
            // Normaliser le statut en fonction de la validité
            if ($result['valid'] === true) {
                $result['status'] = 'active';
            } else {
                $result['status'] = 'invalid';
            }
            
            // Log le résultat
            $logLevel = ($result['valid'] ?? false) ? 'info' : 'warning';
            Log::$logLevel('Résultat de la vérification de licence', [
                'valid' => $result['valid'] ?? false,
                'status' => $result['status'] ?? 'unknown',
                'expires_at' => $result['expires_at'] ?? 'unknown'
            ]);
            
            return [
                'valid' => $result['valid'] ?? false,
                'status' => $result['status'] ?? 'invalid',
                'message' => $result['message'] ?? 'Vérification terminée',
                'domain' => $result['domain'] ?? $domain,
                // 'ip_address' => $result['ip_address'] ?? $ipAddress, // Supprimé car on ne vérifie plus l'IP
                'expires_at' => $result['expires_at'] ?? null,
                'key' => $licenseKey // Ajouter la clé dans les détails pour l'affichage
            ];
        } catch (\Exception $e) {
            Log::error('Exception lors de la vérification de la licence: ' . $e->getMessage(), [
                'exception' => get_class($e),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
    
    /**
     * Vérifie une clé de licence avec le serveur distant et met à jour les settings
     * 
     * @param string $licenseKey
     * @param bool $updateSettings Mettre à jour les settings avec les résultats
     * @return array Détails de la licence et statut
     */
    private function verifyLicenseWithServer($licenseKey, $updateSettings = true)
    {
        Log::info('Début de la vérification de licence avec le serveur distant');
        
        // Si la clé est vide, retourner un résultat d'échec immédiatement
        if (empty($licenseKey)) {
            Log::warning('Tentative de vérification avec une clé vide');
            return [
                'valid' => false,
                'status' => 'missing',
                'message' => 'Aucune clé de licence configurée',
                'key' => ''
            ];
        }
        
        try {
            // Récupérer les détails depuis l'API
            $licenseDetails = $this->getLicenseDetails($licenseKey);
            
            // Forcer la normalisation des champs de résultat
            $isValid = $licenseDetails['valid'] ?? false;
            
            // Si la clé est valide, forcer le statut à "active" 
            if ($isValid && (!isset($licenseDetails['status']) || $licenseDetails['status'] !== 'active')) {
                $licenseDetails['status'] = 'active';
            }
            
            // Journaliser le résultat 
            if ($isValid) {
                Log::info('Licence validée avec succès', [
                    'status' => $licenseDetails['status'] ?? 'active',
                    'expires_at' => $licenseDetails['expires_at'] ?? 'N/A'
                ]);
            } else {
                Log::warning('Vérification de licence échouée', [
                    'status' => $licenseDetails['status'] ?? 'invalid',
                    'message' => $licenseDetails['message'] ?? 'Raison inconnue'
                ]);
                
                // Force le statut à "invalid" si la clé n'est pas valide
                $licenseDetails['status'] = 'invalid';
            }
            
            if ($isValid) {
                // Mettre à jour les settings avec les détails de licence
                Setting::set('license_valid', true);
                Setting::set('license_status', 'active'); // Forcer le statut à active si valide
                Setting::set('license_expires_at', $licenseDetails['expires_at'] ?? null);
                Setting::set('last_license_check', now()->toDateTimeString());
                
                // Activer le bypass pour permettre la navigation
                session(['bypass_license_check' => true]);
                session(['license_details' => $licenseDetails]);
                session(['license_valid' => true]);
                session(['license_status' => 'active']); // Forcer le statut à active en session
                
                // S'assurer que le statut dans les détails est aussi 'active'
                $licenseDetails['status'] = 'active';
                
                Log::info('Vérification de licence réussie', [
                    'key' => substr($licenseKey, 0, 5) . '...',
                    'status' => 'active',
                    'bypass' => 'ACTIF (navigation autorisée)'
                ]);
                Log::info('Paramètres de licence mis à jour', [
                    'valid' => $isValid ? 'OUI' : 'NON',
                    'bypass' => $isValid ? 'ACTIF' : 'INACTIF (navigation bloquée)'
                ]);
            } else {
                // Mettre à jour les settings avec l'erreur
                Setting::set('license_valid', false);
                Setting::set('license_status', 'invalid');
                Setting::set('last_license_check', now()->toDateTimeString());
                
                // Ne PAS activer de bypass en cas d'erreur - bloquer la navigation
                session(['bypass_license_check' => false]);
                session(['license_details' => $licenseDetails]);
                session(['license_valid' => false]);
                session(['license_status' => 'invalid']);
                
                Log::info('Statut de bypass suite à erreur: DÉSACTIVÉ (navigation bloquée)');
            }
            
            return $licenseDetails;
            
        } catch (\Exception $e) {
            // En cas d'erreur, journaliser et retourner un résultat d'échec avec les détails
            Log::error('Exception pendant la vérification de la licence: ' . $e->getMessage());
            
            $error = [
                'valid' => false,
                'status' => 'error',
                'message' => 'Erreur lors de la vérification de la licence: ' . $e->getMessage(),
                'key' => $licenseKey
            ];
            
            if ($updateSettings) {
                // Mettre à jour les settings avec l'erreur
                Setting::set('license_valid', false);
                Setting::set('license_status', 'error');
                Setting::set('last_license_check', now()->toDateTimeString());
                
                // Ne PAS activer de bypass en cas d'erreur - bloquer la navigation
                // Sauf si nous sommes en environnement local/développement
                $bypassForError = config('app.env') === 'local' || config('app.debug', false);
                session(['bypass_license_check' => $bypassForError]);
                session(['license_details' => $error]);
                session(['license_valid' => false]);
                session(['license_status' => 'error']);
                
                Log::info('Statut de bypass suite à erreur: ' . ($bypassForError ? 'ACTIVÉ (env. dev)' : 'DÉSACTIVÉ (navigation bloquée)'));
            }
            
            return $error;
        }
    }
    
    /**
     * Afficher la page de recherche de clés de licence
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function search(Request $request)
    {
        $query = $request->get('query');
        $results = null;
        
        if ($query) {
            $results = SerialKey::where('serial_key', 'like', "%{$query}%")
                ->with('project')
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }
        
        return view('admin.license-search', compact('results'));
    }
    
    /**
     * Afficher les détails d'une clé de licence (pour l'affichage modal)
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function details($id)
    {
        $key = SerialKey::with(['project', 'histories' => function($query) {
            $query->orderBy('created_at', 'desc')->limit(10);
        }])->findOrFail($id);
        
        return View::make('admin.partials.license-details', compact('key'))->render();
    }
    
    /**
     * Suspendre une clé de licence
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function suspend($id)
    {
        $key = SerialKey::findOrFail($id);
        $this->licenceService->suspendKey($key);
        
        return redirect()->back()->with('success', 'La clé de licence a été suspendue avec succès.');
    }
    
    /**
     * Révoquer une clé de licence
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function revoke($id)
    {
        $key = SerialKey::findOrFail($id);
        $this->licenceService->revokeKey($key);
        
        return redirect()->back()->with('success', 'La clé de licence a été révoquée avec succès.');
    }
    
    /**
     * Activer une clé de licence
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function activate($id)
    {
        $key = SerialKey::findOrFail($id);
        $result = $this->licenceService->activateKey($key);
        
        if ($result) {
            return redirect()->back()->with('success', 'La clé de licence a été activée avec succès.');
        } else {
            return redirect()->back()->with('error', 'Impossible d\'activer une clé révoquée.');
        }
    }
}
