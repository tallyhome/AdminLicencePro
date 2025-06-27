@extends('admin.layouts.app')

@section('title', 'Gestion du Frontend')

@section('styles')
<style>
    .color-picker-wrapper {
        position: relative;
        display: inline-block;
    }
    .color-preview {
        width: 40px;
        height: 40px;
        border: 2px solid #ddd;
        border-radius: 4px;
        display: inline-block;
        vertical-align: middle;
        margin-left: 10px;
    }
    .image-preview {
        max-width: 200px;
        max-height: 150px;
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 5px;
    }
    .feature-toggle {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 15px;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-paint-brush"></i> Gestion du Frontend</h1>
        <div>
            <a href="{{ route('admin.settings.frontend.preview') }}" class="btn btn-info me-2" target="_blank">
                <i class="fas fa-eye"></i> Prévisualiser
            </a>
            <a href="{{ route('admin.settings.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Textes et contenu -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-font"></i> Textes et Contenu</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.settings.frontend.update-texts') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="app_name" class="form-label">Nom de l'application</label>
                            <input type="text" class="form-control" id="app_name" name="app_name" 
                                   value="{{ old('app_name', $frontendSettings['app_name']) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="app_tagline" class="form-label">Slogan</label>
                            <input type="text" class="form-control" id="app_tagline" name="app_tagline" 
                                   value="{{ old('app_tagline', $frontendSettings['app_tagline']) }}"
                                   placeholder="Votre slogan accrocheur">
                        </div>

                        <div class="mb-3">
                            <label for="hero_title" class="form-label">Titre principal (Hero)</label>
                            <input type="text" class="form-control" id="hero_title" name="hero_title" 
                                   value="{{ old('hero_title', $frontendSettings['hero_title']) }}"
                                   placeholder="Titre affiché en grand sur la page d'accueil">
                        </div>

                        <div class="mb-3">
                            <label for="hero_subtitle" class="form-label">Sous-titre (Hero)</label>
                            <textarea class="form-control" id="hero_subtitle" name="hero_subtitle" 
                                      rows="2" placeholder="Description sous le titre principal">{{ old('hero_subtitle', $frontendSettings['hero_subtitle']) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="footer_text" class="form-label">Texte du pied de page</label>
                            <input type="text" class="form-control" id="footer_text" name="footer_text" 
                                   value="{{ old('footer_text', $frontendSettings['footer_text']) }}"
                                   placeholder="© 2025 Votre entreprise. Tous droits réservés.">
                        </div>

                        <h5 class="mt-4 mb-3"><i class="fas fa-address-book"></i> Informations de contact</h5>

                        <div class="mb-3">
                            <label for="contact_email" class="form-label">Email de contact</label>
                            <input type="email" class="form-control" id="contact_email" name="contact_email" 
                                   value="{{ old('contact_email', $frontendSettings['contact_email']) }}"
                                   placeholder="contact@votredomaine.com">
                        </div>

                        <div class="mb-3">
                            <label for="contact_phone" class="form-label">Téléphone</label>
                            <input type="text" class="form-control" id="contact_phone" name="contact_phone" 
                                   value="{{ old('contact_phone', $frontendSettings['contact_phone']) }}"
                                   placeholder="+33 1 23 45 67 89">
                        </div>

                        <div class="mb-3">
                            <label for="contact_address" class="form-label">Adresse</label>
                            <textarea class="form-control" id="contact_address" name="contact_address" 
                                      rows="2" placeholder="123 Rue Example, 75001 Paris, France">{{ old('contact_address', $frontendSettings['contact_address']) }}</textarea>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Sauvegarder les textes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Couleurs et thème -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-palette"></i> Couleurs et Thème</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.settings.frontend.update-colors') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="primary_color" class="form-label">Couleur primaire</label>
                            <div class="d-flex align-items-center">
                                <input type="color" class="form-control form-control-color" id="primary_color" 
                                       name="primary_color" value="{{ $frontendSettings['primary_color'] }}">
                                <div class="color-preview" style="background-color: {{ $frontendSettings['primary_color'] }}"></div>
                            </div>
                            <small class="text-muted">Couleur principale des boutons et liens</small>
                        </div>

                        <div class="mb-3">
                            <label for="secondary_color" class="form-label">Couleur secondaire</label>
                            <div class="d-flex align-items-center">
                                <input type="color" class="form-control form-control-color" id="secondary_color" 
                                       name="secondary_color" value="{{ $frontendSettings['secondary_color'] }}">
                                <div class="color-preview" style="background-color: {{ $frontendSettings['secondary_color'] }}"></div>
                            </div>
                            <small class="text-muted">Couleur pour les éléments secondaires</small>
                        </div>

                        <div class="mb-3">
                            <label for="success_color" class="form-label">Couleur de succès</label>
                            <div class="d-flex align-items-center">
                                <input type="color" class="form-control form-control-color" id="success_color" 
                                       name="success_color" value="{{ $frontendSettings['success_color'] }}">
                                <div class="color-preview" style="background-color: {{ $frontendSettings['success_color'] }}"></div>
                            </div>
                            <small class="text-muted">Couleur pour les messages de succès</small>
                        </div>

                        <div class="mb-3">
                            <label for="danger_color" class="form-label">Couleur de danger</label>
                            <div class="d-flex align-items-center">
                                <input type="color" class="form-control form-control-color" id="danger_color" 
                                       name="danger_color" value="{{ $frontendSettings['danger_color'] }}">
                                <div class="color-preview" style="background-color: {{ $frontendSettings['danger_color'] }}"></div>
                            </div>
                            <small class="text-muted">Couleur pour les erreurs et avertissements</small>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Appliquer les couleurs
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Liens sociaux -->
            <div class="card mt-4">
                <div class="card-header">
                    <h3 class="card-title"><i class="fab fa-facebook"></i> Réseaux Sociaux</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.settings.frontend.update-social') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="social_facebook" class="form-label">
                                <i class="fab fa-facebook text-primary"></i> Facebook
                            </label>
                            <input type="url" class="form-control" id="social_facebook" name="social_facebook" 
                                   value="{{ old('social_facebook', $frontendSettings['social_facebook']) }}"
                                   placeholder="https://facebook.com/votrepage">
                        </div>

                        <div class="mb-3">
                            <label for="social_twitter" class="form-label">
                                <i class="fab fa-twitter text-info"></i> Twitter
                            </label>
                            <input type="url" class="form-control" id="social_twitter" name="social_twitter" 
                                   value="{{ old('social_twitter', $frontendSettings['social_twitter']) }}"
                                   placeholder="https://twitter.com/votrepage">
                        </div>

                        <div class="mb-3">
                            <label for="social_linkedin" class="form-label">
                                <i class="fab fa-linkedin text-primary"></i> LinkedIn
                            </label>
                            <input type="url" class="form-control" id="social_linkedin" name="social_linkedin" 
                                   value="{{ old('social_linkedin', $frontendSettings['social_linkedin']) }}"
                                   placeholder="https://linkedin.com/company/votrepage">
                        </div>

                        <div class="mb-3">
                            <label for="social_github" class="form-label">
                                <i class="fab fa-github text-dark"></i> GitHub
                            </label>
                            <input type="url" class="form-control" id="social_github" name="social_github" 
                                   value="{{ old('social_github', $frontendSettings['social_github']) }}"
                                   placeholder="https://github.com/votrepage">
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Sauvegarder les liens
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Gestion des images -->
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-images"></i> Images et Médias</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Logo -->
                        <div class="col-md-4 mb-4">
                            <h5>Logo</h5>
                            <div class="text-center mb-3">
                                <img src="{{ $frontendSettings['logo_url'] }}" alt="Logo actuel" class="image-preview">
                            </div>
                            <form action="{{ route('admin.settings.frontend.update-image') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="image_type" value="logo">
                                <div class="mb-2">
                                    <input type="file" class="form-control" name="image_file" accept="image/*" required>
                                </div>
                                <button type="submit" class="btn btn-sm btn-primary w-100">
                                    <i class="fas fa-upload"></i> Changer le logo
                                </button>
                            </form>
                        </div>

                        <!-- Image Hero -->
                        <div class="col-md-4 mb-4">
                            <h5>Image Hero</h5>
                            <div class="text-center mb-3">
                                <img src="{{ $frontendSettings['hero_image_url'] }}" alt="Image hero actuelle" class="image-preview">
                            </div>
                            <form action="{{ route('admin.settings.frontend.update-image') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="image_type" value="hero_image">
                                <div class="mb-2">
                                    <input type="file" class="form-control" name="image_file" accept="image/*" required>
                                </div>
                                <button type="submit" class="btn btn-sm btn-primary w-100">
                                    <i class="fas fa-upload"></i> Changer l'image
                                </button>
                            </form>
                        </div>

                        <!-- Favicon -->
                        <div class="col-md-4 mb-4">
                            <h5>Favicon</h5>
                            <div class="text-center mb-3">
                                <img src="{{ $frontendSettings['favicon_url'] }}" alt="Favicon actuel" class="image-preview" style="max-width: 64px;">
                            </div>
                            <form action="{{ route('admin.settings.frontend.update-image') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="image_type" value="favicon">
                                <div class="mb-2">
                                    <input type="file" class="form-control" name="image_file" accept="image/*,.ico" required>
                                </div>
                                <button type="submit" class="btn btn-sm btn-primary w-100">
                                    <i class="fas fa-upload"></i> Changer le favicon
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Fonctionnalités d'affichage -->
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-cogs"></i> Affichage</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.settings.frontend.update-features') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="feature-toggle">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="show_hero_section" 
                                       name="show_hero_section" {{ $frontendSettings['show_hero_section'] ? 'checked' : '' }}>
                                <label class="form-check-label" for="show_hero_section">
                                    <strong>Section Hero</strong><br>
                                    <small class="text-muted">Afficher la bannière principale avec titre et image</small>
                                </label>
                            </div>
                        </div>

                        <div class="feature-toggle">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="show_features_section" 
                                       name="show_features_section" {{ $frontendSettings['show_features_section'] ? 'checked' : '' }}>
                                <label class="form-check-label" for="show_features_section">
                                    <strong>Section Fonctionnalités</strong><br>
                                    <small class="text-muted">Afficher la liste des fonctionnalités</small>
                                </label>
                            </div>
                        </div>

                        <div class="feature-toggle">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="show_contact_section" 
                                       name="show_contact_section" {{ $frontendSettings['show_contact_section'] ? 'checked' : '' }}>
                                <label class="form-check-label" for="show_contact_section">
                                    <strong>Section Contact</strong><br>
                                    <small class="text-muted">Afficher les informations de contact</small>
                                </label>
                            </div>
                        </div>

                        <div class="feature-toggle">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="maintenance_mode" 
                                       name="maintenance_mode" {{ $frontendSettings['maintenance_mode'] ? 'checked' : '' }}>
                                <label class="form-check-label" for="maintenance_mode">
                                    <strong>Mode Maintenance</strong><br>
                                    <small class="text-muted">Activer le mode maintenance</small>
                                </label>
                            </div>
                            <div class="mt-2">
                                <input type="text" class="form-control" name="maintenance_message" 
                                       value="{{ $frontendSettings['maintenance_message'] }}"
                                       placeholder="Message de maintenance">
                            </div>
                        </div>

                        <div class="text-end mt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Sauvegarder
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Actions -->
            <div class="card mt-4">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-tools"></i> Actions</h3>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.settings.frontend.preview') }}" class="btn btn-info" target="_blank">
                            <i class="fas fa-eye"></i> Prévisualiser les changements
                        </a>
                        
                        <button type="button" class="btn btn-warning" onclick="if(confirm('Êtes-vous sûr de vouloir réinitialiser tous les paramètres du frontend ?')) { document.getElementById('reset-form').submit(); }">
                            <i class="fas fa-undo"></i> Réinitialiser
                        </button>
                    </div>
                    
                    <form id="reset-form" action="{{ route('admin.settings.frontend.reset') }}" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mettre à jour la prévisualisation des couleurs en temps réel
    const colorInputs = document.querySelectorAll('input[type="color"]');
    colorInputs.forEach(input => {
        input.addEventListener('change', function() {
            const preview = this.parentElement.querySelector('.color-preview');
            if (preview) {
                preview.style.backgroundColor = this.value;
            }
        });
    });

    // Prévisualisation des images avant upload
    const fileInputs = document.querySelectorAll('input[type="file"]');
    fileInputs.forEach(input => {
        input.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = input.closest('.col-md-4').querySelector('.image-preview');
                    if (preview) {
                        preview.src = e.target.result;
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    });
});
</script>
@endsection 