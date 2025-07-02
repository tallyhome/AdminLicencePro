@extends('layouts.client')

@section('title', 'Mes Licences')

@section('content')
<div class="container-fluid py-4">
    <!-- En-tête avec le titre -->
    <div class="card bg-primary text-white mb-4">
        <div class="card-body p-4">
            <h4 class="mb-2">Mes Licences</h4>
            <p class="mb-0">Gérez vos licences et leurs activations</p>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="row g-4 mb-4">
        <!-- Total Licences -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card-stats">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="stat-icon bg-primary">
                            <i class="fas fa-key"></i>
                        </div>
                        <div class="text-end">
                            <h3 class="mb-1">{{ $stats['total'] }}</h3>
                            <p class="text-muted mb-0">Total</p>
                        </div>
                    </div>
                    <h6 class="mb-2">Licences</h6>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-chart-line text-primary me-1"></i>
                        <small class="text-primary">Vue d'ensemble</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Licences Actives -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card-stats">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="stat-icon bg-success">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="text-end">
                            <h3 class="mb-1">{{ $stats['active'] }}</h3>
                            <p class="text-muted mb-0">Actives</p>
                        </div>
                    </div>
                    <h6 class="mb-2">Licences Actives</h6>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-arrow-up text-success me-1"></i>
                        <small class="text-success">En cours d'utilisation</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Licences Expirant Bientôt -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card-stats">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="stat-icon bg-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="text-end">
                            <h3 class="mb-1">{{ $stats['expiring'] }}</h3>
                            <p class="text-muted mb-0">À surveiller</p>
                        </div>
                    </div>
                    <h6 class="mb-2">Expirent Bientôt</h6>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-clock text-warning me-1"></i>
                        <small class="text-warning">Dans les 30 jours</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Licences Expirées -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card-stats">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="stat-icon bg-danger">
                            <i class="fas fa-times-circle"></i>
                        </div>
                        <div class="text-end">
                            <h3 class="mb-1">{{ $stats['expired'] }}</h3>
                            <p class="text-muted mb-0">Expirées</p>
                        </div>
                    </div>
                    <h6 class="mb-2">Licences Expirées</h6>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation text-danger me-1"></i>
                        <small class="text-danger">À renouveler</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres et Liste -->
    <div class="row g-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <!-- Filtres -->
                    <div class="mb-4">
                        <form method="GET" action="{{ route('client.licenses.index') }}" class="row g-3">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Recherche</label>
                                    <input type="text" class="form-control" name="search" 
                                           value="{{ request('search') }}" placeholder="Nom, clé...">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="form-label">Projet</label>
                                    <select class="form-select" name="project">
                                        <option value="">Tous</option>
                                        @foreach($projects as $project)
                                            <option value="{{ $project->id }}" {{ request('project') == $project->id ? 'selected' : '' }}>
                                                {{ $project->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="form-label">Statut</label>
                                    <select class="form-select" name="status">
                                        <option value="">Tous</option>
                                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expirée</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="form-label">Type</label>
                                    <select class="form-select" name="type">
                                        <option value="">Tous</option>
                                        <option value="single" {{ request('type') == 'single' ? 'selected' : '' }}>Unique</option>
                                        <option value="multi" {{ request('type') == 'multi' ? 'selected' : '' }}>Multi-domaine</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <div class="d-flex gap-2 w-100">
                                    <button type="submit" class="btn btn-primary flex-grow-1">
                                        <i class="fas fa-search me-2"></i>Filtrer
                                    </button>
                                    <a href="{{ route('client.licenses.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Liste des licences -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="card-title mb-0">Liste des Licences</h5>
                        <a href="{{ route('client.licenses.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Nouvelle Licence
                        </a>
                    </div>

                    @if($licenses->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Nom</th>
                                        <th>Projet</th>
                                        <th>Type</th>
                                        <th>Statut</th>
                                        <th>Expiration</th>
                                        <th>Créée le</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($licenses as $license)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="me-3">
                                                        <div class="bg-primary rounded-circle p-2 text-white">
                                                            <i class="fas fa-key"></i>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0">{{ $license->name }}</h6>
                                                        <small class="text-muted">{{ Str::limit($license->license_key, 20) }}...</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @if($license->project)
                                                    <span class="badge bg-info">{{ $license->project->name }}</span>
                                                @else
                                                    <span class="text-muted">Aucun</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($license->type === 'single')
                                                    <span class="badge bg-primary">Unique</span>
                                                @else
                                                    <span class="badge bg-secondary">Multi-domaine</span>
                                                @endif
                                            </td>
                                            <td>
                                                @switch($license->status)
                                                    @case('active')
                                                        <span class="badge bg-success">Active</span>
                                                        @break
                                                    @case('inactive')
                                                        <span class="badge bg-secondary">Inactive</span>
                                                        @break
                                                    @case('expired')
                                                        <span class="badge bg-danger">Expirée</span>
                                                        @break
                                                    @default
                                                        <span class="badge bg-warning">{{ ucfirst($license->status) }}</span>
                                                @endswitch
                                            </td>
                                            <td>
                                                @if($license->expires_at)
                                                    <span class="@if($license->expires_at->isPast()) text-danger @elseif($license->expires_at->diffInDays() < 30) text-warning @else text-success @endif">
                                                        {{ $license->expires_at->format('d/m/Y') }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">Jamais</span>
                                                @endif
                                            </td>
                                            <td>{{ $license->created_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ route('client.licenses.show', $license) }}" class="btn btn-sm btn-info">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('client.licenses.edit', $license) }}" class="btn btn-sm btn-warning">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-danger" onclick="deleteLicense({{ $license->id }})">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-end mt-4">
                            {{ $licenses->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-key fa-3x text-muted mb-3"></i>
                            <h5>Aucune licence pour le moment</h5>
                            <p class="text-muted">Commencez par créer votre première licence</p>
                            <a href="{{ route('client.licenses.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Créer une licence
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function deleteLicense(licenseId) {
        if (confirm('Êtes-vous sûr de vouloir supprimer cette licence ?')) {
            fetch(`/client/licenses/${licenseId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    alert('Une erreur est survenue lors de la suppression de la licence.');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Une erreur est survenue lors de la suppression de la licence.');
            });
        }
    }
</script>
@endpush
@endsection 