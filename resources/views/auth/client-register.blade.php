<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Inscription - {{ config('app.name') }}</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .plan-card {
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid #e3e6f0;
        }
        
        .plan-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .plan-card.selected {
            border-color: #5a5c69;
            background-color: rgba(90, 92, 105, 0.05);
        }
        
        .step-content {
            min-height: 400px;
        }
        
        .form-control-user {
            border-radius: 10rem;
            padding: 1.5rem 1rem;
        }
        
        .btn-user {
            border-radius: 10rem;
            padding: 0.75rem 1.5rem;
        }
    </style>
</head>
<body class="bg-gradient-primary">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12 col-md-9">
                <div class="card shadow-lg my-5">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-lg-5 d-none d-lg-block">
                                <div class="p-5 bg-gradient-primary text-white d-flex flex-column justify-content-center h-100">
                                    <h2 class="mb-4">Rejoignez {{ config('app.name') }}</h2>
                                    <p class="mb-4">Créez votre compte et commencez à gérer vos licences en quelques minutes.</p>
                                    <ul class="list-unstyled">
                                        <li class="mb-2"><i class="fas fa-check me-2"></i> Configuration rapide</li>
                                        <li class="mb-2"><i class="fas fa-check me-2"></i> Interface intuitive</li>
                                        <li class="mb-2"><i class="fas fa-check me-2"></i> Support professionnel</li>
                                        <li class="mb-2"><i class="fas fa-check me-2"></i> Sécurité garantie</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-lg-7">
                                <div class="p-5">
                                    <div class="text-center mb-4">
                                        <h1 class="h4 text-gray-900">Créer un compte</h1>
                                        <p class="text-muted">Commençons votre aventure</p>
                                    </div>

                                    @if (session('error'))
                                        <div class="alert alert-danger">
                                            {{ session('error') }}
                                        </div>
                                    @endif

                                    <!-- Indicateur d'étapes -->
                                    <div class="d-flex justify-content-between mb-4">
                                        <div class="step-indicator">
                                            <div class="step active" id="indicator-1">
                                                <i class="fas fa-user"></i>
                                                <div class="small">Personnel</div>
                                            </div>
                                            <div class="step" id="indicator-2">
                                                <i class="fas fa-building"></i>
                                                <div class="small">Entreprise</div>
                                            </div>
                                            <div class="step" id="indicator-3">
                                                <i class="fas fa-star"></i>
                                                <div class="small">Plan</div>
                                            </div>
                                        </div>
                                    </div>

                                    <form method="POST" action="{{ route('client.register') }}" id="registerForm">
                                        @csrf

                                        <!-- Étape 1: Informations personnelles -->
                                        <div class="step-content" id="step-1">
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <input type="text" 
                                                           name="name" 
                                                           class="form-control form-control-user @error('name') is-invalid @enderror" 
                                                           placeholder="Nom complet"
                                                           value="{{ old('name') }}" 
                                                           required>
                                                    @error('name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <input type="email" 
                                                           name="email" 
                                                           class="form-control form-control-user @error('email') is-invalid @enderror" 
                                                           placeholder="Adresse email"
                                                           value="{{ old('email') }}" 
                                                           required>
                                                    @error('email')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <input type="password" 
                                                           name="password" 
                                                           class="form-control form-control-user @error('password') is-invalid @enderror" 
                                                           placeholder="Mot de passe"
                                                           required>
                                                    @error('password')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <input type="password" 
                                                           name="password_confirmation" 
                                                           class="form-control form-control-user" 
                                                           placeholder="Confirmer le mot de passe"
                                                           required>
                                                </div>
                                            </div>

                                            <div class="text-center">
                                                <button type="button" class="btn btn-primary btn-user px-5" onclick="nextStep(2)">
                                                    Suivant <i class="fas fa-arrow-right ms-1"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Étape 2: Informations entreprise -->
                                        <div class="step-content" id="step-2" style="display: none;">
                                            <div class="mb-3">
                                                <input type="text" 
                                                       name="company_name" 
                                                       class="form-control form-control-user @error('company_name') is-invalid @enderror" 
                                                       placeholder="Nom de l'entreprise"
                                                       value="{{ old('company_name') }}" 
                                                       required>
                                                @error('company_name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <input type="text" 
                                                           name="phone" 
                                                           class="form-control form-control-user @error('phone') is-invalid @enderror" 
                                                           placeholder="Téléphone (optionnel)"
                                                           value="{{ old('phone') }}">
                                                    @error('phone')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <select name="country" 
                                                            class="form-control @error('country') is-invalid @enderror" 
                                                            style="border-radius: 10rem; padding: 1.5rem 1rem;"
                                                            required>
                                                        <option value="">Sélectionner un pays</option>
                                                        <option value="FR" {{ old('country') == 'FR' ? 'selected' : '' }}>France</option>
                                                        <option value="BE" {{ old('country') == 'BE' ? 'selected' : '' }}>Belgique</option>
                                                        <option value="CH" {{ old('country') == 'CH' ? 'selected' : '' }}>Suisse</option>
                                                        <option value="CA" {{ old('country') == 'CA' ? 'selected' : '' }}>Canada</option>
                                                        <option value="US" {{ old('country') == 'US' ? 'selected' : '' }}>États-Unis</option>
                                                    </select>
                                                    @error('country')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <input type="text" 
                                                       name="address" 
                                                       class="form-control form-control-user @error('address') is-invalid @enderror" 
                                                       placeholder="Adresse (optionnel)"
                                                       value="{{ old('address') }}">
                                                @error('address')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="row">
                                                <div class="col-md-8 mb-3">
                                                    <input type="text" 
                                                           name="city" 
                                                           class="form-control form-control-user @error('city') is-invalid @enderror" 
                                                           placeholder="Ville (optionnel)"
                                                           value="{{ old('city') }}">
                                                    @error('city')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <input type="text" 
                                                           name="postal_code" 
                                                           class="form-control form-control-user @error('postal_code') is-invalid @enderror" 
                                                           placeholder="Code postal"
                                                           value="{{ old('postal_code') }}">
                                                    @error('postal_code')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="text-center">
                                                <button type="button" class="btn btn-outline-secondary btn-user me-2" onclick="previousStep(1)">
                                                    <i class="fas fa-arrow-left"></i> Précédent
                                                </button>
                                                <button type="button" class="btn btn-primary btn-user px-4" onclick="nextStep(3)">
                                                    Suivant <i class="fas fa-arrow-right ms-1"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Étape 3: Sélection du plan -->
                                        <div class="step-content" id="step-3" style="display: none;">
                                            <h5 class="text-center mb-4">Choisissez votre plan</h5>
                                            
                                            @foreach($plans as $plan)
                                                <div class="plan-card mb-3 p-3 rounded" data-plan-id="{{ $plan->id }}">
                                                    <div class="d-flex align-items-center">
                                                        <input class="form-check-input me-3" 
                                                               type="radio" 
                                                               name="plan_id" 
                                                               value="{{ $plan->id }}" 
                                                               id="plan_{{ $plan->id }}"
                                                               {{ $loop->index === 1 ? 'checked' : '' }}
                                                               required>
                                                        <div class="flex-grow-1">
                                                            <div class="d-flex justify-content-between align-items-start">
                                                                <div>
                                                                    <h6 class="mb-1">{{ $plan->name }}</h6>
                                                                    <p class="text-muted mb-2 small">{{ $plan->description }}</p>
                                                                    @if($plan->features)
                                                                        <div class="d-flex flex-wrap gap-2">
                                                                            @foreach(array_slice($plan->features, 0, 3) as $feature)
                                                                                <span class="badge bg-success bg-opacity-10 text-success">
                                                                                    <i class="fas fa-check me-1"></i>{{ $feature }}
                                                                                </span>
                                                                            @endforeach
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                                <div class="text-end">
                                                                    <div class="h5 mb-0">
                                                                        @if($plan->price > 0)
                                                                            {{ number_format($plan->price, 2) }}€
                                                                            <small class="text-muted d-block">
                                                                                /{{ $plan->billing_cycle === 'yearly' ? 'an' : 'mois' }}
                                                                            </small>
                                                                        @else
                                                                            <span class="text-success">Gratuit</span>
                                                                        @endif
                                                                    </div>
                                                                    @if($plan->trial_days > 0)
                                                                        <small class="badge bg-info">
                                                                            {{ $plan->trial_days }} jours d'essai
                                                                        </small>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach

                                            @error('plan_id')
                                                <div class="text-danger text-center mb-3">{{ $message }}</div>
                                            @enderror

                                            <div class="form-check mb-4">
                                                <input type="checkbox" 
                                                       name="terms_accepted" 
                                                       class="form-check-input @error('terms_accepted') is-invalid @enderror" 
                                                       id="terms_accepted" 
                                                       required>
                                                <label class="form-check-label small" for="terms_accepted">
                                                    J'accepte les 
                                                    <a href="{{ route('frontend.terms') }}" target="_blank">conditions d'utilisation</a> 
                                                    et la 
                                                    <a href="{{ route('frontend.privacy') }}" target="_blank">politique de confidentialité</a>
                                                </label>
                                                @error('terms_accepted')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="text-center">
                                                <button type="button" class="btn btn-outline-secondary btn-user me-2" onclick="previousStep(2)">
                                                    <i class="fas fa-arrow-left"></i> Précédent
                                                </button>
                                                <button type="submit" class="btn btn-success btn-user px-5" onclick="return validateBeforeSubmit()">
                                                    <i class="fas fa-rocket me-1"></i>
                                                    Créer mon compte
                                                </button>
                                            </div>
                                        </div>
                                    </form>

                                    <hr class="mt-4">
                                    <div class="text-center">
                                        <p class="small text-muted">
                                            Vous avez déjà un compte ? 
                                            <a href="{{ route('client.login.form') }}" class="text-decoration-none">
                                                Se connecter
                                            </a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function nextStep(step) {
            if (!validateCurrentStep(step - 1)) {
                return;
            }
            
            // Mettre à jour les indicateurs
            updateStepIndicators(step);
            
            // Cacher toutes les étapes
            document.querySelectorAll('.step-content').forEach(el => {
                el.style.display = 'none';
            });
            
            // Afficher l'étape actuelle
            document.getElementById(`step-${step}`).style.display = 'block';
        }

        function previousStep(step) {
            // Mettre à jour les indicateurs
            updateStepIndicators(step);
            
            // Cacher toutes les étapes
            document.querySelectorAll('.step-content').forEach(el => {
                el.style.display = 'none';
            });
            
            // Afficher l'étape précédente
            document.getElementById(`step-${step}`).style.display = 'block';
        }

        function updateStepIndicators(activeStep) {
            document.querySelectorAll('.step').forEach((el, index) => {
                el.classList.remove('active');
                if (index + 1 === activeStep) {
                    el.classList.add('active');
                }
            });
        }

        function validateCurrentStep(step) {
            if (step === 1) {
                const name = document.querySelector('input[name="name"]').value.trim();
                const email = document.querySelector('input[name="email"]').value.trim();
                const password = document.querySelector('input[name="password"]').value;
                const passwordConfirm = document.querySelector('input[name="password_confirmation"]').value;
                
                if (!name || !email || !password || !passwordConfirm) {
                    alert('Veuillez remplir tous les champs obligatoires de l\'étape 1');
                    return false;
                }
                
                // Validation email simple
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email)) {
                    alert('Veuillez saisir un email valide');
                    return false;
                }
                
                if (password !== passwordConfirm) {
                    alert('Les mots de passe ne correspondent pas');
                    return false;
                }
                
                if (password.length < 8) {
                    alert('Le mot de passe doit contenir au moins 8 caractères');
                    return false;
                }
            }
            
            if (step === 2) {
                const companyName = document.querySelector('input[name="company_name"]').value.trim();
                const country = document.querySelector('select[name="country"]').value;
                
                if (!companyName || !country) {
                    alert('Veuillez remplir les champs obligatoires de l\'étape 2');
                    return false;
                }
            }
            
            if (step === 3) {
                const planSelected = document.querySelector('input[name="plan_id"]:checked');
                const termsAccepted = document.querySelector('input[name="terms_accepted"]').checked;
                
                if (!planSelected) {
                    alert('Veuillez sélectionner un plan d\'abonnement');
                    return false;
                }
                
                if (!termsAccepted) {
                    alert('Veuillez accepter les conditions d\'utilisation');
                    return false;
                }
            }
            
            return true;
        }

        function validateBeforeSubmit() {
            // Vérifier que nous sommes bien à l'étape 3
            const currentStep = document.getElementById('step-3');
            if (!currentStep || currentStep.style.display === 'none') {
                alert('Veuillez compléter toutes les étapes avant de soumettre');
                return false;
            }
            
            // Valider l'étape 3
            if (!validateCurrentStep(3)) {
                return false;
            }
            
            // Vérifier que tous les champs requis sont remplis
            const requiredFields = [
                'name', 'email', 'password', 'password_confirmation', 
                'company_name', 'country', 'plan_id'
            ];
            
            for (let field of requiredFields) {
                const element = document.querySelector(`input[name="${field}"], select[name="${field}"]`);
                if (!element || !element.value.trim()) {
                    alert(`Le champ ${field} est requis`);
                    return false;
                }
            }
            
            // Vérifier les conditions
            const termsAccepted = document.querySelector('input[name="terms_accepted"]').checked;
            if (!termsAccepted) {
                alert('Veuillez accepter les conditions d\'utilisation');
                return false;
            }
            
            console.log('Validation réussie, soumission du formulaire...');
            return true;
        }

        // Gérer la sélection des plans
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.plan-card').forEach(card => {
                card.addEventListener('click', function() {
                    // Désélectionner tous les plans
                    document.querySelectorAll('.plan-card').forEach(c => c.classList.remove('selected'));
                    
                    // Sélectionner le plan cliqué
                    this.classList.add('selected');
                    const radio = this.querySelector('input[type="radio"]');
                    radio.checked = true;
                });
            });
            
            // Sélectionner le plan par défaut
            const defaultPlan = document.querySelector('input[name="plan_id"]:checked');
            if (defaultPlan) {
                defaultPlan.closest('.plan-card').classList.add('selected');
            }
        });
    </script>

    <style>
        .step-indicator {
            display: flex;
            width: 100%;
            justify-content: space-between;
        }
        
        .step {
            flex: 1;
            text-align: center;
            padding: 10px;
            color: #adb5bd;
            border-bottom: 2px solid #e3e6f0;
            transition: all 0.3s ease;
        }
        
        .step.active {
            color: #5a5c69;
            border-bottom-color: #5a5c69;
            font-weight: 600;
        }
        
        .step i {
            font-size: 1.2rem;
            margin-bottom: 5px;
            display: block;
        }
    </style>
</body>
</html> 