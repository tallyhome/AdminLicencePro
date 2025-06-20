@extends('admin.layouts.app')

@section('title', t('settings_license.license.title'))

@php
// Initialisation de toutes les variables utilisées dans la vue pour éviter les erreurs
$envExists = $envExists ?? file_exists(base_path('.env'));
$licenseKey = $licenseKey ?? env('INSTALLATION_LICENSE_KEY', '');
$licenseValid = $licenseValid ?? false;
$isValid = $licenseValid; // Alias pour compatibilité
$lastCheck = $lastCheck ?? null;
$licenseDetails = $licenseDetails ?? [];
$checkFrequency = $checkFrequency ?? \App\Models\Setting::get('license_check_frequency', 5);
$blockNavigation = $blockNavigation ?? (!$licenseValid);

// Variable pour l'expiration
$expiryDate = null;
if (session('license_details') && isset(session('license_details')['expires_at'])) {
    $expiryDate = \Carbon\Carbon::parse(session('license_details')['expires_at']);
} elseif (is_array($licenseDetails) && isset($licenseDetails['expires_at'])) {
    $expiryDate = \Carbon\Carbon::parse($licenseDetails['expires_at']);
} elseif (is_object($licenseDetails) && isset($licenseDetails->expires_at)) {
    $expiryDate = \Carbon\Carbon::parse($licenseDetails->expires_at);
}

// Autres variables utiles
$status = '';
if (session('license_details')) {
    $details = session('license_details');
    $status = $details['status'] ?? (session('license_status') ?? ($licenseValid ? 'active' : 'invalid'));
} else {
    // Si pas de détails en session, utiliser le statut en session ou statut par défaut
    $status = session('license_status') ?? ($licenseValid ? 'active' : 'invalid');
}
@endphp

