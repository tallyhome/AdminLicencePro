# Intégration des Paiements - AdminLicence

## Vue d'ensemble

AdminLicence supporte deux méthodes de paiement principales :
- **Stripe** : Paiements par carte bancaire
- **PayPal** : Paiements via compte PayPal

## Configuration

### 1. Variables d'environnement

Ajoutez ces variables à votre fichier `.env` :

```env
# Configuration Stripe
STRIPE_KEY=pk_test_your_stripe_publishable_key_here
STRIPE_SECRET=sk_test_your_stripe_secret_key_here
STRIPE_WEBHOOK_SECRET=whsec_your_webhook_secret_here
STRIPE_WEBHOOK_TOLERANCE=300

# Configuration PayPal
PAYPAL_CLIENT_ID=your_paypal_client_id_here
PAYPAL_CLIENT_SECRET=your_paypal_client_secret_here
PAYPAL_MODE=sandbox
PAYPAL_WEBHOOK_ID=your_paypal_webhook_id_here
```

### 2. Installation des SDK

```bash
# Installer Stripe PHP SDK
composer require stripe/stripe-php

# Installer PayPal SDK (optionnel, selon votre préférence)
composer require paypal/rest-api-sdk-php
# ou
composer require paypal/paypal-checkout-sdk
```

## Implémentation

### 1. Service Stripe

Le service `StripeService` contient les méthodes pour :
- Créer des clients Stripe
- Gérer les méthodes de paiement
- Créer et gérer les abonnements
- Gérer les webhooks

#### Exemple d'utilisation :

```php
use App\Services\StripeService;

$stripeService = new StripeService();

// Créer un client
$customer = $stripeService->createCustomer([
    'email' => $client->email,
    'name' => $client->name,
    'tenant_id' => $tenant->id
]);

// Créer un setup intent pour ajouter une carte
$setupIntent = $stripeService->createSetupIntent($customer['id']);
```

### 2. Service PayPal

Le service `PaypalService` contient les méthodes pour :
- Créer des accords de facturation
- Gérer les abonnements PayPal
- Gérer les webhooks PayPal

#### Exemple d'utilisation :

```php
use App\Services\PaypalService;

$paypalService = new PaypalService();

// Créer un accord de facturation
$agreement = $paypalService->createBillingAgreement([
    'email' => $client->email,
    'plan_id' => $plan->paypal_plan_id
]);
```

## Frontend

### 1. Stripe Elements

Pour intégrer Stripe Elements dans vos vues :

```html
<script src="https://js.stripe.com/v3/"></script>
<script>
const stripe = Stripe('{{ config("services.stripe.key") }}');
const elements = stripe.elements();

const cardElement = elements.create('card');
cardElement.mount('#card-element');
</script>
```

### 2. PayPal Buttons

Pour intégrer les boutons PayPal :

```html
<script src="https://www.paypal.com/sdk/js?client-id={{ config('services.paypal.client_id') }}"></script>
<script>
paypal.Buttons({
    createOrder: function(data, actions) {
        // Logique de création de commande
    },
    onApprove: function(data, actions) {
        // Logique d'approbation
    }
}).render('#paypal-button-container');
</script>
```

## Webhooks

### 1. Webhooks Stripe

Créez un endpoint pour recevoir les webhooks Stripe :

```php
Route::post('/webhooks/stripe', [WebhookController::class, 'stripe']);
```

### 2. Webhooks PayPal

Créez un endpoint pour recevoir les webhooks PayPal :

```php
Route::post('/webhooks/paypal', [WebhookController::class, 'paypal']);
```

## Sécurité

### 1. Validation des webhooks

Toujours valider les webhooks avec les signatures :

```php
// Stripe
$payload = @file_get_contents('php://input');
$sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
$endpoint_secret = config('services.stripe.webhook.secret');

$event = \Stripe\Webhook::constructEvent($payload, $sig_header, $endpoint_secret);

// PayPal
// Utiliser les outils de validation PayPal
```

### 2. Variables d'environnement

- Ne jamais commiter les clés secrètes
- Utiliser des clés de test en développement
- Utiliser des clés de production uniquement en production

## Tests

### 1. Cartes de test Stripe

```
Visa: 4242424242424242
Mastercard: 5555555555554444
Amex: 378282246310005
```

### 2. Comptes de test PayPal

Utilisez les comptes sandbox PayPal pour les tests.

## Déploiement

### 1. Variables de production

Assurez-vous de configurer les bonnes variables d'environnement :

```env
STRIPE_KEY=pk_live_...
STRIPE_SECRET=sk_live_...
PAYPAL_MODE=live
```

### 2. Webhooks en production

Configurez les URLs de webhook en production :
- Stripe : `https://votre-domaine.com/webhooks/stripe`
- PayPal : `https://votre-domaine.com/webhooks/paypal`

## Support

Pour toute question concernant l'intégration des paiements, consultez :
- [Documentation Stripe](https://stripe.com/docs)
- [Documentation PayPal](https://developer.paypal.com/)

## Structure des fichiers

```
app/
├── Services/
│   ├── PaymentService.php      # Service principal
│   ├── StripeService.php       # Service Stripe
│   └── PaypalService.php       # Service PayPal
├── Models/
│   ├── PaymentMethod.php       # Modèle des méthodes de paiement
│   └── Subscription.php        # Modèle des abonnements
└── Http/Controllers/Client/
    └── PaymentMethodController.php # Contrôleur des méthodes de paiement

resources/views/client/
├── billing/
│   ├── index.blade.php         # Page principale de facturation
│   ├── invoices.blade.php      # Liste des factures
│   └── subscription.blade.php  # Gestion des abonnements
└── payment-methods/
    ├── index.blade.php         # Liste des méthodes de paiement
    └── create.blade.php        # Ajouter une méthode de paiement
``` 