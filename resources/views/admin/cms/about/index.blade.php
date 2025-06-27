@extends('admin.layouts.app')

@section('title', 'Gestion des Sections À propos')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Gestion des Sections À propos</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.cms.index') }}">CMS</a></li>
                        <li class="breadcrumb-item active">À propos</li>
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
                            <h5 class="card-title mb-0">Sections À propos ({{ $aboutSections->total() }})</h5>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('admin.cms.about.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i> Nouvelle Section
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if($aboutSections->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Titre</th>
                                        <th>Type</th>
                                        <th>Statut</th>
                                        <th>Ordre</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($aboutSections as $section)
                                    <tr>
                                        <td>
                                            <div class="fw-medium">{{ $section->title }}</div>
                                            <small class="text-muted">{{ Str::limit(strip_tags($section->content), 80) }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $section->section_type }}</span>
                                        </td>
                                        <td>
                                            @if($section->is_active)
                                                <span class="badge bg-success">Actif</span>
                                            @else
                                                <span class="badge bg-secondary">Inactif</span>
                                            @endif
                                        </td>
                                        <td>{{ $section->sort_order }}</td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-light dropdown-toggle" type="button" 
                                                        data-bs-toggle="dropdown">
                                                    Actions
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('admin.cms.about.show', $section) }}">
                                                            <i class="fas fa-eye me-2"></i> Voir
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('admin.cms.about.edit', $section) }}">
                                                            <i class="fas fa-edit me-2"></i> Modifier
                                                        </a>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <form action="{{ route('admin.cms.about.destroy', $section) }}" 
                                                              method="POST" class="d-inline"
                                                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette section ?')">
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
                            {{ $aboutSections->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-info-circle fa-3x text-muted mb-3"></i>
                            <h5>Aucune section À propos</h5>
                            <p class="text-muted">Commencez par créer votre première section.</p>
                            <a href="{{ route('admin.cms.about.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i> Créer une Section
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 