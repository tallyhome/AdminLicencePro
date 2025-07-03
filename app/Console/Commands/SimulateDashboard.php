<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Client;

class SimulateDashboard extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'simulate:dashboard {client_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Simuler le calcul du dashboard pour un client';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $clientId = $this->argument('client_id');
        $client = Client::find($clientId);
        
        if (!$client) {
            $this->error("Client avec ID {$clientId} non trouvé");
            return;
        }

        $this->info("=== SIMULATION DASHBOARD POUR {$client->name} ===");
        
        $tenant = $client->tenant;
        
        if (!$tenant) {
            $this->error('Aucun tenant associé - Dashboard fallback sera utilisé');
            return;
        }

        $this->info("Tenant: {$tenant->name}");
        
        // Récupérer l'abonnement actuel (copie exacte du contrôleur)
        $subscription = $tenant->subscriptions()->with('plan')->latest()->first();
        
        if (!$subscription) {
            $this->warn('Aucun abonnement trouvé');
            return;
        }
        
        $plan = $subscription->plan;
        $this->info("Plan: {$plan->name}");
        
        // Compter les projets et licences réels (copie exacte du contrôleur)
        $projectCount = $tenant->projects()->count();
        $licenseCount = $tenant->serialKeys()->count();
        $activeLicenseCount = $tenant->serialKeys()->where('serial_keys.status', 'active')->count();
        $totalActivations = $tenant->serialKeys()->sum('current_activations');
        
        $this->info("Données réelles:");
        $this->info("  - Projets: {$projectCount}");
        $this->info("  - Licences: {$licenseCount}");
        $this->info("  - Licences actives: {$activeLicenseCount}");
        $this->info("  - Total activations: {$totalActivations}");
        
        // Calculer les limites et pourcentages (copie exacte du contrôleur)
        $projectLimit = $plan ? ($plan->max_projects === null ? 'Illimité' : $plan->max_projects) : 2;
        $licenseLimit = $plan ? ($plan->max_licenses === null ? 'Illimité' : $plan->max_licenses) : 10;
        
        $projectPercentage = ($projectLimit !== 'Illimité' && $projectLimit > 0) ? round(($projectCount / $projectLimit) * 100) : 0;
        $licensePercentage = ($licenseLimit !== 'Illimité' && $licenseLimit > 0) ? round(($licenseCount / $licenseLimit) * 100) : 0;
        
        $this->info("Limites du plan:");
        $this->info("  - Limite projets: {$projectLimit}");
        $this->info("  - Limite licences: {$licenseLimit}");
        
        $this->info("Calculs finaux:");
        $this->info("  - Projets: {$projectCount} sur {$projectLimit} ({$projectPercentage}%)");
        $this->info("  - Licences: {$licenseCount} sur {$licenseLimit} ({$licensePercentage}%)");
        
        // Statut
        $projectStatus = $projectPercentage < 80 ? 'success' : ($projectPercentage < 90 ? 'warning' : 'danger');
        $licenseStatus = $licensePercentage < 80 ? 'success' : ($licensePercentage < 90 ? 'warning' : 'danger');
        
        $this->info("Statuts:");
        $this->info("  - Projets: {$projectStatus}");
        $this->info("  - Licences: {$licenseStatus}");
    }
}
