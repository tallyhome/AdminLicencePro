@extends('layouts.client')

@section('title', 'Détails de la Licence')

@section('content')
<div class="container-fluid py-4">
    <!-- En-tête avec navigation -->
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('client.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('client.licenses.index') }}">Licences</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $license->name ?? 'Détails' }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- En-tête avec le titre -->
    <div class="card bg-primary text-white mb-4">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-2">{{ $license->name ?? 'Licence #' . $license->id }}</h4>
                    <p class="mb-0">Projet : {{ $license->project->name ?? 'N/A' }}</p>
                </div>
                <div class="text-end">
                    <span class="badge badge-lg 
                        @if($license->status === 'active') bg-success
                        @elseif($license->status === 'inactive') bg-secondary
                        @elseif($license->status === 'expired') bg-danger
                        @else bg-warning
                        @endif">
                        {{ ucfirst($license->status ?? 'unknown') }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques de la licence -->
    <div class="row g-4 mb-4">
        <!-- Activations -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card-stats">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="stat-icon bg-primary">
                            <i class="fas fa-play-circle"></i>
                        </div>
                        <div class="text-end">
                            <h3 class="mb-1">{{ $stats['activations_used'] }}/{{ $stats['activations_max'] }}</h3>
                            <p class="text-muted mb-0">Activations</p>
                        </div>
                    </div>
                    <h6 class="mb-2">Activations Utilisées</h6>
                    <div class="progress" style="height: 4px;">
                        <div class="progress-bar bg-primary" 
                             style="width: {{ $stats['activations_max'] > 0 ? ($stats['activations_used'] / $stats['activations_max']) * 100 : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Comptes -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card-stats">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="stat-icon bg-success">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="text-end">
                            <h3 class="mb-1">{{ $stats['accounts_used'] }}/{{ $stats['accounts_max'] }}</h3>
                            <p class="text-muted mb-0">Comptes</p>
                        </div>
                    </div>
                    <h6 class="mb-2">Comptes Utilisés</h6>
                    <div class="progress" style="height: 4px;">
                        <div class="progress-bar bg-success" 
                             style="width: {{ $stats['accounts_max'] > 0 ? ($stats['accounts_used'] / $stats['accounts_max']) * 100 : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Expiration -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card-stats">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="stat-icon {{ $stats['days_until_expiry'] !== null && $stats['days_until_expiry'] <= 30 ? 'bg-warning' : 'bg-info' }}">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div class="text-end">
                            <h3 class="mb-1">
                                @if($stats['days_until_expiry'] !== null)
                                    {{ $stats['days_until_expiry'] > 0 ? $stats['days_until_expiry'] : 'Expirée' }}
                                @else
                                    ∞
                                @endif
                            </h3>
                            <p class="text-muted mb-0">Jours restants</p>
                        </div>
                    </div>
                    <h6 class="mb-2">Expiration</h6>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-clock {{ $stats['days_until_expiry'] !== null && $stats['days_until_expiry'] <= 30 ? 'text-warning' : 'text-info' }} me-1"></i>
                        <small class="{{ $stats['days_until_expiry'] !== null && $stats['days_until_expiry'] <= 30 ? 'text-warning' : 'text-info' }}">
                            @if($license->expires_at)
                                {{ $license->expires_at->format('d/m/Y') }}
                            @else
                                Jamais
                            @endif
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Type de licence -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card-stats">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="stat-icon bg-secondary">
                            <i class="fas fa-tag"></i>
                        </div>
                        <div class="text-end">
                            <h3 class="mb-1">{{ ucfirst($license->licence_type ?? 'Standard') }}</h3>
                            <p class="text-muted mb-0">Type</p>
                        </div>
                    </div>
                    <h6 class="mb-2">Type de Licence</h6>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-info-circle text-secondary me-1"></i>
                        <small class="text-secondary">Configuration</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Détails de la licence -->
    <div class="row g-4">
        <!-- Informations principales -->
        <div class="col-12 col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informations de la Licence</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-bold">Clé de Licence</label>
                            <div class="input-group">
                                <input type="text" class="form-control" value="{{ $license->serial_key }}" readonly id="licenseKey">
                                <button class="btn btn-outline-secondary" type="button" onclick="copyToClipboard('licenseKey')">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nom</label>
                            <input type="text" class="form-control" value="{{ $license->name ?? 'N/A' }}" readonly>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Projet</label>
                            <input type="text" class="form-control" value="{{ $license->project->name ?? 'N/A' }}" readonly>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Type de Licence</label>
                            <input type="text" class="form-control" value="{{ $license->licence_type ?? 'Standard' }}" readonly>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Statut</label>
                            <input type="text" class="form-control" value="{{ ucfirst($license->status ?? 'unknown') }}" readonly>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Activations Max</label>
                            <input type="text" class="form-control" value="{{ $license->max_activations ?? 'Illimité' }}" readonly>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Comptes Max</label>
                            <input type="text" class="form-control" value="{{ $license->max_accounts ?? 'Illimité' }}" readonly>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Date de Création</label>
                            <input type="text" class="form-control" value="{{ $license->created_at->format('d/m/Y H:i') }}" readonly>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Date d'Expiration</label>
                            <input type="text" class="form-control" value="{{ $license->expires_at ? $license->expires_at->format('d/m/Y H:i') : 'Jamais' }}" readonly>
                        </div>
                        
                        @if($license->last_activation_at)
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Dernière Activation</label>
                            <input type="text" class="form-control" value="{{ $license->last_activation_at->format('d/m/Y H:i') }}" readonly>
                        </div>
                        @endif
                        
                        @if($license->notes)
                        <div class="col-12">
                            <label class="form-label fw-bold">Notes</label>
                            <textarea class="form-control" rows="3" readonly>{{ $license->notes }}</textarea>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions et comptes -->
        <div class="col-12 col-lg-4">
            <!-- Actions -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('client.licenses.edit', $license) }}" class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i>Modifier
                        </a>
                        
                        @if($license->status === 'active')
                        <form action="{{ route('client.licenses.toggle-status', $license) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-warning w-100">
                                <i class="fas fa-pause me-2"></i>Désactiver
                            </button>
                        </form>
                        @else
                        <form action="{{ route('client.licenses.toggle-status', $license) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success w-100">
                                <i class="fas fa-play me-2"></i>Activer
                            </button>
                        </form>
                        @endif
                        
                        <form action="{{ route('client.licenses.regenerate', $license) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-info w-100" onclick="return confirm('Êtes-vous sûr de vouloir régénérer cette licence ?')">
                                <i class="fas fa-sync me-2"></i>Régénérer
                            </button>
                        </form>
                        
                        <a href="{{ route('client.licenses.download', $license) }}" class="btn btn-secondary">
                            <i class="fas fa-download me-2"></i>Télécharger
                        </a>
                        
                        <form action="{{ route('client.licenses.destroy', $license) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette licence ?')">
                                <i class="fas fa-trash me-2"></i>Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Comptes associés -->
            @if($license->accounts && $license->accounts->count() > 0)
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Comptes Associés</h5>
                </div>
                <div class="card-body">
                    @foreach($license->accounts as $account)
                    <div class="d-flex align-items-center mb-3">
                        <div class="me-3">
                            <div class="bg-success rounded-circle p-2 text-white" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-user" style="font-size: 12px;"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1">{{ $account->username ?? 'Compte #' . $account->id }}</h6>
                            <small class="text-muted">{{ $account->email ?? 'N/A' }}</small>
                        </div>
                        <div>
                            <span class="badge bg-{{ $account->status === 'active' ? 'success' : 'secondary' }}">
                                {{ ucfirst($account->status ?? 'inactive') }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function copyToClipboard(elementId) {
    const element = document.getElementById(elementId);
    element.select();
    element.setSelectionRange(0, 99999);
    document.execCommand('copy');
    
    // Afficher une notification
    const button = element.nextElementSibling;
    const originalHTML = button.innerHTML;
    button.innerHTML = '<i class="fas fa-check"></i>';
    button.classList.remove('btn-outline-secondary');
    button.classList.add('btn-success');
    
    setTimeout(() => {
        button.innerHTML = originalHTML;
        button.classList.remove('btn-success');
        button.classList.add('btn-outline-secondary');
    }, 2000);
}
</script>
@endpush
