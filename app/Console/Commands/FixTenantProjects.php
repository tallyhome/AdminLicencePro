<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Project;
use App\Models\Tenant;
use App\Models\SerialKey;

class FixTenantProjects extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:tenant-projects';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix tenant_id for projects and display dashboard statistics';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Vérification et correction des tenant_id pour les projets...');
        
        // Récupérer tous les tenants
        $tenants = Tenant::all();
        $this->info("Nombre de tenants trouvés: {$tenants->count()}");
        
        // Vérifier les projets sans tenant_id
        $projectsWithoutTenant = Project::whereNull('tenant_id')->get();
        $this->info("Projets sans tenant_id: {$projectsWithoutTenant->count()}");
        
        if ($projectsWithoutTenant->count() > 0 && $tenants->count() > 0) {
            // Assigner le premier tenant à tous les projets sans tenant_id
            $firstTenant = $tenants->first();
            $this->info("Assignation du tenant '{$firstTenant->name}' (ID: {$firstTenant->id}) aux projets sans tenant_id...");
            
            foreach ($projectsWithoutTenant as $project) {
                $project->tenant_id = $firstTenant->id;
                $project->save();
                $this->line("  - Projet '{$project->name}' mis à jour avec tenant_id: {$firstTenant->id}");
            }
        }
        
        // Afficher les statistiques pour chaque tenant
        foreach ($tenants as $tenant) {
            $this->info("\nTenant: {$tenant->name}");
            
            $totalProjects = $tenant->projects()->count();
            $projectIds = $tenant->projects()->pluck('id');
            $totalLicenses = SerialKey::whereIn('project_id', $projectIds)->count();
            $activeLicenses = SerialKey::whereIn('project_id', $projectIds)
                ->where('status', 'active')->count();
            $totalActivations = SerialKey::whereIn('project_id', $projectIds)
                ->sum('activation_count');
                
            $this->line("  Statistiques: {$totalProjects} projets, {$totalLicenses} licences, {$activeLicenses} licences actives, {$totalActivations} activations");
        }
        
        $this->success('Correction terminée!');
        
        return 0;
    }
}
