<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Connexion - {{ config('app.name') }}</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
                            <div class="col-lg-6 d-none d-lg-block">
                                <div class="p-5 bg-gradient-primary text-white d-flex flex-column justify-content-center h-100">
                                    <h2 class="mb-4">Bon retour !</h2>
                                    <p class="mb-4">Connectez-vous à votre compte {{ config('app.name') }} pour gérer vos licences et projets.</p>
                                    <div class="text-center mt-4">
                                        <i class="fas fa-lock fa-3x opacity-75"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center mb-4">
                                        <h1 class="h4 text-gray-900">Connexion</h1>
                                        <p class="text-muted">Accédez à votre espace client</p>
                                    </div>

                                    @if (session('success'))
                                        <div class="alert alert-success">
                                            {{ session('success') }}
                                        </div>
                                    @endif

                                    @if (session('error'))
                                        <div class="alert alert-danger">
                                            {{ session('error') }}
                                        </div>
                                    @endif

                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            @foreach ($errors->all() as $error)
                                                {{ $error }}<br>
                                            @endforeach
                                        </div>
                                    @endif

                                    <form method="POST" action="{{ route('client.login') }}">
                                        @csrf

                                        <div class="form-group mb-3">
                                            <input type="email" 
                                                   name="email" 
                                                   class="form-control form-control-user @error('email') is-invalid @enderror" 
                                                   placeholder="Adresse email"
                                                   value="{{ old('email') }}" 
                                                   required 
                                                   autofocus>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group mb-3">
                                            <input type="password" 
                                                   name="password" 
                                                   class="form-control form-control-user @error('password') is-invalid @enderror" 
                                                   placeholder="Mot de passe"
                                                   required>
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" 
                                                       type="checkbox" 
                                                       name="remember" 
                                                       id="remember"
                                                       {{ old('remember') ? 'checked' : '' }}>
                                                <label class="form-check-label" for="remember">
                                                    Se souvenir de moi
                                                </label>
                                            </div>
                                        </div>

                                        <button type="submit" class="btn btn-primary btn-user btn-block w-100">
                                            <i class="fas fa-sign-in-alt me-1"></i>
                                            Se connecter
                                        </button>
                                    </form>

                                    <hr class="my-4">

                                    <div class="text-center">
                                        <a class="small text-decoration-none" href="{{ route('client.forgot-password.form') }}">
                                            Mot de passe oublié ?
                                        </a>
                                    </div>
                                    
                                    <div class="text-center mt-3">
                                        <p class="small text-muted">
                                            Pas encore de compte ? 
                                            <a href="{{ route('client.register.form') }}" class="text-decoration-none">
                                                <strong>Créer un compte</strong>
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
</body>
</html> 