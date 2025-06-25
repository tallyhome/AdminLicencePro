@extends('admin.layouts.app')

@section('title', t('serial_keys.edit_key'))

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>{{ t('serial_keys.edit_key') }}</h1>
        <a href="{{ route('admin.serial-keys.show', $serialKey) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> {{ t('common.back') }}
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ t('serial_keys.information') }}</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.serial-keys.update', $serialKey) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6">
                        <!-- Clé de licence (modifiable avec avertissement) -->
                        <div class="mb-3">
                            <label for="serial_key" class="form-label">
                                {{ t('serial_keys.license_key') }}
                                <i class="fas fa-exclamation-triangle text-warning ms-1" title="Attention : Modifier la clé peut causer des problèmes de compatibilité"></i>
                            </label>
                            <input type="text" class="form-control @error('serial_key') is-invalid @enderror" id="serial_key" name="serial_key" 
                                   value="{{ old('serial_key', $serialKey->serial_key) }}" 
                                   pattern="[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}"
                                   title="Format: XXXX-XXXX-XXXX-XXXX (lettres majuscules et chiffres uniquement)"
                                   onchange="showKeyChangeWarning()">
                            <div class="form-text">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle"></i> 
                                    Format requis : XXXX-XXXX-XXXX-XXXX (ex: AG4M-NNGH-WCDL-6WGQ)
                                </small>
                            </div>
                            @error('serial_key')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Projet (maintenant modifiable) -->
                        <div class="mb-3">
                            <label for="project_id" class="form-label">
                                {{ t('serial_keys.project') }}
                                <i class="fas fa-exchange-alt text-info ms-1" title="Vous pouvez changer le projet de rattachement"></i>
                            </label>
                            <select id="project_id" name="project_id" class="form-select @error('project_id') is-invalid @enderror" required onchange="showProjectChangeWarning()">
                                @foreach($projects as $project)
                                    <option value="{{ $project->id }}" {{ old('project_id', $serialKey->project_id) == $project->id ? 'selected' : '' }}>
                                        {{ $project->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle"></i> 
                                    Changer le projet peut affecter les statistiques et permissions
                                </small>
                            </div>
                            @error('project_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Statut -->
                        <div class="mb-3">
                            <label for="status" class="form-label">{{ t('serial_keys.status') }}</label>
                            <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="active" {{ old('status', $serialKey->status) === 'active' ? 'selected' : '' }}>{{ t('serial_keys.status_active') }}</option>
                                <option value="suspended" {{ old('status', $serialKey->status) === 'suspended' ? 'selected' : '' }}>{{ t('serial_keys.status_suspended') }}</option>
                                <option value="revoked" {{ old('status', $serialKey->status) === 'revoked' ? 'selected' : '' }}>{{ t('serial_keys.status_revoked') }}</option>
                                <option value="expired" {{ old('status', $serialKey->status) === 'expired' ? 'selected' : '' }}>{{ t('serial_keys.status_expired') }}</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <!-- Domaine -->
                        <div class="mb-3">
                            <label for="domain" class="form-label">{{ t('serial_keys.domain') }}</label>
                            <input type="text" class="form-control @error('domain') is-invalid @enderror" id="domain" name="domain" value="{{ old('domain', $serialKey->domain) }}">
                            @error('domain')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Adresse IP -->
                        <div class="mb-3">
                            <label for="ip_address" class="form-label">{{ t('serial_keys.ip_address') }}</label>
                            <input type="text" class="form-control @error('ip_address') is-invalid @enderror" id="ip_address" name="ip_address" value="{{ old('ip_address', $serialKey->ip_address) }}">
                            @error('ip_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Date d'expiration -->
                        <div class="mb-3">
                            <label for="expires_at" class="form-label">{{ t('serial_keys.expiration_date') }}</label>
                            <input type="date" class="form-control @error('expires_at') is-invalid @enderror" id="expires_at" name="expires_at" value="{{ old('expires_at', $serialKey->expires_at ? $serialKey->expires_at->format('Y-m-d') : '') }}">
                            @error('expires_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> {{ t('common.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let keyChangeWarningShown = false;
let projectChangeWarningShown = false;

function showKeyChangeWarning() {
    if (!keyChangeWarningShown) {
        keyChangeWarningShown = true;
        
        const toast = document.createElement('div');
        toast.className = 'toast-container position-fixed top-0 end-0 p-3';
        toast.innerHTML = `
            <div class="toast show" role="alert">
                <div class="toast-header">
                    <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                    <strong class="me-auto">Attention</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
                </div>
                <div class="toast-body">
                    <strong>Modification de la clé de licence :</strong><br>
                    • Vérifiez l'unicité de la nouvelle clé<br>
                    • Les applications utilisant l'ancienne clé cesseront de fonctionner<br>
                    • Cette action sera enregistrée dans l'historique
                </div>
            </div>
        `;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.remove();
        }, 8000);
    }
}

function showProjectChangeWarning() {
    if (!projectChangeWarningShown) {
        projectChangeWarningShown = true;
        
        const toast = document.createElement('div');
        toast.className = 'toast-container position-fixed top-0 end-0 p-3';
        toast.innerHTML = `
            <div class="toast show" role="alert">
                <div class="toast-header">
                    <i class="fas fa-info-circle text-info me-2"></i>
                    <strong class="me-auto">Information</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
                </div>
                <div class="toast-body">
                    <strong>Changement de projet :</strong><br>
                    • Les statistiques du projet seront mises à jour<br>
                    • Vérifiez les permissions d'accès<br>
                    • L'historique de la clé sera conservé
                </div>
            </div>
        `;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.remove();
        }, 6000);
    }
}

// Validation du format de clé en temps réel
document.getElementById('serial_key').addEventListener('input', function(e) {
    let value = e.target.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
    
    // Ajouter les tirets automatiquement
    if (value.length > 4) {
        value = value.substring(0, 4) + '-' + value.substring(4);
    }
    if (value.length > 9) {
        value = value.substring(0, 9) + '-' + value.substring(9);
    }
    if (value.length > 14) {
        value = value.substring(0, 14) + '-' + value.substring(14);
    }
    if (value.length > 19) {
        value = value.substring(0, 19);
    }
    
    e.target.value = value;
    
    // Validation visuelle
    const isValid = /^[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}$/.test(value);
    if (value.length === 19) {
        if (isValid) {
            e.target.classList.remove('is-invalid');
            e.target.classList.add('is-valid');
        } else {
            e.target.classList.remove('is-valid');
            e.target.classList.add('is-invalid');
        }
    } else {
        e.target.classList.remove('is-valid', 'is-invalid');
    }
});
</script>

@endsection