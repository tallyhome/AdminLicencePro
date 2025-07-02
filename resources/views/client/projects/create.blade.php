@extends('layouts.client')

@section('title', 'Nouveau Projet')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Nouveau Projet</h1>
        <a href="{{ route('client.projects.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informations du Projet</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('client.projects.store') }}">
                        @csrf
                        
                        <div class="form-group">
                            <label for="name">Nom du Projet *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4" 
                                      placeholder="Description du projet...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="version">Version</label>
                                    <input type="text" class="form-control @error('version') is-invalid @enderror" 
                                           id="version" name="version" value="{{ old('version', '1.0.0') }}">
                                    @error('version')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status">Statut</label>
                                    <select class="form-control @error('status') is-invalid @enderror" 
                                            id="status" name="status">
                                        <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Actif</option>
                                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactif</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Créer le Projet
                            </button>
                            <a href="{{ route('client.projects.index') }}" class="btn btn-secondary ml-2">
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
                        <strong>Nom du projet:</strong>
                        <p class="small text-muted">
                            Choisissez un nom descriptif et unique pour votre projet.
                        </p>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Version:</strong>
                        <p class="small text-muted">
                            Utilisez le format sémantique (ex: 1.0.0, 2.1.3).
                        </p>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        Une fois créé, vous pourrez générer des licences pour ce projet.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 