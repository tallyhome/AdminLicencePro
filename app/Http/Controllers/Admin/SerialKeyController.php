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
        $licenceTypeFilter = $request->input('licence_type');
        
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
        
        if ($licenceTypeFilter) {
            $query->where('licence_type', $licenceTypeFilter);
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
        
        // Ajouter une logique pour détecter les clés utilisées/non utilisées
        if ($usedFilter === 'true') {
            // Clés utilisées : used_accounts > 0 OU (domain OU ip_address non null pour les single)
            $query->where(function($q) {
                $q->where('used_accounts', '>', 0)
                  ->orWhere(function($subQ) {
                      $subQ->where('licence_type', 'single')
                           ->where(function($innerQ) {
                               $innerQ->whereNotNull('domain')
                                      ->orWhereNotNull('ip_address');
                           });
                  });
            });
        } elseif ($usedFilter === 'false') {
            // Clés non utilisées : used_accounts = 0 ET (domain ET ip_address sont null pour les single)
            $query->where(function($q) {
                $q->where('used_accounts', 0)
                  ->where(function($subQ) {
                      $subQ->where('licence_type', 'multi')
                           ->orWhere(function($innerQ) {
                               $innerQ->where('licence_type', 'single')
                                      ->whereNull('domain')
                                      ->whereNull('ip_address');
                           });
                  });
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
            'licence_type' => 'required|in:single,multi',
            'max_accounts' => 'required_if:licence_type,multi|nullable|integer|min:1|max:1000',
            'domain' => 'nullable|string|max:255',
            'ip_address' => 'nullable|ip',
            'expires_at' => 'nullable|date|after:today',
        ]);

        $project = Project::findOrFail($validated['project_id']);
        $keys = [];

        // Pour les licences single, max_accounts doit être 1
        if ($validated['licence_type'] === 'single') {
            $validated['max_accounts'] = 1;
        }

        DB::transaction(function () use ($validated, $project, &$keys) {
            for ($i = 0; $i < $validated['quantity']; $i++) {
                $key = new SerialKey([
                    'serial_key' => $this->licenceService->generateKey(),
                    'project_id' => $project->id,
                    'licence_type' => $validated['licence_type'],
                    'max_accounts' => $validated['max_accounts'],
                    'used_accounts' => 0,
                    'domain' => $validated['licence_type'] === 'single' ? $validated['domain'] : null,
                    'ip_address' => $validated['licence_type'] === 'single' ? $validated['ip_address'] : null,
                    'expires_at' => $validated['expires_at'],
                    'status' => 'active',
                ]);

                $key->save();
                $keys[] = $key;

                $this->historyService->logAction(
                    $key,
                    'create',
                    'Création d\'une nouvelle clé de licence ' . $validated['licence_type'] . 
                    ($validated['licence_type'] === 'multi' ? ' (max: ' . $validated['max_accounts'] . ' comptes)' : '')
                );
            }
        });

        return redirect()
            ->route('admin.serial-keys.index')
            ->with('success', $validated['quantity'] . ' clé(s) de licence ' . $validated['licence_type'] . ' créée(s) avec succès.');
    }

    /**
     * Afficher les détails d'une clé
     */
    public function show(SerialKey $serialKey)
    {
        $serialKey->load(['project', 'history', 'accounts']);
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
            'serial_key' => [
                'required',
                'string',
                'regex:/^[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}$/',
                'unique:serial_keys,serial_key,' . $serialKey->id
            ],
            'project_id' => 'required|exists:projects,id',
            'status' => 'required|in:active,suspended,revoked,expired',
            'domain' => 'nullable|string|max:255',
            'ip_address' => 'nullable|ip',
            'expires_at' => 'nullable|date|after:today',
        ], [
            'serial_key.regex' => 'La clé de licence doit respecter le format XXXX-XXXX-XXXX-XXXX avec des lettres majuscules et des chiffres uniquement.',
            'serial_key.unique' => 'Cette clé de licence existe déjà. Veuillez en choisir une autre.',
        ]);

        // Récupérer les anciennes valeurs pour l'historique
        $oldSerialKey = $serialKey->serial_key;
        $oldProjectId = $serialKey->project_id;
        $oldStatus = $serialKey->status;

        // Mettre à jour la clé
        $serialKey->update($validated);

        // Enregistrer les changements dans l'historique
        $changes = [];

        if ($oldSerialKey !== $validated['serial_key']) {
            $changes[] = "Clé modifiée de '{$oldSerialKey}' vers '{$validated['serial_key']}'";
            $this->historyService->logAction(
                $serialKey,
                'update',
                "Modification de la clé de licence : {$oldSerialKey} → {$validated['serial_key']}"
            );
        }

        if ($oldProjectId !== $validated['project_id']) {
            $oldProject = Project::find($oldProjectId);
            $newProject = Project::find($validated['project_id']);
            $changes[] = "Projet modifié de '{$oldProject->name}' vers '{$newProject->name}'";
            $this->historyService->logAction(
                $serialKey,
                'update',
                "Changement de projet : {$oldProject->name} → {$newProject->name}"
            );
        }

        if ($oldStatus !== $validated['status']) {
            $changes[] = "Statut modifié de '{$oldStatus}' vers '{$validated['status']}'";
            $this->historyService->logAction(
                $serialKey,
                'status_change',
                "Changement de statut : {$oldStatus} → {$validated['status']}"
            );
        }

        // Message de succès avec détails des changements
        $successMessage = 'Clé de licence mise à jour avec succès.';
        if (!empty($changes)) {
            $successMessage .= ' Modifications : ' . implode(', ', $changes);
        }

        return redirect()
            ->route('admin.serial-keys.show', $serialKey)
            ->with('success', $successMessage);
    }

    /**
     * Supprimer une clé
     */
    public function destroy(SerialKey $serialKey)
    {
        // Enregistrer l'historique AVANT la suppression
        $this->historyService->logAction(
            $serialKey,
            'delete',
            'Suppression de la clé de licence'
        );

        // Supprimer la clé après avoir enregistré l'historique
        $serialKey->delete();

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

    /**
     * Ajouter un nouveau compte à une licence multi
     */
    public function addAccount(Request $request, SerialKey $serialKey)
    {
        // Vérifier que c'est une licence multi
        if (!$serialKey->isMulti()) {
            return redirect()->back()->with('error', 'Cette fonction n\'est disponible que pour les licences multi.');
        }

        // Vérifier si la licence peut accepter un nouveau compte
        if (!$serialKey->canAcceptNewAccount()) {
            return redirect()->back()->with('error', 'Cette licence a atteint son nombre maximum de comptes.');
        }

        $validated = $request->validate([
            'domain' => 'required|string|max:255',
            'ip_address' => 'nullable|ip',
        ]);

        // Vérifier si le domaine n'est pas déjà utilisé pour cette licence
        $existingAccount = $serialKey->accounts()->where('domain', $validated['domain'])->first();
        if ($existingAccount) {
            return redirect()->back()->with('error', 'Ce domaine est déjà associé à cette licence.');
        }

        $account = $serialKey->addAccount($validated['domain'], $validated['ip_address']);

        if ($account) {
            $this->historyService->logAction(
                $serialKey,
                'add_account',
                'Ajout du compte pour le domaine : ' . $validated['domain']
            );

            return redirect()->back()->with('success', 'Compte ajouté avec succès à la licence multi.');
        }

        return redirect()->back()->with('error', 'Erreur lors de l\'ajout du compte.');
    }

    /**
     * Supprimer un compte d'une licence multi
     */
    public function removeAccount(SerialKey $serialKey, \App\Models\LicenceAccount $account)
    {
        // Vérifier que le compte appartient bien à cette licence
        if ($account->serial_key_id !== $serialKey->id) {
            return redirect()->back()->with('error', 'Ce compte n\'appartient pas à cette licence.');
        }

        $domain = $account->domain;
        $account->delete();
        $serialKey->decrement('used_accounts');

        $this->historyService->logAction(
            $serialKey,
            'remove_account',
            'Suppression du compte pour le domaine : ' . $domain
        );

        return redirect()->back()->with('success', 'Compte supprimé avec succès.');
    }

    /**
     * Suspendre un compte d'une licence multi
     */
    public function suspendAccount(SerialKey $serialKey, \App\Models\LicenceAccount $account)
    {
        // Vérifier que le compte appartient bien à cette licence
        if ($account->serial_key_id !== $serialKey->id) {
            return redirect()->back()->with('error', 'Ce compte n\'appartient pas à cette licence.');
        }

        if ($account->status === 'suspended') {
            return redirect()->back()->with('error', 'Ce compte est déjà suspendu.');
        }

        $account->suspend();

        $this->historyService->logAction(
            $serialKey,
            'suspend_account',
            'Suspension du compte pour le domaine : ' . $account->domain
        );

        return redirect()->back()->with('success', 'Compte suspendu avec succès.');
    }

    /**
     * Réactiver un compte suspendu d'une licence multi
     */
    public function reactivateAccount(SerialKey $serialKey, \App\Models\LicenceAccount $account)
    {
        // Vérifier que le compte appartient bien à cette licence
        if ($account->serial_key_id !== $serialKey->id) {
            return redirect()->back()->with('error', 'Ce compte n\'appartient pas à cette licence.');
        }

        if ($account->status !== 'suspended') {
            return redirect()->back()->with('error', 'Ce compte ne peut pas être réactivé.');
        }

        $account->activate();

        $this->historyService->logAction(
            $serialKey,
            'reactivate_account',
            'Réactivation du compte pour le domaine : ' . $account->domain
        );

        return redirect()->back()->with('success', 'Compte réactivé avec succès.');
    }

    /**
     * Afficher l'historique d'une clé de licence
     */
    public function history(SerialKey $serialKey)
    {
        try {
            $history = $this->historyService->getHistory($serialKey);
            
            if (request()->ajax()) {
                $historyData = [];
                
                if ($history->count() > 0) {
                    $historyData = $history->getCollection()->map(function($item) {
                        return [
                            'date' => $item->created_at->format('d/m/Y H:i'),
                            'action' => $item->action,
                            'details' => is_string($item->details) ? $item->details : (is_array($item->details) ? json_encode($item->details) : $item->details),
                            'admin' => $item->admin->name ?? 'Système',
                            'ip_address' => $item->ip_address
                        ];
                    })->toArray();
                }
                
                return response()->json([
                    'success' => true,
                    'history' => $historyData
                ]);
            }

            return view('admin.serial-keys.history', compact('serialKey', 'history'));
            
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Erreur lors du chargement de l\'historique: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Erreur lors du chargement de l\'historique.');
        }
    }
}