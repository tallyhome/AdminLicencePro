<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    /**
     * Afficher la liste des projets
     */
    public function index(Request $request)
    {
        $client = Auth::guard('client')->user();
        $tenant = $client->tenant;

        // Récupérer uniquement les projets du tenant du client
        $query = $tenant->projects()->with('serialKeys');

        // Filtre par recherche
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // Filtre par statut
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Tri
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        $projects = $query->paginate(15);

        return view('client.projects.index', compact('projects', 'client', 'tenant'));
    }

    /**
     * Afficher le formulaire de création d'un projet
     */
    public function create()
    {
        $client = Auth::guard('client')->user();
        $tenant = $client->tenant;

        return view('client.projects.create', compact('client', 'tenant'));
    }

    /**
     * Enregistrer un nouveau projet
     */
    public function store(Request $request)
    {
        $client = Auth::guard('client')->user();
        $tenant = $client->tenant;

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'website_url' => 'nullable|url|max:255',
            'status' => 'required|in:active,inactive'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Créer le projet et l'associer au tenant
            $project = $tenant->projects()->create([
                'name' => $request->name,
                'description' => $request->description,
                'website_url' => $request->website_url,
                'status' => $request->status,
                'version' => $request->version ?? '1.0.0'
            ]);

            return redirect()->route('client.projects.index')
                ->with('success', 'Projet créé avec succès !');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la création du projet : ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Afficher les détails d'un projet
     */
    public function show(Project $project)
    {
        $client = Auth::guard('client')->user();
        $tenant = $client->tenant;

        $project->load(['serialKeys' => function($query) {
            $query->latest();
        }]);

        // Statistiques du projet
        $stats = [
            'total_licenses' => $project->serialKeys->count(),
            'active_licenses' => $project->serialKeys->where('status', 'active')->count(),
            'inactive_licenses' => $project->serialKeys->where('status', 'inactive')->count(),
            'total_activations' => $project->serialKeys->sum('current_activations') ?? 0,
        ];

        return view('client.projects.show', compact('project', 'stats', 'client', 'tenant'));
    }

    /**
     * Afficher le formulaire d'édition d'un projet
     */
    public function edit(Project $project)
    {
        $client = Auth::guard('client')->user();
        $tenant = $client->tenant;

        return view('client.projects.edit', compact('project', 'client', 'tenant'));
    }

    /**
     * Mettre à jour un projet
     */
    public function update(Request $request, Project $project)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'website_url' => 'nullable|url|max:255',
            'status' => 'required|in:active,inactive'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $project->update($request->only(['name', 'description', 'website_url', 'status']));

            return redirect()->route('client.projects.show', $project)
                ->with('success', 'Projet mis à jour avec succès !');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la mise à jour : ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Supprimer un projet
     */
    public function destroy(Project $project)
    {
        try {
            // Vérifier s'il y a des licences associées
            if ($project->serialKeys()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'Impossible de supprimer ce projet car il contient des licences.');
            }

            $project->delete();

            return redirect()->route('client.projects.index')
                ->with('success', 'Projet supprimé avec succès !');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la suppression : ' . $e->getMessage());
        }
    }

    /**
     * Changer le statut d'un projet
     */
    public function toggleStatus(Project $project)
    {
        try {
            $newStatus = $project->status === 'active' ? 'inactive' : 'active';
            $project->update(['status' => $newStatus]);

            return response()->json([
                'success' => true,
                'message' => 'Statut mis à jour avec succès !',
                'status' => $newStatus
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Générer des licences en lot pour un projet
     */
    public function generateLicenses(Request $request, Project $project)
    {
        $client = Auth::guard('client')->user();
        
        // Vérifier que le projet appartient au tenant du client
        if ($project->tenant_id !== $client->tenant_id) {
            abort(403);
        }

        $request->validate([
            'quantity' => 'required|integer|min:1|max:100',
            'licence_type' => 'required|in:single,multi,unlimited',
            'max_activations' => 'required_if:licence_type,multi|nullable|integer|min:1',
            'expiry_days' => 'nullable|integer|min:1|max:3650',
        ]);

        // Vérifier les limites du plan (simplifiées)
        $currentLicenses = $client->tenant->serialKeys()->count();
        $limit = 999; // Limite temporaire
        
        if (($currentLicenses + $request->quantity) > $limit) {
            return back()->with('error', 'La génération de ces licences dépasserait la limite de votre plan.');
        }

        // Générer les licences
        $licenses = [];
        for ($i = 0; $i < $request->quantity; $i++) {
            $licenses[] = [
                'project_id' => $project->id,
                'tenant_id' => $client->tenant_id,
                'client_id' => $client->id,
                'serial_key' => $this->generateUniqueSerialKey(),
                'licence_type' => $request->licence_type,
                'max_activations' => $request->max_activations,
                'expiry_days' => $request->expiry_days,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        $client->tenant->serialKeys()->insert($licenses);

        return redirect()->route('client.projects.show', $project)
            ->with('success', $request->quantity . ' licences générées avec succès !');
    }

    /**
     * Générer une clé de licence unique
     */
    private function generateUniqueSerialKey()
    {
        do {
            $key = strtoupper(substr(md5(uniqid()), 0, 16));
            $key = implode('-', str_split($key, 4));
        } while (\App\Models\SerialKey::where('serial_key', $key)->exists());

        return $key;
    }
} 