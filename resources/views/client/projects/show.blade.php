@extends('layouts.client')

@section('title', $project->name)

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('client.projects.index') }}" class="text-decoration-none">Projets</a>
                    </li>
                    <li class="breadcrumb-item active">{{ $project->name }}</li>
                </ol>
            </nav>
            <h1 class="h3 mb-0 mt-2">{{ $project->name }}</h1>
            <p class="text-muted mb-0">{{ $project->description ?: 'Aucune description' }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('client.projects.edit', $project) }}" class="btn btn-outline-primary">
                <i class="fas fa-edit me-2"></i>Modifier
            </a>
            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="fas fa-ellipsis-v"></i>
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <a class="dropdown-item" href="{{ route('client.licenses.create', ['project_id' => $project->id]) }}">
                            <i class="fas fa-plus me-2"></i>Générer des licences
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item text-danger" href="#" onclick="deleteProject({{ $project->id }})">
                            <i class="fas fa-trash me-2"></i>Supprimer
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Informations principales -->
        <div class="col-lg-8">
            <!-- Statistiques du projet -->
            <div class="row mb-4">
                <div class="col-md-3 mb-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                <i class="fas fa-key text-primary fa-lg"></i>
                            </div>
                            <h4 class="mb-1">{{ $projectStats['total_licenses'] }}</h4>
                            <small class="text-muted">Total licences</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                <i class="fas fa-check-circle text-success fa-lg"></i>
                            </div>
                            <h4 class="mb-1">{{ $projectStats['active_licenses'] }}</h4>
                            <small class="text-muted">Licences actives</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                <i class="fas fa-download text-info fa-lg"></i>
                            </div>
                            <h4 class="mb-1">{{ $projectStats['total_activations'] }}</h4>
                            <small class="text-muted">Total activations</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                <i class="fas fa-clock text-warning fa-lg"></i>
                            </div>
                            <h4 class="mb-1">{{ $projectStats['recent_activations'] }}</h4>
                            <small class="text-muted">Activations récentes</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Détails du projet -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0">Informations du projet</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Nom</label>
                            <p class="mb-0 fw-semibold">{{ $project->name }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Version</label>
                            <p class="mb-0">
                                <span class="badge bg-light text-dark">{{ $project->version }}</span>
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Type de licence</label>
                            <p class="mb-0">
                                @switch($project->licence_type)
                                    @case('single')
                                        <span class="badge bg-info">Unique</span>
                                        @break
                                    @case('multi')
                                        <span class="badge bg-warning">Multiple</span>
                                        @if($project->max_activations)
                                            <small class="text-muted ms-2">(max {{ $project->max_activations }})</small>
                                        @endif
                                        @break
                                    @case('unlimited')
                                        <span class="badge bg-success">Illimitée</span>
                                        @break
                                @endswitch
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Statut</label>
                            <p class="mb-0">
                                @if($project->status === 'active')
                                    <span class="badge bg-success">Actif</span>
                                @else
                                    <span class="badge bg-secondary">Inactif</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Durée de validité</label>
                            <p class="mb-0">
                                @if($project->expiry_days)
                                    {{ $project->expiry_days }} jours
                                @else
                                    <span class="text-muted">Permanente</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Créé le</label>
                            <p class="mb-0">{{ $project->created_at->format('d/m/Y à H:i') }}</p>
                        </div>
                        @if($project->description)
                            <div class="col-12">
                                <label class="form-label text-muted">Description</label>
                                <p class="mb-0">{{ $project->description }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Licences récentes -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Licences récentes</h5>
                    <a href="{{ route('client.licenses.index', ['project_id' => $project->id]) }}" class="btn btn-sm btn-outline-primary">
                        Voir toutes
                    </a>
                </div>
                <div class="card-body p-0">
                    @if($recentLicenses->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Clé de licence</th>
                                        <th>Type</th>
                                        <th>Statut</th>
                                        <th>Activations</th>
                                        <th>Créée le</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentLicenses as $license)
                                        <tr>
                                            <td>
                                                <code class="small">{{ $license->serial_key }}</code>
                                            </td>
                                            <td>
                                                @switch($license->licence_type)
                                                    @case('single')
                                                        <span class="badge bg-info">Unique</span>
                                                        @break
                                                    @case('multi')
                                                        <span class="badge bg-warning">Multiple</span>
                                                        @break
                                                    @case('unlimited')
                                                        <span class="badge bg-success">Illimitée</span>
                                                        @break
                                                @endswitch
                                            </td>
                                            <td>
                                                @if($license->status === 'active')
                                                    <span class="badge bg-success">Actif</span>
                                                @else
                                                    <span class="badge bg-secondary">Inactif</span>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $license->current_activations }}
                                                @if($license->max_activations)
                                                    / {{ $license->max_activations }}
                                                @endif
                                            </td>
                                            <td>
                                                <small class="text-muted">{{ $license->created_at->format('d/m/Y') }}</small>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-key fa-2x text-muted mb-3"></i>
                            <p class="text-muted mb-0">Aucune licence créée pour ce projet</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Actions rapides -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="mb-0">Actions rapides</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('client.licenses.create', ['project_id' => $project->id]) }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Générer des licences
                        </a>
                        <a href="{{ route('client.licenses.index', ['project_id' => $project->id]) }}" class="btn btn-outline-primary">
                            <i class="fas fa-list me-2"></i>Voir toutes les licences
                        </a>
                        <a href="{{ route('client.projects.edit', $project) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-edit me-2"></i>Modifier le projet
                        </a>
                    </div>
                </div>
            </div>

            <!-- Activations récentes -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="mb-0">Activations récentes</h6>
                </div>
                <div class="card-body">
                    @if($recentActivations->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($recentActivations as $license)
                                <div class="list-group-item px-0 py-2">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <code class="small">{{ Str::limit($license->serial_key, 12) }}</code>
                                            <br>
                                            <small class="text-muted">{{ $license->last_activation_at->diffForHumans() }}</small>
                                        </div>
                                        <span class="badge bg-success">{{ $license->current_activations }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center mb-0">Aucune activation récente</p>
                    @endif
                </div>
            </div>

            <!-- Informations techniques -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="mb-0">Informations techniques</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label text-muted small">ID du projet</label>
                        <p class="mb-0">
                            <code class="small">{{ $project->id }}</code>
                        </p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small">Dernière modification</label>
                        <p class="mb-0">{{ $project->updated_at->format('d/m/Y à H:i') }}</p>
                    </div>
                    <div class="mb-0">
                        <label class="form-label text-muted small">API Endpoint</label>
                        <p class="mb-0">
                            <code class="small">/api/v1/check/{{ $project->id }}</code>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation de suppression -->
<div class="modal fade" id="deleteProjectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmer la suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer le projet <strong>{{ $project->name }}</strong> ?</p>
                <p class="text-danger"><small>Cette action est irréversible et supprimera toutes les licences associées.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form id="deleteProjectForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function deleteProject(projectId) {
    const modal = new bootstrap.Modal(document.getElementById('deleteProjectModal'));
    const form = document.getElementById('deleteProjectForm');
    form.action = `/client/projects/${projectId}`;
    modal.show();
}
</script>
@endpush 