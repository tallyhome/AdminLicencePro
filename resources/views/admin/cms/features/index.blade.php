@extends('admin.layouts.app')

@section('title', 'Gestion des Fonctionnalités')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Gestion des Fonctionnalités</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.cms.index') }}">CMS</a></li>
                        <li class="breadcrumb-item active">Fonctionnalités</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="card-title mb-0">Liste des Fonctionnalités ({{ $features->total() }})</h5>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('admin.cms.features.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i> Nouvelle Fonctionnalité
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if($features->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Titre</th>
                                        <th>Icône</th>
                                        <th>Statut</th>
                                        <th>Ordre</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($features as $feature)
                                    <tr>
                                        <td>
                                            <div class="fw-medium">{{ $feature->title }}</div>
                                            <small class="text-muted">{{ Str::limit(strip_tags($feature->description), 80) }}</small>
                                        </td>
                                        <td>
                                            <i class="{{ $feature->icon }}" style="font-size: 1.5rem; color: #007bff;"></i>
                                        </td>
                                        <td>
                                            @if($feature->is_active)
                                                <span class="badge bg-success">Actif</span>
                                            @else
                                                <span class="badge bg-secondary">Inactif</span>
                                            @endif
                                        </td>
                                        <td>{{ $feature->sort_order }}</td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-light dropdown-toggle" type="button" 
                                                        data-bs-toggle="dropdown">
                                                    Actions
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('admin.cms.features.show', $feature) }}">
                                                            <i class="fas fa-eye me-2"></i> Voir
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('admin.cms.features.edit', $feature) }}">
                                                            <i class="fas fa-edit me-2"></i> Modifier
                                                        </a>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <form action="{{ route('admin.cms.features.destroy', $feature) }}" 
                                                              method="POST" class="d-inline"
                                                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette fonctionnalité ?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger">
                                                                <i class="fas fa-trash me-2"></i> Supprimer
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center mt-4">
                            {{ $features->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-star fa-3x text-muted mb-3"></i>
                            <h5>Aucune fonctionnalité</h5>
                            <p class="text-muted">Commencez par créer votre première fonctionnalité.</p>
                            <a href="{{ route('admin.cms.features.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i> Créer une Fonctionnalité
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
