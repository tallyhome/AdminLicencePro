<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\SerialKey;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;

class LicenseController extends Controller
{
    /**
     * Afficher la liste des licences
     */
    public function index(Request $request)
    {
        $client = Auth::guard('client')->user();
        $tenant = $client->tenant;

        // Récupérer uniquement les licences du tenant
        $query = $tenant->serialKeys()->with('project');

        // Filtres
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('serial_key', 'like', '%' . $request->search . '%')
                  ->orWhere('name', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('project') && $request->project) {
            $query->where('project_id', $request->project);
        }

        if ($request->has('type') && $request->type) {
            $query->where('licence_type', $request->type);
        }

        $licenses = $query->latest()->paginate(15);

        // Projets pour le filtre (uniquement ceux du tenant)
        $projects = $tenant->projects()->orderBy('name')->get();

        // Statistiques (uniquement pour le tenant)
        $stats = [
            'total' => $tenant->serialKeys()->count(),
            'active' => $tenant->serialKeys()->where('status', 'active')->count(),
            'inactive' => $tenant->serialKeys()->where('status', 'inactive')->count(),
            'expiring' => $tenant->serialKeys()
                ->where('expires_at', '<=', now()->addDays(30))
                ->where('expires_at', '>', now())
                ->count(),
            'expired' => $tenant->serialKeys()
                ->where('expires_at', '<', now())
                ->count(),
        ];

        return view('client.licenses.index', compact('licenses', 'projects', 'stats', 'client', 'tenant'));
    }

    /**
     * Afficher le formulaire de création d'une licence
     */
    public function create()
    {
        $client = Auth::guard('client')->user();
        $tenant = $client->tenant;

        // Projets du tenant uniquement
        $projects = $tenant->projects()->where('status', 'active')->orderBy('name')->get();

        return view('client.licenses.create', compact('projects', 'client', 'tenant'));
    }

    /**
     * Enregistrer une nouvelle licence
     */
    public function store(Request $request)
    {
        $client = Auth::guard('client')->user();
        $tenant = $client->tenant;

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'project_id' => 'required|exists:projects,id',
            'type' => 'required|in:single,multi',
            'max_activations' => 'required_if:type,multi|nullable|integer|min:1|max:100',
            'expires_at' => 'nullable|date|after:today',
            'allowed_domains' => 'nullable|string',
            'status' => 'required|in:active,inactive'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Vérifier que le projet appartient au tenant
            $project = $tenant->projects()->findOrFail($request->project_id);

            // Générer une clé unique
            $serialKey = $this->generateUniqueKey();

            $licenseData = [
                'tenant_id' => $tenant->id,
                'project_id' => $project->id,
                'name' => $request->name,
                'serial_key' => $serialKey,
                'licence_type' => $request->type,
                'status' => $request->status,
                'max_activations' => $request->type === 'multi' ? $request->max_activations : 1,
                'current_activations' => 0,
                'expires_at' => $request->expires_at,
                'allowed_domains' => $request->allowed_domains ? explode("\n", $request->allowed_domains) : null,
            ];

            $license = SerialKey::create($licenseData);

            return redirect()->route('client.licenses.show', $license)
                ->with('success', 'Licence créée avec succès ! Clé : ' . $serialKey);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la création de la licence : ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Afficher les détails d'une licence
     */
    public function show(SerialKey $license)
    {
        $client = Auth::guard('client')->user();
        $tenant = $client->tenant;

        $license->load(['project', 'accounts']);

        // Statistiques de la licence
        $stats = [
            'activations_used' => $license->current_activations ?? 0,
            'activations_max' => $license->max_activations ?? 1,
            'accounts_used' => $license->used_accounts ?? 0,
            'accounts_max' => $license->max_accounts ?? 1,
            'days_until_expiry' => $license->expires_at ? now()->diffInDays($license->expires_at, false) : null,
        ];

        return view('client.licenses.show', compact('license', 'stats', 'client', 'tenant'));
    }

    /**
     * Afficher le formulaire d'édition d'une licence
     */
    public function edit(SerialKey $license)
    {
        $client = Auth::guard('client')->user();
        $tenant = $client->tenant;

        $projects = Project::where('status', 'active')->orderBy('name')->get();

        return view('client.licenses.edit', compact('license', 'projects', 'client', 'tenant'));
    }

    /**
     * Mettre à jour une licence
     */
    public function update(Request $request, SerialKey $license)
    {
        $validator = Validator::make($request->all(), [
            'project_id' => 'required|exists:projects,id',
            'licence_type' => 'required|in:single,multi',
            'max_activations' => 'required_if:licence_type,multi|nullable|integer|min:1|max:100',
            'expires_at' => 'nullable|date|after:today',
            'domain' => 'nullable|string|max:255',
            'ip_address' => 'nullable|ip',
            'status' => 'required|in:active,inactive'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $updateData = [
                'project_id' => $request->project_id,
                'licence_type' => $request->licence_type,
                'status' => $request->status,
                'max_activations' => $request->licence_type === 'multi' ? $request->max_activations : 1,
                'expires_at' => $request->expires_at,
                'domain' => $request->domain,
                'ip_address' => $request->ip_address,
            ];

            $license->update($updateData);

            return redirect()->route('client.licenses.show', $license)
                ->with('success', 'Licence mise à jour avec succès !');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la mise à jour : ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Supprimer une licence
     */
    public function destroy(SerialKey $license)
    {
        try {
            // Vérifier si la licence est utilisée
            if ($license->current_activations > 0) {
                return redirect()->back()
                    ->with('error', 'Impossible de supprimer cette licence car elle est actuellement utilisée.');
            }

            $license->delete();

            return redirect()->route('client.licenses.index')
                ->with('success', 'Licence supprimée avec succès !');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la suppression : ' . $e->getMessage());
        }
    }

    /**
     * Changer le statut d'une licence
     */
    public function toggleStatus(SerialKey $license)
    {
        try {
            $newStatus = $license->status === 'active' ? 'inactive' : 'active';
            $license->update(['status' => $newStatus]);

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
     * Régénérer une clé de licence
     */
    public function regenerate(SerialKey $license)
    {
        try {
            // Générer une nouvelle clé
            $newKey = $this->generateUniqueKey();
            $license->update(['serial_key' => $newKey]);

            return response()->json([
                'success' => true,
                'message' => 'Clé régénérée avec succès !',
                'new_key' => $newKey
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la régénération : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Télécharger les informations de licence
     */
    public function download(SerialKey $license)
    {
        try {
            $licenseInfo = [
                'Clé de licence' => $license->serial_key,
                'Projet' => $license->project->name ?? 'N/A',
                'Type' => $license->licence_type,
                'Statut' => $license->status,
                'Activations max' => $license->max_activations,
                'Activations utilisées' => $license->current_activations ?? 0,
                'Domaine autorisé' => $license->domain ?? 'N/A',
                'IP autorisée' => $license->ip_address ?? 'N/A',
                'Date d\'expiration' => $license->expires_at ? $license->expires_at->format('d/m/Y H:i') : 'Jamais',
                'Date de création' => $license->created_at->format('d/m/Y H:i'),
            ];

            $content = "=== INFORMATIONS DE LICENCE ===\n\n";
            foreach ($licenseInfo as $key => $value) {
                $content .= "{$key}: {$value}\n";
            }

            $content .= "\n=== INSTRUCTIONS D'UTILISATION ===\n";
            $content .= "1. Copiez la clé de licence dans votre application\n";
            $content .= "2. Respectez les limites d'activation\n";
            $content .= "3. Contactez le support en cas de problème\n";

            $filename = 'licence_' . $license->serial_key . '.txt';

            return Response::make($content, 200, [
                'Content-Type' => 'text/plain',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ]);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors du téléchargement : ' . $e->getMessage());
        }
    }

    /**
     * Générer une clé unique
     */
    private function generateUniqueKey()
    {
        do {
            $key = strtoupper(substr(md5(uniqid()), 0, 16));
            $key = implode('-', str_split($key, 4));
        } while (SerialKey::where('serial_key', $key)->exists());

        return $key;
    }
} 