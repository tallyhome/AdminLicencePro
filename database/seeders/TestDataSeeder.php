<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tenant;
use App\Models\Project;
use App\Models\SerialKey;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer le premier tenant (ou créer si n'existe pas)
        $tenant = Tenant::first();
        
        if (!$tenant) {
            $this->command->error('Aucun tenant trouvé. Veuillez d\'abord créer un tenant.');
            return;
        }

        $this->command->info("Ajout de données de test pour le tenant: {$tenant->name}");

        // Créer 2 projets de test
        $projects = [];
        for ($i = 1; $i <= 2; $i++) {
            $project = Project::create([
                'tenant_id' => $tenant->id,
                'name' => "Projet Test {$i}",
                'description' => "Description du projet de test {$i}",
                'website_url' => "https://projet-test-{$i}.example.com",
                'status' => 'active',
                'created_at' => now()->subDays(rand(1, 30)),
            ]);
            $projects[] = $project;
            $this->command->info("Créé le projet: {$project->name}");
        }

        // Créer 5 licences de test (réparties sur les projets)
        $statuses = ['active', 'inactive', 'expired'];
        
        foreach ($projects as $index => $project) {
            $licensesCount = $index === 0 ? 3 : 2; // 3 licences pour le premier projet, 2 pour le second
            
            for ($j = 1; $j <= $licensesCount; $j++) {
                $serialKey = SerialKey::create([
                    'project_id' => $project->id,
                    'serial_key' => 'TEST-' . strtoupper(substr(md5($project->id . $j . time()), 0, 8)),
                    'status' => $statuses[array_rand($statuses)],
                    'expires_at' => now()->addMonths(rand(1, 12)),
                    'current_activations' => rand(0, 5),
                    'max_activations' => rand(5, 10),
                    'created_at' => now()->subDays(rand(1, 20)),
                ]);
                
                $this->command->info("Créé la licence: {$serialKey->serial_key} (Status: {$serialKey->status})");
            }
        }

        $this->command->info('Données de test ajoutées avec succès !');
        $this->command->info("Total: " . $tenant->projects()->count() . " projets, " . $tenant->serialKeys()->count() . " licences");
    }
}
