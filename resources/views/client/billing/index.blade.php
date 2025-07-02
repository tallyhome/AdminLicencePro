@extends('layouts.client')

@section('title', 'Facturation')

@section('content')
<div class="container-fluid py-4">
    <!-- En-tête avec le titre -->
    <div class="card bg-primary text-white mb-4">
        <div class="card-body p-4">
            <h4 class="mb-2">Facturation</h4>
            <p class="mb-0">Gérez votre abonnement et vos factures</p>
        </div>
    </div>

    <!-- Statistiques principales -->
    <div class="row g-4 mb-4">
        <!-- Abonnement actuel -->
        <div class="col-12 col-sm-6 col-xl-4">
            <div class="card-stats">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="stat-icon bg-primary">
                            <i class="fas fa-crown"></i>
                        </div>
                        <div class="text-end">
                            @if(isset($subscription) && $subscription)
                                <h3 class="mb-1">{{ $subscription->plan->name ?? 'Plan inconnu' }}</h3>
                                <p class="text-muted mb-0">{{ number_format($subscription->plan->price ?? 0, 2) }}€/mois</p>
                            @else
                                <h3 class="mb-1">Aucun plan</h3>
                                <p class="text-muted mb-0">Choisissez un plan</p>
                            @endif
                        </div>
                    </div>
                    <h6 class="mb-2">Abonnement Actuel</h6>
                    @if(isset($subscription) && $subscription)
                        @if($subscription->ends_at)
                            <div class="d-flex align-items-center">
                                <i class="fas fa-clock text-warning me-1"></i>
                                <small class="text-warning">Expire le {{ $subscription->ends_at->format('d/m/Y') }}</small>
                            </div>
                        @else
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check text-success me-1"></i>
                                <small class="text-success">Abonnement actif</small>
                            </div>
                        @endif
                    @else
                        <div class="d-flex align-items-center">
                            <i class="fas fa-exclamation-circle text-danger me-1"></i>
                            <small class="text-danger">Aucun abonnement actif</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Méthode de paiement -->
        <div class="col-12 col-sm-6 col-xl-4">
            <div class="card-stats">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="stat-icon bg-success">
                            <i class="fas fa-credit-card"></i>
                        </div>
                        <div class="text-end">
                            @if(isset($paymentMethod))
                                <h3 class="mb-1">•••• {{ $paymentMethod->last4 ?? '0000' }}</h3>
                                <p class="text-muted mb-0">Expire {{ $paymentMethod->exp_month ?? '00' }}/{{ $paymentMethod->exp_year ?? '00' }}</p>
                            @else
                                <h3 class="mb-1">Non défini</h3>
                                <p class="text-muted mb-0">Ajoutez une carte</p>
                            @endif
                        </div>
                    </div>
                    <h6 class="mb-2">Méthode de Paiement</h6>
                    @if(isset($paymentMethod))
                        <div class="d-flex align-items-center">
                            <i class="fas fa-check text-success me-1"></i>
                            <small class="text-success">Carte enregistrée</small>
                        </div>
                    @else
                        <div class="d-flex align-items-center">
                            <i class="fas fa-exclamation-circle text-warning me-1"></i>
                            <small class="text-warning">Aucune carte enregistrée</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Prochaine facture -->
        <div class="col-12 col-sm-6 col-xl-4">
            <div class="card-stats">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="stat-icon bg-info">
                            <i class="fas fa-file-invoice"></i>
                        </div>
                        <div class="text-end">
                            @if(isset($nextInvoice))
                                <h3 class="mb-1">{{ number_format($nextInvoice->amount ?? 0, 2) }}€</h3>
                                <p class="text-muted mb-0">{{ $nextInvoice->date ?? 'N/A' }}</p>
                            @else
                                <h3 class="mb-1">0,00€</h3>
                                <p class="text-muted mb-0">Aucune facture prévue</p>
                            @endif
                        </div>
                    </div>
                    <h6 class="mb-2">Prochaine Facture</h6>
                    @if(isset($nextInvoice))
                        <div class="d-flex align-items-center">
                            <i class="fas fa-calendar text-info me-1"></i>
                            <small class="text-info">Prochaine échéance</small>
                        </div>
                    @else
                        <div class="d-flex align-items-center">
                            <i class="fas fa-check text-success me-1"></i>
                            <small class="text-success">Aucun paiement en attente</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Actions rapides -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="card-title mb-0">Actions Rapides</h5>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('client.billing.subscription') }}" class="btn btn-primary">
                            <i class="fas fa-crown me-2"></i>Gérer l'abonnement
                        </a>
                        <button class="btn btn-success">
                            <i class="fas fa-credit-card me-2"></i>Modifier le paiement
                        </button>
                        <a href="{{ route('client.billing.invoices') }}" class="btn btn-info">
                            <i class="fas fa-file-invoice me-2"></i>Toutes les factures
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Historique des factures -->
    <div class="row g-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="card-title mb-0">Historique des Factures</h5>
                    </div>

                    @if(isset($invoices) && $invoices->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
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
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="me-3">
                                                    <div class="bg-primary rounded-circle p-2 text-white">
                                                        <i class="fas fa-file-invoice"></i>
                                                    </div>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">{{ $invoice->number }}</h6>
                                                    <small class="text-muted">Facture</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $invoice->created_at->format('d/m/Y') }}</td>
                                        <td>{{ number_format($invoice->amount, 2) }}€</td>
                                        <td>
                                            <span class="badge bg-{{ $invoice->status === 'paid' ? 'success' : 'warning' }}">
                                                {{ $invoice->status === 'paid' ? 'Payée' : 'En attente' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('client.billing.download-invoice', $invoice) }}" 
                                                   class="btn btn-sm btn-primary">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if($invoices instanceof \Illuminate\Pagination\LengthAwarePaginator)
                            <div class="d-flex justify-content-end mt-4">
                                {{ $invoices->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-file-invoice fa-3x text-muted mb-3"></i>
                            <h5>Aucune facture disponible</h5>
                            <p class="text-muted">Vos factures apparaîtront ici</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 