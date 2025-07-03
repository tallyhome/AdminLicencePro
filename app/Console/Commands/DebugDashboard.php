<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Client;
use App\Models\Tenant;

class DebugDashboard extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'debug:dashboard';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Déboguer le problème du dashboard';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== DEBUG DASHBOARD ===');
        
        // Vérifier les clients
        $clients = Client::all();
        $this->info("Nombre total de clients: " . $clients->count());
        
        foreach ($clients as $client) {
            $this->info("Client: {$client->name} (ID: {$client->id})");
            $this->info("  - Email: {$client->email}");
            $this->info("  - Tenant ID: " . ($client->tenant_id ?? 'NULL'));
            
            if ($client->tenant) {
                $tenant = $client->tenant;
                $this->info("  - Tenant: {$tenant->name}");
                $this->info("  - Projets du tenant: " . $tenant->projects()->count());
                $this->info("  - Licences du tenant: " . $tenant->serialKeys()->count());
                
                // Vérifier l'abonnement
                $subscription = $tenant->subscriptions()->with('plan')->latest()->first();
                if ($subscription) {
                    $this->info("  - Plan: " . $subscription->plan->name);
                } else {
                    $this->warn("  - AUCUN ABONNEMENT TROUVÉ");
                }
            } else {
                $this->error("  - AUCUN TENANT ASSOCIÉ");
            }
            $this->line('');
        }
        
        // Vérifier les tenants
        $this->info('=== TENANTS ===');
        $tenants = Tenant::all();
        foreach ($tenants as $tenant) {
            $this->info("Tenant: {$tenant->name} (ID: {$tenant->id})");
            $this->info("  - Projets: " . $tenant->projects()->count());
            $this->info("  - Licences: " . $tenant->serialKeys()->count());
            $this->info("  - Clients: " . $tenant->clients()->count());
            $this->line('');
        }
    }
}
