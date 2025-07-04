@extends('admin.layouts.app')

@section('title', 'Voir Témoignage')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Témoignage : {{ $testimonial->name }}</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.cms.index') }}">CMS</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.cms.testimonials.index') }}">Témoignages</a></li>
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
                    <h5 class="card-title mb-0">Détails du Témoignage</h5>
                    <div>
                        <a href="{{ route('admin.cms.testimonials.edit', $testimonial) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit me-1"></i> Modifier
                        </a>
                        <a href="{{ route('admin.cms.testimonials.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i> Retour
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 mb-4">
                            <div class="testimonial-preview p-4 bg-light rounded">
                                @if($testimonial->avatar_url)
                                    <div class="d-flex align-items-center mb-3">
                                        <img src="{{ asset('storage/' . $testimonial->avatar_url) }}" 
                                             class="rounded-circle me-3" 
                                             style="width: 60px; height: 60px; object-fit: cover;"
                                             alt="{{ $testimonial->name }}">
                                        <div>
                                            <h6 class="mb-0 fw-bold">{{ $testimonial->name }}</h6>
                                            @if($testimonial->position || $testimonial->company)
                                                <small class="text-muted">
                                                    {{ $testimonial->position }}
                                                    @if($testimonial->position && $testimonial->company) - @endif
                                                    {{ $testimonial->company }}
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" 
                                             style="width: 60px; height: 60px;">
                                            <i class="fas fa-user text-white"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-bold">{{ $testimonial->name }}</h6>
                                            @if($testimonial->position || $testimonial->company)
                                                <small class="text-muted">
                                                    {{ $testimonial->position }}
                                                    @if($testimonial->position && $testimonial->company) - @endif
                                                    {{ $testimonial->company }}
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                <div class="mb-3">
                                    <div class="text-warning">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $testimonial->rating)
                                                <i class="fas fa-star"></i>
                                            @else
                                                <i class="far fa-star"></i>
                                            @endif
                                        @endfor
                                        <span class="ms-2 text-muted">({{ $testimonial->rating }}/5)</span>
                                    </div>
                                </div>

                                <blockquote class="blockquote mb-0">
                                    <p>"{{ $testimonial->content }}"</p>
                                </blockquote>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <td class="fw-semibold" style="width: 40%;">Nom :</td>
                                        <td>{{ $testimonial->name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold">Poste :</td>
                                        <td>{{ $testimonial->position ?: 'Non renseigné' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold">Entreprise :</td>
                                        <td>{{ $testimonial->company ?: 'Non renseigné' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold">Note :</td>
                                        <td>{{ $testimonial->rating }}/5 étoiles</td>
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
                                            @if($testimonial->is_active)
                                                <span class="badge bg-success">Publié</span>
                                            @else
                                                <span class="badge bg-secondary">Brouillon</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold">Mise en avant :</td>
                                        <td>
                                            @if($testimonial->is_featured)
                                                <span class="badge bg-warning">Oui</span>
                                            @else
                                                <span class="text-muted">Non</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold">Ordre :</td>
                                        <td>{{ $testimonial->sort_order }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold">Créé le :</td>
                                        <td>{{ $testimonial->created_at->format('d/m/Y à H:i') }}</td>
                                    </tr>
                                </tbody>
                            </table>
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
                        <a href="{{ route('admin.cms.testimonials.edit', $testimonial) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i> Modifier ce témoignage
                        </a>
                        
                        <form action="{{ route('admin.cms.testimonials.destroy', $testimonial) }}" 
                              method="POST" 
                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce témoignage ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="fas fa-trash me-2"></i> Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informations</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-0">
                        <h6 class="alert-heading">État du témoignage :</h6>
                        <ul class="mb-0 small">
                            <li><strong>Créé :</strong> {{ $testimonial->created_at->format('d/m/Y à H:i') }}</li>
                            @if($testimonial->updated_at != $testimonial->created_at)
                                <li><strong>Modifié :</strong> {{ $testimonial->updated_at->format('d/m/Y à H:i') }}</li>
                            @endif
                            <li><strong>Statut :</strong> {{ $testimonial->is_active ? 'Publié' : 'Brouillon' }}</li>
                            @if($testimonial->is_featured)
                                <li><strong>Mis en avant</strong> sur la page d'accueil</li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 