<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\SerialKey;
use App\Services\HistoryService;
use App\Services\LicenceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SerialKeyController extends Controller
{
    protected $licenceService;
    protected $historyService;

    public function __construct(LicenceService $licenceService, HistoryService $historyService)
    {
        $this->licenceService = $licenceService;
        $this->historyService = $historyService;
    }

    /**
     * Afficher la liste des clés de licence
     */
    public function index(Request $request)
    {
        // Récupérer les paramètres de pagination
        $perPage = $request->input('per_page', 10);
        $validPerPage = in_array($perPage, [10, 25, 50, 100, 500, 1000]) ? $perPage : 10;
        
        // Récupérer les paramètres de recherche et de filtrage
        $search = $request->input('search');
        $projectFilter = $request->input('project_id');
        $domainFilter = $request->input('domain');
        $ipFilter = $request->input('ip_address');
        $statusFilter = $request->input('status');
        $usedFilter = $request->input('used');
        
        // Construire la requête
        $query = SerialKey::with(['project']);
        
        // Appliquer les filtres
        if ($projectFilter) {
            $query->where('project_id', $projectFilter);
        }
        
        if ($domainFilter) {
            $query->where('domain', 'like', "%{$domainFilter}%");
        }
        
        if ($ipFilter) {
            $query->where('ip_address', 'like', "%{$ipFilter}%");
        }
        
        if ($statusFilter) {
            $query->where('status', $statusFilter);
        }
        
        // Appliquer la recherche
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('serial_key', 'like', "%{$search}%")
                  ->orWhere('domain', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%")
                  ->orWhereHas('project', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        // Ajouter une logique pour détecter les clés expirées
        if ($request->input('status') === 'expired') {
            $query->where(function($q) {
                $q->whereNotNull('expires_at')
                  ->where('expires_at', '<', now());
            });
        }
        
        // Ajouter une logique pour détecter les clés utilisées
        if ($usedFilter === 'true') {
            $query->where(function($q) {
                $q->whereNotNull('domain')
                  ->orWhereNotNull('ip_address');
            });
        }
        
        // Récupérer les résultats
        $serialKeys = $query->latest()->paginate($validPerPage)->appends(request()->query());
        
        // Récupérer la liste des projets pour le filtre
        $projects = Project::all();
        
        // Liste des statuts pour le filtre
        $statuses = [
            'active' => 'Active',
            'suspended' => 'Suspendue',
            'revoked' => 'Révoquée',
            'expired' => 'Expirée',
            'used' => 'Utilisé'
        ];
        
        return view('admin.serial-keys.index', compact('serialKeys', 'projects', 'statuses'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $projects = Project::all();
        return view('admin.serial-keys.create', compact('projects'));
    }

    /**
     * Stocker une nouvelle clé de licence
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'quantity' => 'required|integer|min:1|max:100000',
            'domain' => 'nullable|string|max:255',
            'ip_address' => 'nullable|ip',
            'expires_at' => 'nullable|date|after:today',
        ]);

        $project = Project::findOrFail($validated['project_id']);
        $keys = [];

        DB::transaction(function () use ($validated, $project, &$keys) {
            for ($i = 0; $i < $validated['quantity']; $i++) {
                $key = new SerialKey([
                    'serial_key' => $this->licenceService->generateKey(),
                    'project_id' => $project->id,
                    'domain' => $validated['domain'],
                    'ip_address' => $validated['ip_address'],
                    'expires_at' => $validated['expires_at'],
                    'status' => 'active',
                ]);

                $key->save();
                $keys[] = $key;

                $this->historyService->logAction(
                    $key,
                    'create',
                    'Création d\'une nouvelle clé de licence'
                );
            }
        });

        return redirect()
            ->route('admin.serial-keys.index')
            ->with('success', $validated['quantity'] . ' clé(s) de licence créée(s) avec succès.');
    }

    /**
     * Afficher les détails d'une clé
     */
    public function show(SerialKey $serialKey)
    {
        $serialKey->load(['project', 'history']);
        return view('admin.serial-keys.show', compact('serialKey'));
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(SerialKey $serialKey)
    {
        $projects = Project::all();
        return view('admin.serial-keys.edit', compact('serialKey', 'projects'));
    }

    /**
     * Mettre à jour une clé
     */
    public function update(Request $request, SerialKey $serialKey)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'status' => 'required|in:active,suspended,revoked,expired',
            'domain' => 'nullable|string|max:255',
            'ip_address' => 'nullable|ip',
            'expires_at' => 'nullable|date|after:today',
        ]);

        $oldStatus = $serialKey->status;
        $serialKey->update($validated);

        if ($oldStatus !== $validated['status']) {
            $this->historyService->logAction(
                $serialKey,
                'status_change',
                'Changement de statut de la clé de ' . $oldStatus . ' à ' . $validated['status']
            );
        }

        return redirect()
            ->route('admin.serial-keys.show', $serialKey)
            ->with('success', 'Clé de licence mise à jour avec succès.');
    }

    /**
     * Supprimer une clé
     */
    public function destroy(SerialKey $serialKey)
    {
        $serialKey->delete();

        $this->historyService->logAction(
            $serialKey,
            'delete',
            'Suppression de la clé de licence'
        );

        return redirect()
            ->route('admin.serial-keys.index')
            ->with('success', 'Clé de licence supprimée avec succès.');
    }

    /**
     * Révoquer une clé
     */
    public function revoke(SerialKey $serialKey)
    {
        if ($serialKey->status === 'active') {
            $serialKey->update(['status' => 'revoked']);

            $this->historyService->logAction(
                $serialKey,
                'revoke',
                'Révocation de la clé de licence'
            );

            return redirect()
                ->route('admin.serial-keys.show', $serialKey)
                ->with('success', 'Clé de licence révoquée avec succès.');
        }

        return redirect()
            ->route('admin.serial-keys.show', $serialKey)
            ->with('error', 'Impossible de révoquer une clé non active.');
    }

    /**
     * Suspendre une clé
     */
    public function suspend(SerialKey $serialKey)
    {
        if ($serialKey->status === 'active') {
            $serialKey->update(['status' => 'suspended']);

            $this->historyService->logAction(
                $serialKey,
                'suspend',
                'Suspension de la clé de licence'
            );

            return redirect()
                ->route('admin.serial-keys.show', $serialKey)
                ->with('success', 'Clé de licence suspendue avec succès.');
        }

        return redirect()
            ->route('admin.serial-keys.show', $serialKey)
            ->with('error', 'Impossible de suspendre une clé non active.');
    }

    /**
     * Réactiver une clé
     */
    public function reactivate(SerialKey $serialKey)
    {
        if ($serialKey->status === 'suspended') {
            $serialKey->update(['status' => 'active']);

            $this->historyService->logAction(
                $serialKey,
                'reactivate',
                'Réactivation de la clé de licence'
            );

            return redirect()
                ->route('admin.serial-keys.show', $serialKey)
                ->with('success', 'Clé de licence réactivée avec succès.');
        }

        return redirect()
            ->route('admin.serial-keys.show', $serialKey)
            ->with('error', 'Impossible de réactiver une clé non suspendue.');
    }
}