@extends('admin.layouts.app')

@section('title', 'Modifier FAQ')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Modifier FAQ</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.cms.index') }}">CMS</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.cms.faqs.index') }}">FAQs</a></li>
                        <li class="breadcrumb-item active">Modifier</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Modifier la FAQ</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.cms.faqs.update', $faq) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="question" class="form-label">Question <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('question') is-invalid @enderror" 
                                       id="question" name="question" value="{{ old('question', $faq->question) }}" required>
                                @error('question')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="answer" class="form-label">Réponse <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('answer') is-invalid @enderror" 
                                          id="answer" name="answer" rows="6" required>{{ old('answer', $faq->answer) }}</textarea>
                                @error('answer')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="category" class="form-label">Catégorie</label>
                                <div class="input-group">
                                    <input type="text" class="form-control @error('category') is-invalid @enderror" 
                                           id="category" name="category" value="{{ old('category', $faq->category) }}" list="categories">
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
                                       id="sort_order" name="sort_order" value="{{ old('sort_order', $faq->sort_order) }}" min="0" required>
                                @error('sort_order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" 
                                           value="1" {{ old('is_featured', $faq->is_featured) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_featured">
                                        Mettre en avant cette FAQ (page d'accueil)
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-12 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                           value="1" {{ old('is_active', $faq->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        Publier cette FAQ
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="text-end">
                            <a href="{{ route('admin.cms.faqs.show', $faq) }}" class="btn btn-light me-2">Annuler</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Mettre à jour
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
                        <h6 class="alert-heading">Conseils :</h6>
                        <ul class="mb-0 small">
                            <li>Formulez des questions claires et précises</li>
                            <li>Répondez de manière complète mais concise</li>
                            <li>Utilisez "Mise en avant" pour afficher sur la page d'accueil</li>
                            <li>Les catégories aident à organiser vos FAQs</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informations</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-secondary mb-0">
                        <h6 class="alert-heading">Historique :</h6>
                        <ul class="mb-0 small">
                            <li><strong>Créé :</strong> {{ $faq->created_at->format('d/m/Y à H:i') }}</li>
                            @if($faq->updated_at != $faq->created_at)
                                <li><strong>Modifié :</strong> {{ $faq->updated_at->format('d/m/Y à H:i') }}</li>
                            @endif
                            @if($faq->views_count)
                                <li><strong>Vues :</strong> {{ $faq->views_count }}</li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 