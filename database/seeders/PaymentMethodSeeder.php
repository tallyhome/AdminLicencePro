<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use App\Models\Tenant;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer le premier tenant pour les tests
        $tenant = Tenant::first();
        
        if (!$tenant) {
            $this->command->info('Aucun tenant trouvé. Veuillez d\'abord créer un tenant.');
            return;
        }

        // Créer une méthode de paiement Stripe de test
        PaymentMethod::create([
            'tenant_id' => $tenant->id,
            'type' => 'stripe',
            'stripe_payment_method_id' => 'pm_test_' . uniqid(),
            'last_four' => '4242',
            'brand' => 'visa',
            'exp_month' => 12,
            'exp_year' => 2025,
            'is_default' => true,
            'metadata' => [
                'created_for_testing' => true,
                'card_type' => 'test_card'
            ]
        ]);

        // Créer une méthode de paiement PayPal de test
        PaymentMethod::create([
            'tenant_id' => $tenant->id,
            'type' => 'paypal',
            'paypal_billing_agreement_id' => 'B-test_' . uniqid(),
            'is_default' => false,
            'metadata' => [
                'created_for_testing' => true,
                'email' => 'test@paypal.com'
            ]
        ]);

        $this->command->info('Méthodes de paiement de test créées avec succès!');
    }
} 