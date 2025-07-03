<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Plan;

class UpdatePlansWithStorageLimitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Nouvelles limites de stockage selon les spécifications
        $storageMapping = [
            'gratuit' => 50,   // 50MB pour le plan gratuit
            'free' => 50,      // 50MB pour le plan gratuit
            'standard' => 150, // 150MB pour le plan standard
            'basic' => 150,    // 150MB pour le plan basic
            'pro' => 300,      // 300MB pour le plan pro
            'premium' => 300,  // 300MB pour le plan premium
        ];

        $plans = Plan::all();

        foreach ($plans as $plan) {
            $slug = strtolower($plan->slug ?? $plan->name);
            $name = strtolower($plan->name);
            
            // Déterminer la limite de stockage basée sur le nom/slug du plan
            $storageLimit = 50; // Par défaut (gratuit)
            
            // Vérifier d'abord le nom du plan
            foreach ($storageMapping as $planType => $limit) {
                if (str_contains($name, $planType) || str_contains($slug, $planType)) {
                    $storageLimit = $limit;
                    break;
                }
            }
            
            // Attribution basée sur le prix si pas trouvé par nom
            if ($storageLimit == 50) { // Si toujours par défaut
                if ($plan->price >= 30) {
                    $storageLimit = 300; // Plan Pro
                } elseif ($plan->price >= 10) {
                    $storageLimit = 150; // Plan Standard
                } else {
                    $storageLimit = 50;  // Plan Gratuit
                }
            }

            $plan->update(['storage_limit_mb' => $storageLimit]);
            
            $this->command->info("Mis à jour le plan '{$plan->name}' avec {$storageLimit}MB de stockage");
        }
    }
}