@section('content')
<div class="container-fluid p-0">
    <h1 class="h3 mb-3">{{ t('settings_license.license.title') }}</h1>

    <div class="row">
        <div class="col-12">
            {{-- Les alertes de session sont gérées par le layout principal app.blade.php --}}
            {{-- Suppression des alertes dupliquées pour éviter l'affichage double --}}

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ t('settings_license.license.info_title') }}</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6>{{ t('settings_license.license.installation_key') }}</h6>
                            <div class="d-flex align-items-center mb-3">
                                <div class="input-group">
                                    <input type="text" class="form-control" value="{{ $licenseKey ?? 'Non configurée' }}" readonly>
                                    <button class="btn btn-outline-secondary" type="button" id="copyLicenseKey" data-bs-toggle="tooltip" title="{{ t('settings_license.license.copy_key') }}">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <h6>{{ t('settings_license.license.status') }}</h6>
                            <div class="mb-3">
                                @php
                                    // Utiliser la même logique que la partie droite pour la cohérence
                                    $licenseValid = $licenseValid ?? false;
                                    $currentStatus = session('license_status') ?? \App\Models\Setting::get('license_status', 'invalid');
                                @endphp
                                @if($licenseValid && $currentStatus === 'active')
                                    <span class="badge bg-success">{{ t('settings_license.license.valid') }}</span>
                                @else
                                    <span class="badge bg-danger">{{ t('settings_license.license.invalid') }}</span>
                                @endif
                            </div>
                            
                            @php
                                $expiryDate = null;
                                if (session('license_details') && isset(session('license_details')['expires_at'])) {
                                    $expiryDate = \Carbon\Carbon::parse(session('license_details')['expires_at']);
                                } elseif (isset($licenseDetails['expires_at'])) {
                                    $expiryDate = \Carbon\Carbon::parse($licenseDetails['expires_at']);
                                } elseif (isset($licenseDetails->expires_at)) {
                                    $expiryDate = \Carbon\Carbon::parse($licenseDetails->expires_at);
                                }
                            @endphp
                            
                            @if($expiryDate)
                                <h6>{{ t('settings_license.license.expiry_date') }}</h6>
                                <div class="mb-3">
                                    <span class="{{ $expiryDate->isPast() ? 'text-danger' : '' }}">
                                        {{ $expiryDate->format('d/m/Y') }}
                                    </span>
                                </div>
                            @endif
                            
                            <h6>{{ t('settings_license.license.last_check') }}</h6>
                            <div class="mb-3">
                                {{ $lastCheck ? \Carbon\Carbon::parse($lastCheck)->format('d/m/Y H:i:s') : t('settings_license.license.never') }}
                            </div>
                            
                            <div class="mt-4">
                                <a href="{{ route('admin.settings.license.force-check') }}" class="btn btn-primary">
                                    <i class="fas fa-sync-alt"></i> {{ t('settings_license.license.check_now') }}
                                </a>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <h6>{{ t('settings_license.license.details') }}</h6>
                                <table class="table table-sm">
                                    <tbody>
                                        @if(session('license_details'))
                                            <tr>
                                            <th>{{ t('settings_license.license.status_label') }}</th>
                                            <td>
                                                @php
                                                    $licenseDetails = session('license_details', []);
                                                    // Utiliser les variables passées par le contrôleur pour la cohérence
                                                    $status = $licenseStatus ?? session('license_status') ?? \App\Models\Setting::get('license_status', 'invalid');
                                                    $statusText = $status;
                                                    $currentLicenseValid = $licenseValid ?? false;
                                                    $statusClass = '';
                                                    
                                                    switch($status) {
                                                        case 'active':
                                                            $statusClass = 'text-success';
                                                            $statusText = 'Active';
                                                            break;
                                                        case 'suspended':
                                                            $statusClass = 'text-warning';
                                                            $statusText = 'Suspendue';
                                                            break;
                                                        case 'revoked':
                                                            $statusClass = 'text-danger';
                                                            $statusText = 'Révoquée';
                                                            break;
                                                        default:
                                                            $statusText = ucfirst($status);
                                                    }
                                                @endphp
                                                <span class="{{ $statusClass }}">{{ $statusText }}</span>
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                            <th>{{ t('settings_license.license.key_label') }}</th>
                                            <td>{{ $licenseDetails['key'] ?? $licenseKey }}</td>
                                        </tr>

                                        <tr>
                                            <th>{{ t('settings_license.license.expiry_date_label') }}</th>
                                            <td>
                                                @php
                                                    $licenseDetails = session('license_details', []);
                                                    $expiryDate = $licenseDetails['expiry_date'] ?? null;
                                                    $hasExpiry = false;
                                                    $expiry = null;
                                                    $expired = false;
                                                    
                                                    if (!empty($expiryDate)) {
                                                        try {
                                                            $expiry = new \DateTime($expiryDate);
                                                            $now = new \DateTime();
                                                            $expired = $expiry < $now;
                                                            $hasExpiry = true;
                                                        } catch (\Exception $e) {
                                                            // Si la date n'est pas au bon format, on l'affiche telle quelle
                                                            $hasExpiry = false;
                                                        }
                                                    }
                                                @endphp
                                                
                                                @if($hasExpiry)
                                                    <span class="{{ $expired ? 'text-danger' : 'text-success' }}">
                                                        {{ $expiry->format('d/m/Y') }}
                                                        @if($expired)
                                                            <i class="fas fa-exclamation-triangle" data-bs-toggle="tooltip" title="Licence expirée"></i>
                                                        @else
                                                            <i class="fas fa-check-circle" data-bs-toggle="tooltip" title="Licence valide jusqu'à cette date"></i>
                                                        @endif
                                                    </span>
                                                @else
                                                    @if($expiryDate)
                                                        {{ $expiryDate }} <i class="fas fa-info-circle" data-bs-toggle="tooltip" title="Format de date non reconnu"></i>
                                                    @else
                                                        <span class="text-muted">Non spécifiée</span>
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                        
                                        @if(session('license_details') && isset(session('license_details')['registered_domain']) && session('license_details')['registered_domain'])
                                        <tr>
                                            <th>Domaine enregistré</th>
                                            <td>{{ session('license_details')['registered_domain'] }}</td>
                                        </tr>
                                        @endif
                                        
                                        @if(session('license_details') && isset(session('license_details')['registered_ip']) && session('license_details')['registered_ip'])
                                        <tr>
                                            <th>Adresse IP enregistrée</th>
                                            <td>{{ session('license_details')['registered_ip'] }}</td>
                                        </tr>
                                        @endif
                                        @endif
                                        
                                        @if($licenseDetails)
                                        @php
                                            // Gérer le cas où $licenseDetails peut être un objet ou un array
                                            $isObject = is_object($licenseDetails);
                                            $projectName = $isObject ? ($licenseDetails->project->name ?? 'N/A') : 'N/A';
                                            $createdAt = $isObject && $licenseDetails->created_at ? $licenseDetails->created_at->format('d/m/Y') : 'N/A';
                                            $expiresAt = $isObject && $licenseDetails->expires_at ? $licenseDetails->expires_at : (is_array($licenseDetails) ? ($licenseDetails['expires_at'] ?? null) : null);
                                            $domain = $isObject ? ($licenseDetails->domain ?? t('settings_license.license.not_defined')) : ($licenseDetails['domain'] ?? t('settings_license.license.not_defined'));
                                            $ipAddress = $isObject ? ($licenseDetails->ip_address ?? t('settings_license.license.not_defined')) : ($licenseDetails['ip_address'] ?? t('settings_license.license.not_defined'));
                                            $status = $isObject ? $licenseDetails->status : ($licenseDetails['status'] ?? 'unknown');
                                        @endphp
                                        <tr>
                                            <th>Projet</th>
                                            <td>{{ $projectName }}</td>
                                        </tr>
                                        <tr>
                                            <th>Créée le</th>
                                            <td>{{ $createdAt }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ t('settings_license.license.expires_on') }}</th>
                                            <td class="{{ $expiresAt && (is_string($expiresAt) ? strtotime($expiresAt) < time() : $expiresAt->isPast()) ? 'text-danger' : '' }}">
                                                @if($expiresAt)
                                                    {{ is_string($expiresAt) ? date('d/m/Y', strtotime($expiresAt)) : $expiresAt->format('d/m/Y') }}
                                                @else
                                                    {{ t('settings_license.license.never') }}
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>{{ t('settings_license.license.domain') }}</th>
                                            <td>{{ $domain }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ t('settings_license.license.ip_address') }}</th>
                                            <td>{{ $ipAddress }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ t('settings_license.license.status') }}</th>
                                            <td>
                                                @if($status == 'active')
                                                     <span class="badge bg-success">{{ t('settings_license.license.status_active') }}</span>
                                                @elseif($status == 'suspended')
                                                     <span class="badge bg-warning">{{ t('settings_license.license.status_suspended') }}</span>
                                                @elseif($status == 'revoked')
                                                     <span class="badge bg-danger">{{ t('settings_license.license.status_revoked') }}</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ $status }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endif

                                    @if(!session('license_details') && $licenseDetails && isset($licenseDetails->expires_at))
                                    <tr>
                                        <th>{{ t('settings_license.license.status_label') }}</th>
                                        <td>
                                            <span class="{{ $isValid ? 'text-success' : 'text-danger' }}">
                                                {{ $isValid ? t('settings_license.license.valid') : t('settings_license.license.invalid') }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>{{ t('settings_license.license.key_label') }}</th>
                                        <td>{{ $licenseKey }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ t('settings_license.license.expiry_date_label') }}</th>
                                        <td>
                                            {{ \Carbon\Carbon::parse($licenseDetails->expires_at)->format('d/m/Y') }}
                                        </td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        
                            <div class="alert alert-warning" style="display: {{ ($licenseKey || (isset($licenseDetails) && $licenseDetails)) ? 'none' : 'block' }}">
                                <i class="fas fa-exclamation-triangle"></i> {{ t('settings_license.license.no_details') }}
                            </div>
                        
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ t('settings_license.license.configuration') }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.settings.license.update') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="license_key" class="form-label">{{ t('settings_license.license.installation_key') }}</label>
                            <input type="text" class="form-control" id="license_key" name="license_key" value="{{ $licenseKey }}" placeholder="XXXX-XXXX-XXXX-XXXX">
                            <div class="form-text">
                                @if($envExists)
                                    {{ t('settings_license.license.key_saved_in_env') }}
                                @else
                                    <span class="text-warning"><i class="fas fa-exclamation-triangle"></i> {{ t('settings_license.license.env_not_exists') }}</span>
                                @endif
                            </div>
                        </div>
                        

                        
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> {{ t('settings_license.license.save_settings') }}
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ t('settings_license.license.manual_verification') }}</h5>
                </div>
                <div class="card-body">
                    <p>{{ t('settings_license.license.manual_verification_desc') }}</p>
                    <a href="{{ route('admin.settings.license.force-check') }}" class="btn btn-primary">
                        <i class="fas fa-sync-alt"></i> {{ t('settings_license.license.check_now') }}
                    </a>
                </div>
            </div>
            
            <!-- Débogage -->
            <div class="card mt-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">{{ t('settings_license.license.debug_info') }}</h5>
                    <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#debugInfo">
                        <i class="fas fa-chevron-down"></i>
                    </button>
                </div>
                <div class="card-body collapse" id="debugInfo">
                    <h6>{{ t('settings_license.license.expiry_date') }}</h6>
                    <div class="mb-3">
                        {{ t('settings_license.license.detected_value') }}: <code>{{ (string) \App\Models\Setting::get('debug_expiry_date', t('settings_license.license.not_found')) }}</code>
                    </div>
                    
                    <h6>{{ t('settings_license.license.status') }}</h6>
                    <div class="mb-3">
                        {{ t('settings_license.license.detected_value') }}: <code>{{ (string) \App\Models\Setting::get('license_status', t('settings_license.license.not_found')) }}</code>
                    </div>
                    
                    <h6>{{ t('settings_license.license.http_code') }}</h6>
                    <div class="mb-3">
                        <code>{{ (string) \App\Models\Setting::get('debug_api_http_code', 'N/A') }}</code>
                    </div>
                    
                    <h6>{{ t('settings_license.license.raw_api_response') }}</h6>
                    <div class="mb-3">
                        @php
                            $apiResponse = \App\Models\Setting::get('debug_api_response', t('settings_license.license.no_response'));
                            if (!is_string($apiResponse)) {
                                $apiResponse = json_encode($apiResponse, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) ?: t('settings_license.license.unviewable_format');
                            }
                        @endphp
                        <textarea class="form-control" rows="8" readonly>{{ $apiResponse }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Déterminer si la navigation doit être bloquée
        const blockNavigation = {{ $blockNavigation ? 'true' : 'false' }};
        
        if (blockNavigation) {
            // Uniquement bloquer si requis (licence invalide sans bypass)
            console.log('Navigation restreinte - Licence invalide');
            
            // Définir la fonction de gestion pour bloquer la navigation
            window.licenseNavigationHandler = function(event) {
                // Permettre la navigation vers les pages d'authentification et de licence
                const href = event.target.href || event.target.closest('a')?.href;
                if (href && (href.includes('/admin/login') || href.includes('/admin/logout') || href.includes('/admin/settings/license'))) {
                    return true; // Permettre la navigation
                }
                
                // Bloquer les autres navigations
                event.preventDefault();
                alert('{{ t("settings_license.license.blocked_navigation") }}');
                return false;
            };
            
            // Ajouter des gestionnaires d'événements pour tous les liens
            document.querySelectorAll('a').forEach(function(link) {
                link.addEventListener('click', window.licenseNavigationHandler);
            });
            
            // Bloquer la navigation par URL directe
            window.addEventListener('beforeunload', function(e) {
                const currentPath = window.location.pathname;
                const licenseRoute = '{{ route("admin.settings.license") }}';
                
                // Autoriser la navigation vers/depuis la page de licence
                if (currentPath === licenseRoute) {
                    return;
                }
                
                e.preventDefault();
                e.returnValue = '{{ t("settings_license.license.blocked_navigation") }}';
                return '{{ t("settings_license.license.blocked_navigation") }}';
            });
        } else {
            // Désactiver tout éventuel blocage existant
            if (window.disableLicenseCheck) {
                window.disableLicenseCheck();
            }
            
            // Supprimer les écouteurs d'événements qui bloquent la navigation
            if (window.licenseNavigationHandler) {
                document.querySelectorAll('a').forEach(function(link) {
                    link.removeEventListener('click', window.licenseNavigationHandler);
                });
                window.removeEventListener('beforeunload', window.licenseNavigationHandler);
                delete window.licenseNavigationHandler;
            }
            
            // Permettre la navigation libre
            console.log('Navigation libre - Bypass activé ou licence valide');
        }

        // Les alertes de session sont déjà gérées par le script précédent
        // Pas besoin d'auto-dismiss supplémentaire pour éviter les doublons
        
        // Initialiser les tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
        
        // Fonction de copie de la clé de licence
        document.getElementById('copyLicenseKey').addEventListener('click', function() {
            var licenseInput = this.parentElement.querySelector('input');
            licenseInput.select();
            document.execCommand('copy');
            
            // Changer temporairement le tooltip
            var tooltip = bootstrap.Tooltip.getInstance(this);
            var originalTitle = this.getAttribute('data-bs-original-title');
            tooltip.hide();
            this.setAttribute('data-bs-original-title', '{{ t('settings_license.license.copied') }}');
            tooltip.show();
            
            // Restaurer le titre original après 1.5 secondes
            setTimeout(function() {
                tooltip.hide();
                this.setAttribute('data-bs-original-title', originalTitle);
            }.bind(this), 1500);
        });
    });
</script>
@endsection

@section('styles')
<style>
    .license-info-item {
        margin-bottom: 1rem;
    }
    .license-info-item h5 {
        font-size: 0.9rem;
        font-weight: bold;
        color: #4e73df;
        margin-bottom: 0.5rem;
    }
    .license-info-item p {
        margin-bottom: 0.25rem;
    }
</style>
@endsection
