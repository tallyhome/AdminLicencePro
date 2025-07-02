@extends('layouts.client')

@section('title', 'Documentation')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Documentation</h1>
        <a href="{{ route('client.support.index') }}" class="btn btn-primary">
            <i class="fas fa-arrow-left"></i> Retour au Support
        </a>
    </div>

    <!-- Quick Navigation -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body">
                    <h5 class="card-title">Navigation Rapide</h5>
                    <div class="row">
                        <div class="col-md-3">
                            <a href="#getting-started" class="btn btn-outline-primary btn-block mb-2">
                                <i class="fas fa-play"></i> Démarrage
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="#api-integration" class="btn btn-outline-success btn-block mb-2">
                                <i class="fas fa-code"></i> Intégration API
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="#license-management" class="btn btn-outline-info btn-block mb-2">
                                <i class="fas fa-key"></i> Gestion Licences
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="#troubleshooting" class="btn btn-outline-warning btn-block mb-2">
                                <i class="fas fa-wrench"></i> Dépannage
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Documentation Sections -->
    <div class="row">
        <div class="col-12">
            <!-- Getting Started -->
            <div class="card shadow mb-4" id="getting-started">
                <div class="card-header py-3 bg-primary text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-play mr-2"></i>Guide de Démarrage
                    </h6>
                </div>
                <div class="card-body">
                    <h5>1. Création de votre premier projet</h5>
                    <p>Pour commencer à utiliser AdminLicence, vous devez d'abord créer un projet :</p>
                    <ol>
                        <li>Connectez-vous à votre espace client</li>
                        <li>Cliquez sur "Projets" dans le menu de navigation</li>
                        <li>Cliquez sur "Créer un projet"</li>
                        <li>Remplissez les informations requises (nom, description, domaine)</li>
                        <li>Validez la création</li>
                    </ol>

                    <h5 class="mt-4">2. Génération de licences</h5>
                    <p>Une fois votre projet créé, vous pouvez générer des licences :</p>
                    <ol>
                        <li>Accédez à la section "Licences"</li>
                        <li>Sélectionnez votre projet</li>
                        <li>Cliquez sur "Générer des licences"</li>
                        <li>Choisissez le type et la quantité</li>
                        <li>Configurez les paramètres d'expiration si nécessaire</li>
                    </ol>
                </div>
            </div>

            <!-- API Integration -->
            <div class="card shadow mb-4" id="api-integration">
                <div class="card-header py-3 bg-success text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-code mr-2"></i>Intégration API
                    </h6>
                </div>
                <div class="card-body">
                    <h5>Configuration de base</h5>
                    <p>Pour intégrer l'API AdminLicence dans votre application :</p>
                    
                    <h6>1. Obtenir votre clé API</h6>
                    <ul>
                        <li>Allez dans "Paramètres" > "Clés API"</li>
                        <li>Créez une nouvelle clé API</li>
                        <li>Copiez la clé générée (elle ne sera plus affichée)</li>
                    </ul>

                    <h6>2. Vérification de licence (PHP)</h6>
                    <div class="bg-light p-3 rounded">
                        <pre><code>&lt;?php
$api_key = 'votre_cle_api';
$license_key = 'cle_licence_a_verifier';
$domain = $_SERVER['HTTP_HOST'];

$url = 'https://api.adminlicence.com/v1/verify';
$data = [
    'api_key' => $api_key,
    'license_key' => $license_key,
    'domain' => $domain
];

$response = file_get_contents($url . '?' . http_build_query($data));
$result = json_decode($response, true);

