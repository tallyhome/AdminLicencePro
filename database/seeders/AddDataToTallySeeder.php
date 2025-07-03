<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Client;
use App\Models\Project;
use App\Models\SerialKey;

class AddDataToTallySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer le client Tally home
        $client = Client::where('email', 'tally@obione.net')->first();
        
        if (!$client) {
            $this->command->error('Client Tally home non trouvé');
            return;
        }

        $tenant = $client->tenant;
        
        if (!$tenant) {
            $this->command->error('Aucun tenant associé à Tally home');
            return;
        }

        $this->command->info("Ajout de données pour Tally home - Tenant: {$tenant->name}");

        // Ajouter 3 projets supplémentaires
        for ($i = 1; $i <= 3; $i++) {
            $project = Project::create([
                'tenant_id' => $tenant->id,
                'name' => "Application Mobile {$i}",
                'description' => "Application mobile de test numéro {$i}",
                'website_url' => "https://app-mobile-{$i}.obione.net",
                'status' => 'active',
                'created_at' => now()->subDays(rand(1, 15)),
            ]);
            
            $this->command->info("Créé le projet: {$project->name}");

            // Ajouter 2-4 licences par projet
            $licensesCount = rand(2, 4);
            for ($j = 1; $j <= $licensesCount; $j++) {
                $serialKey = SerialKey::create([
                    'project_id' => $project->id,
                    'serial_key' => 'TALLY-' . strtoupper(substr(md5($project->id . $j . time()), 0, 8)),
                    'expires_at' => now()->addMonths(rand(3, 18)),
                    'current_activations' => rand(1, 8),
                    'created_at' => now()->subDays(rand(1, 10)),
                ]);
                
                $this->command->info("  Créé la licence: {$serialKey->serial_key}");
            }
        }

        $this->command->info('Données ajoutées avec succès !');
        $this->command->info("Nouveau total pour {$tenant->name}: " . $tenant->projects()->count() . " projets, " . $tenant->serialKeys()->count() . " licences");
    }
}
