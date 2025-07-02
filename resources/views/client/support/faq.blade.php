@extends('layouts.client')

@section('title', 'FAQ - Questions Fréquentes')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">FAQ - Questions Fréquentes</h1>
        <a href="{{ route('client.support.index') }}" class="btn btn-primary">
            <i class="fas fa-arrow-left"></i> Retour au Support
        </a>
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
                                   value="{{ request('query') }}">
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

    <!-- FAQ Categories -->
    <div class="row">
        @foreach($faqCategories as $categoryKey => $category)
        <div class="col-lg-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3 bg-primary text-white">
                    <h6 class="m-0 font-weight-bold">{{ $category['title'] }}</h6>
                </div>
                <div class="card-body">
                    <div class="accordion" id="accordion{{ ucfirst($categoryKey) }}">
                        @foreach($category['articles'] as $index => $article)
                        <div class="card mb-2">
                            <div class="card-header p-0" id="heading{{ ucfirst($categoryKey) }}{{ $index }}">
                                <h2 class="mb-0">
                                    <button class="btn btn-link btn-block text-left collapsed" 
                                            type="button" 
                                            data-toggle="collapse" 
                                            data-target="#collapse{{ ucfirst($categoryKey) }}{{ $index }}" 
                                            aria-expanded="false" 
                                            aria-controls="collapse{{ ucfirst($categoryKey) }}{{ $index }}">
                                        <i class="fas fa-question-circle text-primary mr-2"></i>
                                        {{ $article['question'] }}
                                    </button>
                                </h2>
                            </div>
                            <div id="collapse{{ ucfirst($categoryKey) }}{{ $index }}" 
                                 class="collapse" 
                                 aria-labelledby="heading{{ ucfirst($categoryKey) }}{{ $index }}" 
                                 data-parent="#accordion{{ ucfirst($categoryKey) }}">
                                <div class="card-body">
                                    <p class="text-muted mb-0">{{ $article['answer'] }}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Contact Support -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow border-left-info">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="font-weight-bold text-info">Vous ne trouvez pas votre réponse ?</h5>
                            <p class="text-muted mb-0">Notre équipe support est là pour vous aider. Créez un ticket de support pour obtenir une assistance personnalisée.</p>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('client.support.create') }}" class="btn btn-info">
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
    $('input[name="query"]').focus();
    
    // Animation smooth pour les accordéons
    $('.collapse').on('show.bs.collapse', function () {
        $(this).parent().find('.btn-link i').removeClass('fa-question-circle').addClass('fa-minus-circle');
    });
    
    $('.collapse').on('hide.bs.collapse', function () {
        $(this).parent().find('.btn-link i').removeClass('fa-minus-circle').addClass('fa-question-circle');
    });
});
</script>
@endpush
@endsection 