if ($result['valid']) {
    echo "Licence valide";
} else {
    echo "Licence invalide: " . $result['message'];
}
?&gt;</code></pre>
                    </div>

                    <h6>3. Endpoints disponibles</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Endpoint</th>
                                    <th>Méthode</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>/api/v1/verify</td>
                                    <td>GET</td>
                                    <td>Vérifier une licence</td>
                                </tr>
                                <tr>
                                    <td>/api/v1/activate</td>
                                    <td>POST</td>
                                    <td>Activer une licence</td>
                                </tr>
                                <tr>
                                    <td>/api/v1/deactivate</td>
                                    <td>POST</td>
                                    <td>Désactiver une licence</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- License Management -->
            <div class="card shadow mb-4" id="license-management">
                <div class="card-header py-3 bg-info text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-key mr-2"></i>Gestion des Licences
                    </h6>
                </div>
                <div class="card-body">
                    <h5>Types de licences</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Licence Standard</h6>
                            <ul>
                                <li>Utilisation sur un seul domaine</li>
                                <li>Support par email</li>
                                <li>Mises à jour incluses</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6>Licence Étendue</h6>
                            <ul>
                                <li>Utilisation sur plusieurs domaines</li>
                                <li>Support prioritaire</li>
                                <li>Fonctionnalités avancées</li>
                            </ul>
                        </div>
                    </div>

                    <h5 class="mt-4">États des licences</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>État</th>
                                    <th>Description</th>
                                    <th>Action possible</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><span class="badge badge-success">Active</span></td>
                                    <td>Licence fonctionnelle</td>
                                    <td>Suspendre, Révoquer</td>
                                </tr>
                                <tr>
                                    <td><span class="badge badge-warning">Suspendue</span></td>
                                    <td>Temporairement désactivée</td>
                                    <td>Réactiver, Révoquer</td>
                                </tr>
                                <tr>
                                    <td><span class="badge badge-danger">Révoquée</span></td>
                                    <td>Définitivement désactivée</td>
                                    <td>Aucune</td>
                                </tr>
                                <tr>
                                    <td><span class="badge badge-secondary">Expirée</span></td>
                                    <td>Date d'expiration dépassée</td>
                                    <td>Renouveler</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Troubleshooting -->
            <div class="card shadow mb-4" id="troubleshooting">
                <div class="card-header py-3 bg-warning text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-wrench mr-2"></i>Guide de Dépannage
                    </h6>
                </div>
                <div class="card-body">
                    <h5>Problèmes courants</h5>
                    
                    <h6>1. Erreur "Licence invalide"</h6>
                    <p><strong>Causes possibles :</strong></p>
                    <ul>
                        <li>Clé de licence incorrecte</li>
                        <li>Domaine non autorisé</li>
                        <li>Licence expirée ou révoquée</li>
                    </ul>
                    <p><strong>Solutions :</strong></p>
                    <ul>
                        <li>Vérifiez la clé de licence dans votre espace client</li>
                        <li>Assurez-vous que le domaine est autorisé</li>
                        <li>Vérifiez l'état de la licence</li>
                    </ul>

                    <h6>2. Erreur de connexion API</h6>
                    <p><strong>Causes possibles :</strong></p>
                    <ul>
                        <li>Problème de réseau</li>
                        <li>Clé API incorrecte</li>
                        <li>Serveur temporairement indisponible</li>
                    </ul>
                    <p><strong>Solutions :</strong></p>
                    <ul>
                        <li>Vérifiez votre connexion internet</li>
                        <li>Régénérez votre clé API si nécessaire</li>
                        <li>Implémentez un système de cache pour les vérifications</li>
                    </ul>

                    <h6>3. Performances lentes</h6>
                    <p><strong>Solutions recommandées :</strong></p>
                    <ul>
                        <li>Mettez en cache les résultats de vérification</li>
                        <li>Utilisez des vérifications asynchrones</li>
                        <li>Implémentez un fallback en cas d'indisponibilité</li>
                    </ul>
                </div>
            </div>

            <!-- Contact Support -->
            <div class="card shadow border-left-info">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="font-weight-bold text-info">Besoin d'aide supplémentaire ?</h5>
                            <p class="text-muted mb-0">Si cette documentation ne répond pas à vos questions, notre équipe support est là pour vous aider.</p>
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
    // Smooth scrolling pour les liens d'ancrage
    $('a[href^="#"]').on('click', function(event) {
        var target = $(this.getAttribute('href'));
        if( target.length ) {
            event.preventDefault();
            $('html, body').stop().animate({
                scrollTop: target.offset().top - 100
            }, 1000);
        }
    });
});
</script>
@endpush
@endsection 