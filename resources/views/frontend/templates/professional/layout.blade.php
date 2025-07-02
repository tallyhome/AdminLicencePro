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
    <!-- Essayer plusieurs sources pour FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&family=Open+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <!-- Backup: Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary-color: #1a365d;
            --secondary-color: #2d3748;
            --accent-color: #3182ce;
            --success-color: #38a169;
            --warning-color: #d69e2e;
            --gray-50: #f9fafb;
            --gray-100: #f7fafc;
            --gray-200: #edf2f7;
            --gray-300: #e2e8f0;
            --gray-400: #cbd5e0;
            --gray-500: #a0aec0;
            --gray-600: #718096;
            --gray-700: #4a5568;
            --gray-800: #2d3748;
            --gray-900: #1a202c;
            --font-primary: 'Roboto', sans-serif;
            --font-secondary: 'Open Sans', sans-serif;
        }

        * {
            font-family: var(--font-primary);
        }

        /* Correction des icônes FontAwesome */
        .fas, .far, .fab {
            font-family: "Font Awesome 6 Free", "Font Awesome 6 Brands" !important;
            font-style: normal !important;
            font-variant: normal !important;
            text-rendering: auto !important;
            line-height: 1 !important;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            display: inline-block !important;
        }

        .fas {
            font-weight: 900 !important;
        }

        .far {
            font-weight: 400 !important;
        }

        /* Forcer l'affichage des icônes */
        i.fas, i.far, i.fab {
            font-family: "Font Awesome 6 Free" !important;
            font-weight: 900 !important;
            display: inline-block !important;
            font-style: normal !important;
            font-variant: normal !important;
            text-rendering: auto !important;
            line-height: 1 !important;
            width: auto !important;
            height: auto !important;
        }

        /* Styles pour les icônes dans les cercles */
        .bg-opacity-10 {
            --bs-bg-opacity: 0.1;
        }

        .rounded-circle {
            border-radius: 50% !important;
        }

        /* Assurer que les icônes sont visibles dans tous les contextes */
        .d-inline-flex i, .bg-opacity-10 i, .rounded-circle i {
            display: inline-block !important;
            font-style: normal !important;
            font-variant: normal !important;
            text-rendering: auto !important;
            line-height: 1 !important;
            font-family: "Font Awesome 6 Free" !important;
            font-weight: 900 !important;
        }

        /* Correction spécifique pour les icônes de contact */
        .contact-info-professional i {
            font-family: "Font Awesome 6 Free" !important;
            font-weight: 900 !important;
            display: inline-block !important;
        }

        body {
            background-color: var(--gray-50);
            color: var(--gray-800);
            line-height: 1.6;
        }

        /* Navigation */
        .navbar-professional {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            box-shadow: 0 2px 20px rgba(26, 54, 93, 0.15);
            padding: 12px 0;
        }

        .navbar-brand-professional {
            font-family: var(--font-secondary);
            font-weight: 700;
            font-size: 1.4rem;
            color: white !important;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .navbar-brand-professional i {
            color: var(--accent-color);
            font-size: 1.6rem;
        }

        .nav-link-professional {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 500;
            padding: 8px 16px !important;
            border-radius: 6px;
            transition: all 0.3s ease;
            font-family: var(--font-secondary);
        }

        .nav-link-professional:hover {
            color: white !important;
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-1px);
        }

        .btn-admin-professional {
            background: var(--accent-color);
            border: none;
            color: white;
            font-weight: 600;
            padding: 8px 20px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .btn-admin-professional:hover {
            background: #2c7ae0;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(49, 130, 206, 0.4);
        }

        /* Hero Section */
        .hero-professional {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 50%, var(--accent-color) 100%);
            color: white;
            padding: 100px 0 80px;
            position: relative;
            overflow: hidden;
        }

        .hero-professional::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 20"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(255,255,255,0.05)" stroke-width="1"/></pattern></defs><rect width="100" height="20" fill="url(%23grid)"/></svg>');
            opacity: 0.3;
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .security-badge-professional {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: rgba(56, 161, 105, 0.15);
            color: var(--success-color);
            padding: 12px 20px;
            border-radius: 30px;
            font-size: 0.95rem;
            font-weight: 600;
            margin-bottom: 25px;
            border: 1px solid rgba(56, 161, 105, 0.3);
        }

        /* Buttons */
        .btn-primary-professional {
            background: var(--accent-color);
            border: none;
            color: white;
            font-weight: 600;
            padding: 14px 28px;
            border-radius: 8px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary-professional:hover {
            background: #2c7ae0;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(49, 130, 206, 0.4);
        }

        .btn-secondary-professional {
            background: var(--gray-100);
            border: 2px solid var(--accent-color);
            color: var(--accent-color);
            font-weight: 600;
            padding: 12px 26px;
            border-radius: 8px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-secondary-professional:hover {
            background: var(--accent-color);
            border-color: var(--accent-color);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(49, 130, 206, 0.3);
        }

        /* Bouton secondaire sur fond sombre */
        .hero-professional .btn-secondary-professional,
        .cta-professional .btn-secondary-professional,
        .bg-primary .btn-secondary-professional {
            background: transparent;
            border: 2px solid rgba(255, 255, 255, 0.7);
            color: white;
        }

        .hero-professional .btn-secondary-professional:hover,
        .cta-professional .btn-secondary-professional:hover,
        .bg-primary .btn-secondary-professional:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: white;
            color: white;
        }

        /* Sections */
        .section-professional {
            padding: 80px 0;
        }

        .section-title-professional {
            font-family: var(--font-secondary);
            font-weight: 700;
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 20px;
            position: relative;
        }

        .section-title-professional::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, var(--accent-color), var(--success-color));
            border-radius: 2px;
        }

        .section-subtitle-professional {
            font-size: 1.2rem;
            color: var(--gray-600);
            margin-bottom: 50px;
            font-weight: 400;
        }

        /* Cards */
        .card-professional {
            background: white;
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 25px rgba(26, 54, 93, 0.08);
            transition: all 0.4s ease;
            height: 100%;
            overflow: hidden;
        }

        .card-professional:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 45px rgba(26, 54, 93, 0.15);
        }

        .card-icon-professional {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--accent-color), var(--success-color));
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            color: white;
            font-size: 2rem;
        }

        /* Testimonials */
        .testimonial-professional {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 25px rgba(26, 54, 93, 0.08);
            border-left: 4px solid var(--accent-color);
            height: 100%;
        }

        .testimonial-quote {
            font-size: 1.1rem;
            color: var(--gray-700);
            font-style: italic;
            margin-bottom: 25px;
            line-height: 1.7;
        }

        .testimonial-author {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .testimonial-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: var(--gray-200);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: var(--primary-color);
            font-size: 1.2rem;
        }

        /* FAQ */
        .faq-professional .accordion-item {
            border: none;
            border-radius: 12px !important;
            margin-bottom: 15px;
            box-shadow: 0 2px 15px rgba(26, 54, 93, 0.05);
        }

        .faq-professional .accordion-button {
            background: white;
            color: var(--primary-color);
            font-weight: 600;
            border-radius: 12px !important;
            padding: 20px 25px;
            font-size: 1.1rem;
        }

        .faq-professional .accordion-button:not(.collapsed) {
            background: var(--gray-50);
            color: var(--accent-color);
        }

        .faq-professional .accordion-body {
            padding: 25px;
            color: var(--gray-700);
            line-height: 1.7;
        }

        /* CTA Section */
        .cta-professional {
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            color: white;
            padding: 80px 0;
            position: relative;
        }

        .cta-professional::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 20"><defs><pattern id="dots" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="20" fill="url(%23dots)"/></svg>');
        }

        /* Footer */
        .footer-professional {
            background: var(--gray-900);
            color: var(--gray-300);
            padding: 60px 0 30px;
        }

        .footer-professional h6 {
            color: white;
            font-weight: 700;
            margin-bottom: 20px;
            font-family: var(--font-secondary);
        }

        .footer-professional a {
            color: var(--gray-400);
            text-decoration: none;
            transition: color 0.3s ease;
            display: block;
            margin-bottom: 8px;
        }

        .footer-professional a:hover {
            color: var(--accent-color);
        }

        .footer-bottom {
            border-top: 1px solid var(--gray-700);
            margin-top: 40px;
            padding-top: 25px;
        }

        /* Dark Mode Styles */
        body.dark-mode {
            background-color: #0d1117;
            color: #e6edf3;
        }

        body.dark-mode .navbar-professional {
            background: linear-gradient(135deg, #161b22, #21262d);
        }

        body.dark-mode .nav-link-professional {
            color: rgba(230, 237, 243, 0.9) !important;
        }

        body.dark-mode .nav-link-professional:hover {
            color: white !important;
            background: rgba(255, 255, 255, 0.1);
        }

        body.dark-mode .btn-admin-professional {
            background: var(--accent-color);
            border-color: var(--accent-color);
        }

        body.dark-mode .card-professional {
            background: #21262d;
            border: 1px solid #30363d;
            color: #e6edf3;
        }

        body.dark-mode .testimonial-professional {
            background: #21262d;
            border-left-color: var(--accent-color);
            border: 1px solid #30363d;
            color: #e6edf3;
        }

        body.dark-mode .testimonial-quote {
            color: #b3bcc8;
        }

        body.dark-mode .testimonial-avatar {
            background: #30363d;
            color: var(--accent-color);
        }

        body.dark-mode .faq-professional .accordion-item {
            background: #21262d;
            border: 1px solid #30363d;
        }

        body.dark-mode .faq-professional .accordion-button {
            background: #21262d;
            color: #e6edf3;
            border: none;
        }

        body.dark-mode .faq-professional .accordion-button:not(.collapsed) {
            background: #161b22;
            color: var(--accent-color);
        }

        body.dark-mode .faq-professional .accordion-body {
            background: #21262d;
            color: #b3bcc8;
            border-top: 1px solid #30363d;
        }

        body.dark-mode .footer-professional {
            background: #010409;
            border-top: 1px solid #21262d;
        }

        body.dark-mode .section-title-professional {
            color: #e6edf3;
        }

        body.dark-mode .section-subtitle-professional {
            color: #b3bcc8;
        }

        /* Styles pour l'icône dark mode */
        #darkModeToggle {
            transition: all 0.3s ease;
            color: rgba(255, 255, 255, 0.9) !important;
        }
        #darkModeToggle:hover {
            transform: rotate(15deg);
            color: white !important;
        }
        body.dark-mode #darkModeToggle {
            color: #fbbf24 !important;
        }
        body.dark-mode #darkModeToggle:hover {
            color: #fcd34d !important;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-professional {
                padding: 80px 0 60px;
            }
            
            .section-title-professional {
                font-size: 2rem;
            }
            
            .section-professional {
                padding: 60px 0;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-professional fixed-top">
        <div class="container">
            <a class="navbar-brand-professional" href="{{ route('frontend.home') }}">
                <i class="fas fa-briefcase"></i>
                {{ $settings['site_title'] ?? 'AdminLicence' }}
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" style="border: none;">
                <span style="color: white;"><i class="fas fa-bars"></i></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link-professional" href="{{ route('frontend.home') }}">{{ $settings['nav_home_text'] ?? 'Accueil' }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link-professional" href="{{ route('frontend.features') }}">{{ $settings['nav_features_text'] ?? 'Fonctionnalités' }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link-professional" href="{{ route('frontend.pricing') }}">{{ $settings['nav_pricing_text'] ?? 'Tarifs' }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link-professional" href="{{ route('frontend.about') }}">{{ $settings['nav_about_text'] ?? 'À propos' }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link-professional" href="{{ route('frontend.faqs') }}">{{ $settings['nav_faq_text'] ?? 'FAQ' }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link-professional" href="{{ route('frontend.contact') }}">{{ $settings['nav_contact_text'] ?? 'Contact' }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link-professional" href="{{ route('frontend.support') }}">{{ $settings['nav_support_text'] ?? 'Support' }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link-professional" href="#" id="darkModeToggle" title="Basculer le mode sombre" style="font-size: 1.2rem;">
                            <i class="fas fa-moon"></i>
                        </a>
                    </li>
                    <li class="nav-item me-2">
                        <a class="btn btn-outline-light" href="{{ route('client.login.form') }}" style="border-color: rgba(255,255,255,0.3);">
                            <i class="fas fa-user"></i> Espace Client
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-admin-professional" href="{{ url('/admin') }}">
                            <i class="fas fa-user-shield"></i> {{ $settings['nav_admin_text'] ?? 'Admin' }}
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
    <footer class="footer-professional">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-briefcase text-primary me-3" style="font-size: 1.5rem;"></i>
                        <h5 class="mb-0 text-white fw-bold">{{ $settings['site_title'] ?? 'AdminLicence' }}</h5>
                    </div>
                    <p class="text-muted mb-4">{{ $settings['site_tagline'] ?? 'Système de gestion de licences ultra-sécurisé' }}</p>
                    <div class="d-flex align-items-center text-success">
                        <i class="fas fa-shield-check me-2"></i>
                        <span class="fw-semibold">{{ $settings['footer_security_badge_text'] ?? 'Chiffrement AES-256' }}</span>
                    </div>
                </div>
                
                <div class="col-lg-2 col-md-6 mb-4">
                    <h6>{{ $settings['footer_product_title'] ?? 'Produit' }}</h6>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('frontend.home') }}">{{ $settings['footer_product_features'] ?? 'Fonctionnalités' }}</a></li>
                        <li><a href="{{ route('frontend.about') }}">{{ $settings['footer_product_about'] ?? 'À propos' }}</a></li>
                        <li><a href="{{ route('frontend.faqs') }}">FAQ</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-2 col-md-6 mb-4">
                    <h6>{{ $settings['footer_support_title'] ?? 'Support' }}</h6>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('frontend.contact') }}">{{ $settings['footer_support_contact'] ?? 'Contact' }}</a></li>
                        <li><a href="#">{{ $settings['footer_support_documentation'] ?? 'Documentation' }}</a></li>
                        <li><a href="#">API</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-4 mb-4">
                    <h6>{{ $settings['footer_contact_title'] ?? 'Contact' }}</h6>
                    @if($settings['contact_email'])
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-envelope text-primary me-3"></i>
                            <span>{{ $settings['contact_email'] }}</span>
                        </div>
                    @endif
                    @if($settings['contact_phone'])
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-phone text-primary me-3"></i>
                            <span>{{ $settings['contact_phone'] }}</span>
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="footer-bottom">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <p class="mb-0">
                            {{ $settings['footer_text'] ?? '© 2025 AdminLicence. Solution sécurisée de gestion de licences.' }}
                        </p>
                        <div class="mt-2">
                            <a href="{{ route('frontend.terms') }}" class="text-muted text-decoration-none me-3 small">
                                {{ $settings['footer_terms_text'] ?? 'Termes et Conditions' }}
                            </a>
                            <a href="{{ route('frontend.privacy') }}" class="text-muted text-decoration-none small">
                                {{ $settings['footer_privacy_text'] ?? 'Politique de confidentialité' }}
                            </a>
                        </div>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <small class="text-muted">
                            <i class="fas fa-shield-check text-success me-1"></i>
                            {{ $settings['footer_secure_text'] ?? 'Site sécurisé par AdminLicence' }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Dark Mode Toggle for Professional Template
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
        <i class="fas fa-eye"></i> MODE PRÉVISUALISATION TEMPLATE PROFESSIONAL - 
        <a href="{{ url('/admin/cms/templates') }}" class="text-dark">Retour aux templates</a>
    </div>
    @endif

    @stack('scripts')
</body>
</html> 