@extends('layouts.client')

@section('title', 'Ajouter une méthode de paiement')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-plus mr-2"></i>
                        Ajouter une méthode de paiement
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('client.payment-methods.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Retour
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Stripe -->
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="fab fa-stripe mr-2"></i>
                                        Carte bancaire (Stripe)
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <p class="text-muted">Paiement sécurisé par carte bancaire via Stripe</p>
                                    <ul class="list-unstyled">
                                        <li><i class="fas fa-check text-success mr-2"></i>Paiement sécurisé</li>
                                        <li><i class="fas fa-check text-success mr-2"></i>Cartes Visa, Mastercard, etc.</li>
                                        <li><i class="fas fa-check text-success mr-2"></i>Processus rapide</li>
                                    </ul>
                                </div>
                                <div class="card-footer">
                                    <button type="button" class="btn btn-primary btn-block" onclick="setupStripe()">
                                        <i class="fas fa-credit-card mr-2"></i>
                                        Configurer Stripe
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- PayPal -->
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="fab fa-paypal mr-2"></i>
                                        PayPal
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <p class="text-muted">Paiement via votre compte PayPal</p>
                                    <ul class="list-unstyled">
                                        <li><i class="fas fa-check text-success mr-2"></i>Compte PayPal existant</li>
                                        <li><i class="fas fa-check text-success mr-2"></i>Protection des acheteurs</li>
                                        <li><i class="fas fa-check text-success mr-2"></i>Paiement en un clic</li>
                                    </ul>
                                </div>
                                <div class="card-footer">
                                    <button type="button" class="btn btn-primary btn-block" onclick="setupPaypal()">
                                        <i class="fab fa-paypal mr-2"></i>
                                        Configurer PayPal
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Stripe -->
<div class="modal fade" id="stripeModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fab fa-stripe mr-2"></i>
                    Ajouter une carte bancaire
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="stripeForm">
                    @csrf
                    <div class="form-group">
                        <label>Informations de la carte</label>
                        <div id="card-element" class="form-control" style="height: 40px; padding: 10px;">
                            <!-- Stripe Elements sera inséré ici -->
                        </div>
                        <div id="card-errors" role="alert" class="text-danger mt-2"></div>
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="stripeDefault" name="is_default">
                            <label class="custom-control-label" for="stripeDefault">
                                Définir comme méthode par défaut
                            </label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" id="stripeSubmit">
                    <i class="fas fa-plus mr-2"></i>
                    Ajouter la carte
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal PayPal -->
<div class="modal fade" id="paypalModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fab fa-paypal mr-2"></i>
                    Configurer PayPal
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Vous allez être redirigé vers PayPal pour configurer votre méthode de paiement.</p>
                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="paypalDefault" name="is_default">
                        <label class="custom-control-label" for="paypalDefault">
                            Définir comme méthode par défaut
                        </label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" id="paypalSubmit">
                    <i class="fab fa-paypal mr-2"></i>
                    Continuer avec PayPal
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function setupStripe() {
    const modal = new bootstrap.Modal(document.getElementById('stripeModal'));
    modal.show();
    
    // Simulation de l'intégration Stripe
    // Dans un environnement réel, vous utiliseriez Stripe Elements
    document.getElementById('card-element').innerHTML = `
        <div class="text-center p-3">
            <i class="fab fa-stripe fa-2x text-primary mb-2"></i>
            <p class="text-muted">Intégration Stripe en cours de développement</p>
            <small class="text-muted">Les champs de carte seront ici avec Stripe Elements</small>
        </div>
    `;
}

function setupPaypal() {
    const modal = new bootstrap.Modal(document.getElementById('paypalModal'));
    modal.show();
}

document.getElementById('stripeSubmit').addEventListener('click', function() {
    // Simulation de l'ajout d'une carte Stripe
    const isDefault = document.getElementById('stripeDefault').checked;
    
    // Créer une méthode de paiement de test
    const formData = new FormData();
    formData.append('_token', document.querySelector('input[name="_token"]').value);
    formData.append('type', 'stripe');
    formData.append('stripe_payment_method_id', 'pm_test_' + Math.random().toString(36).substr(2, 9));
    formData.append('last_four', '4242');
    formData.append('brand', 'visa');
    formData.append('exp_month', '12');
    formData.append('exp_year', '2025');
    formData.append('is_default', isDefault ? '1' : '0');
    
    fetch('{{ route("client.payment-methods.store") }}', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = '{{ route("client.payment-methods.index") }}';
        } else {
            alert('Erreur lors de l\'ajout de la carte');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erreur lors de l\'ajout de la carte');
    });
});

document.getElementById('paypalSubmit').addEventListener('click', function() {
    // Simulation de l'ajout PayPal
    const isDefault = document.getElementById('paypalDefault').checked;
    
    // Créer une méthode de paiement de test
    const formData = new FormData();
    formData.append('_token', document.querySelector('input[name="_token"]').value);
    formData.append('type', 'paypal');
    formData.append('paypal_billing_agreement_id', 'B-' + Math.random().toString(36).substr(2, 9));
    formData.append('is_default', isDefault ? '1' : '0');
    
    fetch('{{ route("client.payment-methods.store") }}', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = '{{ route("client.payment-methods.index") }}';
        } else {
            alert('Erreur lors de l\'ajout de PayPal');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erreur lors de l\'ajout de PayPal');
    });
});
</script>
@endsection 