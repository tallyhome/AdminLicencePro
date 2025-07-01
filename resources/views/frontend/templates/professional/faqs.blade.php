@extends($layout ?? 'frontend.templates.professional.layout')

@section('title', 'FAQ - AdminLicence')

@section('content')
<div class="bg-light min-vh-100">
    <!-- Hero Section -->
    <section class="hero-professional">
        <div class="container">
            <div class="hero-content text-center">
                <div class="security-badge-professional">
                    <i class="fas fa-question-circle"></i>
                    <span>Centre d'Aide</span>
                </div>
                <h1 class="display-4 fw-bold mb-4">Questions Fréquentes</h1>
                <p class="lead mb-0 opacity-90">Trouvez rapidement les réponses à vos questions sur AdminLicence</p>
            </div>
        </div>
    </section>

    <!-- FAQ Content -->
    <section class="section-professional bg-white">
        <div class="container">
            @if($categories->count() > 0)
            <!-- Categories Filter -->
            <div class="text-center mb-5">
                <div class="btn-group flex-wrap" role="group">
                    <button type="button" class="btn btn-outline-primary active" data-category="all">
                        Toutes les catégories
                    </button>
                    @foreach($categories as $category)
                    <button type="button" class="btn btn-outline-primary" data-category="{{ Str::slug($category) }}">
                        {{ $category }}
                    </button>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- FAQ Accordion -->
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="accordion faq-professional" id="faqAccordion">
                        @foreach($faqs as $index => $faq)
                        <div class="accordion-item faq-item" data-category="{{ Str::slug($faq->category) }}">
                            <h2 class="accordion-header" id="faq{{ $index }}">
                                <button class="accordion-button {{ $index === 0 ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}">
                                    <div class="d-flex align-items-center w-100">
                                        <i class="fas fa-question-circle text-primary me-3"></i>
                                        <span class="fw-semibold">{{ $faq->question }}</span>
                                        @if($faq->category)
                                        <span class="badge bg-light text-primary ms-auto me-3">{{ $faq->category }}</span>
                                        @endif
                                    </div>
                                </button>
                            </h2>
                            <div id="collapse{{ $index }}" class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0 me-3">
                                            <div class="bg-success bg-opacity-10 rounded-circle p-2">
                                                <i class="fas fa-check text-success"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            {!! nl2br(e($faq->answer)) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Contact CTA -->
            <div class="text-center mt-5 pt-5">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-5">
                        <h3 class="section-title-professional mb-4">Vous ne trouvez pas votre réponse ?</h3>
                        <p class="lead text-muted mb-4">Notre équipe support est là pour vous aider</p>
                        <div class="d-flex justify-content-center gap-3 flex-wrap">
                            <a href="{{ route('frontend.contact') }}" class="btn btn-primary-professional">
                                <i class="fas fa-envelope"></i>
                                Nous contacter
                            </a>
                            <a href="{{ route('frontend.support') }}" class="btn btn-secondary-professional">
                                <i class="fas fa-headset"></i>
                                Centre de support
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
.faq-professional .accordion-item {
    border: 1px solid var(--gray-200);
    border-radius: 12px;
    margin-bottom: 1rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.faq-professional .accordion-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.faq-professional .accordion-button {
    background: white;
    border: none;
    padding: 1.5rem;
    font-weight: 600;
    color: var(--gray-800);
    border-radius: 12px;
}

.faq-professional .accordion-button:not(.collapsed) {
    background: var(--gray-50);
    color: var(--primary-color);
    box-shadow: none;
}

.faq-professional .accordion-button:focus {
    border-color: var(--accent-color);
    box-shadow: 0 0 0 0.25rem rgba(49, 130, 206, 0.25);
}

.faq-professional .accordion-body {
    padding: 1.5rem;
    background: var(--gray-50);
    border-radius: 0 0 12px 12px;
}

.btn-group .btn {
    margin: 0.25rem;
    border-radius: 25px;
    padding: 8px 20px;
    font-weight: 500;
}

.faq-item.d-none {
    display: none !important;
}

@media (max-width: 768px) {
    .btn-group {
        flex-direction: column;
        width: 100%;
    }
    
    .btn-group .btn {
        margin: 0.25rem 0;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filter functionality
    const filterButtons = document.querySelectorAll('[data-category]');
    const faqItems = document.querySelectorAll('.faq-item');
    
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            const category = this.getAttribute('data-category');
            
            // Update active button
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            // Filter FAQ items
            faqItems.forEach(item => {
                if (category === 'all' || item.getAttribute('data-category') === category) {
                    item.classList.remove('d-none');
                } else {
                    item.classList.add('d-none');
                }
            });
        });
    });
});
</script>
@endsection 