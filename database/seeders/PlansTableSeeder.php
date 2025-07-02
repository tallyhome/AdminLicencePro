<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plan;

class PlansTableSeeder extends Seeder
{
    public function run()
    {
        // Plan Gratuit
        Plan::updateOrCreate(
            ['slug' => 'plan-gratuit'],
            [
                'name' => 'Plan Gratuit',
                'description' => 'Plan gratuit avec fonctionnalités de base',
                'price' => 0,
                'billing_cycle' => 'monthly',
                'features' => [
                    '1 projet',
                    '1 licence',
                    'Gestion client basique'
                ],
                'is_active' => true,
                'stripe_price_id' => null,
                'paypal_plan_id' => null,
                'trial_days' => 0,
                'max_licenses' => 1,
                'max_projects' => 1,
                'max_clients' => 1
            ]
        );

        // Plan Standard
        Plan::updateOrCreate(
            ['slug' => 'plan-standard'],
            [
                'name' => 'Plan Standard',
                'description' => 'Pour les petites entreprises',
                'price' => 29.99,
                'billing_cycle' => 'monthly',
                'features' => [
                    '5 projets',
                    '10 licences',
                    'Support prioritaire',
                    'Gestion client avancée'
                ],
                'is_active' => true,
                'stripe_price_id' => null,
                'paypal_plan_id' => null,
                'trial_days' => 14,
                'max_licenses' => 10,
                'max_projects' => 5,
                'max_clients' => 50
            ]
        );

        // Plan Pro
        Plan::updateOrCreate(
            ['slug' => 'plan-pro'],
            [
                'name' => 'Plan Pro',
                'description' => 'Pour les entreprises en croissance',
                'price' => 99.99,
                'billing_cycle' => 'monthly',
                'features' => [
                    'Projets illimités',
                    'Licences illimitées',
                    'Support 24/7',
                    'API complète',
                    'Tableau de bord personnalisé'
                ],
                'is_active' => true,
                'stripe_price_id' => null,
                'paypal_plan_id' => null,
                'trial_days' => 14,
                'max_licenses' => -1, // illimité
                'max_projects' => -1, // illimité
                'max_clients' => -1 // illimité
            ]
        );
    }
} 