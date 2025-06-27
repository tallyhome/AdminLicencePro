@extends('admin.layouts.app')

@section('title', 'Voir FAQ')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">FAQ : {{ Str::limit($faq->question, 50) }}</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.cms.index') }}">CMS</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.cms.faqs.index') }}">FAQs</a></li>
                        <li class="breadcrumb-item active">Voir</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Détails de la FAQ</h5>
                    <div>
                        <a href="{{ route('admin.cms.faqs.edit', $faq) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit me-1"></i> Modifier
                        </a>
                        <a href="{{ route('admin.cms.faqs.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i> Retour
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Prévisualisation -->
                    <div class="row">
                        <div class="col-md-12 mb-4">
                            <div class="faq-preview p-4 bg-light rounded">
                                <div class="accordion" id="faqPreview">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingPreview">
                                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePreview">
                                                {{ $faq->question }}
                                            </button>
                                        </h2>
                                        <div id="collapsePreview" class="accordion-collapse collapse show" data-bs-parent="#faqPreview">
                                            <div class="accordion-body">
                                                {!! nl2br(e($faq->answer)) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Détails -->
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <td class="fw-semibold" style="width: 40%;">Question :</td>
                                        <td>{{ $faq->question }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold">Catégorie :</td>
                                        <td>
                                            @if($faq->category)
                                                <span class="badge bg-info">{{ $faq->category }}</span>
                                            @else
                                                <span class="text-muted">Aucune</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold">Ordre :</td>
                                        <td>{{ $faq->sort_order }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <td class="fw-semibold" style="width: 40%;">Statut :</td>
                                        <td>
                                            @if($faq->is_active)
                                                <span class="badge bg-success">Publié</span>
                                            @else
                                                <span class="badge bg-secondary">Brouillon</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold">Mise en avant :</td>
                                        <td>
                                            @if($faq->is_featured)
                                                <span class="badge bg-warning">Oui</span>
                                            @else
                                                <span class="text-muted">Non</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold">Vues :</td>
                                        <td>{{ $faq->views_count ?? 0 }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Réponse complète -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h6 class="fw-semibold mb-3">Réponse complète :</h6>
                            <div class="p-3 bg-light rounded">
                                {!! nl2br(e($faq->answer)) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.cms.faqs.edit', $faq) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i> Modifier cette FAQ
                        </a>
                        
                        <form action="{{ route('admin.cms.faqs.destroy', $faq) }}" 
                              method="POST" 
                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette FAQ ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="fas fa-trash me-2"></i> Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informations</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-0">
                        <h6 class="alert-heading">État de la FAQ :</h6>
                        <ul class="mb-0 small">
                            <li><strong>Créé :</strong> {{ $faq->created_at->format('d/m/Y à H:i') }}</li>
                            @if($faq->updated_at != $faq->created_at)
                                <li><strong>Modifié :</strong> {{ $faq->updated_at->format('d/m/Y à H:i') }}</li>
                            @endif
                            <li><strong>Statut :</strong> {{ $faq->is_active ? 'Publié' : 'Brouillon' }}</li>
                            @if($faq->is_featured)
                                <li><strong>Mis en avant</strong> sur la page d'accueil</li>
                            @endif
                            @if($faq->category)
                                <li><strong>Catégorie :</strong> {{ $faq->category }}</li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 