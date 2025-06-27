@extends('admin.layouts.app')

@section('title', 'Éditer Fonctionnalité')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 fw-bold text-primary">
                        <i class="fas fa-edit me-2"></i>Éditer Fonctionnalité
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.cms.features.update', $feature) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Titre *</label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                           id="title" name="title" value="{{ old('title', $feature->title) }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description *</label>
                                    <textarea class="form-control wysiwyg @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="6" required>{{ old('description', $feature->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="benefits" class="form-label">Avantages (un par ligne)</label>
                                    <textarea class="form-control @error('benefits') is-invalid @enderror" 
                                              id="benefits" name="benefits" rows="4" 
                                              placeholder="Avantage 1&#10;Avantage 2&#10;Avantage 3">{{ old('benefits', is_array($feature->benefits) ? implode("\n", $feature->benefits) : '') }}</textarea>
                                    @error('benefits')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="icon" class="form-label">Icône (FontAwesome)</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i id="icon-preview" class="{{ $feature->icon ?: 'fas fa-star' }}"></i>
                                        </span>
                                        <input type="text" class="form-control @error('icon') is-invalid @enderror" 
                                               id="icon" name="icon" value="{{ old('icon', $feature->icon) }}" 
                                               placeholder="fas fa-star">
                                    </div>
                                    @error('icon')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="image" class="form-label">Image</label>
                                    @if($feature->image)
                                        <div class="mb-2">
                                            <img src="{{ $feature->image }}" alt="Image actuelle" class="img-thumbnail" style="max-width: 200px;">
                                        </div>
                                    @endif
                                    <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                           id="image" name="image" accept="image/*">
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="order" class="form-label">Ordre</label>
                                    <input type="number" class="form-control @error('order') is-invalid @enderror" 
                                           id="order" name="order" value="{{ old('order', $feature->order) }}" min="0">
                                    @error('order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" 
                                           id="is_active" name="is_active" value="1" 
                                           {{ old('is_active', $feature->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        Actif
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.cms.features.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Retour
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Mettre à jour
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Prévisualisation de l'icône
    const iconInput = document.getElementById('icon');
    const iconPreview = document.getElementById('icon-preview');
    
    iconInput.addEventListener('input', function() {
        iconPreview.className = this.value || 'fas fa-star';
    });
    
    // Initialiser TinyMCE pour ce formulaire
    if (typeof initTinyMCE === 'function') {
        initTinyMCE('.wysiwyg', 300);
    }
});
</script>
@endpush
@endsection 