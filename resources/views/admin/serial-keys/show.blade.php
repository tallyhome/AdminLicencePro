@extends('admin.layouts.app')

@section('title', t('serial_keys.details'))

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>{{ t('serial_keys.details') }}</h1>
        <div class="btn-group">
            <a href="{{ route('admin.serial-keys.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> {{ t('common.back') }}
            </a>
            <a href="{{ route('admin.serial-keys.edit', $serialKey) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> {{ t('serial_keys.edit') }}
            </a>
            @if($serialKey->status === 'active')
                <form action="{{ route('admin.serial-keys.suspend', $serialKey) }}" method="POST" class="d-inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-warning" onclick="return confirm('{{ t('serial_keys.confirm_suspend') }}')">
                        <i class="fas fa-pause"></i> {{ t('serial_keys.suspend') }}
                    </button>
                </form>
            @elseif($serialKey->status === 'suspended')
                <form action="{{ route('admin.serial-keys.revoke', $serialKey) }}" method="POST" class="d-inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('{{ t('serial_keys.confirm_revoke') }}')">
                        <i class="fas fa-ban"></i> {{ t('serial_keys.revoke') }}
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="row">
        <!-- Informations principales -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-key"></i> {{ t('serial_keys.information') }}
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <dl class="row">
                                <dt class="col-sm-4">{{ t('serial_keys.license_key') }}</dt>
                                <dd class="col-sm-8">
                                    <code class="bg-light p-2 rounded">{{ $serialKey->serial_key }}</code>
                                </dd>

                                <dt class="col-sm-4">{{ t('serial_keys.project') }}</dt>
                                <dd class="col-sm-8">
                                    <a href="{{ route('admin.projects.show', $serialKey->project) }}" class="text-decoration-none">
                                        <i class="fas fa-folder"></i> {{ $serialKey->project->name }}
                                    </a>
                                </dd>

                                <dt class="col-sm-4">Type de licence</dt>
                                <dd class="col-sm-8">
                                    @if($serialKey->licence_type === 'single')
                                        <span class="badge bg-primary fs-6">
                                            <i class="fas fa-user"></i> Licence Single
                                        </span>
                                        <br><small class="text-muted">1 licence = 1 domaine</small>
                                    @else
                                        <span class="badge bg-warning fs-6">
                                            <i class="fas fa-users"></i> Licence Multi
                                        </span>
                                        <br><small class="text-muted">1 licence = {{ $serialKey->max_accounts }} domaines max</small>
                                    @endif
                                </dd>

                                <dt class="col-sm-4">{{ t('serial_keys.status') }}</dt>
                                <dd class="col-sm-8">
                                    <span class="badge bg-{{ $serialKey->status === 'active' ? 'success' : ($serialKey->status === 'suspended' ? 'warning' : 'danger') }} fs-6">
                                        <i class="fas fa-{{ $serialKey->status === 'active' ? 'check-circle' : ($serialKey->status === 'suspended' ? 'pause-circle' : 'times-circle') }}"></i>
                                        {{ t('serial_keys.status_' . $serialKey->status) }}
                                    </span>
                                </dd>
                            </dl>
                        </div>
                        <div class="col-md-6">
                            <dl class="row">
                                @if($serialKey->licence_type === 'single')
                                    <dt class="col-sm-4">{{ t('serial_keys.domain') }}</dt>
                                    <dd class="col-sm-8">
                                        @if($serialKey->domain)
                                            <i class="fas fa-globe text-success"></i> {{ $serialKey->domain }}
                                        @else
                                            <i class="fas fa-globe text-muted"></i> {{ t('serial_keys.not_specified') }}
                                        @endif
                                    </dd>

                                    <dt class="col-sm-4">{{ t('serial_keys.ip_address') }}</dt>
                                    <dd class="col-sm-8">
                                        @if($serialKey->ip_address)
                                            <i class="fas fa-network-wired text-success"></i> {{ $serialKey->ip_address }}
                                        @else
                                            <i class="fas fa-network-wired text-muted"></i> {{ t('serial_keys.not_specified') }}
                                        @endif
                                    </dd>
                                @else
                                    <dt class="col-sm-4">Utilisation</dt>
                                    <dd class="col-sm-8">
                                        <div class="d-flex align-items-center mb-2">
                                            <span class="me-2 fw-bold">{{ $serialKey->used_accounts }}/{{ $serialKey->max_accounts }} comptes</span>
                                        </div>
                                        <div class="progress" style="height: 8px;">
                                            <div class="progress-bar {{ $serialKey->used_accounts >= $serialKey->max_accounts ? 'bg-danger' : ($serialKey->used_accounts > $serialKey->max_accounts * 0.8 ? 'bg-warning' : 'bg-success') }}" 
                                                 style="width: {{ $serialKey->max_accounts > 0 ? ($serialKey->used_accounts / $serialKey->max_accounts) * 100 : 0 }}%"></div>
                                        </div>
                                        <small class="text-muted">
                                            {{ $serialKey->max_accounts - $serialKey->used_accounts }} slot(s) disponible(s)
                                        </small>
                                    </dd>

                                    <dt class="col-sm-4">Comptes actifs</dt>
                                    <dd class="col-sm-8">
                                        <span class="badge bg-info">{{ $serialKey->activeAccounts()->count() }}</span> comptes actifs
                                    </dd>
                                @endif

                                <dt class="col-sm-4">{{ t('serial_keys.expiration_date') }}</dt>
                                <dd class="col-sm-8">
                                    @if($serialKey->expires_at)
                                        <i class="fas fa-calendar-alt text-warning"></i> {{ $serialKey->expires_at->format('d/m/Y H:i') }}
                                        @if($serialKey->expires_at->isPast())
                                            <span class="badge bg-danger ms-2">Expirée</span>
                                        @endif
                                    @else
                                        <i class="fas fa-infinity text-success"></i> {{ t('serial_keys.no_expiration') }}
                                    @endif
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            @if($serialKey->licence_type === 'multi')
                <!-- Gestion des comptes pour les licences multi -->
                <div class="card mt-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">
                            <i class="fas fa-users"></i> Comptes actifs ({{ $serialKey->accounts()->count() }})
                        </h4>
                        @if($serialKey->canAcceptNewAccount())
                            <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#addAccountModal">
                                <i class="fas fa-plus"></i> Ajouter un compte
                            </button>
                        @endif
                    </div>
                    <div class="card-body">
                        @if($serialKey->accounts()->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Domaine</th>
                                            <th>IP</th>
                                            <th>Statut</th>
                                            <th>Activé le</th>
                                            <th>Dernière utilisation</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($serialKey->accounts as $account)
                                            <tr>
                                                <td>
                                                    <i class="fas fa-globe"></i> {{ $account->domain ?? 'Non spécifié' }}
                                                </td>
                                                <td>{{ $account->ip_address ?? 'Non spécifié' }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $account->status === 'active' ? 'success' : ($account->status === 'suspended' ? 'warning' : 'danger') }}">
                                                        {{ ucfirst($account->status) }}
                                                    </span>
                                                </td>
                                                <td>{{ $account->activated_at?->format('d/m/Y H:i') ?? '-' }}</td>
                                                <td>{{ $account->last_used_at?->format('d/m/Y H:i') ?? 'Jamais' }}</td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        @if($account->status === 'active')
                                                            <form action="{{ route('admin.serial-keys.accounts.suspend', [$serialKey, $account]) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button type="submit" class="btn btn-warning btn-sm" title="Suspendre" onclick="return confirm('Êtes-vous sûr de vouloir suspendre ce compte ?')">
                                                                    <i class="fas fa-pause"></i>
                                                                </button>
                                                            </form>
                                                        @elseif($account->status === 'suspended')
                                                            <form action="{{ route('admin.serial-keys.accounts.reactivate', [$serialKey, $account]) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button type="submit" class="btn btn-success btn-sm" title="Réactiver" onclick="return confirm('Êtes-vous sûr de vouloir réactiver ce compte ?')">
                                                                    <i class="fas fa-play"></i>
                                                                </button>
                                                            </form>
                                                        @endif
                                                        <form action="{{ route('admin.serial-keys.accounts.destroy', [$serialKey, $account]) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce compte ? Cette action est irréversible.')">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center text-muted py-4">
                                <i class="fas fa-users fa-3x mb-3 opacity-50"></i>
                                <p>Aucun compte activé pour cette licence multi.</p>
                                @if($serialKey->canAcceptNewAccount())
                                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAccountModal">
                                        <i class="fas fa-plus"></i> Ajouter le premier compte
                                    </button>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Statistiques et actions -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        <i class="fas fa-chart-pie"></i> Statistiques
                    </h4>
                </div>
                <div class="card-body">
                    @if($serialKey->licence_type === 'single')
                        <div class="text-center">
                            <div class="mb-3">
                                @if($serialKey->used_accounts > 0)
                                    <i class="fas fa-check-circle fa-3x text-success"></i>
                                    <h5 class="mt-2 text-success">Licence utilisée</h5>
                                @else
                                    <i class="fas fa-clock fa-3x text-warning"></i>
                                    <h5 class="mt-2 text-warning">En attente d'activation</h5>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="border-end">
                                    <h3 class="text-primary">{{ $serialKey->used_accounts }}</h3>
                                    <small class="text-muted">Utilisés</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <h3 class="text-success">{{ $serialKey->max_accounts - $serialKey->used_accounts }}</h3>
                                <small class="text-muted">Disponibles</small>
                            </div>
                        </div>
                        <hr>
                        <div class="d-grid">
                            <small class="text-muted mb-2">Taux d'utilisation</small>
                            <div class="progress mb-2" style="height: 20px;">
                                <div class="progress-bar" style="width: {{ $serialKey->max_accounts > 0 ? ($serialKey->used_accounts / $serialKey->max_accounts) * 100 : 0 }}%">
                                    {{ $serialKey->max_accounts > 0 ? round(($serialKey->used_accounts / $serialKey->max_accounts) * 100) : 0 }}%
                                </div>
                            </div>
                        </div>
                    @endif

                    <hr>
                    <div class="d-grid gap-2">
                        <small class="text-muted">Actions rapides</small>
                        @if($serialKey->status === 'active')
                            <form action="{{ route('admin.serial-keys.suspend', $serialKey) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-outline-warning btn-sm w-100" onclick="return confirm('Êtes-vous sûr de vouloir suspendre cette clé de licence ?')">
                                    <i class="fas fa-pause"></i> Suspendre
                                </button>
                            </form>
                        @elseif($serialKey->status === 'suspended')
                            <form action="{{ route('admin.serial-keys.reactivate', $serialKey) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-outline-success btn-sm w-100" onclick="return confirm('Êtes-vous sûr de vouloir réactiver cette clé de licence ?')">
                                    <i class="fas fa-play"></i> Réactiver
                                </button>
                            </form>
                        @endif
                        <a href="{{ route('admin.serial-keys.history', $serialKey) }}" class="btn btn-outline-secondary btn-sm" onclick="showHistory(event)">
                            <i class="fas fa-history"></i> Historique
                        </a>
                        <button class="btn btn-outline-info btn-sm" onclick="exportData()">
                            <i class="fas fa-download"></i> Exporter les données
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if($serialKey->licence_type === 'multi')
    <!-- Modal pour ajouter un compte -->
    <div class="modal fade" id="addAccountModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ajouter un nouveau compte</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.serial-keys.accounts.store', $serialKey) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="domain" class="form-label">Domaine *</label>
                            <input type="text" class="form-control" id="domain" name="domain" placeholder="exemple.com" required>
                        </div>
                        <div class="mb-3">
                            <label for="ip_address" class="form-label">Adresse IP (optionnel)</label>
                            <input type="text" class="form-control" id="ip_address" name="ip_address" placeholder="192.168.1.1">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Ajouter le compte</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif

