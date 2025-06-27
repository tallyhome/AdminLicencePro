@extends('admin.layouts.app')

@section('title', 'Voir Fonctionnalité')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Fonctionnalité : {{ Str::limit($feature->title, 50) }}</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.cms.index') }}">CMS</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.cms.features.index') }}">Fonctionnalités</a></li>
                        <li class="breadcrumb-item active">Voir</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Détails de la Fonctionnalité</h5>
                    <div>
                        <a href="{{ route('admin.cms.features.edit', $feature) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit me-1"></i> Modifier
                        </a>
                        <a href="{{ route('admin.cms.features.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i> Retour
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Prévisualisation -->
                    <div class="row">
                        <div class="col-md-12 mb-4">
                            <div class="feature-preview p-4 bg-light rounded">
                                <div class="text-center">
                                    @if($feature->image_url)
                                        <div class="mb-3">
                                            <img src="{{ asset('storage/' . $feature->image_url) }}" 
                                                 class="img-fluid rounded" 
                                                 style="max-height: 200px; object-fit: cover;"
                                                 alt="{{ $feature->title }}">
                                        </div>
                                    @endif
                                    
                                    <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 rounded-circle mb-3" 
                                         style="width: 64px; height: 64px;">
                                        <i class="{{ $feature->icon }} text-primary" style="font-size: 24px;"></i>
                                    </div>
                                    
                                    <h5 class="fw-bold mb-3">{{ $feature->title }}</h5>
                                    <p class="text-muted mb-3">{{ $feature->description }}</p>
                                    
                                    @if($feature->link_url && $feature->link_text)
                                        <a href="{{ $feature->link_url }}" class="btn btn-primary" target="_blank">
                                            {{ $feature->link_text }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Détails -->
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <td class="fw-semibold" style="width: 40%;">Titre :</td>
                                        <td>{{ $feature->title }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold">Icône :</td>
                                        <td>
                                            <i class="{{ $feature->icon }} me-2"></i>
                                            <code>{{ $feature->icon }}</code>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold">Ordre :</td>
                                        <td>{{ $feature->sort_order }}</td>
                                    </tr>
                                    @if($feature->link_url)
                                        <tr>
                                            <td class="fw-semibold">Lien :</td>
                                            <td>
                                                <a href="{{ $feature->link_url }}" target="_blank" class="text-decoration-none">
                                                    {{ $feature->link_text ?: 'Voir plus' }}
                                                    <i class="fas fa-external-link-alt ms-1 small"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <td class="fw-semibold" style="width: 40%;">Statut :</td>
                                        <td>
                                            @if($feature->is_active)
                                                <span class="badge bg-success">Actif</span>
                                            @else
                                                <span class="badge bg-secondary">Inactif</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold">Image :</td>
                                        <td>
                                            @if($feature->image_url)
                                                <span class="badge bg-success">Présente</span>
                                            @else
                                                <span class="text-muted">Aucune</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold">Créé le :</td>
                                        <td>{{ $feature->created_at->format('d/m/Y à H:i') }}</td>
                                    </tr>
                                    @if($feature->updated_at != $feature->created_at)
                                        <tr>
                                            <td class="fw-semibold">Modifié le :</td>
                                            <td>{{ $feature->updated_at->format('d/m/Y à H:i') }}</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Description complète -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h6 class="fw-semibold mb-3">Description complète :</h6>
                            <div class="p-3 bg-light rounded">
                                {!! nl2br(e($feature->description)) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.cms.features.edit', $feature) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i> Modifier cette fonctionnalité
                        </a>
                        
                        <form action="{{ route('admin.cms.features.destroy', $feature) }}" 
                              method="POST" 
                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette fonctionnalité ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="fas fa-trash me-2"></i> Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            @if($feature->image_url)
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Image</h5>
                </div>
                <div class="card-body">
                    <img src="{{ asset('storage/' . $feature->image_url) }}" 
                         class="img-fluid rounded" 
                         alt="{{ $feature->title }}">
                </div>
            </div>
            @endif

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informations</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-0">
                        <h6 class="alert-heading">État de la fonctionnalité :</h6>
                        <ul class="mb-0 small">
                            <li><strong>Créé :</strong> {{ $feature->created_at->format('d/m/Y à H:i') }}</li>
                            @if($feature->updated_at != $feature->created_at)
                                <li><strong>Modifié :</strong> {{ $feature->updated_at->format('d/m/Y à H:i') }}</li>
                            @endif
                            <li><strong>Statut :</strong> {{ $feature->is_active ? 'Actif' : 'Inactif' }}</li>
                            @if($feature->image_url)
                                <li><strong>Image :</strong> Présente</li>
                            @endif
                            @if($feature->link_url)
                                <li><strong>Lien externe :</strong> Configuré</li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 