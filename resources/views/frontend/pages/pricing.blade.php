@extends($layout ?? 'frontend.templates.modern.layout')

@section('title', 'Tarifs - AdminLicence')

@section('content')
<div class="bg-light min-vh-100">
    <!-- Hero Section -->
    <div class="hero-section text-center">
        <div class="container">
            <h1 class="display-4 fw-bold mb-4">Plans Tarifaires</h1>
            <p class="lead mb-0">Choisissez la solution qui correspond à vos besoins de gestion de licences</p>
        </div>
    </div>

    <!-- Pricing Plans -->
    <div class="container py-5">
        <div class="row g-4 justify-content-center mb-5">
            
            <!-- Plan Starter -->
            <div class="col-lg-4 col-md-6">
                <div class="card feature-card h-100">
                    <div class="card-body p-4 text-center">
                        <h5 class="card-title fw-bold mb-4">Starter</h5>
                        <div class="mb-4">
                            <span class="display-4 fw-bold text-primary">29€</span>
                            <span class="text-muted">/mois</span>
                        </div>
                        
                        <ul class="list-unstyled mb-4">
                            <li class="d-flex align-items-center mb-3">
                                <i class="fas fa-check text-success me-3"></i>
                                <span>Jusqu'à 100 licences</span>
                            </li>
                            <li class="d-flex align-items-center mb-3">
                                <i class="fas fa-check text-success me-3"></i>
                                <span>Dashboard basique</span>
                            </li>
                            <li class="d-flex align-items-center mb-3">
                                <i class="fas fa-check text-success me-3"></i>
                                <span>Support email</span>
                            </li>
                            <li class="d-flex align-items-center mb-3">
                                <i class="fas fa-check text-success me-3"></i>
                                <span>API de base</span>
                            </li>
                        </ul>
                        
                        <button class="btn btn-outline-primary w-100">
                            Commencer
                        </button>
                    </div>
                </div>
            </div>

            <!-- Plan Professional -->
            <div class="col-lg-4 col-md-6">
                <div class="card feature-card h-100 border-primary position-relative">
                    <div class="position-absolute top-0 start-50 translate-middle">
                        <span class="badge bg-primary px-3 py-2">Populaire</span>
                    </div>
                    
                    <div class="card-body p-4 text-center">
                        <h5 class="card-title fw-bold mb-4">Professional</h5>
                        <div class="mb-4">
                            <span class="display-4 fw-bold text-primary">89€</span>
                            <span class="text-muted">/mois</span>
                        </div>
                        
                        <ul class="list-unstyled mb-4">
                            <li class="d-flex align-items-center mb-3">
                                <i class="fas fa-check text-success me-3"></i>
                                <span>Jusqu'à 1000 licences</span>
                            </li>
                            <li class="d-flex align-items-center mb-3">
                                <i class="fas fa-check text-success me-3"></i>
                                <span>Dashboard avancé</span>
                            </li>
                            <li class="d-flex align-items-center mb-3">
                                <i class="fas fa-check text-success me-3"></i>
                                <span>Support prioritaire</span>
                            </li>
                            <li class="d-flex align-items-center mb-3">
                                <i class="fas fa-check text-success me-3"></i>
                                <span>API complète</span>
                            </li>
                            <li class="d-flex align-items-center mb-3">
                                <i class="fas fa-check text-success me-3"></i>
                                <span>Rapports personnalisés</span>
                            </li>
                        </ul>
                        
                        <button class="btn btn-primary w-100">
                            Choisir Professional
                        </button>
                    </div>
                </div>
            </div>

            <!-- Plan Enterprise -->
            <div class="col-lg-4 col-md-6">
                <div class="card feature-card h-100">
                    <div class="card-body p-4 text-center">
                        <h5 class="card-title fw-bold mb-4">Enterprise</h5>
                        <div class="mb-4">
                            <span class="display-4 fw-bold text-primary">249€</span>
                            <span class="text-muted">/mois</span>
                        </div>
                        
                        <ul class="list-unstyled mb-4">
                            <li class="d-flex align-items-center mb-3">
                                <i class="fas fa-check text-success me-3"></i>
                                <span>Licences illimitées</span>
                            </li>
                            <li class="d-flex align-items-center mb-3">
                                <i class="fas fa-check text-success me-3"></i>
                                <span>Dashboard personnalisé</span>
                            </li>
                            <li class="d-flex align-items-center mb-3">
                                <i class="fas fa-check text-success me-3"></i>
                                <span>Support dédié 24/7</span>
                            </li>
                            <li class="d-flex align-items-center mb-3">
                                <i class="fas fa-check text-success me-3"></i>
                                <span>API premium + webhooks</span>
                            </li>
                            <li class="d-flex align-items-center mb-3">
                                <i class="fas fa-check text-success me-3"></i>
                                <span>Intégrations sur mesure</span>
                            </li>
                            <li class="d-flex align-items-center mb-3">
                                <i class="fas fa-check text-success me-3"></i>
                                <span>SLA garanti</span>
                            </li>
                        </ul>
                        
                        <button class="btn btn-outline-primary w-100">
                            Contactez-nous
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- FAQ Section -->
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="text-center mb-5">
                    <h2 class="section-title fw-bold">Questions Fréquentes</h2>
                    <p class="lead text-muted">Tout ce que vous devez savoir sur nos tarifs</p>
                </div>

                <div class="accordion" id="pricingFAQ">
                    <div class="accordion-item mb-3">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                Puis-je changer de plan à tout moment ?
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#pricingFAQ">
                            <div class="accordion-body">
                                Oui, vous pouvez upgrader ou downgrader votre plan à tout moment. Les changements prennent effet immédiatement.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item mb-3">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                Y a-t-il une période d'essai gratuite ?
                            </button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#pricingFAQ">
                            <div class="accordion-body">
                                Oui, tous nos plans incluent une période d'essai gratuite de 14 jours, sans engagement.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item mb-3">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                Quels moyens de paiement acceptez-vous ?
                            </button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#pricingFAQ">
                            <div class="accordion-body">
                                Nous acceptons toutes les cartes de crédit majeures, PayPal, et les virements bancaires pour les comptes Enterprise.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item mb-3">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                                Proposez-vous des remises pour les organisations à but non lucratif ?
                            </button>
                        </h2>
                        <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#pricingFAQ">
                            <div class="accordion-body">
                                Oui, nous offrons des remises spéciales pour les organisations à but non lucratif et éducatives. Contactez-nous pour plus d'informations.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