<script>
function showHistory(event) {
    event.preventDefault();
    
    // Créer et afficher un modal pour l'historique
    const modal = document.createElement('div');
    modal.className = 'modal fade';
    modal.id = 'historyModal';
    modal.setAttribute('tabindex', '-1');
    modal.innerHTML = `
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-history"></i> Historique de la clé {{ $serialKey->serial_key }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Chargement...</span>
                        </div>
                        <p class="mt-2">Chargement de l'historique...</p>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    const bootstrapModal = new bootstrap.Modal(modal);
    bootstrapModal.show();
    
    // Charger l'historique via AJAX
    fetch('{{ route("admin.serial-keys.history", $serialKey) }}', {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            let historyHtml = '';
            if (data.history.length > 0) {
                historyHtml = `
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Action</th>
                                    <th>Détails</th>
                                    <th>Administrateur</th>
                                    <th>IP</th>
                                </tr>
                            </thead>
                            <tbody>
                `;
                
                data.history.forEach(item => {
                    const actionBadge = getActionBadge(item.action);
                    historyHtml += `
                        <tr>
                            <td>${item.date}</td>
                            <td>${actionBadge}</td>
                            <td>${item.details || '-'}</td>
                            <td>${item.admin}</td>
                            <td><small class="text-muted">${item.ip_address}</small></td>
                        </tr>
                    `;
                });
                
                historyHtml += `
                            </tbody>
                        </table>
                    </div>
                `;
            } else {
                historyHtml = `
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        Aucun historique disponible pour cette clé de licence.
                    </div>
                `;
            }
            
            modal.querySelector('.modal-body').innerHTML = historyHtml;
        } else {
            modal.querySelector('.modal-body').innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    Erreur lors du chargement de l'historique.
                </div>
            `;
        }
    })
    .catch(error => {
        modal.querySelector('.modal-body').innerHTML = `
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle"></i>
                Erreur lors du chargement de l'historique : ${error.message}
            </div>
        `;
    });
    
    // Nettoyer le modal quand il est fermé
    modal.addEventListener('hidden.bs.modal', () => {
        modal.remove();
    });
}

