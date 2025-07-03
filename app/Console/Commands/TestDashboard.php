<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tenant;

class TestDashboard extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:dashboard';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tester les statistiques du dashboard';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tenant = Tenant::first();
        
        if (!$tenant) {
            $this->error('Aucun tenant trouvé');
            return;
        }

        $this->info("Test des statistiques pour le tenant: {$tenant->name}");
        
        // Test des projets
        $projectCount = $tenant->projects()->count();
        $this->info("Projets: {$projectCount}");
        
        // Test des licences
        $licenseCount = $tenant->serialKeys()->count();
        $this->info("Licences totales: {$licenseCount}");
        
        $activeLicenseCount = $tenant->serialKeys()->where('serial_keys.status', 'active')->count();
        $this->info("Licences actives: {$activeLicenseCount}");
        
        $totalActivations = $tenant->serialKeys()->sum('current_activations');
        $this->info("Total activations: {$totalActivations}");
        
        // Test de l'abonnement
        $subscription = $tenant->subscriptions()->with('plan')->latest()->first();
        if ($subscription) {
            $plan = $subscription->plan;
            $this->info("Plan: {$plan->name}");
            $this->info("Limite projets: " . ($plan->max_projects ?? 'Illimité'));
            $this->info("Limite licences: " . ($plan->max_licenses ?? 'Illimité'));
            $this->info("Limite stockage: {$plan->storage_limit_mb}MB");
        } else {
            $this->warn("Aucun abonnement trouvé");
        }
        
        $this->info('Test terminé !');
    }
}
