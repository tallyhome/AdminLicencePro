@extends('admin.layouts.app')

@section('title', 'Nouvelle FAQ')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Nouvelle FAQ</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.cms.index') }}">CMS</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.cms.faqs.index') }}">FAQs</a></li>
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
                    <h5 class="card-title mb-0">Informations de la FAQ</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.cms.faqs.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="question" class="form-label">Question <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('question') is-invalid @enderror" 
                                       id="question" name="question" value="{{ old('question') }}" required>
                                @error('question')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="answer" class="form-label">Réponse <span class="text-danger">*</span></label>
                                <textarea class="form-control tinymce @error('answer') is-invalid @enderror" 
                                          id="answer" name="answer" rows="6" required>{{ old('answer') }}</textarea>
                                @error('answer')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="category" class="form-label">Catégorie</label>
                                <div class="input-group">
                                    <input type="text" class="form-control @error('category') is-invalid @enderror" 
                                           id="category" name="category" value="{{ old('category') }}" list="categories">
                                    <datalist id="categories">
                                        @foreach($categories as $category)
                                            <option value="{{ $category }}">
                                        @endforeach
                                    </datalist>
                                </div>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Choisissez une catégorie existante ou créez-en une nouvelle</small>
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
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" 
                                                   value="1" {{ old('is_featured') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_featured">
                                                Mettre en avant cette FAQ
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                                   value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_active">
                                                Activer cette FAQ
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-end">
                            <a href="{{ route('admin.cms.faqs.index') }}" class="btn btn-light me-2">Annuler</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Créer la FAQ
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
                    <h6>Conseils pour les FAQs</h6>
                    <ul class="small">
                        <li>Rédigez des questions claires et concises</li>
                        <li>Apportez des réponses complètes et utiles</li>
                        <li>Utilisez les catégories pour organiser vos FAQs</li>
                        <li>Mettez en avant les questions les plus importantes</li>
                        <li>Utilisez l'ordre d'affichage pour prioriser</li>
                    </ul>
                </div>
            </div>

            @if($categories->count() > 0)
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Catégories existantes</h5>
                </div>
                <div class="card-body">
                    @foreach($categories as $category)
                        <span class="badge bg-light text-dark me-1 mb-1 category-badge" 
                              style="cursor: pointer;" onclick="selectCategory('{{ $category }}')">
                            {{ $category }}
                        </span>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
function selectCategory(category) {
    document.getElementById('category').value = category;
}
</script>
@endsection 