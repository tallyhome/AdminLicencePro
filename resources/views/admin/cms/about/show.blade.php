@extends('admin.layouts.app')

@section('title', 'Voir Section À propos')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Section : {{ Str::limit($about->title, 50) }}</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.cms.index') }}">CMS</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.cms.about.index') }}">À propos</a></li>
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
                    <h5 class="card-title mb-0">Détails de la Section</h5>
                    <div>
                        <a href="{{ route('admin.cms.about.edit', $about) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit me-1"></i> Modifier
                        </a>
                        <a href="{{ route('admin.cms.about.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i> Retour
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Prévisualisation -->
                    <div class="row">
                        <div class="col-md-12 mb-4">
                            <div class="section-preview p-4 bg-light rounded">
                                @if($about->section_type === 'text')
                                    <!-- Section texte simple -->
                                    <div class="text-center">
                                        <h5 class="fw-bold mb-3">{{ $about->title }}</h5>
                                        <div class="text-muted">{!! nl2br(e($about->content)) !!}</div>
                                    </div>
                                @elseif($about->section_type === 'image_left')
                                    <!-- Image à gauche -->
                                    <div class="row align-items-center">
                                        <div class="col-md-4">
                                            @if($about->image_url)
                                                <img src="{{ asset('storage/' . $about->image_url) }}" 
                                                     class="img-fluid rounded" 
                                                     alt="{{ $about->title }}">
                                            @else
                                                <div class="bg-secondary rounded d-flex align-items-center justify-content-center" 
                                                     style="height: 200px;">
                                                    <span class="text-white">Aucune image</span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="col-md-8">
                                            <h5 class="fw-bold mb-3">{{ $about->title }}</h5>
                                            <div class="text-muted">{!! nl2br(e($about->content)) !!}</div>
                                        </div>
                                    </div>
                                @elseif($about->section_type === 'image_right')
                                    <!-- Image à droite -->
                                    <div class="row align-items-center">
                                        <div class="col-md-8">
                                            <h5 class="fw-bold mb-3">{{ $about->title }}</h5>
                                            <div class="text-muted">{!! nl2br(e($about->content)) !!}</div>
                                        </div>
                                        <div class="col-md-4">
                                            @if($about->image_url)
                                                <img src="{{ asset('storage/' . $about->image_url) }}" 
                                                     class="img-fluid rounded" 
                                                     alt="{{ $about->title }}">
                                            @else
                                                <div class="bg-secondary rounded d-flex align-items-center justify-content-center" 
                                                     style="height: 200px;">
                                                    <span class="text-white">Aucune image</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <!-- Pleine largeur -->
                                    <div class="text-center">
                                        @if($about->image_url)
                                            <div class="mb-4">
                                                <img src="{{ asset('storage/' . $about->image_url) }}" 
                                                     class="img-fluid rounded" 
                                                     style="max-height: 300px; object-fit: cover;"
                                                     alt="{{ $about->title }}">
                                            </div>
                                        @endif
                                        <h5 class="fw-bold mb-3">{{ $about->title }}</h5>
                                        <div class="text-muted">{!! nl2br(e($about->content)) !!}</div>
                                    </div>
                                @endif
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
                                        <td>{{ $about->title }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold">Type :</td>
                                        <td>
                                            <span class="badge bg-info">
                                                @switch($about->section_type)
                                                    @case('text')
                                                        Texte simple
                                                        @break
                                                    @case('image_left')
                                                        Image à gauche
                                                        @break
                                                    @case('image_right')
                                                        Image à droite
                                                        @break
                                                    @case('full_width')
                                                        Pleine largeur
                                                        @break
                                                    @default
                                                        {{ $about->section_type }}
                                                @endswitch
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold">Ordre :</td>
                                        <td>{{ $about->sort_order }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <td class="fw-semibold" style="width: 40%;">Statut :</td>
                                        <td>
                                            @if($about->is_active)
                                                <span class="badge bg-success">Publié</span>
                                            @else
                                                <span class="badge bg-secondary">Brouillon</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold">Image :</td>
                                        <td>
                                            @if($about->image_url)
                                                <span class="badge bg-success">Présente</span>
                                            @else
                                                <span class="text-muted">Aucune</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold">Créé le :</td>
                                        <td>{{ $about->created_at->format('d/m/Y à H:i') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Contenu complet -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h6 class="fw-semibold mb-3">Contenu complet :</h6>
                            <div class="p-3 bg-light rounded">
                                {!! nl2br(e($about->content)) !!}
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
                        <a href="{{ route('admin.cms.about.edit', $about) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i> Modifier cette section
                        </a>
                        
                        <form action="{{ route('admin.cms.about.destroy', $about) }}" 
                              method="POST" 
                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette section ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="fas fa-trash me-2"></i> Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            @if($about->image_url)
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Image</h5>
                </div>
                <div class="card-body">
                    <img src="{{ asset('storage/' . $about->image_url) }}" 
                         class="img-fluid rounded" 
                         alt="{{ $about->title }}">
                </div>
            </div>
            @endif

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informations</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-0">
                        <h6 class="alert-heading">État de la section :</h6>
                        <ul class="mb-0 small">
                            <li><strong>Créé :</strong> {{ $about->created_at->format('d/m/Y à H:i') }}</li>
                            @if($about->updated_at != $about->created_at)
                                <li><strong>Modifié :</strong> {{ $about->updated_at->format('d/m/Y à H:i') }}</li>
                            @endif
                            <li><strong>Statut :</strong> {{ $about->is_active ? 'Publié' : 'Brouillon' }}</li>
                            @if($about->image_url)
                                <li><strong>Image :</strong> Présente</li>
                            @endif
                            <li><strong>Type :</strong> {{ ucfirst(str_replace('_', ' ', $about->section_type)) }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 