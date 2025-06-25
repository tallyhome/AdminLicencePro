@extends('admin.layouts.app')

@section('title', t('serial_keys.create_key'))

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>{{ t('serial_keys.create_key') }}</h1>
        <a href="{{ route('admin.serial-keys.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> {{ t('common.back') }}
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ t('serial_keys.create_form') }}</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.serial-keys.store') }}" method="POST">
                @csrf
                
                <div class="row">
                    <div class="col-md-6">
                        <!-- Projet -->
                        <div class="mb-3">
                            <label for="project_id" class="form-label">{{ t('serial_keys.project') }}</label>
                            <select id="project_id" name="project_id" class="form-select @error('project_id') is-invalid @enderror" required>
                                <option value="">{{ t('serial_keys.select_project') }}</option>
                                @foreach($projects as $project)
                                    <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                        {{ $project->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('project_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Type de licence -->
                        <div class="mb-3">
                            <label for="licence_type" class="form-label">
                                <i class="fas fa-key"></i> Type de licence
                                <span class="text-muted">*</span>
                            </label>
                            <select id="licence_type" name="licence_type" class="form-select @error('licence_type') is-invalid @enderror" required>
                                <option value="">Sélectionner le type</option>
                                <option value="single" {{ old('licence_type', 'single') == 'single' ? 'selected' : '' }}>
                                    <i class="fas fa-user"></i> Single - 1 licence = 1 domaine
                                </option>
                                <option value="multi" {{ old('licence_type') == 'multi' ? 'selected' : '' }}>
                                    <i class="fas fa-users"></i> Multi - 1 licence = X domaines
                                </option>
                            </select>
                            @error('licence_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <small>
                                    <strong>Single:</strong> Parfait pour développeurs individuels<br>
                                    <strong>Multi:</strong> Idéal pour agences et grandes entreprises
                                </small>
                            </div>
                        </div>

                        <!-- Nombre maximum de comptes (pour multi uniquement) -->
                        <div class="mb-3" id="max_accounts_field" style="display: none;">
                            <label for="max_accounts" class="form-label">
                                <i class="fas fa-hashtag"></i> Nombre maximum de comptes
                                <span class="text-muted">*</span>
                            </label>
                            <input type="number" id="max_accounts" name="max_accounts" 
                                   class="form-control @error('max_accounts') is-invalid @enderror" 
                                   value="{{ old('max_accounts', 10) }}" min="1" max="1000">
                            @error('max_accounts')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <small>Nombre de domaines/logiciels pouvant utiliser cette licence</small>
                            </div>
                        </div>

                        <!-- Quantité -->
                        <div class="mb-3">
                            <label for="quantity" class="form-label">{{ t('serial_keys.quantity') }}</label>
                            <input type="number" id="quantity" name="quantity" class="form-control @error('quantity') is-invalid @enderror" 
                                   value="{{ old('quantity', 1) }}" min="1" max="100000" required>
                            @error('quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <!-- Information sur le type de licence -->
                        <div class="alert alert-info" id="licence_info">
                            <h6><i class="fas fa-info-circle"></i> Information</h6>
                            <p>Sélectionnez d'abord le type de licence pour voir les options appropriées.</p>
                        </div>

                        <!-- Champs pour licence single -->
                        <div id="single_fields" style="display: none;">
                            <!-- Domaine -->
                            <div class="mb-3">
                                <label for="domain" class="form-label">
                                    <i class="fas fa-globe"></i> {{ t('serial_keys.domain_optional') }}
                                </label>
                                <input type="text" id="domain" name="domain" class="form-control @error('domain') is-invalid @enderror" 
                                       value="{{ old('domain') }}" placeholder="exemple.com">
                                @error('domain')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    <small>Pré-assigner la licence à un domaine spécifique (optionnel)</small>
                                </div>
                            </div>

                            <!-- Adresse IP -->
                            <div class="mb-3">
                                <label for="ip_address" class="form-label">
                                    <i class="fas fa-network-wired"></i> {{ t('serial_keys.ip_address_optional') }}
                                </label>
                                <input type="text" id="ip_address" name="ip_address" class="form-control @error('ip_address') is-invalid @enderror" 
                                       value="{{ old('ip_address') }}" placeholder="192.168.1.1">
                                @error('ip_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Date d'expiration -->
                        <div class="mb-3">
                            <label for="expires_at" class="form-label">
                                <i class="fas fa-calendar-alt"></i> {{ t('serial_keys.expiration_date_optional') }}
                            </label>
                            <input type="date" id="expires_at" name="expires_at" class="form-control @error('expires_at') is-invalid @enderror" 
                                   value="{{ old('expires_at') }}">
                            @error('expires_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> {{ t('serial_keys.create_keys') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const licenceTypeSelect = document.getElementById('licence_type');
    const maxAccountsField = document.getElementById('max_accounts_field');
    const singleFields = document.getElementById('single_fields');
    const licenceInfo = document.getElementById('licence_info');
    const maxAccountsInput = document.getElementById('max_accounts');

    function updateFields() {
        const licenceType = licenceTypeSelect.value;
        
        if (licenceType === 'single') {
            maxAccountsField.style.display = 'none';
            singleFields.style.display = 'block';
            maxAccountsInput.removeAttribute('required');
            
            licenceInfo.innerHTML = `
                <h6><i class="fas fa-user text-primary"></i> Licence Single</h6>
                <ul class="mb-0">
                    <li><strong>1 licence = 1 domaine</strong></li>
                    <li>Parfait pour développeurs individuels</li>
                    <li>Sécurité maximale avec validation stricte</li>
                    <li>Prix recommandé : <span class="badge bg-success">29€/mois</span></li>
                </ul>
            `;
        } else if (licenceType === 'multi') {
            maxAccountsField.style.display = 'block';
            singleFields.style.display = 'none';
            maxAccountsInput.setAttribute('required', 'required');
            
            licenceInfo.innerHTML = `
                <h6><i class="fas fa-users text-warning"></i> Licence Multi</h6>
                <ul class="mb-0">
                    <li><strong>1 licence = X domaines</strong></li>
                    <li>Idéal pour agences et grandes entreprises</li>
                    <li>Gestion dynamique des slots</li>
                    <li>Prix recommandé : <span class="badge bg-warning">149€/mois (10 slots)</span></li>
                </ul>
            `;
        } else {
            maxAccountsField.style.display = 'none';
            singleFields.style.display = 'none';
            maxAccountsInput.removeAttribute('required');
            
            licenceInfo.innerHTML = `
                <h6><i class="fas fa-info-circle"></i> Information</h6>
                <p>Sélectionnez d'abord le type de licence pour voir les options appropriées.</p>
            `;
        }
    }

    licenceTypeSelect.addEventListener('change', updateFields);
    updateFields(); // Appel initial
});
</script>
@endsection