@extends($layout ?? 'frontend.templates.modern.layout')

@section('title', 'Contact - AdminLicence')

@section('content')
<div class="contact-page">
    <!-- Hero Contact -->
    <section class="hero-contact bg-primary text-white py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-3">Contactez-nous</h1>
                    <p class="lead mb-4">Notre équipe est là pour répondre à toutes vos questions et vous accompagner dans votre projet.</p>
                    <div class="contact-info">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-white bg-opacity-20 rounded-circle p-3 me-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                <span class="text-white" style="font-size: 18px;">📧</span>
                            </div>
                            <div>
                                <h6 class="mb-0 text-white">Email</h6>
                                <small class="text-white-50">contact@adminlicence.com</small>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-white bg-opacity-20 rounded-circle p-3 me-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                <span class="text-white" style="font-size: 18px;">📞</span>
                            </div>
                            <div>
                                <h6 class="mb-0 text-white">Téléphone</h6>
                                <small class="text-white-50">+33 1 23 45 67 89</small>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="bg-white bg-opacity-20 rounded-circle p-3 me-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                <span class="text-white" style="font-size: 18px;">🕒</span>
                            </div>
                            <div>
                                <h6 class="mb-0 text-white">Horaires</h6>
                                <small class="text-white-50">Lun-Ven 9h-18h</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="contact-visual text-center">
                        <span class="text-white" style="font-size: 8rem; opacity: 0.3;">🎧</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Formulaire de contact -->
    <section class="contact-form py-5 bg-light">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-lg">
                        <div class="card-header bg-white text-center py-4">
                            <h2 class="mb-0">Envoyez-nous un message</h2>
                            <p class="text-muted mb-0">Nous vous répondrons dans les plus brefs délais</p>
                        </div>
                        <div class="card-body p-5">
                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <span class="me-2">✅</span>
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            @if(session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <span class="me-2">❌</span>
                                    {{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <form action="{{ route('frontend.contact.submit') }}" method="POST" id="contactForm">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                                   id="name" name="name" placeholder="Votre nom" value="{{ old('name') }}" required>
                                            <label for="name"><span class="me-2">👤</span>Nom complet *</label>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                                   id="email" name="email" placeholder="votre@email.com" value="{{ old('email') }}" required>
                                            <label for="email"><span class="me-2">📧</span>Email *</label>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-floating">
                                            <input type="text" class="form-control @error('subject') is-invalid @enderror" 
                                                   id="subject" name="subject" placeholder="Sujet" value="{{ old('subject') }}" required>
                                            <label for="subject"><span class="me-2">🏷️</span>Sujet *</label>
                                            @error('subject')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-floating">
                                            <textarea class="form-control @error('message') is-invalid @enderror" 
                                                      id="message" name="message" placeholder="Votre message" 
                                                      style="height: 120px;" required>{{ old('message') }}</textarea>
                                            <label for="message"><span class="me-2">💬</span>Message *</label>
                                            @error('message')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="text-center mt-4">
                                    <button type="submit" class="btn btn-primary btn-lg px-5 py-3">
                                        <span class="me-2">📤</span>
                                        Envoyer le message
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ rapide -->
    <section class="quick-faq py-5 bg-white">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-6 fw-bold mb-3">Questions Fréquentes</h2>
                <p class="lead text-muted">Peut-être que votre question a déjà une réponse</p>
            </div>
            
            <div class="row">
                <div class="col-lg-6">
                    <div class="faq-item mb-4">
                        <h5 class="fw-bold text-primary mb-2">
                            <span class="me-2">❓</span>
                            Comment démarrer avec AdminLicence ?
                        </h5>
                        <p class="text-muted">Inscrivez-vous pour un compte gratuit et suivez notre guide de démarrage rapide. Notre équipe peut aussi vous accompagner.</p>
                    </div>
                    <div class="faq-item mb-4">
                        <h5 class="fw-bold text-primary mb-2">
                            <span class="me-2">❓</span>
                            Quel support proposez-vous ?
                        </h5>
                        <p class="text-muted">Support email pour tous, support prioritaire pour les plans Pro, et support dédié 24/7 pour Enterprise.</p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="faq-item mb-4">
                        <h5 class="fw-bold text-primary mb-2">
                            <span class="me-2">❓</span>
                            Puis-je essayer avant d'acheter ?
                        </h5>
                        <p class="text-muted">Oui ! Nous proposons une période d'essai gratuite de 14 jours sans engagement.</p>
                    </div>
                    <div class="faq-item mb-4">
                        <h5 class="fw-bold text-primary mb-2">
                            <span class="me-2">❓</span>
                            Les données sont-elles sécurisées ?
                        </h5>
                        <p class="text-muted">Absolument. Nous utilisons un chiffrement 256-bit et respectons les normes de sécurité les plus strictes.</p>
                    </div>
                </div>
            </div>
            
                        <div class="text-center mt-4">
                <a href="{{ route('frontend.faqs') }}" class="btn btn-primary me-3">
                    <span class="me-2">❓</span>Voir toutes les FAQs
                </a>
                <a href="{{ route('frontend.pricing') }}" class="btn btn-outline-primary">
                    <span class="me-2">🏷️</span>Voir les tarifs
                </a>
            </div>
        </div>
    </section>
</div>

<style>
.contact-page .form-floating > label {
    padding-left: 1rem;
}

.contact-page .form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

.contact-page .faq-item {
    padding: 1.5rem;
    border: 1px solid #e9ecef;
    border-radius: 0.5rem;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.contact-page .faq-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.hero-contact {
    background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
}

@media (max-width: 768px) {
    .contact-page .contact-info {
        margin-top: 2rem;
    }
    
    .contact-page .contact-visual {
        display: none;
    }
}
</style>
@endsection 