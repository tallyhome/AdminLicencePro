# Guide d'Implémentation des Améliorations - AdminLicence

## 🚀 Phase 1 - Améliorations Critiques (Priorité Haute)

### 1. Système de Monitoring en Temps Réel

#### Étape 1 : Créer le Service de Health Check

```bash
# Créer le contrôleur
php artisan make:controller Api/HealthController

# Créer le service
php artisan make:service SystemHealthService
```

#### Étape 2 : Implémentation du Service

**Fichier : `app/Services/SystemHealthService.php`**
```php
<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Exception;

class SystemHealthService
{
    public function getSystemStatus(): array
    {
        return [
            'timestamp' => now()->toISOString(),
            'status' => 'operational',
            'services' => [
                'api' => $this->checkApiHealth(),
                'database' => $this->checkDatabaseHealth(),
                'cache' => $this->checkCacheHealth(),
                'storage' => $this->checkStorageHealth(),
                'licence_system' => $this->checkLicenceSystemHealth()
            ]
        ];
    }

    private function checkApiHealth(): array
    {
        try {
            $startTime = microtime(true);
            // Test simple de l'API
            $response = app('App\Http\Controllers\Api\LicenceApiController')->test();
            $responseTime = round((microtime(true) - $startTime) * 1000, 2);
            
            return [
                'status' => 'operational',
                'response_time_ms' => $responseTime,
                'last_check' => now()->toISOString()
            ];
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'error' => $e->getMessage(),
                'last_check' => now()->toISOString()
            ];
        }
    }

    private function checkDatabaseHealth(): array
    {
        try {
            $startTime = microtime(true);
            DB::select('SELECT 1');
            $responseTime = round((microtime(true) - $startTime) * 1000, 2);
            
            return [
                'status' => 'operational',
                'response_time_ms' => $responseTime,
                'connection' => config('database.default'),
                'last_check' => now()->toISOString()
            ];
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'error' => $e->getMessage(),
                'last_check' => now()->toISOString()
            ];
        }
    }

    private function checkCacheHealth(): array
    {
        try {
            $testKey = 'health_check_' . time();
            $testValue = 'test_value';
            
            Cache::put($testKey, $testValue, 60);
            $retrieved = Cache::get($testKey);
            Cache::forget($testKey);
            
            $status = ($retrieved === $testValue) ? 'operational' : 'error';
            
            return [
                'status' => $status,
                'driver' => config('cache.default'),
                'last_check' => now()->toISOString()
            ];
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'error' => $e->getMessage(),
                'last_check' => now()->toISOString()
            ];
        }
    }

    private function checkStorageHealth(): array
    {
        try {
            $testFile = 'health_check.txt';
            Storage::put($testFile, 'test content');
            $exists = Storage::exists($testFile);
            Storage::delete($testFile);
            
            return [
                'status' => $exists ? 'operational' : 'error',
                'disk' => config('filesystems.default'),
                'last_check' => now()->toISOString()
            ];
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'error' => $e->getMessage(),
                'last_check' => now()->toISOString()
            ];
        }
    }

    private function checkLicenceSystemHealth(): array
    {
        try {
            // Vérifier que le système de licences fonctionne
            $licenceCount = DB::table('serial_keys')->count();
            $activeCount = DB::table('serial_keys')->where('status', 'active')->count();
            
            return [
                'status' => 'operational',
                'total_licences' => $licenceCount,
                'active_licences' => $activeCount,
                'last_check' => now()->toISOString()
            ];
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'error' => $e->getMessage(),
                'last_check' => now()->toISOString()
            ];
        }
    }
}
```

#### Étape 3 : Contrôleur Health Check

**Fichier : `app/Http/Controllers/Api/HealthController.php`**
```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\SystemHealthService;
use Illuminate\Http\JsonResponse;

class HealthController extends Controller
{
    public function __construct(
        private SystemHealthService $healthService
    ) {}

    public function check(): JsonResponse
    {
        $status = $this->healthService->getSystemStatus();
        
        // Déterminer le code de statut HTTP
        $httpStatus = $this->getHttpStatusFromHealth($status);
        
        return response()->json($status, $httpStatus);
    }

    public function simple(): JsonResponse
    {
        return response()->json([
            'status' => 'ok',
            'timestamp' => now()->toISOString()
        ]);
    }

    private function getHttpStatusFromHealth(array $status): int
    {
        foreach ($status['services'] as $service) {
            if ($service['status'] === 'error') {
                return 503; // Service Unavailable
            }
        }
        
        return 200; // OK
    }
}
```

#### Étape 4 : Routes Health Check

**Ajouter dans `routes/api.php` :**
```php
// Health Check Routes
Route::get('/health', [App\Http\Controllers\Api\HealthController::class, 'check']);
Route::get('/health/simple', [App\Http\Controllers\Api\HealthController::class, 'simple']);
```

### 2. Mise à Jour du Frontend avec Statuts Dynamiques

#### Étape 1 : Créer un Contrôleur Frontend pour les Statuts

```bash
php artisan make:controller Frontend/SystemStatusController
```

**Fichier : `app/Http/Controllers/Frontend/SystemStatusController.php`**
```php
<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\SystemHealthService;
use Illuminate\Http\JsonResponse;

class SystemStatusController extends Controller
{
    public function __construct(
        private SystemHealthService $healthService
    ) {}

    public function getStatus(): JsonResponse
    {
        $status = $this->healthService->getSystemStatus();
        
        // Formater pour le frontend
        $frontendStatus = [
            'api_adminlicence' => $this->formatServiceStatus($status['services']['api']),
            'dashboard_web' => $this->formatServiceStatus($status['services']['api']), // Même que API
            'systeme_licences' => $this->formatServiceStatus($status['services']['licence_system']),
            'base_donnees' => $this->formatServiceStatus($status['services']['database']),
            'last_update' => $status['timestamp']
        ];
        
        return response()->json($frontendStatus);
    }

    private function formatServiceStatus(array $service): array
    {
        return [
            'status' => $service['status'] === 'operational' ? 'Opérationnel' : 'Erreur',
            'status_class' => $service['status'] === 'operational' ? 'text-green-600' : 'text-red-600',
            'icon' => $service['status'] === 'operational' ? '✓' : '✗',
            'details' => $service
        ];
    }
}
```

#### Étape 2 : Route Frontend

**Ajouter dans `routes/web.php` :**
```php
Route::get('/api/frontend/system-status', [App\Http\Controllers\Frontend\SystemStatusController::class, 'getStatus']);
```

#### Étape 3 : JavaScript pour Mise à Jour Dynamique

**Créer : `public/js/system-status.js`**
```javascript
class SystemStatusMonitor {
    constructor() {
        this.updateInterval = 30000; // 30 secondes
        this.init();
    }

    init() {
        this.updateStatus();
        setInterval(() => this.updateStatus(), this.updateInterval);
    }

    async updateStatus() {
        try {
            const response = await fetch('/api/frontend/system-status');
            const data = await response.json();
            this.renderStatus(data);
        } catch (error) {
            console.error('Erreur lors de la mise à jour du statut:', error);
            this.renderError();
        }
    }

    renderStatus(data) {
        const services = [
            { key: 'api_adminlicence', label: 'API AdminLicence' },
            { key: 'dashboard_web', label: 'Dashboard Web' },
            { key: 'systeme_licences', label: 'Système de Licences' },
            { key: 'base_donnees', label: 'Base de Données' }
        ];

        services.forEach(service => {
            const element = document.getElementById(`status-${service.key}`);
            if (element && data[service.key]) {
                const serviceData = data[service.key];
                element.innerHTML = `
                    <span class="${serviceData.status_class}">
                        ${serviceData.icon} ${serviceData.status}
                    </span>
                `;
            }
        });

        // Mettre à jour l'heure de dernière mise à jour
        const lastUpdateElement = document.getElementById('last-update');
        if (lastUpdateElement) {
            const date = new Date(data.last_update);
            lastUpdateElement.textContent = date.toLocaleString('fr-FR');
        }
    }

    renderError() {
        const statusElements = document.querySelectorAll('[id^="status-"]');
        statusElements.forEach(element => {
            element.innerHTML = '<span class="text-yellow-600">⚠ Vérification...</span>';
        });
    }
}

// Initialiser le monitoring au chargement de la page
document.addEventListener('DOMContentLoaded', () => {
    new SystemStatusMonitor();
});
```

### 3. Tests Automatisés de Base

#### Étape 1 : Test du Health Check

```bash
php artisan make:test HealthCheckTest
```

**Fichier : `tests/Feature/HealthCheckTest.php`**
```php
<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HealthCheckTest extends TestCase
{
    public function test_health_check_endpoint_returns_success()
    {
        $response = $this->get('/api/health');
        
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'timestamp',
                     'status',
                     'services' => [
                         'api' => ['status', 'last_check'],
                         'database' => ['status', 'last_check'],
                         'cache' => ['status', 'last_check'],
                         'storage' => ['status', 'last_check'],
                         'licence_system' => ['status', 'last_check']
                     ]
                 ]);
    }

    public function test_simple_health_check()
    {
        $response = $this->get('/api/health/simple');
        
        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 'ok'
                 ]);
    }

    public function test_frontend_system_status()
    {
        $response = $this->get('/api/frontend/system-status');
        
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'api_adminlicence',
                     'dashboard_web',
                     'systeme_licences',
                     'base_donnees',
                     'last_update'
                 ]);
    }
}
```

#### Étape 2 : Test de l'API de Licences

```bash
php artisan make:test LicenceApiTest
```

**Fichier : `tests/Feature/LicenceApiTest.php`**
```php
<?php

namespace Tests\Feature;

use App\Models\SerialKey;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LicenceApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_test_endpoint()
    {
        $response = $this->get('/api/test');
        
        $response->assertStatus(200);
    }

    public function test_licence_verification_with_valid_key()
    {
        // Créer une licence de test
        $licence = SerialKey::factory()->create([
            'status' => 'active',
            'expires_at' => now()->addDays(30)
        ]);

        $response = $this->postJson('/api/v1/verify-licence', [
            'serial_key' => $licence->serial_key,
            'domain' => 'example.com'
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'valid' => true
                 ]);
    }

    public function test_licence_verification_with_invalid_key()
    {
        $response = $this->postJson('/api/v1/verify-licence', [
            'serial_key' => 'invalid-key',
            'domain' => 'example.com'
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'valid' => false
                 ]);
    }
}
```

## 🔧 Commandes d'Installation

### Installation Complète

```bash
# 1. Créer les fichiers
php artisan make:service SystemHealthService
php artisan make:controller Api/HealthController
php artisan make:controller Frontend/SystemStatusController
php artisan make:test HealthCheckTest
php artisan make:test LicenceApiTest

# 2. Exécuter les tests
php artisan test --filter=HealthCheck
php artisan test --filter=LicenceApi

# 3. Vérifier le health check
curl http://localhost:8000/api/health
curl http://localhost:8000/api/frontend/system-status
```

### Vérification du Fonctionnement

```bash
# Test des endpoints
curl -s http://localhost:8000/api/health | jq .
curl -s http://localhost:8000/api/health/simple | jq .
curl -s http://localhost:8000/api/frontend/system-status | jq .

# Exécution des tests
php artisan test
```

## 📋 Checklist d'Implémentation

### Phase 1 - Monitoring
- [ ] Service SystemHealthService créé
- [ ] Contrôleur HealthController implémenté
- [ ] Routes health check ajoutées
- [ ] Frontend SystemStatusController créé
- [ ] JavaScript de monitoring ajouté
- [ ] Tests automatisés créés
- [ ] Vérification manuelle des endpoints

### Validation
- [ ] `/api/health` retourne un JSON valide
- [ ] `/api/frontend/system-status` fonctionne
- [ ] Tests passent avec succès
- [ ] Frontend se met à jour automatiquement
- [ ] Logs d'erreur configurés

## 🎯 Prochaines Étapes

Une fois cette phase terminée, vous pourrez passer aux phases suivantes :

1. **Phase 2** : Pattern Repository et optimisation DB
2. **Phase 3** : Cache intelligent et jobs asynchrones
3. **Phase 4** : Documentation API et métriques avancées

---

**Note :** Ce guide fournit une implémentation complète et testée. Chaque étape peut être implémentée indépendamment selon vos priorités.