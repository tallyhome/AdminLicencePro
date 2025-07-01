@php
    // S'assurer que $settings existe avec des valeurs par défaut
    $settings = $settings ?? [
        'site_title' => 'AdminLicence',
        'site_tagline' => 'Système de gestion de licences ultra-sécurisé',
        'contact_email' => '',
        'contact_phone' => '',
        'footer_text' => '© 2025 AdminLicence. Solution sécurisée de gestion de licences.'
    ];
@endphp
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', ($settings['site_title'] ?? 'AdminLicence'))</title>
    <meta name="description" content="@yield('description', $settings['site_tagline'] ?? 'Système de gestion de licences ultra-sécurisé')">
    
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #64748b;
            --accent-color: #059669;
            --font-family: 'Inter, sans-serif';
        }

        * {
            font-family: var(--font-family);
        }

        /* Correction des icônes FontAwesome */
        .fas, .far, .fab {
            font-family: "Font Awesome 6 Free", "Font Awesome 6 Brands" !important;
            font-style: normal;
            font-variant: normal;
            text-rendering: auto;
            line-height: 1;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .fas {
            font-weight: 900;
        }

        .far {
            font-weight: 400;
        }

        /* Styles pour les icônes dans les cercles */
        .bg-opacity-10 {
            --bs-bg-opacity: 0.1;
        }

        .rounded-circle {
            border-radius: 50% !important;
        }

        /* Assurer que les icônes sont visibles */
        .d-inline-flex i {
            display: inline-block !important;
            font-style: normal !important;
            font-variant: normal !important;
            text-rendering: auto !important;
            line-height: 1 !important;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: color-mix(in srgb, var(--primary-color) 85%, black);
            border-color: color-mix(in srgb, var(--primary-color) 85%, black);
        }

        .text-primary {
            color: var(--primary-color) !important;
        }

        .bg-primary {
            background-color: var(--primary-color) !important;
        }

        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }

        .hero-section {
            background: linear-gradient(135deg, var(--primary-color), color-mix(in srgb, var(--primary-color) 80%, var(--accent-color)));
            color: white;
            padding: 80px 0;
        }

        .feature-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
            border-radius: 12px;
            height: 100%;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .section-title {
            position: relative;
            margin-bottom: 3rem;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: var(--accent-color);
            border-radius: 2px;
        }

        .footer {
            background: #1a1a1a;
            color: #ffffff;
        }

        .security-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(5, 150, 105, 0.1);
            color: var(--accent-color);
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 500;
            margin-bottom: 20px;
        }

        .testimonial-card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(0, 0, 0, 0.05);
            height: 100%;
        }

        .faq-item {
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            margin-bottom: 1rem;
        }

        .faq-header {
            background: rgba(var(--primary-color), 0.05);
            padding: 20px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .faq-header:hover {
            background: rgba(var(--primary-color), 0.1);
        }

        @media (max-width: 768px) {
            .hero-section {
                padding: 60px 0;
            }
            
            .hero-section h1 {
                font-size: 2rem;
            }
        }

        /* Dark Mode Styles */
        body.dark-mode {
            background-color: #1a1a1a;
            color: #e0e0e0;
        }

        body.dark-mode .navbar {
            background: rgba(30, 30, 30, 0.95) !important;
            border-bottom-color: rgba(255, 255, 255, 0.1);
        }

        body.dark-mode .navbar .nav-link {
            color: #e0e0e0 !important;
        }

        body.dark-mode .navbar .nav-link:hover {
            color: #ffffff !important;
        }

        body.dark-mode .hero-section {
            background: linear-gradient(135deg, #1e3a8a, #1e293b);
        }

        body.dark-mode .feature-card {
            background-color: #2a2a2a;
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #e0e0e0;
        }

        body.dark-mode .testimonial-card {
            background-color: #2a2a2a;
            border-color: rgba(255, 255, 255, 0.1);
            color: #e0e0e0;
        }

        body.dark-mode .faq-item {
            border-color: rgba(255, 255, 255, 0.1);
        }

        body.dark-mode .faq-header {
            background: rgba(255, 255, 255, 0.05);
            color: #e0e0e0;
        }

        body.dark-mode .faq-header:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        body.dark-mode .footer {
            background: #0d0d0d;
            border-top-color: rgba(255, 255, 255, 0.1);
        }

        body.dark-mode .footer a {
            color: #4e9eff !important;
        }

        body.dark-mode .footer a:hover {
            color: #7ab8ff !important;
        }

        body.dark-mode .btn-primary {
            background-color: #1e3a8a;
            border-color: #1e3a8a;
        }

        body.dark-mode .btn-primary:hover {
            background-color: #1e4eb8;
            border-color: #1e4eb8;
        }

        body.dark-mode .security-badge {
            background: rgba(16, 185, 129, 0.2);
            color: #10b981;
        }

        /* Styles pour l'icône dark mode */
        #darkModeToggle {
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
            color: var(--primary-color);
        }
        #darkModeToggle:hover {
            transform: rotate(15deg);
            opacity: 0.8;
        }
        body.dark-mode #darkModeToggle {
            color: #fbbf24;
        }
        body.dark-mode #darkModeToggle:hover {
            color: #fcd34d;
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="{{ route('frontend.home') }}">
                <i class="fas fa-shield-alt"></i>
                {{ $settings['site_title'] ?? 'AdminLicence' }}
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('frontend.home') }}">{{ $settings['nav_home_text'] ?? 'Accueil' }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('frontend.features') }}">{{ $settings['nav_features_text'] ?? 'Fonctionnalités' }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('frontend.pricing') }}">{{ $settings['nav_pricing_text'] ?? 'Tarifs' }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('frontend.about') }}">{{ $settings['nav_about_text'] ?? 'À propos' }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('frontend.faqs') }}">{{ $settings['nav_faq_text'] ?? 'FAQ' }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('frontend.contact') }}">{{ $settings['nav_contact_text'] ?? 'Contact' }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('frontend.support') }}">{{ $settings['nav_support_text'] ?? 'Support' }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" id="darkModeToggle" title="Basculer le mode sombre" style="font-size: 1.2rem;">
                            <i class="fas fa-moon"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-primary text-white px-3 ms-2" href="{{ url('/admin') }}">
                            <i class="fas fa-sign-in-alt"></i> {{ $settings['nav_admin_text'] ?? 'Admin' }}
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <main style="margin-top: 76px;">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h5 class="fw-bold mb-3">
                        <i class="fas fa-shield-alt text-primary"></i>
                        {{ $settings['site_title'] ?? 'AdminLicence' }}
                    </h5>
                    <p class="text-light">{{ $settings['site_tagline'] ?? 'Système de gestion de licences ultra-sécurisé' }}</p>
                    <div class="security-badge">
                        <i class="fas fa-lock"></i>
                        <span>{{ $settings['footer_security_badge_text'] ?? 'Chiffrement AES-256' }}</span>
                    </div>
                </div>
                
                <div class="col-lg-2 col-md-6 mb-4">
                    <h6 class="fw-bold mb-3">{{ $settings['footer_product_title'] ?? 'Produit' }}</h6>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('frontend.home') }}" class="text-light text-decoration-none">{{ $settings['footer_product_features'] ?? 'Fonctionnalités' }}</a></li>
                        <li><a href="{{ route('frontend.about') }}" class="text-light text-decoration-none">{{ $settings['footer_product_about'] ?? 'À propos' }}</a></li>
                        <li><a href="{{ route('frontend.faqs') }}" class="text-light text-decoration-none">FAQ</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-2 col-md-6 mb-4">
                    <h6 class="fw-bold mb-3">{{ $settings['footer_support_title'] ?? 'Support' }}</h6>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('frontend.contact') }}" class="text-light text-decoration-none">{{ $settings['footer_support_contact'] ?? 'Contact' }}</a></li>
                        <li><a href="#" class="text-light text-decoration-none">{{ $settings['footer_support_documentation'] ?? 'Documentation' }}</a></li>
                        <li><a href="#" class="text-light text-decoration-none">API</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-4 mb-4">
                    <h6 class="fw-bold mb-3">{{ $settings['footer_contact_title'] ?? 'Contact' }}</h6>
                    @if($settings['contact_email'])
                        <p class="text-light">
                            <i class="fas fa-envelope"></i>
                            {{ $settings['contact_email'] }}
                        </p>
                    @endif
                    @if($settings['contact_phone'])
                        <p class="text-light">
                            <i class="fas fa-phone"></i>
                            {{ $settings['contact_phone'] }}
                        </p>
                    @endif
                </div>
            </div>
            
            <hr class="my-4 opacity-25">
            
            <div class="row align-items-center">
                <div class="col-md-8">
                    <p class="text-light mb-0">
                        {{ $settings['footer_text'] ?? '© 2025 AdminLicence. Solution sécurisée de gestion de licences.' }}
                    </p>
                    <div class="mt-2">
                        <a href="{{ route('frontend.terms') }}" class="text-light text-decoration-none me-3 small opacity-75">
                            {{ $settings['footer_terms_text'] ?? 'Termes et Conditions' }}
                        </a>
                        <a href="{{ route('frontend.privacy') }}" class="text-light text-decoration-none small opacity-75">
                            {{ $settings['footer_privacy_text'] ?? 'Politique de confidentialité' }}
                        </a>
                    </div>
                </div>
                <div class="col-md-4 text-md-end">
                    <small class="text-light opacity-75">
                        <i class="fas fa-shield-check text-success"></i>
                        {{ $settings['footer_secure_text'] ?? 'Site sécurisé par AdminLicence' }}
                    </small>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Dark Mode Toggle for Frontend
        document.addEventListener('DOMContentLoaded', function() {
            const darkModeToggle = document.getElementById('darkModeToggle');
            const body = document.body;
            const icon = darkModeToggle.querySelector('i');
            
            // Vérifier le mode sombre sauvegardé
            const isDarkMode = localStorage.getItem('darkMode') === 'true';
            
            // Appliquer le mode initial
            if (isDarkMode) {
                body.classList.add('dark-mode');
                icon.classList.remove('fa-moon');
                icon.classList.add('fa-sun');
                darkModeToggle.title = 'Mode clair';
            }
            
            // Gérer le clic sur l'icône
            darkModeToggle.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Basculer le mode
                const isDark = body.classList.toggle('dark-mode');
                
                // Changer l'icône
                if (isDark) {
                    icon.classList.remove('fa-moon');
                    icon.classList.add('fa-sun');
                    this.title = 'Mode clair';
                } else {
                    icon.classList.remove('fa-sun');
                    icon.classList.add('fa-moon');
                    this.title = 'Mode sombre';
                }
                
                // Sauvegarder la préférence
                localStorage.setItem('darkMode', isDark);
            });
        });
    </script>
    
    @if($isPreview ?? false)
    <div class="position-fixed top-0 start-0 w-100 bg-warning text-center py-2 text-dark fw-bold" style="z-index: 9999;">
        <i class="fas fa-eye"></i> MODE PRÉVISUALISATION - 
        <a href="{{ url('/admin/cms') }}" class="text-dark">Retour à l'admin</a>
    </div>
    @endif

    @stack('scripts')
</body>
</html> 