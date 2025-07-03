<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Plan;

class UpdatePlansWithLimitsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Configuration complète des plans
        $plansConfig = [
            'Plan Gratuit' => [
                'storage_limit_mb' => 50,
                'max_projects' => 2,
                'max_licenses' => 10,
                'max_clients' => 1,
                'features' => [
                    'Support par email',
                    'Validation de licences basique',
                    'Dashboard simple'
                ]
            ],
            'Plan Standard' => [
                'storage_limit_mb' => 150,
                'max_projects' => 10,
                'max_licenses' => 100,
                'max_clients' => 5,
                'features' => [
                    'Support prioritaire',
                    'Analytics avancées',
                    'API complète',
                    'Personnalisation du branding',
                    'Historique des activations'
                ]
            ],
            'Plan Pro' => [
                'storage_limit_mb' => 300,
                'max_projects' => null, // Illimité
                'max_licenses' => null, // Illimité
                'max_clients' => null,  // Illimité
                'features' => [
                    'Support téléphonique',
                    'Analytics en temps réel',
                    'API avec webhooks',
                    'Branding personnalisé complet',
                    'Intégrations tierces',
                    'Rapports personnalisés',
                    'Multi-utilisateurs',
                    'Sauvegarde automatique'
                ]
            ]
        ];

        foreach ($plansConfig as $planName => $config) {
            $plan = Plan::where('name', $planName)->first();
            
            if ($plan) {
                $plan->update([
                    'storage_limit_mb' => $config['storage_limit_mb'],
                    'max_projects' => $config['max_projects'],
                    'max_licenses' => $config['max_licenses'],
                    'max_clients' => $config['max_clients'],
                    'features' => $config['features']
                ]);
                
                $this->command->info("Mis à jour le plan '{$planName}' avec toutes ses limites");
            } else {
                $this->command->warn("Plan '{$planName}' non trouvé");
            }
        }
    }
}
