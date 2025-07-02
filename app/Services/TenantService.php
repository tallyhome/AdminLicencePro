<?php

namespace App\Services;

use App\Models\Client;
use App\Models\Tenant;
use App\Models\Role;
use App\Models\Permission;

class TenantService
{
    /**
     * Configurer les données initiales pour un nouveau tenant
     */
    public function setupInitialTenantData(Tenant $tenant, Client $client): void
    {
        $this->createDefaultRoles($tenant);
        $this->assignOwnerRole($client);
        $this->createDefaultSettings($tenant);
    }

    /**
     * Créer les rôles par défaut pour le tenant
     */
    protected function createDefaultRoles(Tenant $tenant): void
    {
        $roles = [
            [
                'name' => 'Propriétaire',
                'slug' => 'owner',
                'description' => 'Propriétaire du compte avec tous les accès'
            ],
            [
                'name' => 'Administrateur',
                'slug' => 'admin', 
                'description' => 'Administrateur avec accès à la plupart des fonctionnalités'
            ],
            [
                'name' => 'Utilisateur',
                'slug' => 'user',
                'description' => 'Utilisateur standard avec accès limité'
            ]
        ];

        foreach ($roles as $roleData) {
            // Vérifier si le rôle existe déjà
            $existingRole = Role::where('slug', $roleData['slug'])->first();
            if (!$existingRole) {
                Role::create($roleData);
            }
        }

        $this->createDefaultPermissions();
    }

    /**
     * Créer les permissions par défaut
     */
    protected function createDefaultPermissions(): void
    {
        $permissions = [
            // Gestion des projets
            ['name' => 'Voir projets', 'slug' => 'projects.view', 'description' => 'Voir la liste des projets'],
            ['name' => 'Créer projets', 'slug' => 'projects.create', 'description' => 'Créer de nouveaux projets'],
            ['name' => 'Modifier projets', 'slug' => 'projects.edit', 'description' => 'Modifier les projets existants'],
            ['name' => 'Supprimer projets', 'slug' => 'projects.delete', 'description' => 'Supprimer des projets'],

            // Gestion des licences
            ['name' => 'Voir licences', 'slug' => 'licenses.view', 'description' => 'Voir la liste des licences'],
            ['name' => 'Créer licences', 'slug' => 'licenses.create', 'description' => 'Créer de nouvelles licences'],
            ['name' => 'Modifier licences', 'slug' => 'licenses.edit', 'description' => 'Modifier les licences existantes'],
            ['name' => 'Supprimer licences', 'slug' => 'licenses.delete', 'description' => 'Supprimer des licences'],

            // Gestion des clients
            ['name' => 'Voir clients', 'slug' => 'clients.view', 'description' => 'Voir la liste des clients'],
            ['name' => 'Créer clients', 'slug' => 'clients.create', 'description' => 'Créer de nouveaux clients'],
            ['name' => 'Modifier clients', 'slug' => 'clients.edit', 'description' => 'Modifier les clients existants'],
            ['name' => 'Supprimer clients', 'slug' => 'clients.delete', 'description' => 'Supprimer des clients'],

            // Gestion du support
            ['name' => 'Voir tickets', 'slug' => 'support.view', 'description' => 'Voir les tickets de support'],
            ['name' => 'Créer tickets', 'slug' => 'support.create', 'description' => 'Créer des tickets de support'],
            ['name' => 'Répondre tickets', 'slug' => 'support.respond', 'description' => 'Répondre aux tickets de support'],

            // Gestion des paramètres
            ['name' => 'Voir paramètres', 'slug' => 'settings.view', 'description' => 'Voir les paramètres du compte'],
            ['name' => 'Modifier paramètres', 'slug' => 'settings.edit', 'description' => 'Modifier les paramètres du compte'],

            // Gestion de la facturation
            ['name' => 'Voir facturation', 'slug' => 'billing.view', 'description' => 'Voir la facturation et les abonnements'],
            ['name' => 'Modifier facturation', 'slug' => 'billing.edit', 'description' => 'Modifier les informations de facturation'],
        ];

        foreach ($permissions as $permissionData) {
            // Vérifier si la permission existe déjà
            $existingPermission = Permission::where('slug', $permissionData['slug'])->first();
            if (!$existingPermission) {
                Permission::create($permissionData);
            }
        }

        $this->assignPermissionsToRoles();
    }