function getActionBadge(action) {
    const badges = {
        'create': '<span class="badge bg-success">Création</span>',
        'update': '<span class="badge bg-primary">Modification</span>',
        'delete': '<span class="badge bg-danger">Suppression</span>',
        'suspend': '<span class="badge bg-warning">Suspension</span>',
        'reactivate': '<span class="badge bg-success">Réactivation</span>',
        'revoke': '<span class="badge bg-danger">Révocation</span>',
        'add_account': '<span class="badge bg-info">Ajout compte</span>',
        'remove_account': '<span class="badge bg-warning">Suppression compte</span>',
        'suspend_account': '<span class="badge bg-warning">Suspension compte</span>',
        'reactivate_account': '<span class="badge bg-success">Réactivation compte</span>',
        'status_change': '<span class="badge bg-primary">Changement statut</span>'
    };
    
    return badges[action] || `<span class="badge bg-secondary">${action}</span>`;
}

function exportData() {
    // Créer les données à exporter
    const data = {
        serial_key: '{{ $serialKey->serial_key }}',
        project: '{{ $serialKey->project->name }}',
        licence_type: '{{ $serialKey->licence_type }}',
        status: '{{ $serialKey->status }}',
        max_accounts: {{ $serialKey->max_accounts }},
        used_accounts: {{ $serialKey->used_accounts }},
        domain: '{{ $serialKey->domain ?? "" }}',
        ip_address: '{{ $serialKey->ip_address ?? "" }}',
        expires_at: '{{ $serialKey->expires_at?->format("d/m/Y H:i") ?? "Aucune" }}',
        created_at: '{{ $serialKey->created_at->format("d/m/Y H:i") }}',
        @if($serialKey->licence_type === 'multi')
        accounts: [
            @foreach($serialKey->accounts as $account)
            {
                domain: '{{ $account->domain }}',
                ip_address: '{{ $account->ip_address ?? "" }}',
                status: '{{ $account->status }}',
                activated_at: '{{ $account->activated_at?->format("d/m/Y H:i") ?? "" }}',
                last_used_at: '{{ $account->last_used_at?->format("d/m/Y H:i") ?? "Jamais" }}'
            }{{ !$loop->last ? ',' : '' }}
            @endforeach
        ]
        @endif
    };
    
    // Créer le fichier JSON
    const jsonString = JSON.stringify(data, null, 2);
    const blob = new Blob([jsonString], { type: 'application/json' });
    const url = URL.createObjectURL(blob);
    
    // Créer un lien de téléchargement
    const link = document.createElement('a');
    link.href = url;
    link.download = `licence_{{ $serialKey->serial_key }}_{{ now()->format('Y-m-d_H-i-s') }}.json`;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    URL.revokeObjectURL(url);
    
    // Afficher une notification de succès
    const toast = document.createElement('div');
    toast.className = 'toast-container position-fixed top-0 end-0 p-3';
    toast.innerHTML = `
        <div class="toast show" role="alert">
            <div class="toast-header">
                <i class="fas fa-download text-success me-2"></i>
                <strong class="me-auto">Export réussi</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body">
                Les données de la licence ont été exportées avec succès.
            </div>
        </div>
    `;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.remove();
    }, 5000);
}
</script>

@endsection