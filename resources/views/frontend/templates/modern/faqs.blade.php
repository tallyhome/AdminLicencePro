@extends('frontend.templates.modern.layout')

@section('title', 'Questions Fréquentes - AdminLicence')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="text-center mb-5">
                <h1 class="display-4 fw-bold mb-4">Questions Fréquentes</h1>
                <p class="lead text-muted">Trouvez rapidement les réponses à vos questions sur AdminLicence</p>
            </div>

            @if(isset($faqs) && $faqs->count() > 0)
                <div class="accordion" id="faqAccordion">
                    @foreach($faqs as $index => $faq)
                    <div class="accordion-item mb-3">
                        <h2 class="accordion-header" id="faq{{ $index }}">
                            <button class="accordion-button {{ $index === 0 ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}">
                                {{ $faq->question }}
                            </button>
                        </h2>
                        <div id="collapse{{ $index }}" class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                {!! nl2br(e($faq->answer)) !!}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-question-circle fa-3x text-muted mb-3"></i>
                    <h5>Aucune FAQ disponible</h5>
                    <p class="text-muted">Les questions fréquentes seront bientôt disponibles.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 