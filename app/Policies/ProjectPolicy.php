<?php

namespace App\Policies;

use App\Models\Client;
use App\Models\Project;

class ProjectPolicy
{
    /**
     * Détermine si le client peut voir tous les projets
     */
    public function viewAny(Client $client): bool
    {
        return $client->hasPermission('projects.view');
    }

    /**
     * Détermine si le client peut voir ce projet
     */
    public function view(Client $client, Project $project): bool
    {
        return $client->tenant_id === $project->tenant_id && 
               $client->hasPermission('projects.view');
    }

    /**
     * Détermine si le client peut créer des projets
     */
    public function create(Client $client): bool
    {
        return $client->hasPermission('projects.create');
    }

    /**
     * Détermine si le client peut modifier ce projet
     */
    public function update(Client $client, Project $project): bool
    {
        return $client->tenant_id === $project->tenant_id && 
               $client->hasPermission('projects.edit');
    }

    /**
     * Détermine si le client peut supprimer ce projet
     */
    public function delete(Client $client, Project $project): bool
    {
        return $client->tenant_id === $project->tenant_id && 
               $client->hasPermission('projects.delete');
    }
} 