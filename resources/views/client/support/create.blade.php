@extends('client.layouts.app')

@section('title', 'Créer un Ticket')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Créer un Ticket de Support</h1>
        <a href="{{ route('client.support.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Nouveau Ticket</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('client.support.store') }}" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="form-group">
                            <label for="subject">Sujet *</label>
                            <input type="text" class="form-control @error('subject') is-invalid @enderror" 
                                   id="subject" name="subject" value="{{ old('subject') }}" required>
                            @error('subject')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="priority">Priorité *</label>
                                    <select class="form-control @error('priority') is-invalid @enderror" 
                                            id="priority" name="priority" required>
                                        <option value="">Sélectionner une priorité</option>
                                        <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Faible</option>
                                        <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Moyenne</option>
                                        <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>Haute</option>
                                        <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgente</option>
                                    </select>
                                    @error('priority')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="category">Catégorie *</label>
                                    <select class="form-control @error('category') is-invalid @enderror" 
                                            id="category" name="category" required>
                                        <option value="">Sélectionner une catégorie</option>
                                        <option value="technical" {{ old('category') == 'technical' ? 'selected' : '' }}>Technique</option>
                                        <option value="billing" {{ old('category') == 'billing' ? 'selected' : '' }}>Facturation</option>
                                        <option value="general" {{ old('category') == 'general' ? 'selected' : '' }}>Général</option>
                                        <option value="feature_request" {{ old('category') == 'feature_request' ? 'selected' : '' }}>Demande de fonctionnalité</option>
                                    </select>
                                    @error('category')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description">Description *</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="8" required 
                                      placeholder="Décrivez votre problème en détail...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="attachments">Pièces jointes (optionnel)</label>
                            <input type="file" class="form-control-file @error('attachments.*') is-invalid @enderror" 
                                   id="attachments" name="attachments[]" multiple 
                                   accept=".jpg,.jpeg,.png,.gif,.pdf,.txt,.doc,.docx,.zip">
                            <small class="form-text text-muted">
                                Fichiers acceptés: JPG, PNG, PDF, TXT, DOC, ZIP. Taille max: 10MB par fichier.
                            </small>
                            @error('attachments.*')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i> Créer le Ticket
                            </button>
                            <a href="{{ route('client.support.index') }}" class="btn btn-secondary ml-2">
                                Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Conseils</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Avant de créer un ticket:</strong>
                        <ul class="mt-2">
                            <li>Consultez la <a href="{{ route('client.support.faq') }}">FAQ</a></li>
                            <li>Vérifiez la <a href="{{ route('client.support.documentation') }}">documentation</a></li>
                        </ul>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Pour une résolution rapide:</strong>
                        <ul class="mt-2">
                            <li>Soyez précis dans votre description</li>
                            <li>Incluez les messages d'erreur</li>
                            <li>Mentionnez les étapes pour reproduire</li>
                            <li>Ajoutez des captures d'écran si nécessaire</li>
                        </ul>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Temps de réponse moyen:</strong><br>
                        • Faible/Moyenne: 24-48h<br>
                        • Haute: 4-12h<br>
                        • Urgente: 1-4h
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 