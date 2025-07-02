<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Plan;
use App\Models\Tenant;
use App\Models\Subscription;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ClientsTableSeeder extends Seeder
{
    public function run()
    {
        // Récupérer le plan gratuit
        $freePlan = Plan::where('price', 0)->first();

        if (!$freePlan) {
            // Si le plan gratuit n'existe pas, on le crée
            $this->call(PlansTableSeeder::class);
            $freePlan = Plan::where('price', 0)->first();
        }

        // Créer un tenant de test
        $tenant = Tenant::create([
            'name' => 'Entreprise Test',
            'domain' => 'test',
            'database' => config('database.default'),
            'status' => Tenant::STATUS_ACTIVE,
            'subscription_status' => $freePlan->hasTrial() ? Tenant::SUBSCRIPTION_TRIAL : Tenant::SUBSCRIPTION_ACTIVE,
            'subscription_ends_at' => now()->addMonth(),
            'trial_ends_at' => $freePlan->hasTrial() ? now()->addDays($freePlan->trial_days) : null,
            'settings' => [
                'timezone' => 'Europe/Paris',
                'locale' => 'fr',
                'currency' => 'EUR',
                'date_format' => 'd/m/Y',
                'time_format' => 'H:i',
            ]
        ]);

        // Créer un client de test
        $client = Client::create([
            'name' => 'Client Test',
            'email' => 'client@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'company_name' => 'Entreprise Test',
            'phone' => '+33123456789',
            'address' => '123 Rue Test',
            'city' => 'Paris',
            'state' => 'Île-de-France',
            'postal_code' => '75000',
            'country' => 'FR',
            'tenant_id' => $tenant->id,
            'status' => Client::STATUS_ACTIVE,
        ]);

        // Créer un abonnement
        Subscription::create([
            'tenant_id' => $tenant->id,
            'plan_id' => $freePlan->id,
            'status' => $freePlan->hasTrial() ? 'trial' : 'active',
            'trial_ends_at' => $freePlan->hasTrial() ? now()->addDays($freePlan->trial_days) : null,
            'starts_at' => now(),
            'ends_at' => now()->addMonth(),
            'renewal_price' => $freePlan->price,
            'billing_cycle' => $freePlan->billing_cycle,
            'auto_renew' => true,
        ]);
    }
}