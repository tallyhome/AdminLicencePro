@extends($layout ?? 'frontend.templates.modern.layout')

@section('title', 'À propos - AdminLicence')

@section('content')
<div class="about-page">
    <!-- Hero À propos -->
    <section class="hero-about bg-primary text-white py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-3">À propos d'AdminLicence</h1>
                    <p class="lead mb-4">Nous sommes une équipe passionnée dédiée à la protection de votre propriété intellectuelle.</p>
                </div>
                <div class="col-lg-6">
                    <div class="text-center">
                        <span class="text-white" style="font-size: 8rem; opacity: 0.3;">🛡️</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Notre Mission -->
    <section class="mission py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold mb-3">Notre Mission</h2>
                <p class="lead text-muted">Fournir des solutions de licences robustes et sécurisées</p>
            </div>
            
            <div class="row g-4">
                @if($aboutSections && $aboutSections->count() > 0)
                    @foreach($aboutSections as $section)
                    <div class="col-lg-4">
                        <div class="mission-card h-100 bg-white p-4 rounded shadow-sm">
                            <div class="mission-icon mb-3">
                                <span class="text-primary" style="font-size: 3rem;">
                                    @if($section->icon && str_contains($section->icon, 'shield'))
                                        🛡️
                                    @elseif($section->icon && str_contains($section->icon, 'rocket'))
                                        🚀
                                    @elseif($section->icon && str_contains($section->icon, 'headset'))
                                        🎧
                                    @else
                                        ⭐
                                    @endif
                                </span>
                            </div>
                            <h4 class="fw-bold mb-3">{{ $section->title }}</h4>
                            <p class="text-muted">{{ $section->content }}</p>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="col-lg-4">
                        <div class="mission-card h-100 bg-white p-4 rounded shadow-sm">
                            <div class="mission-icon mb-3">
                                <span class="text-primary" style="font-size: 3rem;">🛡️</span>
                            </div>
                            <h4 class="fw-bold mb-3">Sécurité</h4>
                            <p class="text-muted">Protection maximale de vos licences avec un chiffrement de niveau militaire.</p>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="mission-card h-100 bg-white p-4 rounded shadow-sm">
                            <div class="mission-icon mb-3">
                                <span class="text-primary" style="font-size: 3rem;">🚀</span>
                            </div>
                            <h4 class="fw-bold mb-3">Performance</h4>
                            <p class="text-muted">Des API ultra-rapides pour une validation de licences en temps réel.</p>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="mission-card h-100 bg-white p-4 rounded shadow-sm">
                            <div class="mission-icon mb-3">
                                <span class="text-primary" style="font-size: 3rem;">🎧</span>
                            </div>
                            <h4 class="fw-bold mb-3">Support</h4>
                            <p class="text-muted">Une équipe dédiée pour vous accompagner à chaque étape.</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- Statistiques -->
    <section class="stats py-5 bg-white">
        <div class="container">
            <div class="row g-4 text-center">
                <div class="col-lg-3 col-md-6">
                    <div class="stat-item">
                        <h2 class="display-4 fw-bold text-primary mb-2">500+</h2>
                        <p class="text-muted">Clients satisfaits</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stat-item">
                        <h2 class="display-4 fw-bold text-success mb-2">1M+</h2>
                        <p class="text-muted">Licences gérées</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stat-item">
                        <h2 class="display-4 fw-bold text-warning mb-2">99.9%</h2>
                        <p class="text-muted">Temps de disponibilité</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stat-item">
                        <h2 class="display-4 fw-bold text-info mb-2">24/7</h2>
                        <p class="text-muted">Support disponible</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="cta py-5 bg-primary text-white">
        <div class="container">
            <div class="text-center">
                <h2 class="display-6 fw-bold mb-3">Prêt à nous rejoindre ?</h2>
                <p class="lead mb-4">Découvrez comment AdminLicence peut protéger vos logiciels</p>
                <div class="d-flex gap-3 justify-content-center flex-wrap">
                    <a href="{{ route('frontend.contact') }}" class="btn btn-light btn-lg px-4">
                        <span class="me-2">📞</span>Contactez-nous
                    </a>
                    <a href="{{ route('frontend.pricing') }}" class="btn btn-outline-light btn-lg px-4">
                        <span class="me-2">🏷️</span>Voir les tarifs
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
.mission-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.mission-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15) !important;
}

.stat-item {
    padding: 2rem 1rem;
}

.hero-about {
    background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
}

@media (max-width: 768px) {
    .display-4 {
        font-size: 2.5rem;
    }
}
</style>
@endsection 