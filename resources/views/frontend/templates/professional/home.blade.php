@extends('frontend.templates.professional.layout')

@section('title', ($settings['site_title'] ?? 'AdminLicence') . ' - ' . ($settings['site_tagline'] ?? 'Système de gestion de licences'))

@section('content')
    @if($settings['show_hero'] ?? true)
    <!-- Hero Section -->
    <section class="hero-professional">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 hero-content">
                    <div class="security-badge-professional">
                        <i class="fas fa-shield-check"></i>
                        <span>Certification Sécurité Enterprise</span>
                    </div>
                    
                    <h1 class="display-3 fw-bold mb-4" style="line-height: 1.2;">
                        {{ $settings['hero_title'] ?? 'Sécurisez vos licences logicielles' }}
                    </h1>
                    
                    <p class="lead mb-5" style="font-size: 1.3rem; color: rgba(255,255,255,0.9);">
                        {{ $settings['hero_subtitle'] ?? 'Solution professionnelle de gestion de licences avec sécurité avancée' }}
                    </p>
                    
                    <div class="d-flex flex-wrap gap-4">
                        <a href="{{ $settings['cta_button_url'] ?? '/admin' }}" class="btn-primary-professional">
                            <i class="fas fa-rocket"></i>
                            {{ $settings['hero_button_text'] ?? 'Commencer maintenant' }}
                        </a>
                        <a href="{{ route('frontend.about') }}" class="btn-secondary-professional">
                            <i class="fas fa-info-circle"></i>
                            {{ $settings['hero_button_secondary_text'] ?? 'En savoir plus' }}
                        </a>
                    </div>
                </div>
                
                <div class="col-lg-6 text-center">
                    <div class="position-relative">
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="card-professional p-4">
                                    <div class="card-icon-professional mx-auto mb-3" style="width: 60px; height: 60px;">
                                        <i class="fas fa-lock"></i>
                                    </div>
                                    <h6 class="fw-bold text-primary">Sécurité AES-256</h6>
                                    <small class="text-muted">Cryptage militaire</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="card-professional p-4">
                                    <div class="card-icon-professional mx-auto mb-3" style="width: 60px; height: 60px;">
                                        <i class="fas fa-users"></i>
                                    </div>
                                    <h6 class="fw-bold text-primary">Multi-utilisateurs</h6>
                                    <small class="text-muted">Gestion d'équipe</small>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="card-professional p-4">
                                    <div class="card-icon-professional mx-auto mb-3" style="width: 60px; height: 60px;">
                                        <i class="fas fa-chart-line"></i>
                                    </div>
                                    <h6 class="fw-bold text-primary">Analytics Avancées</h6>
                                    <small class="text-muted">Tableaux de bord en temps réel</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    @if(($settings['show_features'] ?? true) && !empty($content['features']))
    <!-- Features Section -->
    <section class="section-professional bg-white">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title-professional">{{ $settings['features_section_title'] ?? 'Fonctionnalités Avancées' }}</h2>
                <p class="section-subtitle-professional">{{ $settings['features_section_subtitle'] ?? 'Découvrez pourquoi AdminLicence est la solution de référence pour la sécurisation de vos licences' }}</p>
            </div>
            
            <div class="row g-4">
                @foreach($content['features'] as $feature)
                <div class="col-lg-4 col-md-6">
                    <div class="card-professional">
                        <div class="card-body p-4 text-center">
                            @if($feature->icon)
                                <div class="card-icon-professional">
                                    <i class="{{ $feature->icon }}"></i>
                                </div>
                            @endif
                            
                            <h5 class="fw-bold mb-3 text-primary">{{ $feature->title }}</h5>
                            <p class="text-muted mb-4">{{ $feature->description }}</p>
                            
                            @if($feature->link_url)
                                <a href="{{ $feature->link_url }}" class="btn btn-outline-primary btn-sm fw-semibold">
                                    {{ $feature->link_text ?? 'En savoir plus' }}
                                    <i class="fas fa-arrow-right ms-2"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    @if(($settings['show_testimonials'] ?? true) && !empty($content['testimonials']))
    <!-- Testimonials Section -->
    <section class="section-professional" style="background: var(--gray-50);">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title-professional">{{ $settings['testimonials_section_title'] ?? 'Ce que disent nos clients' }}</h2>
                <p class="section-subtitle-professional">{{ $settings['testimonials_section_subtitle'] ?? 'Découvrez les témoignages de nos utilisateurs satisfaits' }}</p>
            </div>
            
            <div class="row g-4">
                @foreach($content['testimonials'] as $testimonial)
                <div class="col-lg-4 col-md-6">
                    <div class="testimonial-professional">
                        <div class="mb-3">
                            <div class="text-warning">
                                @for($i = 0; $i < $testimonial->rating; $i++)
                                    <i class="fas fa-star"></i>
                                @endfor
                                @for($i = $testimonial->rating; $i < 5; $i++)
                                    <i class="far fa-star opacity-50"></i>
                                @endfor
                            </div>
                        </div>
                        
                        <blockquote class="testimonial-quote">
                            "{{ $testimonial->content }}"
                        </blockquote>
                        
                        <div class="testimonial-author">
                            <div class="testimonial-avatar">
                                @if($testimonial->avatar_url)
                                    <img src="{{ $testimonial->avatar_url }}" alt="{{ $testimonial->name }}" class="w-100 h-100 rounded-circle object-fit-cover">
                                @else
                                    {{ strtoupper(substr($testimonial->name, 0, 1)) }}
                                @endif
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold text-primary">{{ $testimonial->name }}</h6>
                                @if($testimonial->position)
                                    <small class="text-muted d-block">{{ $testimonial->position }}</small>
                                @endif
                                @if($testimonial->company)
                                    <small class="text-muted d-block fw-semibold">{{ $testimonial->company }}</small>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    @if(($settings['show_faqs'] ?? true) && !empty($content['faqs']))
    <!-- FAQ Section -->
    <section class="section-professional bg-white">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title-professional">{{ $settings['faqs_section_title'] ?? 'Questions Fréquentes' }}</h2>
                <p class="section-subtitle-professional">{{ $settings['faqs_section_subtitle'] ?? 'Trouvez rapidement les réponses à vos questions' }}</p>
            </div>
            
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="accordion faq-professional" id="faqAccordion">
                        @foreach($content['faqs'] as $index => $faq)
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faq{{ $index }}">
                                <button class="accordion-button {{ $index === 0 ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}">
                                    <i class="fas fa-question-circle text-primary me-3"></i>
                                    {{ $faq->question }}
                                </button>
                            </h2>
                            <div id="collapse{{ $index }}" class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    {!! nl2br(e($faq->answer)) !!}
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <div class="text-center mt-5">
                        <a href="{{ route('frontend.faqs') }}" class="btn btn-outline-primary btn-lg">
                            <i class="fas fa-arrow-right me-2"></i>
                            Voir toutes les FAQs
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- Statistics Section -->
    <section class="section-professional" style="background: linear-gradient(135deg, var(--primary-color), var(--accent-color)); color: white;">
        <div class="container">
            <div class="row text-center">
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="d-flex flex-column align-items-center">
                        <i class="fas fa-users fa-3x mb-3 text-warning"></i>
                        <h3 class="fw-bold mb-2">10,000+</h3>
                        <p class="mb-0 opacity-90">Utilisateurs actifs</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="d-flex flex-column align-items-center">
                        <i class="fas fa-shield-check fa-3x mb-3 text-success"></i>
                        <h3 class="fw-bold mb-2">99.9%</h3>
                        <p class="mb-0 opacity-90">Temps de disponibilité</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="d-flex flex-column align-items-center">
                        <i class="fas fa-globe fa-3x mb-3 text-info"></i>
                        <h3 class="fw-bold mb-2">50+</h3>
                        <p class="mb-0 opacity-90">Pays couverts</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="d-flex flex-column align-items-center">
                        <i class="fas fa-clock fa-3x mb-3" style="color: #ff6b6b;"></i>
                        <h3 class="fw-bold mb-2">24/7</h3>
                        <p class="mb-0 opacity-90">Support technique</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-professional">
        <div class="container position-relative">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h2 class="display-5 fw-bold mb-4">{{ $settings['cta_title'] ?? 'Prêt à sécuriser vos licences ?' }}</h2>
                    <p class="lead mb-0 opacity-90" style="font-size: 1.2rem;">{{ $settings['cta_subtitle'] ?? 'Rejoignez des milliers d\'entreprises qui font confiance à AdminLicence pour protéger leurs logiciels.' }}</p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="{{ $settings['cta_button_url'] ?? '/admin' }}" class="btn btn-light btn-lg px-5 py-3 fw-bold" style="border-radius: 10px;">
                        <i class="fas fa-rocket me-2"></i>
                        {{ $settings['cta_button_text'] ?? 'Commencer maintenant' }}
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection 