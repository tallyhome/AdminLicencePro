@extends($layout ?? 'frontend.templates.modern.layout')

@section('title', 'Fonctionnalités - AdminLicence')

@section('content')
<div class="bg-light min-vh-100">
    <!-- Hero Section -->
    <div class="hero-section text-center">
        <div class="container">
            <h1 class="display-4 fw-bold mb-4">Fonctionnalités Avancées</h1>
            <p class="lead mb-0">Découvrez toutes les capacités d'AdminLicence pour sécuriser et gérer vos licences logicielles</p>
        </div>
    </div>

    <!-- Features Grid -->
    <div class="container py-5">
        <!-- Main Features -->
        <div class="row g-4 mb-5">
            <div class="col-lg-4 col-md-6">
                <div class="card feature-card h-100 text-center">
                    <div class="card-body p-4">
                        <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 rounded-circle mb-4" style="width: 64px; height: 64px;">
                            <span class="text-primary" style="font-size: 24px;">🛡️</span>
                        </div>
                        <h5 class="card-title fw-bold mb-3">Sécurité Maximale</h5>
                        <p class="card-text text-muted">
                            Chiffrement de bout en bout et validation cryptographique avancée pour protéger vos licences
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="card feature-card h-100 text-center">
                    <div class="card-body p-4">
                        <div class="d-inline-flex align-items-center justify-content-center bg-success bg-opacity-10 rounded-circle mb-4" style="width: 64px; height: 64px;">
                            <span class="text-success" style="font-size: 24px;">📊</span>
                        </div>
                        <h5 class="card-title fw-bold mb-3">Dashboard Intuitif</h5>
                        <p class="card-text text-muted">
                            Interface moderne et facile à utiliser pour gérer toutes vos licences en un coup d'œil
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="card feature-card h-100 text-center">
                    <div class="card-body p-4">
                        <div class="d-inline-flex align-items-center justify-content-center bg-info bg-opacity-10 rounded-circle mb-4" style="width: 64px; height: 64px;">
                            <span class="text-info" style="font-size: 24px;">💻</span>
                        </div>
                        <h5 class="card-title fw-bold mb-3">API Complète</h5>
                        <p class="card-text text-muted">
                            Intégration facile avec votre infrastructure existante grâce à notre API REST complète
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="card feature-card h-100 text-center">
                    <div class="card-body p-4">
                        <div class="d-inline-flex align-items-center justify-content-center bg-warning bg-opacity-10 rounded-circle mb-4" style="width: 64px; height: 64px;">
                            <span class="text-warning" style="font-size: 24px;">📈</span>
                        </div>
                        <h5 class="card-title fw-bold mb-3">Analytics Avancés</h5>
                        <p class="card-text text-muted">
                            Suivez l'utilisation de vos licences avec des rapports détaillés et des graphiques interactifs
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="card feature-card h-100 text-center">
                    <div class="card-body p-4">
                        <div class="d-inline-flex align-items-center justify-content-center bg-danger bg-opacity-10 rounded-circle mb-4" style="width: 64px; height: 64px;">
                            <span class="text-danger" style="font-size: 24px;">🔔</span>
                        </div>
                        <h5 class="card-title fw-bold mb-3">Alertes Intelligentes</h5>
                        <p class="card-text text-muted">
                            Notifications automatiques pour les expirations, violations et événements importants
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="card feature-card h-100 text-center">
                    <div class="card-body p-4">
                        <div class="d-inline-flex align-items-center justify-content-center bg-secondary bg-opacity-10 rounded-circle mb-4" style="width: 64px; height: 64px;">
                            <span class="text-secondary" style="font-size: 24px;">👥</span>
                        </div>
                        <h5 class="card-title fw-bold mb-3">Gestion Multi-Utilisateurs</h5>
                        <p class="card-text text-muted">
                            Contrôle d'accès granulaire et gestion des rôles pour votre équipe
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Technical Features -->
        <div class="card shadow-sm mb-5">
            <div class="card-body p-5">
                <div class="text-center mb-5">
                    <h2 class="section-title fw-bold">Fonctionnalités Techniques</h2>
                    <p class="lead text-muted">Une architecture robuste pour vos besoins les plus exigeants</p>
                </div>

                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="d-flex align-items-start">
                            <div class="flex-shrink-0 me-3">
                                <div class="d-inline-flex align-items-center justify-content-center bg-success rounded-circle" style="width: 32px; height: 32px;">
                                    <span class="text-white" style="font-size: 14px;">✅</span>
                                </div>
                            </div>
                            <div>
                                <h6 class="fw-semibold mb-2">Chiffrement AES-256</h6>
                                <p class="text-muted small mb-0">Protection militaire pour vos données sensibles</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="d-flex align-items-start">
                            <div class="flex-shrink-0 me-3">
                                <div class="d-inline-flex align-items-center justify-content-center bg-success rounded-circle" style="width: 32px; height: 32px;">
                                    <span class="text-white" style="font-size: 14px;">✅</span>
                                </div>
                            </div>
                            <div>
                                <h6 class="fw-semibold mb-2">Validation Cryptographique</h6>
                                <p class="text-muted small mb-0">Signatures numériques pour l'intégrité des licences</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="d-flex align-items-start">
                            <div class="flex-shrink-0 me-3">
                                <div class="d-inline-flex align-items-center justify-content-center bg-success rounded-circle" style="width: 32px; height: 32px;">
                                    <span class="text-white" style="font-size: 14px;">✅</span>
                                </div>
                            </div>
                            <div>
                                <h6 class="fw-semibold mb-2">API RESTful</h6>
                                <p class="text-muted small mb-0">Intégration simple avec documentation complète</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="d-flex align-items-start">
                            <div class="flex-shrink-0 me-3">
                                <div class="d-inline-flex align-items-center justify-content-center bg-success rounded-circle" style="width: 32px; height: 32px;">
                                    <span class="text-white" style="font-size: 14px;">✅</span>
                                </div>
                            </div>
                            <div>
                                <h6 class="fw-semibold mb-2">Haute Disponibilité</h6>
                                <p class="text-muted small mb-0">Architecture redondante avec SLA 99.9%</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="d-flex align-items-start">
                            <div class="flex-shrink-0 me-3">
                                <div class="d-inline-flex align-items-center justify-content-center bg-success rounded-circle" style="width: 32px; height: 32px;">
                                    <span class="text-white" style="font-size: 14px;">✅</span>
                                </div>
                            </div>
                            <div>
                                <h6 class="fw-semibold mb-2">Sauvegarde Automatique</h6>
                                <p class="text-muted small mb-0">Vos données protégées avec sauvegarde continue</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="d-flex align-items-start">
                            <div class="flex-shrink-0 me-3">
                                <div class="d-inline-flex align-items-center justify-content-center bg-success rounded-circle" style="width: 32px; height: 32px;">
                                    <span class="text-white" style="font-size: 14px;">✅</span>
                                </div>
                            </div>
                            <div>
                                <h6 class="fw-semibold mb-2">Monitoring 24/7</h6>
                                <p class="text-muted small mb-0">Surveillance continue de la performance</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Integration Section -->
        <div class="card shadow-sm mb-5">
            <div class="card-body p-5">
                <div class="text-center mb-5">
                    <h2 class="section-title fw-bold">Intégrations Disponibles</h2>
                    <p class="lead text-muted">Connectez AdminLicence à vos outils favoris</p>
                </div>

                <div class="row g-4 text-center">
                    <div class="col-lg-2 col-md-4 col-6">
                        <div class="p-3">
                            <span style="font-size: 48px; color: #e74c3c;">🅻</span>
                            <h6 class="mt-2 mb-0">Laravel</h6>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4 col-6">
                        <div class="p-3">
                            <span style="font-size: 48px; color: #0d6efd;">🐘</span>
                            <h6 class="mt-2 mb-0">PHP</h6>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4 col-6">
                        <div class="p-3">
                            <span style="font-size: 48px; color: #ffc107;">🟨</span>
                            <h6 class="mt-2 mb-0">JavaScript</h6>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4 col-6">
                        <div class="p-3">
                            <span style="font-size: 48px; color: #198754;">🐍</span>
                            <h6 class="mt-2 mb-0">Python</h6>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4 col-6">
                        <div class="p-3">
                            <span style="font-size: 48px; color: #198754;">🟢</span>
                            <h6 class="mt-2 mb-0">Node.js</h6>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4 col-6">
                        <div class="p-3">
                            <span style="font-size: 48px; color: #0dcaf0;">🐳</span>
                            <h6 class="mt-2 mb-0">Docker</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="text-center">
            <div class="card bg-primary text-white">
                <div class="card-body p-5">
                    <h2 class="card-title fw-bold mb-4">Prêt à Sécuriser Vos Licences ?</h2>
                    <p class="card-text lead mb-4">
                        Rejoignez des milliers d'entreprises qui font confiance à AdminLicence
                    </p>
                    <div class="d-flex gap-3 justify-content-center flex-wrap">
                        <a href="{{ route('frontend.contact') }}" class="btn btn-light btn-lg">
                            <span class="me-2">📧</span>Nous Contacter
                        </a>
                        <a href="{{ route('frontend.pricing') }}" class="btn btn-outline-light btn-lg">
                            <span class="me-2">🏷️</span>Voir les Tarifs
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