    /**
     * Assigner les permissions aux rôles
     */
    protected function assignPermissionsToRoles(): void
    {
        $ownerRole = Role::where('slug', 'owner')->first();
        $adminRole = Role::where('slug', 'admin')->first();
        $userRole = Role::where('slug', 'user')->first();

        if ($ownerRole && $ownerRole->permissions()->count() === 0) {
            // Le propriétaire a toutes les permissions
            $allPermissions = Permission::all();
            $ownerRole->permissions()->attach($allPermissions);
        }

        if ($adminRole && $adminRole->permissions()->count() === 0) {
            // L'admin a la plupart des permissions sauf la facturation
            $adminPermissions = Permission::whereNotIn('slug', ['billing.edit'])->get();
            $adminRole->permissions()->attach($adminPermissions);
        }

        if ($userRole && $userRole->permissions()->count() === 0) {
            // L'utilisateur a des permissions limitées
            $userPermissions = Permission::whereIn('slug', [
                'projects.view', 'licenses.view', 'clients.view',
                'support.view', 'support.create', 'settings.view'
            ])->get();
            $userRole->permissions()->attach($userPermissions);
        }
    }

    /**
     * Assigner le rôle de propriétaire au client
     */
    protected function assignOwnerRole(Client $client): void
    {
        $ownerRole = Role::where('slug', 'owner')->first();
        if ($ownerRole) {
            $client->roles()->attach($ownerRole);
        }
    }

    /**
     * Créer les paramètres par défaut pour le tenant
     */
    protected function createDefaultSettings(Tenant $tenant): void
    {
        $defaultSettings = [
            'company_logo' => null,
            'company_website' => null,
            'notification_preferences' => [
                'email_notifications' => true,
                'license_expiry_alerts' => true,
                'payment_reminders' => true,
                'usage_reports' => true,
            ],
            'api_settings' => [
                'rate_limit' => 1000, // Par heure
                'allowed_ips' => [],
                'webhook_url' => null,
            ],
            'ui_preferences' => [
                'theme' => 'light',
                'sidebar_collapsed' => false,
                'items_per_page' => 25,
            ]
        ];

        $currentSettings = $tenant->settings ?? [];
        $tenant->settings = array_merge($defaultSettings, $currentSettings);
        $tenant->save();
    }

    /**
     * Vérifier si un tenant a atteint ses limites
     */
    public function checkTenantLimits(Tenant $tenant): array
    {
        $subscription = $tenant->subscriptions()->first();
        $plan = $subscription ? $subscription->plan : null;

        if (!$plan) {
            return ['within_limits' => false, 'message' => 'Aucun plan actif'];
        }

        $limits = [
            'projects' => [
                'current' => $tenant->projects()->count(),
                'max' => $plan->max_projects,
                'within_limit' => true
            ],
            'licenses' => [
                'current' => $tenant->serialKeys()->count(),
                'max' => $plan->max_licenses,
                'within_limit' => true
            ],
            'clients' => [
                'current' => $tenant->clients()->count(),
                'max' => $plan->max_clients,
                'within_limit' => true
            ]
        ];

        foreach ($limits as $key => &$limit) {
            if ($limit['max'] > 0 && $limit['current'] >= $limit['max']) {
                $limit['within_limit'] = false;
            }
        }

        $withinLimits = collect($limits)->every(function ($limit) {
            return $limit['within_limit'];
        });

        return [
            'within_limits' => $withinLimits,
            'limits' => $limits,
            'plan' => $plan
        ];
    }

    /**
     * Obtenir les statistiques d'usage du tenant
     */
    public function getTenantUsageStats(Tenant $tenant): array
    {
        $subscription = $tenant->subscriptions()->first();
        $plan = $subscription ? $subscription->plan : null;

        return [
            'projects' => [
                'count' => $tenant->projects()->count(),
                'limit' => $plan ? $plan->max_projects : 0,
                'percentage' => $plan && $plan->max_projects > 0 
                    ? round(($tenant->projects()->count() / $plan->max_projects) * 100, 1)
                    : 0
            ],
            'licenses' => [
                'count' => $tenant->serialKeys()->count(),
                'active' => $tenant->serialKeys()->where('status', 'active')->count(),
                'limit' => $plan ? $plan->max_licenses : 0,
                'percentage' => $plan && $plan->max_licenses > 0
                    ? round(($tenant->serialKeys()->count() / $plan->max_licenses) * 100, 1)
                    : 0
            ],
            'clients' => [
                'count' => $tenant->clients()->count(),
                'active' => $tenant->clients()->where('status', 'active')->count(),
                'limit' => $plan ? $plan->max_clients : 0,
                'percentage' => $plan && $plan->max_clients > 0
                    ? round(($tenant->clients()->count() / $plan->max_clients) * 100, 1)
                    : 0
            ],
            'support_tickets' => [
                'total' => $tenant->supportTickets()->count(),
                'open' => $tenant->supportTickets()->where('status', 'open')->count(),
                'resolved' => $tenant->supportTickets()->where('status', 'resolved')->count(),
            ]
        ];
    }
} 