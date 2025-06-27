@extends('frontend.templates.modern.layout')

@section('title', 'Support - AdminLicence')

@section('content')
<div class="bg-light min-vh-100">
    <!-- Hero Section -->
    <div class="hero-section text-center">
        <div class="container">
            <h1 class="display-4 fw-bold mb-4">Centre de Support</h1>
            <p class="lead mb-0">Nous sommes là pour vous aider. Trouvez des réponses ou contactez notre équipe</p>
        </div>
    </div>

    <!-- Support Options -->
    <div class="container py-5">
        <div class="row g-4 mb-5">
            <!-- Documentation -->
            <div class="col-md-4">
                <div class="card feature-card h-100 text-center">
                    <div class="card-body p-4">
                        <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 rounded-circle mb-4" style="width: 64px; height: 64px;">
                            <i class="fas fa-book text-primary" style="font-size: 24px;"></i>
                        </div>
                        <h5 class="card-title fw-bold mb-3">Documentation</h5>
                        <p class="card-text text-muted mb-4">
                            Consultez notre documentation complète avec guides d'installation, API et tutoriels
                        </p>
                        <a href="/documentation" class="btn btn-primary">
                            Voir la Documentation
                        </a>
                    </div>
                </div>
            </div>

            <!-- FAQ -->
            <div class="col-md-4">
                <div class="card feature-card h-100 text-center">
                    <div class="card-body p-4">
                        <div class="d-inline-flex align-items-center justify-content-center bg-success bg-opacity-10 rounded-circle mb-4" style="width: 64px; height: 64px;">
                            <i class="fas fa-question-circle text-success" style="font-size: 24px;"></i>
                        </div>
                        <h5 class="card-title fw-bold mb-3">FAQ</h5>
                        <p class="card-text text-muted mb-4">
                            Trouvez rapidement des réponses aux questions les plus fréquemment posées
                        </p>
                        <a href="/faq" class="btn btn-success">
                            Consulter la FAQ
                        </a>
                    </div>
                </div>
            </div>

            <!-- Contact Direct -->
            <div class="col-md-4">
                <div class="card feature-card h-100 text-center">
                    <div class="card-body p-4">
                        <div class="d-inline-flex align-items-center justify-content-center bg-info bg-opacity-10 rounded-circle mb-4" style="width: 64px; height: 64px;">
                            <i class="fas fa-envelope text-info" style="font-size: 24px;"></i>
                        </div>
                        <h5 class="card-title fw-bold mb-3">Contact Direct</h5>
                        <p class="card-text text-muted mb-4">
                            Contactez directement notre équipe support pour une assistance personnalisée
                        </p>
                        <a href="/contact" class="btn btn-info text-white">
                            Nous Contacter
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="card shadow-sm mb-5">
            <div class="card-body p-5">
                <div class="text-center mb-5">
                    <h2 class="section-title fw-bold">Informations de Contact</h2>
                    <p class="lead text-muted">Plusieurs moyens pour nous joindre</p>
                </div>

                <div class="row g-4">
                    <!-- Email Support -->
                    <div class="col-lg-3 col-md-6 text-center">
                        <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 rounded-circle mb-3" style="width: 48px; height: 48px;">
                            <i class="fas fa-envelope text-primary" style="font-size: 18px;"></i>
                        </div>
                        <h6 class="fw-semibold mb-2">Email Support</h6>
                        <p class="text-muted small mb-0">support@adminlicence.com</p>
                    </div>

                    <!-- Téléphone -->
                    <div class="col-lg-3 col-md-6 text-center">
                        <div class="d-inline-flex align-items-center justify-content-center bg-success bg-opacity-10 rounded-circle mb-3" style="width: 48px; height: 48px;">
                            <i class="fas fa-phone text-success" style="font-size: 18px;"></i>
                        </div>
                        <h6 class="fw-semibold mb-2">Téléphone</h6>
                        <p class="text-muted small mb-0">+33 1 23 45 67 89</p>
                    </div>

                    <!-- Chat en Direct -->
                    <div class="col-lg-3 col-md-6 text-center">
                        <div class="d-inline-flex align-items-center justify-content-center bg-info bg-opacity-10 rounded-circle mb-3" style="width: 48px; height: 48px;">
                            <i class="fas fa-comments text-info" style="font-size: 18px;"></i>
                        </div>
                        <h6 class="fw-semibold mb-2">Chat en Direct</h6>
                        <p class="text-muted small mb-0">Lun-Ven 9h-18h</p>
                    </div>

                    <!-- Horaires -->
                    <div class="col-lg-3 col-md-6 text-center">
                        <div class="d-inline-flex align-items-center justify-content-center bg-warning bg-opacity-10 rounded-circle mb-3" style="width: 48px; height: 48px;">
                            <i class="fas fa-clock text-warning" style="font-size: 18px;"></i>
                        </div>
                        <h6 class="fw-semibold mb-2">Horaires</h6>
                        <p class="text-muted small mb-0">Lun-Ven 9h-18h CET</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Système -->
        <div class="card shadow-sm">
            <div class="card-body p-5">
                <div class="text-center mb-5">
                    <h2 class="section-title fw-bold">État du Système</h2>
                    <p class="lead text-muted">Surveillez la disponibilité de nos services</p>
                </div>

                <div class="row g-3">
                    <div class="col-12">
                        <div class="d-flex align-items-center justify-content-between p-3 bg-success bg-opacity-10 rounded">
                            <div class="d-flex align-items-center">
                                <div class="bg-success rounded-circle me-3" style="width: 12px; height: 12px;"></div>
                                <span class="fw-semibold">API AdminLicence</span>
                            </div>
                            <span class="text-success fw-semibold">Opérationnel</span>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="d-flex align-items-center justify-content-between p-3 bg-success bg-opacity-10 rounded">
                            <div class="d-flex align-items-center">
                                <div class="bg-success rounded-circle me-3" style="width: 12px; height: 12px;"></div>
                                <span class="fw-semibold">Dashboard Web</span>
                            </div>
                            <span class="text-success fw-semibold">Opérationnel</span>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="d-flex align-items-center justify-content-between p-3 bg-success bg-opacity-10 rounded">
                            <div class="d-flex align-items-center">
                                <div class="bg-success rounded-circle me-3" style="width: 12px; height: 12px;"></div>
                                <span class="fw-semibold">Système de Licences</span>
                            </div>
                            <span class="text-success fw-semibold">Opérationnel</span>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="d-flex align-items-center justify-content-between p-3 bg-success bg-opacity-10 rounded">
                            <div class="d-flex align-items-center">
                                <div class="bg-success rounded-circle me-3" style="width: 12px; height: 12px;"></div>
                                <span class="fw-semibold">Base de Données</span>
                            </div>
                            <span class="text-success fw-semibold">Opérationnel</span>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <p class="text-muted mb-0">
                        Dernière mise à jour : {{ date('d/m/Y H:i') }} CET
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
