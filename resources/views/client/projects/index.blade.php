@extends('layouts.client')

@section('title', 'Mes Projets')

@section('content')
<div class="container-fluid py-4">
    <!-- En-tête avec le titre -->
    <div class="card bg-primary text-white mb-4">
        <div class="card-body p-4">
            <h4 class="mb-2">Mes Projets</h4>
            <p class="mb-0">Gérez vos projets et leurs licences</p>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="row g-4 mb-4">
        <!-- Total Projets -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card-stats">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="stat-icon bg-primary">
                            <i class="fas fa-folder"></i>
                        </div>
                        <div class="text-end">
                            <h3 class="mb-1">{{ $projects->total() }}</h3>
                            <p class="text-muted mb-0">Total</p>
                        </div>
                    </div>
                    <h6 class="mb-2">Projets</h6>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-chart-line text-primary me-1"></i>
                        <small class="text-primary">Vue d'ensemble</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Projets Actifs -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card-stats">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="stat-icon bg-success">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="text-end">
                            <h3 class="mb-1">{{ $projects->where('status', 'active')->count() }}</h3>
                            <p class="text-muted mb-0">Actifs</p>
                        </div>
                    </div>
                    <h6 class="mb-2">Projets Actifs</h6>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-arrow-up text-success me-1"></i>
                        <small class="text-success">En cours d'utilisation</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Licences -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card-stats">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="stat-icon bg-info">
                            <i class="fas fa-key"></i>
                        </div>
                        <div class="text-end">
                            <h3 class="mb-1">{{ $projects->sum(function($project) { return $project->serialKeys->count(); }) }}</h3>
                            <p class="text-muted mb-0">Total</p>
                        </div>
                    </div>
                    <h6 class="mb-2">Licences</h6>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-chart-bar text-info me-1"></i>
                        <small class="text-info">Toutes licences</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Licences Actives -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card-stats">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="stat-icon bg-warning">
                            <i class="fas fa-rocket"></i>
                        </div>
                        <div class="text-end">
                            <h3 class="mb-1">{{ $projects->sum(function($project) { return $project->serialKeys->where('status', 'active')->count(); }) }}</h3>
                            <p class="text-muted mb-0">Actives</p>
                        </div>
                    </div>
                    <h6 class="mb-2">Licences Actives</h6>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-bolt text-warning me-1"></i>
                        <small class="text-warning">En utilisation</small>
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
                        <form method="GET" action="{{ route('client.projects.index') }}" class="row g-3">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Rechercher</label>
                                    <input type="text" class="form-control" name="search" 
                                           value="{{ request('search') }}" placeholder="Nom du projet...">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Statut</label>
                                    <select class="form-select" name="status">
                                        <option value="">Tous les statuts</option>
                                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Actif</option>
                                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactif</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Trier par</label>
                                    <select class="form-select" name="sort">
                                        <option value="created_at" {{ request('sort') === 'created_at' ? 'selected' : '' }}>Date de création</option>
                                        <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Nom</option>
                                        <option value="updated_at" {{ request('sort') === 'updated_at' ? 'selected' : '' }}>Dernière modification</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-search me-2"></i>Filtrer
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Liste des projets -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="card-title mb-0">Liste des Projets</h5>
                        <a href="{{ route('client.projects.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Nouveau Projet
                        </a>
                    </div>

                    @if($projects->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Nom</th>
                                        <th>Description</th>
                                        <th>Site Web</th>
                                        <th>Licences</th>
                                        <th>Statut</th>
                                        <th>Créé le</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($projects as $project)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="me-3">
                                                    <div class="bg-primary rounded-circle p-2 text-white">
                                                        <i class="fas fa-folder"></i>
                                                    </div>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">{{ $project->name }}</h6>
                                                    <small class="text-muted">ID: {{ $project->id }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-truncate" style="max-width: 200px;" title="{{ $project->description }}">
                                                {{ $project->description ?: 'Aucune description' }}
                                            </div>
                                        </td>
                                        <td>
                                            @if($project->website_url)
                                                <a href="{{ $project->website_url }}" target="_blank" class="text-primary">
                                                    <i class="fas fa-external-link-alt me-1"></i>Voir le site
                                                </a>
                                            @else
                                                <span class="text-muted">Non défini</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-info">
                                                {{ $project->serialKeys->count() }} licence(s)
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $project->status === 'active' ? 'success' : 'secondary' }}">
                                                {{ ucfirst($project->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $project->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('client.projects.show', $project) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('client.projects.edit', $project) }}" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-danger" onclick="deleteProject({{ $project->id }})">
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
                            {{ $projects->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                            <h5>Aucun projet pour le moment</h5>
                            <p class="text-muted">Commencez par créer votre premier projet</p>
                            <a href="{{ route('client.projects.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Créer un projet
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
    function deleteProject(projectId) {
        if (confirm('Êtes-vous sûr de vouloir supprimer ce projet ?')) {
            fetch(`/client/projects/${projectId}`, {
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
                    alert('Une erreur est survenue lors de la suppression du projet.');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Une erreur est survenue lors de la suppression du projet.');
            });
        }
    }
</script>
@endpush
@endsection 