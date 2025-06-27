@extends('admin.layouts.app')

@section('title', 'Gestion des Témoignages')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Gestion des Témoignages</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.cms.index') }}">CMS</a></li>
                        <li class="breadcrumb-item active">Témoignages</li>
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
                            <h5 class="card-title mb-0">Liste des Témoignages ({{ $testimonials->total() }})</h5>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('admin.cms.testimonials.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i> Nouveau Témoignage
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if($testimonials->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Client</th>
                                        <th>Entreprise</th>
                                        <th>Note</th>
                                        <th>Statut</th>
                                        <th>Mise en avant</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($testimonials as $testimonial)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($testimonial->avatar_url)
                                                    <img src="{{ Storage::url($testimonial->avatar_url) }}" class="rounded-circle me-3" width="40" height="40">
                                                @else
                                                    <div class="bg-primary rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                        <i class="fas fa-user text-white"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="fw-medium">{{ $testimonial->name }}</div>
                                                    <small class="text-muted">{{ $testimonial->position }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $testimonial->company }}</td>
                                        <td>
                                            <div class="text-warning">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $testimonial->rating)
                                                        <i class="fas fa-star"></i>
                                                    @else
                                                        <i class="far fa-star"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                        </td>
                                        <td>
                                            @if($testimonial->is_active)
                                                <span class="badge bg-success">Actif</span>
                                            @else
                                                <span class="badge bg-secondary">Inactif</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($testimonial->is_featured)
                                                <span class="badge bg-warning">Mise en avant</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-light dropdown-toggle" type="button" 
                                                        data-bs-toggle="dropdown">
                                                    Actions
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('admin.cms.testimonials.show', $testimonial) }}">
                                                            <i class="fas fa-eye me-2"></i> Voir
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('admin.cms.testimonials.edit', $testimonial) }}">
                                                            <i class="fas fa-edit me-2"></i> Modifier
                                                        </a>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <form action="{{ route('admin.cms.testimonials.destroy', $testimonial) }}" 
                                                              method="POST" class="d-inline"
                                                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce témoignage ?')">
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
                            {{ $testimonials->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                            <h5>Aucun témoignage</h5>
                            <p class="text-muted">Commencez par ajouter votre premier témoignage client.</p>
                            <a href="{{ route('admin.cms.testimonials.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i> Créer un Témoignage
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 