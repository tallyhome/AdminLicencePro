<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\ApiKey;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ApiKeyController extends Controller
{
    /**
     * Afficher la liste des clés API
     */
    public function index(Request $request)
    {
        $client = Auth::guard('client')->user();
        $tenant = $client->tenant;

        $query = $tenant->apiKeys()->with(['project']);

        // Filtre par projet
        if ($request->has('project') && $request->project) {
            $query->where('project_id', $request->project);
        }

        // Recherche
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('key', 'like', '%' . $request->search . '%');
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

        $apiKeys = $query->paginate(20);

        // Statistiques
        $stats = [
            'total' => $tenant->apiKeys()->count(),
            'active' => $tenant->apiKeys()->where('status', 'active')->count(),
            'inactive' => $tenant->apiKeys()->where('status', 'inactive')->count(),
            'last_used' => $tenant->apiKeys()->whereNotNull('last_used_at')->count(),
        ];

        // Projets disponibles pour le filtre
        $projects = $tenant->projects()->orderBy('name')->get();

        // Limites du plan (simplifiées)
        $planLimits = [
            'limits' => [
                'api_keys' => [
                    'within_limit' => true,
                    'current' => $stats['total'],
                    'limit' => 999
                ]
            ]
        ];

        return view('client.api-keys.index', compact(
            'apiKeys', 
            'stats', 
            'projects', 
            'planLimits', 
            'client', 
            'tenant'
        ));
    }

    /**
     * Afficher le formulaire de création d'une clé API
     */
    public function create(Request $request)
    {
        $client = Auth::guard('client')->user();
        $tenant = $client->tenant;

        // Vérifier les limites (simplifiées)
        $planLimits = [
            'limits' => [
                'api_keys' => [
                    'within_limit' => true,
                    'current' => $tenant->apiKeys()->count(),
                    'limit' => 999
                ]
            ]
        ];
        
        if (!$planLimits['limits']['api_keys']['within_limit']) {
            return redirect()->route('client.api-keys.index')
                ->with('error', 'Vous avez atteint la limite de clés API de votre plan. Mettez à niveau pour créer plus de clés API.');
        }

        // Projets disponibles
        $projects = $tenant->projects()->where('status', 'active')->orderBy('name')->get();

        // Projet présélectionné
        $selectedProject = null;
        if ($request->has('project')) {
            $selectedProject = $projects->find($request->project);
        }

        return view('client.api-keys.create', compact('client', 'tenant', 'projects', 'selectedProject', 'planLimits'));
    }

    /**
     * Enregistrer une nouvelle clé API
     */
    public function store(Request $request)
    {
        $client = Auth::guard('client')->user();
        $tenant = $client->tenant;

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'project_id' => ['required', 'exists:projects,id'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', 'in:read,write,delete'],
            'expires_at' => ['nullable', 'date', 'after:today'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        // Vérifier que le projet appartient au tenant
        $project = $tenant->projects()->findOrFail($request->project_id);

        // Vérifier les limites (simplifiées)
        $currentCount = $tenant->apiKeys()->count();
        if ($currentCount >= 999) {
            return redirect()->back()
                ->with('error', 'Vous avez atteint la limite de clés API de votre plan.');
        }

        // Générer la clé API
        $apiKey = $this->generateApiKey();

        $key = ApiKey::create([
            'name' => $request->name,
            'key' => $apiKey,
            'project_id' => $project->id,
            'tenant_id' => $tenant->id,
            'permissions' => $request->permissions ?? ['read'],
            'expires_at' => $request->expires_at,
            'status' => $request->status,
            'last_used_at' => null,
        ]);

        return redirect()->route('client.api-keys.show', $key)
            ->with('success', 'Clé API créée avec succès !')
            ->with('new_api_key', $apiKey); // Pour afficher la clé une seule fois
    }

    /**
     * Afficher les détails d'une clé API
     */
    public function show(ApiKey $apiKey)
    {
        $client = Auth::guard('client')->user();
        $this->authorize('view', $apiKey);

        // Charger les relations
        $apiKey->load(['project']);

        // Statistiques d'utilisation (simulées pour l'exemple)
        $usageStats = [
            'total_requests' => rand(100, 5000),
            'requests_today' => rand(10, 100),
            'requests_this_month' => rand(100, 1000),
            'last_request' => $apiKey->last_used_at ?: 'Jamais utilisée',
        ];

        return view('client.api-keys.show', compact('apiKey', 'usageStats', 'client'));
    }

    /**
     * Afficher le formulaire d'édition d'une clé API
     */
    public function edit(ApiKey $apiKey)
    {
        $client = Auth::guard('client')->user();
        $this->authorize('update', $apiKey);

        $tenant = $client->tenant;
        $projects = $tenant->projects()->where('status', 'active')->orderBy('name')->get();

        return view('client.api-keys.edit', compact('apiKey', 'projects', 'client'));
    }

    /**
     * Mettre à jour une clé API
     */
    public function update(Request $request, ApiKey $apiKey)
    {
        $client = Auth::guard('client')->user();
        $this->authorize('update', $apiKey);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'project_id' => ['required', 'exists:projects,id'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', 'in:read,write,delete'],
            'expires_at' => ['nullable', 'date'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        // Vérifier que le projet appartient au tenant
        $client->tenant->projects()->findOrFail($request->project_id);

        $apiKey->update([
            'name' => $request->name,
            'project_id' => $request->project_id,
            'permissions' => $request->permissions ?? ['read'],
            'expires_at' => $request->expires_at,
            'status' => $request->status,
        ]);

        return redirect()->route('client.api-keys.show', $apiKey)
            ->with('success', 'Clé API mise à jour avec succès !');
    }

    /**
     * Supprimer une clé API
     */
    public function destroy(ApiKey $apiKey)
    {
        $client = Auth::guard('client')->user();
        $this->authorize('delete', $apiKey);

        $apiKeyName = $apiKey->name;
        $apiKey->delete();

        return redirect()->route('client.api-keys.index')
            ->with('success', "La clé API '{$apiKeyName}' a été supprimée avec succès.");
    }

    /**
     * Régénérer une clé API
     */
    public function regenerate(ApiKey $apiKey)
    {
        $client = Auth::guard('client')->user();
        $this->authorize('update', $apiKey);

        $newKey = $this->generateApiKey();
        $oldKey = $apiKey->key;
        
        $apiKey->update(['key' => $newKey]);

        return redirect()->route('client.api-keys.show', $apiKey)
            ->with('success', 'Clé API régénérée avec succès !')
            ->with('new_api_key', $newKey);
    }

    /**
     * Désactiver une clé API
     */
    public function deactivate(ApiKey $apiKey)
    {
        $client = Auth::guard('client')->user();
        $this->authorize('update', $apiKey);

        $apiKey->update(['status' => 'inactive']);

        return redirect()->back()
            ->with('success', 'Clé API désactivée avec succès.');
    }

    /**
     * Activer une clé API
     */
    public function activate(ApiKey $apiKey)
    {
        $client = Auth::guard('client')->user();
        $this->authorize('update', $apiKey);

        $apiKey->update(['status' => 'active']);

        return redirect()->back()
            ->with('success', 'Clé API activée avec succès.');
    }

    /**
     * Tester une clé API
     */
    public function test(ApiKey $apiKey)
    {
        $client = Auth::guard('client')->user();
        $this->authorize('view', $apiKey);

        // Simuler un test d'API
        $testResults = [
            'status' => 'success',
            'response_time' => rand(50, 500),
            'endpoint' => '/api/v1/licenses/validate',
            'timestamp' => now()->toISOString(),
        ];

        return response()->json($testResults);
    }

    /**
     * Exporter les clés API
     */
    public function export(Request $request)
    {
        $client = Auth::guard('client')->user();
        $tenant = $client->tenant;

        $query = $tenant->apiKeys()->with(['project']);

        // Filtres
        if ($request->has('project') && $request->project) {
            $query->where('project_id', $request->project);
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $apiKeys = $query->get();
        $format = $request->get('format', 'json');

        $data = [
            'api_keys' => $apiKeys->map(function($key) {
                return [
                    'name' => $key->name,
                    'project' => $key->project->name,
                    'status' => $key->status,
                    'permissions' => $key->permissions,
                    'created_at' => $key->created_at->format('d/m/Y'),
                    'expires_at' => $key->expires_at ? $key->expires_at->format('d/m/Y') : null,
                    'last_used_at' => $key->last_used_at ? $key->last_used_at->format('d/m/Y') : null,
                ];
            })->toArray(),
            'exported_at' => now()->toISOString(),
            'total_count' => $apiKeys->count(),
        ];

        if ($format === 'csv') {
            return $this->exportToCsv($apiKeys);
        }

        return response()->json($data);
    }

    /**
     * Générer une clé API unique
     */
    private function generateApiKey(): string
    {
        do {
            $key = 'ak_' . Str::random(32);
        } while (ApiKey::where('key', $key)->exists());

        return $key;
    }

    /**
     * Exporter en CSV
     */
    private function exportToCsv($apiKeys)
    {
        $filename = 'api_keys_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($apiKeys) {
            $file = fopen('php://output', 'w');
            
            // En-têtes
            fputcsv($file, [
                'Nom',
                'Projet',
                'Statut',
                'Permissions',
                'Expire le',
                'Dernière utilisation',
                'Créé le'
            ]);

            // Données
            foreach ($apiKeys as $key) {
                fputcsv($file, [
                    $key->name,
                    $key->project->name,
                    $key->status,
                    implode(', ', $key->permissions),
                    $key->expires_at ? $key->expires_at->format('d/m/Y') : '',
                    $key->last_used_at ? $key->last_used_at->format('d/m/Y') : '',
                    $key->created_at->format('d/m/Y')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
} 