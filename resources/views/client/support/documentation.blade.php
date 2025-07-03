@extends('layouts.client')

@section('title', 'Documentation')

@section('content')
<div class="container-fluid py-4">
    <!-- En-tête avec navigation -->
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('client.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('client.support.index') }}">Support</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Documentation</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- En-tête avec le titre -->
    <div class="card bg-primary text-white mb-4">
        <div class="card-body p-4">
            <h4 class="mb-2">Documentation API</h4>
            <p class="mb-0">Guide complet d'intégration et d'utilisation de l'API AdminLicence</p>
        </div>
    </div>

    <div class="row">
        <!-- Menu de navigation -->
        <div class="col-12 col-lg-3">
            <div class="card sticky-top" style="top: 20px;">
                <div class="card-header">
                    <h6 class="card-title mb-0">Sommaire</h6>
                </div>
                <div class="card-body p-3">
                    <div class="list-group list-group-flush">
                        <a href="#introduction" class="list-group-item list-group-item-action d-flex align-items-center py-3 border-0 rounded mb-1">
                            <i class="fas fa-info-circle me-3 text-primary"></i>
                            <span class="fw-medium">Introduction</span>
                        </a>
                        <a href="#authentication" class="list-group-item list-group-item-action d-flex align-items-center py-3 border-0 rounded mb-1">
                            <i class="fas fa-key me-3 text-warning"></i>
                            <span class="fw-medium">Authentification</span>
                        </a>
                        <a href="#endpoints" class="list-group-item list-group-item-action d-flex align-items-center py-3 border-0 rounded mb-1">
                            <i class="fas fa-plug me-3 text-success"></i>
                            <span class="fw-medium">Endpoints API</span>
                        </a>
                        <a href="#validation" class="list-group-item list-group-item-action d-flex align-items-center py-3 border-0 rounded mb-1">
                            <i class="fas fa-check-circle me-3 text-info"></i>
                            <span class="fw-medium">Validation de Licence</span>
                        </a>
                        <a href="#examples" class="list-group-item list-group-item-action d-flex align-items-center py-3 border-0 rounded mb-1">
                            <i class="fas fa-code me-3 text-secondary"></i>
                            <span class="fw-medium">Exemples de Code</span>
                        </a>
                        <a href="#errors" class="list-group-item list-group-item-action d-flex align-items-center py-3 border-0 rounded mb-1">
                            <i class="fas fa-exclamation-triangle me-3 text-danger"></i>
                            <span class="fw-medium">Codes d'Erreur</span>
                        </a>
                        <a href="#sdk" class="list-group-item list-group-item-action d-flex align-items-center py-3 border-0 rounded mb-1">
                            <i class="fas fa-download me-3 text-primary"></i>
                            <span class="fw-medium">SDKs Disponibles</span>
                        </a>
                        <a href="#webhook" class="list-group-item list-group-item-action d-flex align-items-center py-3 border-0 rounded mb-1">
                            <i class="fas fa-webhook me-3 text-info"></i>
                            <span class="fw-medium">Webhooks</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contenu principal -->
        <div class="col-12 col-lg-9">
            <!-- Introduction -->
            <section id="introduction" class="mb-5">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Introduction</h5>
                    </div>
                    <div class="card-body">
                        <p>L'API AdminLicence vous permet d'intégrer facilement la validation de licences dans vos applications. Cette documentation vous guide à travers l'utilisation de l'API pour :</p>
                        <ul>
                            <li>Valider des licences en temps réel</li>
                            <li>Gérer les activations et désactivations</li>
                            <li>Suivre l'utilisation des licences</li>
                            <li>Recevoir des notifications via webhooks</li>
                        </ul>
                        
                        <h6>URL de Base</h6>
                        <pre><code>{{ url('/api/v1') }}</code></pre>
                        
                        <h6>Format de Réponse</h6>
                        <p>Toutes les réponses de l'API sont au format JSON avec la structure suivante :</p>
                        <pre><code>{
  "success": true|false,
  "data": {...},
  "message": "Message descriptif",
  "timestamp": "2024-01-01T12:00:00Z"
}</code></pre>
                    </div>
                </div>
            </section>

            <!-- Authentification -->
            <section id="authentication" class="mb-5">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Authentification</h5>
                    </div>
                    <div class="card-body">
                        <p>L'API utilise des clés API pour l'authentification. Vous devez inclure votre clé API dans chaque requête.</p>
                        
                        <h6>Obtenir une Clé API</h6>
                        <ol>
                            <li>Connectez-vous à votre espace client</li>
                            <li>Allez dans <strong>Paramètres > Clés API</strong></li>
                            <li>Cliquez sur <strong>"Nouvelle Clé API"</strong></li>
                            <li>Copiez votre clé API générée</li>
                        </ol>
                        
                        <h6>Utilisation</h6>
                        <p>Incluez votre clé API dans l'en-tête <code>Authorization</code> ou dans le corps de la requête :</p>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Via En-tête HTTP</h6>
                                <pre><code>Authorization: Bearer VOTRE_CLE_API</code></pre>
                            </div>
                            <div class="col-md-6">
                                <h6>Via Corps de Requête</h6>
                                <pre><code>{
  "api_key": "VOTRE_CLE_API",
  ...
}</code></pre>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Endpoints API -->
            <section id="endpoints" class="mb-5">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Endpoints API</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Méthode</th>
                                        <th>Endpoint</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><span class="badge bg-success">POST</span></td>
                                        <td><code>/api/v1/validate</code></td>
                                        <td>Valider une licence</td>
                                    </tr>
                                    <tr>
                                        <td><span class="badge bg-primary">GET</span></td>
                                        <td><code>/api/v1/license/{key}</code></td>
                                        <td>Obtenir les détails d'une licence</td>
                                    </tr>
                                    <tr>
                                        <td><span class="badge bg-warning">PUT</span></td>
                                        <td><code>/api/v1/activate</code></td>
                                        <td>Activer une licence</td>
                                    </tr>
                                    <tr>
                                        <td><span class="badge bg-danger">DELETE</span></td>
                                        <td><code>/api/v1/deactivate</code></td>
                                        <td>Désactiver une licence</td>
                                    </tr>
                                    <tr>
                                        <td><span class="badge bg-primary">GET</span></td>
                                        <td><code>/api/v1/status</code></td>
                                        <td>Vérifier le statut de l'API</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Validation de Licence -->
            <section id="validation" class="mb-5">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Validation de Licence</h5>
                    </div>
                    <div class="card-body">
                        <p>L'endpoint principal pour valider une licence :</p>
                        
                        <h6>Requête</h6>
                        <pre><code>POST /api/v1/validate
Content-Type: application/json

{
  "api_key": "votre_cle_api",
  "license_key": "XXXX-XXXX-XXXX-XXXX",
  "domain": "example.com",
  "ip": "192.168.1.1",
  "user_agent": "Mon Application v1.0"
}</code></pre>

                        <h6>Paramètres</h6>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Paramètre</th>
                                        <th>Type</th>
                                        <th>Requis</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><code>api_key</code></td>
                                        <td>string</td>
                                        <td>✓</td>
                                        <td>Votre clé API</td>
                                    </tr>
                                    <tr>
                                        <td><code>license_key</code></td>
                                        <td>string</td>
                                        <td>✓</td>
                                        <td>Clé de licence à valider</td>
                                    </tr>
                                    <tr>
                                        <td><code>domain</code></td>
                                        <td>string</td>
                                        <td>-</td>
                                        <td>Domaine d'utilisation</td>
                                    </tr>
                                    <tr>
                                        <td><code>ip</code></td>
                                        <td>string</td>
                                        <td>-</td>
                                        <td>Adresse IP du client</td>
                                    </tr>
                                    <tr>
                                        <td><code>user_agent</code></td>
                                        <td>string</td>
                                        <td>-</td>
                                        <td>Identification de l'application</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <h6>Réponse Succès</h6>
                        <pre><code>{
  "success": true,
  "data": {
    "license": {
      "key": "XXXX-XXXX-XXXX-XXXX",
      "status": "active",
      "expires_at": "2024-12-31T23:59:59Z",
      "activations_used": 1,
      "activations_max": 5,
      "project": {
        "name": "Mon Projet",
        "version": "1.0"
      }
    },
    "validation": {
      "valid": true,
      "reason": "License is valid and active"
    }
  },
  "message": "License validated successfully",
  "timestamp": "2024-01-01T12:00:00Z"
}</code></pre>

                        <h6>Réponse Erreur</h6>
                        <pre><code>{
  "success": false,
  "data": null,
  "message": "License not found or expired",
  "error_code": "LICENSE_NOT_FOUND",
  "timestamp": "2024-01-01T12:00:00Z"
}</code></pre>
                    </div>
                </div>
            </section>

            <!-- Exemples de Code -->
            <section id="examples" class="mb-5">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Exemples de Code</h5>
                    </div>
                    <div class="card-body">
                        <!-- Onglets pour les langages -->
                        <ul class="nav nav-tabs" id="codeExamples" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="php-tab" data-bs-toggle="tab" data-bs-target="#php" type="button">PHP</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="javascript-tab" data-bs-toggle="tab" data-bs-target="#javascript" type="button">JavaScript</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="python-tab" data-bs-toggle="tab" data-bs-target="#python" type="button">Python</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="curl-tab" data-bs-toggle="tab" data-bs-target="#curl" type="button">cURL</button>
                            </li>
                        </ul>
                        
                        <div class="tab-content" id="codeExamplesContent">
                            <!-- PHP -->
                            <div class="tab-pane fade show active" id="php" role="tabpanel">
                                <pre><code>&lt;?php
$api_key = 'votre_cle_api';
$license_key = 'XXXX-XXXX-XXXX-XXXX';
$domain = 'example.com';

$data = [
    'api_key' => $api_key,
    'license_key' => $license_key,
    'domain' => $domain,
    'ip' => $_SERVER['REMOTE_ADDR'] ?? '',
    'user_agent' => 'Mon App v1.0'
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, '{{ url('/api/v1/validate') }}');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($http_code === 200) {
    $result = json_decode($response, true);
    if ($result['success'] && $result['data']['validation']['valid']) {
        echo "Licence valide !";
    } else {
        echo "Licence invalide : " . $result['message'];
    }
} else {
    echo "Erreur API : " . $http_code;
}
?&gt;</code></pre>
                            </div>
                            
                            <!-- JavaScript -->
                            <div class="tab-pane fade" id="javascript" role="tabpanel">
                                <pre><code>async function validateLicense(apiKey, licenseKey, domain) {
    const data = {
        api_key: apiKey,
        license_key: licenseKey,
        domain: domain,
        user_agent: 'Mon App v1.0'
    };

    try {
        const response = await fetch('{{ url('/api/v1/validate') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        });

        const result = await response.json();

        if (response.ok && result.success && result.data.validation.valid) {
            console.log('Licence valide !');
            return true;
        } else {
            console.error('Licence invalide :', result.message);
            return false;
        }
    } catch (error) {
        console.error('Erreur API :', error);
        return false;
    }
}

// Utilisation
validateLicense('votre_cle_api', 'XXXX-XXXX-XXXX-XXXX', 'example.com');</code></pre>
                            </div>
                            
                            <!-- Python -->
                            <div class="tab-pane fade" id="python" role="tabpanel">
                                <pre><code>import requests
import json

def validate_license(api_key, license_key, domain):
    url = '{{ url('/api/v1/validate') }}'
    
    data = {
        'api_key': api_key,
        'license_key': license_key,
        'domain': domain,
        'user_agent': 'Mon App v1.0'
    }
    
    headers = {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    }
    
    try:
        response = requests.post(url, json=data, headers=headers)
        result = response.json()
        
        if response.status_code == 200 and result['success'] and result['data']['validation']['valid']:
            print("Licence valide !")
            return True
        else:
            print(f"Licence invalide : {result['message']}")
            return False
            
    except requests.RequestException as e:
        print(f"Erreur API : {e}")
        return False

# Utilisation
validate_license('votre_cle_api', 'XXXX-XXXX-XXXX-XXXX', 'example.com')</code></pre>
                            </div>
                            
                            <!-- cURL -->
                            <div class="tab-pane fade" id="curl" role="tabpanel">
                                <pre><code>curl -X POST {{ url('/api/v1/validate') }} \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "api_key": "votre_cle_api",
    "license_key": "XXXX-XXXX-XXXX-XXXX",
    "domain": "example.com",
    "user_agent": "Mon App v1.0"
  }'</code></pre>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Codes d'Erreur -->
            <section id="errors" class="mb-5">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Codes d'Erreur</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Code HTTP</th>
                                        <th>Code Erreur</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>400</td>
                                        <td>INVALID_REQUEST</td>
                                        <td>Paramètres manquants ou invalides</td>
                                    </tr>
                                    <tr>
                                        <td>401</td>
                                        <td>INVALID_API_KEY</td>
                                        <td>Clé API invalide ou manquante</td>
                                    </tr>
                                    <tr>
                                        <td>403</td>
                                        <td>LICENSE_EXPIRED</td>
                                        <td>Licence expirée</td>
                                    </tr>
                                    <tr>
                                        <td>403</td>
                                        <td>LICENSE_INACTIVE</td>
                                        <td>Licence désactivée</td>
                                    </tr>
                                    <tr>
                                        <td>404</td>
                                        <td>LICENSE_NOT_FOUND</td>
                                        <td>Licence introuvable</td>
                                    </tr>
                                    <tr>
                                        <td>429</td>
                                        <td>ACTIVATION_LIMIT_REACHED</td>
                                        <td>Limite d'activations atteinte</td>
                                    </tr>
                                    <tr>
                                        <td>429</td>
                                        <td>RATE_LIMIT_EXCEEDED</td>
                                        <td>Limite de requêtes dépassée</td>
                                    </tr>
                                    <tr>
                                        <td>500</td>
                                        <td>INTERNAL_ERROR</td>
                                        <td>Erreur interne du serveur</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>

            <!-- SDKs -->
            <section id="sdk" class="mb-5">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">SDKs Disponibles</h5>
                    </div>
                    <div class="card-body">
                        <p>Nous proposons des SDKs officiels pour faciliter l'intégration :</p>
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6><i class="fab fa-php me-2"></i>PHP SDK</h6>
                                        <p class="text-muted">Compatible PHP 7.4+</p>
                                        <pre><code>composer require adminlicence/php-sdk</code></pre>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6><i class="fab fa-js me-2"></i>JavaScript SDK</h6>
                                        <p class="text-muted">Compatible Node.js et navigateurs</p>
                                        <pre><code>npm install adminlicence-js</code></pre>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6><i class="fab fa-python me-2"></i>Python SDK</h6>
                                        <p class="text-muted">Compatible Python 3.6+</p>
                                        <pre><code>pip install adminlicence</code></pre>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6><i class="fab fa-java me-2"></i>Java SDK</h6>
                                        <p class="text-muted">Compatible Java 8+</p>
                                        <p class="text-muted">Bientôt disponible</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Webhooks -->
            <section id="webhook" class="mb-5">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Webhooks</h5>
                    </div>
                    <div class="card-body">
                        <p>Les webhooks vous permettent de recevoir des notifications en temps réel lors d'événements importants.</p>
                        
                        <h6>Configuration</h6>
                        <ol>
                            <li>Allez dans <strong>Paramètres > Webhooks</strong></li>
                            <li>Ajoutez l'URL de votre endpoint</li>
                            <li>Sélectionnez les événements à écouter</li>
                            <li>Configurez la signature de sécurité</li>
                        </ol>
                        
                        <h6>Événements Disponibles</h6>
                        <ul>
                            <li><code>license.activated</code> - Licence activée</li>
                            <li><code>license.deactivated</code> - Licence désactivée</li>
                            <li><code>license.expired</code> - Licence expirée</li>
                            <li><code>license.limit_reached</code> - Limite d'activations atteinte</li>
                        </ul>
                        
                        <h6>Format du Webhook</h6>
                        <pre><code>{
  "event": "license.activated",
  "timestamp": "2024-01-01T12:00:00Z",
  "data": {
    "license_key": "XXXX-XXXX-XXXX-XXXX",
    "project_id": 123,
    "domain": "example.com",
    "ip": "192.168.1.1"
  },
  "signature": "sha256=..."
}</code></pre>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Style personnalisé pour le sommaire */
.list-group-item {
    color: #333 !important;
    text-decoration: none !important;
    transition: all 0.3s ease;
    background-color: transparent !important;
}

.list-group-item:hover {
    background-color: #f8f9fa !important;
    color: #0d6efd !important;
    transform: translateX(5px);
}

.list-group-item.active {
    background-color: #0d6efd !important;
    color: white !important;
    border-color: #0d6efd !important;
}

.list-group-item.active i {
    color: white !important;
}

.list-group-item.active span {
    color: white !important;
}

/* Améliorer la visibilité du sticky menu */
.sticky-top {
    z-index: 1020;
}

/* Style pour les icônes */
.list-group-item i {
    width: 20px;
    text-align: center;
}

/* Assurer que le texte est toujours visible */
.list-group-item span {
    color: #333;
    font-size: 14px;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Navigation fluide
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                // Supprimer la classe active de tous les liens
                document.querySelectorAll('.list-group-item').forEach(link => {
                    link.classList.remove('active');
                });
                // Ajouter la classe active au lien cliqué
                this.classList.add('active');
                
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // Mise à jour de la navigation active lors du scroll
    const sections = document.querySelectorAll('section[id]');
    const navLinks = document.querySelectorAll('.list-group-item');
    
    window.addEventListener('scroll', function() {
        let current = '';
        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.clientHeight;
            if (window.scrollY >= (sectionTop - 200)) {
                current = section.getAttribute('id');
            }
        });
        
        navLinks.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href') === '#' + current) {
                link.classList.add('active');
            }
        });
    });
    
    // Activer le premier lien par défaut
    const firstLink = document.querySelector('.list-group-item[href="#introduction"]');
    if (firstLink) {
        firstLink.classList.add('active');
    }
});
</script>
@endpush
