@extends('layouts.client')

@section('title', 'Ticket #' . $ticket->ticket_number)

@section('content')
<div class="container-fluid py-4">
    <!-- En-tête avec navigation -->
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('client.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('client.support.index') }}">Support</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Ticket #{{ $ticket->ticket_number }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Détails du ticket -->
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">{{ $ticket->subject }}</h5>
                <span class="badge bg-{{ $ticket->status === 'open' ? 'success' : ($ticket->status === 'closed' ? 'secondary' : 'warning') }}">
                    {{ ucfirst($ticket->status) }}
                </span>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Numéro :</strong> {{ $ticket->ticket_number }}
                </div>
                <div class="col-md-6">
                    <strong>Priorité :</strong> 
                    <span class="badge bg-{{ $ticket->priority === 'urgent' ? 'danger' : ($ticket->priority === 'high' ? 'warning' : 'info') }}">
                        {{ ucfirst($ticket->priority) }}
                    </span>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Catégorie :</strong> {{ ucfirst(str_replace('_', ' ', $ticket->category)) }}
                </div>
                <div class="col-md-6">
                    <strong>Créé le :</strong> {{ $ticket->created_at->format('d/m/Y H:i') }}
                </div>
            </div>

            <div class="mb-3">
                <strong>Description :</strong>
                <div class="mt-2 p-3 bg-light rounded">
                    {!! nl2br(e($ticket->description)) !!}
                </div>
            </div>

            @if($ticket->attachments->count() > 0)
                <div class="mb-3">
                    <strong>Pièces jointes :</strong>
                    <ul class="list-unstyled mt-2">
                        @foreach($ticket->attachments as $attachment)
                            <li>
                                <a href="#" class="text-decoration-none">
                                    <i class="fas fa-paperclip me-1"></i>
                                    {{ $attachment->filename }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>

    <!-- Réponses -->
    @if($ticket->replies->count() > 0)
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="card-title mb-0">Réponses ({{ $ticket->replies->count() }})</h6>
            </div>
            <div class="card-body">
                @foreach($ticket->replies as $reply)
                    <div class="border-bottom pb-3 mb-3">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <strong>
                                    @if($reply->user_type === 'client')
                                        Vous
                                    @elseif($reply->user_type === 'admin')
                                        Support AdminLicence
                                    @else
                                        Système
                                    @endif
                                </strong>
                                <small class="text-muted ms-2">{{ $reply->created_at->format('d/m/Y H:i') }}</small>
                            </div>
                        </div>
                        <div class="mt-2">
                            {!! nl2br(e($reply->message)) !!}
                        </div>
                        
                        @if($reply->attachments && count($reply->attachments) > 0)
                            <div class="mt-2">
                                <small class="text-muted">Pièces jointes :</small>
                                <ul class="list-unstyled mt-1">
                                    @foreach($reply->attachments as $index => $attachment)
                                        <li>
                                            <a href="{{ route('client.support.download-attachment', [$ticket->id, $reply->id, $index]) }}" class="text-decoration-none">
                                                <i class="fas fa-paperclip me-1"></i>
                                                {{ $attachment['filename'] }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Formulaire de réponse -->
    @if($ticket->status !== 'closed')
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="card-title mb-0">Ajouter une réponse</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('client.support.reply', $ticket) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="message" class="form-label">Message</label>
                        <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="attachments" class="form-label">Pièces jointes (optionnel)</label>
                        <input type="file" class="form-control" id="attachments" name="attachments[]" multiple>
                        <div class="form-text">Formats acceptés : JPG, PNG, PDF, DOC, TXT, ZIP (max 10MB par fichier)</div>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-reply me-2"></i>Répondre
                        </button>
                        <a href="{{ route('client.support.index') }}" class="btn btn-outline-secondary">Retour</a>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Actions -->
    <div class="card mt-4">
        <div class="card-body">
            <div class="d-flex gap-2">
                @if($ticket->status !== 'closed')
                    <form action="{{ route('client.support.close', $ticket) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Êtes-vous sûr de vouloir fermer ce ticket ?')">
                            <i class="fas fa-times me-2"></i>Fermer le ticket
                        </button>
                    </form>
                @else
                    <form action="{{ route('client.support.reopen', $ticket) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-success">
                            <i class="fas fa-redo me-2"></i>Rouvrir le ticket
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection 