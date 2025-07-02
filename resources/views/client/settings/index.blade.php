@extends('layouts.client')

@section('title', 'Paramètres')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Paramètres</h1>

    <div class="row">
        <div class="col-lg-8">
            <!-- Profil -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informations du Profil</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('client.settings.update-profile') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="first_name">Prénom</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" 
                                           value="{{ $client->first_name }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="last_name">Nom</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" 
                                           value="{{ $client->last_name }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="{{ $client->email }}" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Téléphone</label>
                            <input type="text" class="form-control" id="phone" name="phone" 
                                   value="{{ $client->phone }}">
                        </div>
                        <button type="submit" class="btn btn-primary">Mettre à jour</button>
                    </form>
                </div>
            </div>

            <!-- Entreprise -->
            @if($tenant)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informations de l'Entreprise</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Nom:</strong> {{ $tenant->name }}
                        </div>
                        <div class="col-md-6">
                            <strong>Domaine:</strong> {{ $tenant->domain }}
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <div class="col-lg-4">
            <!-- Statistiques d'utilisation -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Utilisation</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted">Stockage utilisé</small>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" style="width: 45%"></div>
                        </div>
                        <small>45% utilisé</small>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Dernière connexion</small><br>
                        <small>{{ $usageStats['last_login'] ? $usageStats['last_login']->diffForHumans() : 'Jamais' }}</small>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Compte créé</small><br>
                        <small>{{ $usageStats['account_created']->format('d/m/Y') }}</small>
                    </div>
                </div>
            </div>

            <!-- Abonnement -->
            @if($subscription)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Abonnement</h6>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <h5 class="text-primary">{{ $subscription->plan->name }}</h5>
                        <p class="text-muted">{{ number_format($subscription->plan->price, 2) }}€/mois</p>
                        <small class="text-muted">
                            Expire le {{ $subscription->ends_at ? $subscription->ends_at->format('d/m/Y') : 'Jamais' }}
                        </small>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection 