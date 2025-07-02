@extends('client.layouts.app')

@section('title', 'Facturation')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Facturation</h1>

    <div class="row">
        <!-- Abonnement actuel -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Abonnement Actuel</h6>
                </div>
                <div class="card-body">
                    @if(isset($subscription) && $subscription)
                        <div class="text-center mb-3">
                            <h4 class="text-primary">{{ $subscription->plan->name ?? 'Plan inconnu' }}</h4>
                            <p class="text-muted mb-1">{{ number_format($subscription->plan->price ?? 0, 2) }}€/mois</p>
                            <span class="badge badge-{{ $subscription->status === 'active' ? 'success' : 'warning' }}">
                                {{ ucfirst($subscription->status) }}
                            </span>
                        </div>
                        
                        @if($subscription->ends_at)
                        <div class="mb-3">
                            <small class="text-muted">Expire le</small><br>
                            <strong>{{ $subscription->ends_at->format('d/m/Y') }}</strong>
                        </div>
                        @endif
                        
                        <div class="d-grid gap-2">
                            <a href="{{ route('client.billing.subscription') }}" class="btn btn-primary btn-sm">
                                Gérer l'abonnement
                            </a>
                        </div>
                    @else
                        <div class="text-center">
                            <i class="fas fa-exclamation-circle fa-3x text-warning mb-3"></i>
                            <p class="text-muted">Aucun abonnement actif</p>
                            <a href="{{ route('client.billing.subscription') }}" class="btn btn-primary">
                                Choisir un plan
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Méthode de paiement -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Méthode de Paiement</h6>
                </div>
                <div class="card-body">
                    @if(isset($paymentMethod))
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-credit-card fa-2x text-primary mr-3"></i>
                            <div>
                                <strong>•••• •••• •••• {{ $paymentMethod->last4 ?? '0000' }}</strong><br>
                                <small class="text-muted">Expire {{ $paymentMethod->exp_month ?? '00' }}/{{ $paymentMethod->exp_year ?? '00' }}</small>
                            </div>
                        </div>
                        <button class="btn btn-outline-primary btn-sm">Modifier</button>
                    @else
                        <div class="text-center">
                            <i class="fas fa-credit-card fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Aucune méthode de paiement</p>
                            <button class="btn btn-primary btn-sm">Ajouter une carte</button>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Prochaine facture -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Prochaine Facture</h6>
                </div>
                <div class="card-body">
                    @if(isset($nextInvoice))
                        <div class="mb-3">
                            <small class="text-muted">Montant</small><br>
                            <h4 class="text-primary">{{ number_format($nextInvoice->amount ?? 0, 2) }}€</h4>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted">Date</small><br>
                            <strong>{{ $nextInvoice->date ?? 'N/A' }}</strong>
                        </div>
                    @else
                        <div class="text-center">
                            <i class="fas fa-file-invoice fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Aucune facture prévue</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Historique des factures -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Historique des Factures</h6>
            <a href="{{ route('client.billing.invoices') }}" class="btn btn-primary btn-sm">
                Voir toutes les factures
            </a>
        </div>
        <div class="card-body">
            @if(isset($invoices) && $invoices->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Numéro</th>
                                <th>Date</th>
                                <th>Montant</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($invoices as $invoice)
                            <tr>
                                <td>{{ $invoice->number }}</td>
                                <td>{{ $invoice->created_at->format('d/m/Y') }}</td>
                                <td>{{ number_format($invoice->amount, 2) }}€</td>
                                <td>
                                    <span class="badge badge-{{ $invoice->status === 'paid' ? 'success' : 'warning' }}">
                                        {{ $invoice->status === 'paid' ? 'Payée' : 'En attente' }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('client.billing.download-invoice', $invoice) }}" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-download"></i> Télécharger
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-file-invoice fa-3x text-gray-300 mb-3"></i>
                    <p class="text-muted">Aucune facture disponible</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 