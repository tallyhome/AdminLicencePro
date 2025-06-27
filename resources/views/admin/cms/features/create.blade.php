@extends('admin.layouts.app')

@section('title', 'Nouvelle Fonctionnalité')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Nouvelle Fonctionnalité</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.cms.index') }}">CMS</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.cms.features.index') }}">Fonctionnalités</a></li>
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
                    <h5 class="card-title mb-0">Informations de la Fonctionnalité</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.cms.features.store') }}" method="POST" enctype="multipart/form-data">
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
                                <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                                <textarea class="form-control tinymce @error('description') is-invalid @enderror" 
                                          id="description" name="description" rows="5" required>{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="icon" class="form-label">Icône (FontAwesome) <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('icon') is-invalid @enderror" 
                                       id="icon" name="icon" value="{{ old('icon', 'fas fa-star') }}" 
                                       placeholder="fas fa-star" required>
                                @error('icon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Exemple: fas fa-star, fab fa-google, far fa-heart</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="sort_order" class="form-label">Ordre d'affichage <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('sort_order') is-invalid @enderror" 
                                       id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}" min="0" required>
                                @error('sort_order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="link_url" class="form-label">URL du lien (optionnel)</label>
                                <input type="url" class="form-control @error('link_url') is-invalid @enderror" 
                                       id="link_url" name="link_url" value="{{ old('link_url') }}">
                                @error('link_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="link_text" class="form-label">Texte du lien (optionnel)</label>
                                <input type="text" class="form-control @error('link_text') is-invalid @enderror" 
                                       id="link_text" name="link_text" value="{{ old('link_text') }}">
                                @error('link_text')
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
                            </div>

                            <div class="col-md-12 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                           value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        Activer cette fonctionnalité
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="text-end">
                            <a href="{{ route('admin.cms.features.index') }}" class="btn btn-light me-2">Annuler</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Créer la Fonctionnalité
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Aperçu de l'icône</h5>
                </div>
                <div class="card-body text-center">
                    <div id="icon-preview" style="font-size: 3rem; color: #007bff;">
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="text-muted mt-2">L'icône s'affichera ici</p>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Aide</h5>
                </div>
                <div class="card-body">
                    <h6>Icônes FontAwesome</h6>
                    <p class="small">Utilisez les classes FontAwesome pour les icônes :</p>
                    <ul class="small">
                        <li><code>fas fa-shield-alt</code> - Sécurité</li>
                        <li><code>fas fa-cogs</code> - Configuration</li>
                        <li><code>fas fa-code</code> - Développement</li>
                        <li><code>fas fa-rocket</code> - Performance</li>
                    </ul>
                    <p class="small">
                        <a href="https://fontawesome.com/icons" target="_blank">
                            Voir toutes les icônes <i class="fas fa-external-link-alt"></i>
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const iconInput = document.getElementById('icon');
    const iconPreview = document.getElementById('icon-preview');
    
    iconInput.addEventListener('input', function() {
        const iconClass = this.value.trim();
        if (iconClass) {
            iconPreview.innerHTML = `<i class="${iconClass}"></i>`;
        } else {
            iconPreview.innerHTML = `<i class="fas fa-star"></i>`;
        }
    });
});
</script>
@endsection 