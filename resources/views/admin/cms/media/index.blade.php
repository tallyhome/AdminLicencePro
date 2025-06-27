@extends('admin.layouts.app')

@section('title', 'Gestionnaire de Médias')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                <div class="mb-2 mb-md-0">
                    <h1 class="h3 mb-1 d-flex align-items-center">
                        <i class="fas fa-images text-primary me-2"></i>
                        Gestionnaire de Médias
                    </h1>
                    <p class="text-muted mb-0">Gérez vos images et fichiers multimédias</p>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#uploadModal">
                        <i class="fas fa-upload me-1"></i> Téléverser
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Galerie de médias -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 pb-0">
            <h5 class="mb-0">Galerie des médias ({{ count($medias) }} fichiers)</h5>
        </div>
        <div class="card-body">
            @if(count($medias) > 0)
                <div class="row g-3">
                    @foreach($medias as $media)
                    <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                        <div class="card media-item h-100 border-0 shadow-sm">
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 150px;">
                                @if(in_array(strtolower(pathinfo($media['name'], PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif', 'svg']))
                                    <img src="{{ $media['url'] }}" alt="{{ $media['name'] }}" class="img-fluid rounded" style="max-height: 140px; max-width: 100%; object-fit: cover;">
                                @else
                                    <i class="fas fa-file fa-3x text-muted"></i>
                                @endif
                            </div>
                            <div class="card-body p-2">
                                <h6 class="card-title mb-1 text-truncate" title="{{ $media['name'] }}">{{ $media['name'] }}</h6>
                                <small class="text-muted d-block">{{ number_format($media['size'] / 1024, 1) }} KB</small>
                                <div class="d-flex gap-1 mt-2">
                                    <button class="btn btn-sm btn-outline-primary copy-url-btn" data-url="{{ $media['url'] }}">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                    <a href="{{ $media['url'] }}" target="_blank" class="btn btn-sm btn-outline-info">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                    <button class="btn btn-sm btn-outline-danger delete-media-btn" data-filename="{{ $media['name'] }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-images fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Aucun média</h5>
                    <p class="text-muted">Commencez par téléverser vos premiers fichiers</p>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">
                        <i class="fas fa-upload me-2"></i> Téléverser des fichiers
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal d'upload -->
<div class="modal fade" id="uploadModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Téléverser des médias</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="dropZone" class="border border-2 border-dashed border-primary rounded p-5 text-center mb-3">
                    <i class="fas fa-cloud-upload-alt fa-3x text-primary mb-3"></i>
                    <h5>Glissez-déposez vos fichiers ici</h5>
                    <p class="text-muted">ou cliquez pour sélectionner</p>
                    <input type="file" id="fileInput" class="d-none" multiple accept="image/*">
                    <button type="button" class="btn btn-primary" onclick="document.getElementById('fileInput').click()">
                        Choisir des fichiers
                    </button>
                </div>
                <div id="uploadResults"></div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestion du drag & drop
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('fileInput');

    dropZone.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.classList.add('bg-light');
    });

    dropZone.addEventListener('drop', function(e) {
        e.preventDefault();
        this.classList.remove('bg-light');
        handleFiles(e.dataTransfer.files);
    });

    fileInput.addEventListener('change', function() {
        handleFiles(this.files);
    });

    function handleFiles(files) {
        const uploadResults = document.getElementById('uploadResults');
        uploadResults.innerHTML = '';

        Array.from(files).forEach((file) => {
            const formData = new FormData();
            formData.append('file', file);

            fetch('{{ route("admin.cms.upload-image") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.location) {
                    uploadResults.innerHTML += `<div class="alert alert-success"><i class="fas fa-check me-2"></i>${file.name} téléversé</div>`;
                    setTimeout(() => location.reload(), 1000);
                } else {
                    uploadResults.innerHTML += `<div class="alert alert-danger"><i class="fas fa-times me-2"></i>Erreur: ${data.error}</div>`;
                }
            });
        });
    }

    // Copier URL
    document.querySelectorAll('.copy-url-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            navigator.clipboard.writeText(this.getAttribute('data-url'));
            this.innerHTML = '<i class="fas fa-check"></i>';
            setTimeout(() => {
                this.innerHTML = '<i class="fas fa-copy"></i>';
            }, 2000);
        });
    });

    // Supprimer média
    document.querySelectorAll('.delete-media-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            if (confirm('Supprimer ce fichier ?')) {
                fetch('{{ route("admin.cms.delete-media") }}', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ filename: this.getAttribute('data-filename') })
                })
                .then(() => location.reload());
            }
        });
    });
});
</script>
@endpush

<style>
.media-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}
#dropZone.bg-light {
    background-color: #f8f9fa !important;
}
</style>
@endsection 