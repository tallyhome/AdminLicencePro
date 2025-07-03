<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Tenant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TestProjectsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tenant = Tenant::first();
        
        if (!$tenant) {
            $this->command->error('Aucun tenant trouvé. Veuillez d\'abord créer un tenant.');
            return;
        }

        $this->command->info('Création de 20 projets de test...');

        for ($i = 1; $i <= 20; $i++) {
            Project::create([
                'name' => 'Projet Test ' . $i,
                'description' => 'Description du projet test numéro ' . $i . ' pour tester la pagination.',
                'tenant_id' => $tenant->id,
                'status' => 'active'
            ]);
        }

        $this->command->info('20 projets de test créés avec succès !');
    }
}
