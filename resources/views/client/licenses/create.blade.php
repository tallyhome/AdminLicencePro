@extends('client.layouts.app')

@section('title', 'Nouvelle Licence')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Nouvelle Licence</h1>
        <a href="{{ route('client.licenses.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informations de la Licence</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('client.licenses.store') }}">
                        @csrf
                        
                        <div class="form-group">
                            <label for="name">Nom de la Licence *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="project_id">Projet *</label>
                            <select class="form-control @error('project_id') is-invalid @enderror" 
                                    id="project_id" name="project_id" required>
                                <option value="">Sélectionner un projet</option>
                                @foreach($projects ?? [] as $project)
                                    <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                        {{ $project->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('project_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="type">Type de Licence</label>
                                    <select class="form-control @error('type') is-invalid @enderror" 
                                            id="type" name="type">
                                        <option value="single" {{ old('type', 'single') == 'single' ? 'selected' : '' }}>Unique (1 domaine)</option>
                                        <option value="multi" {{ old('type') == 'multi' ? 'selected' : '' }}>Multi-domaine</option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status">Statut</label>
                                    <select class="form-control @error('status') is-invalid @enderror" 
                                            id="status" name="status">
                                        <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="expires_at">Date d'Expiration</label>
                            <input type="date" class="form-control @error('expires_at') is-invalid @enderror" 
                                   id="expires_at" name="expires_at" value="{{ old('expires_at') }}">
                            <small class="form-text text-muted">Laissez vide pour une licence illimitée</small>
                            @error('expires_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="allowed_domains">Domaines Autorisés</label>
                            <textarea class="form-control @error('allowed_domains') is-invalid @enderror" 
                                      id="allowed_domains" name="allowed_domains" rows="3" 
                                      placeholder="example.com&#10;subdomain.example.com&#10;(un domaine par ligne)">{{ old('allowed_domains') }}</textarea>
                            <small class="form-text text-muted">Un domaine par ligne. Laissez vide pour tous les domaines.</small>
                            @error('allowed_domains')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-key"></i> Générer la Licence
                            </button>
                            <a href="{{ route('client.licenses.index') }}" class="btn btn-secondary ml-2">
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
                    <h6 class="m-0 font-weight-bold text-primary">Types de Licence</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Licence Unique:</strong>
                        <p class="small text-muted">
                            Peut être utilisée sur un seul domaine. Idéale pour des projets spécifiques.
                        </p>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Licence Multi-domaine:</strong>
                        <p class="small text-muted">
                            Peut être utilisée sur plusieurs domaines. Parfaite pour des solutions distribuées.
                        </p>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        La clé de licence sera générée automatiquement et affichée après création.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 