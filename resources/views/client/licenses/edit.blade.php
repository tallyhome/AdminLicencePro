@extends('layouts.client')

@section('title', 'Modifier la Licence')

@section('content')
<div class="container-fluid py-4">
    <!-- En-tête avec navigation -->
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('client.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('client.licenses.index') }}">Licences</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('client.licenses.show', $license) }}">{{ $license->name ?? 'Détails' }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Modifier</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- En-tête avec le titre -->
    <div class="card bg-primary text-white mb-4">
        <div class="card-body p-4">
            <h4 class="mb-2">Modifier la Licence</h4>
            <p class="mb-0">{{ $license->name ?? 'Licence #' . $license->id }}</p>
        </div>
    </div>

    <!-- Formulaire de modification -->
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informations de la Licence</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('client.licenses.update', $license) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Nom de la Licence</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       name="name" value="{{ old('name', $license->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Activations Maximum</label>
                                <input type="number" class="form-control @error('max_activations') is-invalid @enderror" 
                                       name="max_activations" value="{{ old('max_activations', $license->max_activations) }}" min="1">
                                @error('max_activations')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Laissez vide pour illimité</small>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Comptes Maximum</label>
                                <input type="number" class="form-control @error('max_accounts') is-invalid @enderror" 
                                       name="max_accounts" value="{{ old('max_accounts', $license->max_accounts) }}" min="1">
                                @error('max_accounts')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Laissez vide pour illimité</small>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Date d'Expiration</label>
                                <input type="datetime-local" class="form-control @error('expires_at') is-invalid @enderror" 
                                       name="expires_at" value="{{ old('expires_at', $license->expires_at ? $license->expires_at->format('Y-m-d\TH:i') : '') }}">
                                @error('expires_at')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Laissez vide pour jamais</small>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Statut</label>
                                <select class="form-select @error('status') is-invalid @enderror" name="status" required>
                                    <option value="active" {{ old('status', $license->status) === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status', $license->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    <option value="expired" {{ old('status', $license->status) === 'expired' ? 'selected' : '' }}>Expirée</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-12">
                                <label class="form-label">Notes</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror" 
                                          name="notes" rows="3" placeholder="Notes optionnelles...">{{ old('notes', $license->notes) }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('client.licenses.show', $license) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Retour
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Enregistrer les Modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
