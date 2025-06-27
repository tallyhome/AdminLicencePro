@extends('admin.layouts.app')

@section('title', 'Nouvelle Section À propos')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Nouvelle Section À propos</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.cms.index') }}">CMS</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.cms.about.index') }}">À propos</a></li>
                        <li class="breadcrumb-item active">Nouvelle</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informations de la Section</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.cms.about.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="title" class="form-label">Titre <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                       id="title" name="title" value="{{ old('title') }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="content" class="form-label">Contenu <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('content') is-invalid @enderror" 
                                          id="content" name="content" rows="8" required>{{ old('content') }}</textarea>
                                @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="section_type" class="form-label">Type de section <span class="text-danger">*</span></label>
                                <select class="form-control @error('section_type') is-invalid @enderror" 
                                        id="section_type" name="section_type" required>
                                    <option value="">Choisir un type</option>
                                    <option value="text" {{ old('section_type') == 'text' ? 'selected' : '' }}>Texte simple</option>
                                    <option value="image_left" {{ old('section_type') == 'image_left' ? 'selected' : '' }}>Image à gauche</option>
                                    <option value="image_right" {{ old('section_type') == 'image_right' ? 'selected' : '' }}>Image à droite</option>
                                    <option value="full_width" {{ old('section_type') == 'full_width' ? 'selected' : '' }}>Pleine largeur</option>
                                </select>
                                @error('section_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="sort_order" class="form-label">Ordre d'affichage <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('sort_order') is-invalid @enderror" 
                                       id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}" min="0" required>
                                @error('sort_order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="image" class="form-label">Image (optionnel)</label>
                                <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                       id="image" name="image" accept="image/*">
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Formats acceptés : JPEG, PNG, JPG, GIF. Taille max : 2MB</small>
                            </div>

                            <div class="col-md-12 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                           value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        Publier cette section
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="text-end">
                            <a href="{{ route('admin.cms.about.index') }}" class="btn btn-light me-2">Annuler</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Créer la Section
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Aide</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h6 class="alert-heading">Types de sections :</h6>
                        <ul class="mb-0 small">
                            <li><strong>Texte simple :</strong> Contenu texte uniquement</li>
                            <li><strong>Image à gauche :</strong> Image à gauche, texte à droite</li>
                            <li><strong>Image à droite :</strong> Texte à gauche, image à droite</li>
                            <li><strong>Pleine largeur :</strong> Section sur toute la largeur</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Conseils</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-secondary mb-0">
                        <h6 class="alert-heading">Bonnes pratiques :</h6>
                        <ul class="mb-0 small">
                            <li>Utilisez des titres courts et accrocheurs</li>
                            <li>Rédigez un contenu clair et engageant</li>
                            <li>L'ordre d'affichage détermine la position (0 = premier)</li>
                            <li>Les images améliorent l'attrait visuel</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 