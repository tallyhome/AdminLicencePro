@extends('layouts.client')

@section('title', 'Recherche FAQ')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Recherche FAQ</h1>
        <div>
            <a href="{{ route('client.support.faq') }}" class="btn btn-secondary mr-2">
                <i class="fas fa-arrow-left"></i> Retour FAQ
            </a>
            <a href="{{ route('client.support.index') }}" class="btn btn-primary">
                <i class="fas fa-home"></i> Support
            </a>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body">
                    <form action="{{ route('client.support.search-faq') }}" method="GET">
                        <div class="input-group">
                            <input type="text" class="form-control" name="query" 
                                   placeholder="Rechercher dans la FAQ..." 
                                   value="{{ $query }}" autofocus>
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-search"></i> Rechercher
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Search Results -->
    <div class="row">
        <div class="col-12">
            @if($query)
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            Résultats pour "{{ $query }}"
                        </h6>
                    </div>
                    <div class="card-body">
                        @if(count($results) > 0)
                            @foreach($results as $result)
                            <div class="mb-4 p-3 border-left border-primary">
                                <h5 class="text-primary">{{ $result['question'] }}</h5>
                                <p class="text-muted mb-2">{{ $result['answer'] }}</p>
                                <small class="text-secondary">
                                    <i class="fas fa-tag"></i> {{ $result['category'] }}
                                </small>
                            </div>
                            @endforeach
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-search text-muted mb-3" style="font-size: 3rem;"></i>
                                <h5 class="text-muted">Aucun résultat trouvé</h5>
                                <p class="text-muted">
                                    Essayez avec des mots-clés différents ou consultez toute la FAQ.
                                </p>
                                <a href="{{ route('client.support.faq') }}" class="btn btn-primary">
                                    <i class="fas fa-list"></i> Voir toute la FAQ
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <div class="card shadow">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-search text-muted mb-3" style="font-size: 3rem;"></i>
                        <h5 class="text-muted">Rechercher dans la FAQ</h5>
                        <p class="text-muted">
                            Saisissez votre recherche dans le champ ci-dessus pour trouver des réponses.
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Suggestions -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow border-left-info">
                <div class="card-body">
                    <h5 class="font-weight-bold text-info">Suggestions de recherche</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Mots-clés populaires :</strong></p>
                            <div class="mb-3">
                                <a href="{{ route('client.support.search-faq', ['query' => 'licence']) }}" class="badge badge-primary mr-1">licence</a>
                                <a href="{{ route('client.support.search-faq', ['query' => 'API']) }}" class="badge badge-primary mr-1">API</a>
                                <a href="{{ route('client.support.search-faq', ['query' => 'intégration']) }}" class="badge badge-primary mr-1">intégration</a>
                                <a href="{{ route('client.support.search-faq', ['query' => 'projet']) }}" class="badge badge-primary mr-1">projet</a>
                                <a href="{{ route('client.support.search-faq', ['query' => 'domaine']) }}" class="badge badge-primary mr-1">domaine</a>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Pas de résultats ? Essayez :</strong></p>
                            <ul class="mb-0">
                                <li>Utilisez des mots-clés plus généraux</li>
                                <li>Vérifiez l'orthographe</li>
                                <li>Utilisez des synonymes</li>
                                <li>Contactez le support pour plus d'aide</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Support -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow border-left-success">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="font-weight-bold text-success">Toujours pas de réponse ?</h5>
                            <p class="text-muted mb-0">Notre équipe support est là pour vous aider avec des questions spécifiques.</p>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('client.support.create') }}" class="btn btn-success">
                                <i class="fas fa-plus"></i> Créer un Ticket
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Auto-focus sur le champ de recherche
    $('input[name="query"]').focus().select();
    
    // Recherche en temps réel (optionnel)
    let searchTimeout;
    $('input[name="query"]').on('input', function() {
        clearTimeout(searchTimeout);
        const query = $(this).val();
        
        if (query.length >= 3) {
            searchTimeout = setTimeout(function() {
                // Ici vous pourriez implémenter une recherche AJAX en temps réel
                console.log('Recherche en temps réel pour:', query);
            }, 500);
        }
    });
});
</script>
@endpush
@endsection 