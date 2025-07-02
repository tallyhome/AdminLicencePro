<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Models\SupportTicketReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SupportController extends Controller
{
    /**
     * Afficher la liste des tickets de support
     */
    public function index(Request $request)
    {
        $client = Auth::guard('client')->user();
        $tenant = $client->tenant;

        $query = $tenant->supportTickets()->with(['client', 'replies']);

        // Filtres
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('priority') && $request->priority) {
            $query->where('priority', $request->priority);
        }

        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('subject', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // Tri
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        $tickets = $query->paginate(15);

        // Statistiques
        $stats = [
            'total' => $tenant->supportTickets()->count(),
            'open' => $tenant->supportTickets()->where('status', 'open')->count(),
            'in_progress' => $tenant->supportTickets()->where('status', 'in_progress')->count(),
            'closed' => $tenant->supportTickets()->where('status', 'closed')->count(),
        ];

        // Limites du plan (simplifiées)
        $planLimits = [
            'support_tickets' => [
                'limit' => 999,
                'open' => $stats['open']
            ]
        ];

        return view('client.support.index', compact('tickets', 'stats', 'planLimits'));
    }

    /**
     * Afficher le formulaire de création de ticket
     */
    public function create()
    {
        $client = Auth::guard('client')->user();
        $tenant = $client->tenant;

        // Vérifier les limites du plan (simplifiées)
        $openTickets = $tenant->supportTickets()->where('status', 'open')->count();
        
        if ($openTickets >= 999) {
            return redirect()->route('client.support.index')
                ->with('error', 'Vous avez atteint la limite de tickets ouverts de votre plan. Veuillez résoudre vos tickets existants ou mettre à niveau votre abonnement.');
        }

        return view('client.support.create');
    }

    /**
     * Créer un nouveau ticket de support
     */
    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'category' => 'required|in:technical,billing,general,feature_request',
            'attachments.*' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,gif,pdf,txt,doc,docx,zip',
        ]);

        $client = Auth::guard('client')->user();
        $tenant = $client->tenant;

        // Vérifier les limites du plan (simplifiées)
        $openTickets = $tenant->supportTickets()->where('status', 'open')->count();
        
        if ($openTickets >= 999) {
            return back()->with('error', 'Vous avez atteint la limite de tickets ouverts de votre plan.');
        }

        // Générer un numéro de ticket unique
        $ticketNumber = 'TK-' . strtoupper(uniqid());

        $ticket = $tenant->supportTickets()->create([
            'client_id' => $client->id,
            'ticket_number' => $ticketNumber,
            'subject' => $request->subject,
            'description' => $request->description,
            'priority' => $request->priority,
            'category' => $request->category,
            'status' => 'open',
        ]);

        // Gérer les pièces jointes
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('support-attachments/' . $ticket->id, 'public');
                
                $ticket->attachments()->create([
                    'filename' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                ]);
            }
        }

        return redirect()->route('client.support.show', $ticket)
            ->with('success', 'Ticket de support créé avec succès ! Numéro : ' . $ticketNumber);
    }

    /**
     * Afficher un ticket de support spécifique
     */
    public function show(SupportTicket $ticket)
    {
        $client = Auth::guard('client')->user();
        
        // Vérifier que le ticket appartient au tenant du client
        if ($ticket->tenant_id !== $client->tenant_id) {
            abort(403);
        }

        // Charger les réponses avec leurs auteurs
        $ticket->load(['replies.admin', 'replies.client', 'attachments']);

        // Marquer le ticket comme lu par le client
        $ticket->update(['last_read_by_client_at' => now()]);

        return view('client.support.show', compact('ticket'));
    }

    /**
     * Ajouter une réponse à un ticket
     */
    public function reply(Request $request, SupportTicket $ticket)
    {
        $client = Auth::guard('client')->user();
        
        // Vérifier que le ticket appartient au tenant du client
        if ($ticket->tenant_id !== $client->tenant_id) {
            abort(403);
        }

        $request->validate([
            'message' => 'required|string',
            'attachments.*' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,gif,pdf,txt,doc,docx,zip',
        ]);

        $reply = $ticket->replies()->create([
            'client_id' => $client->id,
            'message' => $request->message,
            'is_from_client' => true,
        ]);

        // Gérer les pièces jointes
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('support-attachments/' . $ticket->id . '/replies', 'public');
                
                $reply->attachments()->create([
                    'filename' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                ]);
            }
        }

        // Mettre à jour le statut du ticket si nécessaire
        if ($ticket->status === 'closed') {
            $ticket->update(['status' => 'open']);
        }

        // Mettre à jour la date de dernière activité
        $ticket->update([
            'last_activity_at' => now(),
            'last_reply_by_client_at' => now(),
        ]);

        return back()->with('success', 'Réponse ajoutée avec succès !');
    }

    /**
     * Fermer un ticket
     */
    public function close(SupportTicket $ticket)
    {
        $client = Auth::guard('client')->user();
        
        // Vérifier que le ticket appartient au tenant du client
        if ($ticket->tenant_id !== $client->tenant_id) {
            abort(403);
        }

        $ticket->update([
            'status' => 'closed',
            'closed_at' => now(),
            'closed_by_client' => true,
        ]);

        return redirect()->route('client.support.index')
            ->with('success', 'Ticket fermé avec succès !');
    }

    /**
     * Rouvrir un ticket
     */
    public function reopen(SupportTicket $ticket)
    {
        $client = Auth::guard('client')->user();
        
        // Vérifier que le ticket appartient au tenant du client
        if ($ticket->tenant_id !== $client->tenant_id) {
            abort(403);
        }

        $ticket->update([
            'status' => 'open',
            'closed_at' => null,
            'closed_by_client' => false,
            'last_activity_at' => now(),
        ]);

        return back()->with('success', 'Ticket rouvert avec succès !');
    }

    /**
     * Télécharger une pièce jointe
     */
    public function downloadAttachment($ticketId, $attachmentId)
    {
        $client = Auth::guard('client')->user();
        
        $ticket = SupportTicket::where('tenant_id', $client->tenant_id)->findOrFail($ticketId);
        $attachment = $ticket->attachments()->findOrFail($attachmentId);

        if (!Storage::disk('public')->exists($attachment->path)) {
            abort(404, 'Fichier non trouvé');
        }

        return Storage::disk('public')->download($attachment->path, $attachment->filename);
    }


} 