@extends('admin.layouts.app')

@section('title', 'Templates CMS')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Templates CMS</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.cms.index') }}">CMS</a></li>
                        <li class="breadcrumb-item active">Templates</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Templates Disponibles</h5>
                </div>
                <div class="card-body">
                    @if(isset($templates) && $templates->count() > 0)
                        <div class="row">
                            @foreach($templates as $template)
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card template-card {{ $template->is_default ? 'border-primary' : '' }}">
                                    <div class="card-header">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0">{{ $template->name }}</h6>
                                            @if($template->is_default)
                                                <span class="badge bg-primary">Actuel</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <p class="text-muted">{{ $template->description ?? 'Template moderne et responsive' }}</p>
                                        @if(!$template->is_default)
                                            <form action="{{ route('admin.cms.activate-template', $template) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-primary btn-sm">
                                                    Activer
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-palette fa-3x text-muted mb-3"></i>
                            <h5>Aucun template</h5>
                            <p class="text-muted">Les templates seront automatiquement détectés.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 