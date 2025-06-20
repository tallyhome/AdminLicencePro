<?php

// Charger Laravel
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== FORÇAGE DU RAFRAÎCHISSEMENT DE LICENCE ===\n\n";

// 1. Récupérer la clé de licence
$licenseKey = env('INSTALLATION_LICENSE_KEY');
echo "1. Clé de licence actuelle: " . ($licenseKey ?: 'NON TROUVÉE') . "\n\n";

if (!$licenseKey) {
    echo "ERREUR: Aucune clé de licence trouvée!\n";
    exit(1);
}

// 2. Vider tous les caches liés à la licence
echo "2. Vidage des caches de licence...\n";

// Cache de vérification de licence
$cacheKey = 'license_verification_' . md5($licenseKey);
echo "   - Suppression du cache: {$cacheKey}\n";
\Illuminate\Support\Facades\Cache::forget($cacheKey);

// Cache de la dernière clé vérifiée
echo "   - Suppression de 'last_verified_license_key'\n";
\Illuminate\Support\Facades\Cache::forget('last_verified_license_key');

// Cache de la dernière vérification
$lastCheckKey = 'last_license_check_' . md5($licenseKey);
echo "   - Suppression du cache: {$lastCheckKey}\n";
\Illuminate\Support\Facades\Cache::forget($lastCheckKey);

// Vider le cache général
echo "   - Vidage du cache général...\n";
\Illuminate\Support\Facades\Artisan::call('cache:clear');

// 3. Forcer une nouvelle validation
echo "\n3. Validation forcée de la licence...\n";
try {
    $licenceService = app(\App\Services\LicenceService::class);
    $domain = request()->getHost() ?: 'localhost';
    $ipAddress = $_SERVER['SERVER_ADDR'] ?? '127.0.0.1';
    
    echo "   Domaine: {$domain}\n";
    echo "   IP: {$ipAddress}\n";
    echo "   Validation en cours...\n";
    
    $result = $licenceService->validateSerialKey($licenseKey, $domain, $ipAddress);
    echo "   Résultat: " . ($result['valid'] ? 'VALIDE' : 'INVALIDE') . "\n";
    echo "   Message: " . $result['message'] . "\n";
    
    if ($result['valid']) {
        echo "   ✓ Licence validée avec succès!\n";
        
        // Mettre à jour le cache avec le nouveau résultat
        \Illuminate\Support\Facades\Cache::put($cacheKey, true, 60 * 24); // 24 heures
        \Illuminate\Support\Facades\Cache::put('last_verified_license_key', $licenseKey, 60 * 24 * 7); // 7 jours
        \Illuminate\Support\Facades\Cache::put($lastCheckKey, time(), 60 * 24 * 7); // 7 jours
        
        echo "   ✓ Cache mis à jour!\n";
    } else {
        echo "   ✗ Échec de la validation!\n";
        if (isset($result['data'])) {
            echo "   Données: " . json_encode($result['data'], JSON_PRETTY_PRINT) . "\n";
        }
    }
} catch (Exception $e) {
    echo "   ERREUR: " . $e->getMessage() . "\n";
    echo "   Trace: " . $e->getTraceAsString() . "\n";
}

// 4. Vérifier le nouveau statut du cache
echo "\n4. Vérification du cache après mise à jour...\n";
$cachedResult = \Illuminate\Support\Facades\Cache::get($cacheKey);
echo "   Cache de validation: " . ($cachedResult !== null ? ($cachedResult ? 'VALIDE' : 'INVALIDE') : 'AUCUN') . "\n";

$lastVerifiedKey = \Illuminate\Support\Facades\Cache::get('last_verified_license_key');
echo "   Dernière clé vérifiée: " . ($lastVerifiedKey ?: 'AUCUNE') . "\n";

$lastCheck = \Illuminate\Support\Facades\Cache::get($lastCheckKey, 0);
echo "   Dernière vérification: " . ($lastCheck ? date('Y-m-d H:i:s', $lastCheck) : 'JAMAIS') . "\n";

echo "\n=== RAFRAÎCHISSEMENT TERMINÉ ===\n";
echo "\nVous pouvez maintenant redémarrer le serveur et tester l'accès à l'application.\n";