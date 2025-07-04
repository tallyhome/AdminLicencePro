@extends('layouts.client')

@section('title', 'Gestion de l\'abonnement')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Abonnement actuel -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-crown mr-2"></i>
                        Mon Abonnement Actuel
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('client.billing.index') }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-arrow-left"></i> Retour
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($subscription && $subscription->plan)
                        <div class="row">
                            <div class="col-md-6">
                                <h4>{{ $subscription->plan->name }}</h4>
                                <p class="text-muted">{{ $subscription->plan->description }}</p>
                                
                                <div class="mt-3">
                                    <strong>Prix :</strong> 
                                    <span class="badge badge-success">
                                        {{ number_format($subscription->plan->price, 2) }} € 
                                        / {{ $subscription->plan->billing_cycle === 'monthly' ? 'mois' : 'an' }}
                                    </span>
                                </div>
                                
                                <div class="mt-2">
                                    <strong>Statut :</strong>
                                    @switch($subscription->status)
                                        @case('active')
                                            <span class="badge badge-success">Actif</span>
                                            @break
                                        @case('trial')
                                            <span class="badge badge-info">Période d'essai</span>
                                            @break
                                        @case('cancelled')
                                            <span class="badge badge-warning">Annulé</span>
                                            @break
                                        @default
                                            <span class="badge badge-secondary">{{ $subscription->status }}</span>
                                    @endswitch
                                </div>

                                @if($subscription->ends_at)
                                    <div class="mt-2">
                                        <strong>Se termine le :</strong> {{ $subscription->ends_at->format('d/m/Y') }}
                                    </div>
                                @endif
                            </div>
                            
                            <div class="col-md-6">
                                <h5>Fonctionnalités incluses :</h5>
                                <ul class="list-unstyled">
                                    @if($subscription->plan->features)
                                        @foreach($subscription->plan->features as $feature)
                                            <li><i class="fas fa-check text-success mr-2"></i>{{ $feature }}</li>
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                        </div>

                        <div class="mt-4">
                            <div class="btn-group">
                                @if($subscription->status === 'active')
                                    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#cancelModal">
                                        <i class="fas fa-times"></i> Annuler l'abonnement
                                    </button>
                                @endif
                                
                                                <a href="{{ route('client.payment-methods.index') }}" class="btn btn-primary">
                    <i class="fas fa-credit-card"></i> Gérer les modes de paiement
                </a>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-crown fa-3x text-muted mb-3"></i>
                            <h4 class="text-muted">Aucun abonnement actif</h4>
                            <p class="text-muted">Choisissez un plan pour commencer.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Plans disponibles -->
            @if($plans && $plans->count() > 0)
                <div class="card mt-4">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-list mr-2"></i>
                            Plans Disponibles
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($plans as $plan)
                                <div class="col-md-4 mb-4">
                                    <div class="card {{ $subscription && $subscription->plan_id === $plan->id ? 'border-primary' : '' }}">
                                        <div class="card-header text-center">
                                            <h5 class="card-title">{{ $plan->name }}</h5>
                                            @if($subscription && $subscription->plan_id === $plan->id)
                                                <span class="badge badge-primary">Plan actuel</span>
                                            @endif
                                        </div>
                                        <div class="card-body text-center">
                                            <h2 class="text-primary">
                                                {{ number_format($plan->price, 2) }} €
                                                <small class="text-muted">
                                                    / {{ $plan->billing_cycle === 'monthly' ? 'mois' : 'an' }}
                                                </small>
                                            </h2>
                                            <p class="text-muted">{{ $plan->description }}</p>
                                            
                                            @if($plan->features)
                                                <ul class="list-unstyled text-left">
                                                    @foreach($plan->features as $feature)
                                                        <li><i class="fas fa-check text-success mr-2"></i>{{ $feature }}</li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                            
                                            @if(!$subscription || $subscription->plan_id !== $plan->id)
                                                <form method="POST" action="{{ route('client.billing.upgrade') }}">
                                                    @csrf
                                                    <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                                                    <button type="submit" class="btn btn-primary btn-block">
                                                        @if($subscription)
                                                            Changer pour ce plan
                                                        @else
                                                            Choisir ce plan
                                                        @endif
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal d'annulation -->
<div class="modal fade" id="cancelModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Annuler l'abonnement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir annuler votre abonnement ?</p>
                <p class="text-muted">Votre abonnement restera actif jusqu'à la fin de la période de facturation.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form method="POST" action="{{ route('client.billing.cancel') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-danger">Confirmer l'annulation</button>
                </form>
            </div>
        </div>
    </div>
</div>


@endsection 