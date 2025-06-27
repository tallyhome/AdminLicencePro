@extends('frontend.templates.modern.layout')

@section('title', ($settings['site_title'] ?? 'AdminLicence') . ' - ' . ($settings['site_tagline'] ?? 'Système de gestion de licences'))

@section('content')
    @if($settings['show_hero'] ?? true)
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="security-badge">
                        <i class="fas fa-shield-check"></i>
                        <span>Sécurité Certifiée AES-256</span>
                    </div>
                    
                    <h1 class="display-4 fw-bold mb-4">
                        {{ $settings['hero_title'] ?? 'Sécurisez vos licences logicielles' }}
                    </h1>
                    
                    <p class="lead mb-4">
                        {{ $settings['hero_subtitle'] ?? 'Solution professionnelle de gestion de licences avec sécurité avancée' }}
                    </p>
                    
                    <div class="d-flex flex-wrap gap-3">
                        <a href="{{ url('/admin') }}" class="btn btn-light btn-lg px-4">
                            <i class="fas fa-rocket"></i>
                            Commencer maintenant
                        </a>
                        <a href="{{ route('frontend.about') }}" class="btn btn-outline-light btn-lg px-4">
                            <i class="fas fa-info-circle"></i>
                            En savoir plus
                        </a>
                    </div>
                </div>
                
                <div class="col-lg-6 text-center">
                    <div class="position-relative">
                        <div class="bg-white bg-opacity-10 rounded-3 p-4 backdrop-blur">
                            <i class="fas fa-shield-alt fa-8x text-white opacity-75"></i>
                            <div class="mt-3">
                                <h3 class="h5">Sécurité Maximale</h3>
                                <p class="small mb-0">Chiffrement cryptographique avancé</p>
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
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">Fonctionnalités Avancées</h2>
                <p class="text-muted">Découvrez pourquoi AdminLicence est la solution de référence pour la sécurisation de vos licences</p>
            </div>
            
            <div class="row g-4">
                @foreach($content['features'] as $feature)
                <div class="col-lg-4 col-md-6">
                    <div class="card feature-card shadow-sm">
                        <div class="card-body text-center p-4">
                            @if($feature->icon)
                                <div class="mb-3">
                                    <i class="{{ $feature->icon }} fa-3x text-primary"></i>
                                </div>
                            @endif
                            
                            <h5 class="fw-bold mb-3">{{ $feature->title }}</h5>
                            <p class="text-muted mb-4">{{ $feature->description }}</p>
                            
                            @if($feature->link_url)
                                <a href="{{ $feature->link_url }}" class="btn btn-primary">
                                    {{ $feature->link_text ?? 'En savoir plus' }}
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
    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">Ce que disent nos clients</h2>
                <p class="text-muted">Découvrez les témoignages de nos utilisateurs satisfaits</p>
            </div>
            
            <div class="row g-4">
                @foreach($content['testimonials'] as $testimonial)
                <div class="col-lg-4 col-md-6">
                    <div class="testimonial-card">
                        <div class="mb-3">
                            <div class="text-warning">
                                @for($i = 0; $i < $testimonial->rating; $i++)
                                    <i class="fas fa-star"></i>
                                @endfor
                                @for($i = $testimonial->rating; $i < 5; $i++)
                                    <i class="far fa-star"></i>
                                @endfor
                            </div>
                        </div>
                        
                        <blockquote class="mb-4">
                            "{{ $testimonial->content }}"
                        </blockquote>
                        
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                @if($testimonial->avatar_url)
                                    <img src="{{ $testimonial->avatar_url }}" alt="{{ $testimonial->name }}" class="rounded-circle" width="50" height="50">
                                @else
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                        {{ strtoupper(substr($testimonial->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0 fw-bold">{{ $testimonial->name }}</h6>
                                @if($testimonial->position)
                                    <small class="text-muted">{{ $testimonial->position }}</small>
                                @endif
                                @if($testimonial->company)
                                    <small class="text-muted">{{ $testimonial->position ? ' - ' : '' }}{{ $testimonial->company }}</small>
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
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">Questions Fréquentes</h2>
                <p class="text-muted">Trouvez rapidement les réponses à vos questions</p>
            </div>
            
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="accordion" id="faqAccordion">
                        @foreach($content['faqs'] as $index => $faq)
                        <div class="accordion-item faq-item">
                            <h2 class="accordion-header" id="faq{{ $index }}">
                                <button class="accordion-button {{ $index === 0 ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}">
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
                    
                    <div class="text-center mt-4">
                        <a href="{{ route('frontend.faqs') }}" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-right"></i>
                            Voir toutes les FAQs
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- CTA Section -->
    <section class="py-5 bg-primary text-white">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h2 class="fw-bold mb-3">Prêt à sécuriser vos licences ?</h2>
                    <p class="lead mb-0">Rejoignez des milliers d'entreprises qui font confiance à AdminLicence pour protéger leurs logiciels.</p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="{{ url('/admin') }}" class="btn btn-light btn-lg px-4">
                        <i class="fas fa-rocket"></i>
                        Commencer maintenant
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection 