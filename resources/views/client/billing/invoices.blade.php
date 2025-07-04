@extends('layouts.client')

@section('title', 'Mes Factures')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-file-invoice-dollar mr-2"></i>
                        Mes Factures
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('client.billing.index') }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-arrow-left"></i> Retour à la facturation
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($invoices && $invoices->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
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
                                                <strong>{{ $invoice->number ?? '#' . $invoice->id }}</strong>
                                            </td>
                                            <td>
                                                {{ $invoice->created_at->format('d/m/Y') }}
                                            </td>
                                            <td>
                                                <span class="badge badge-info">
                                                    {{ number_format($invoice->amount ?? 0, 2) }} €
                                                </span>
                                            </td>
                                            <td>
                                                @switch($invoice->status ?? 'pending')
                                                    @case('paid')
                                                        <span class="badge badge-success">Payée</span>
                                                        @break
                                                    @case('pending')
                                                        <span class="badge badge-warning">En attente</span>
                                                        @break
                                                    @case('failed')
                                                        <span class="badge badge-danger">Échec</span>
                                                        @break
                                                    @default
                                                        <span class="badge badge-secondary">{{ $invoice->status }}</span>
                                                @endswitch
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    @if(method_exists($invoice, 'downloadUrl'))
                                                        <a href="{{ $invoice->downloadUrl() }}" 
                                                           class="btn btn-outline-primary" 
                                                           target="_blank">
                                                            <i class="fas fa-download"></i> Télécharger
                                                        </a>
                                                    @else
                                                        <button class="btn btn-outline-primary" 
                                                                onclick="downloadInvoice({{ $invoice->id }})">
                                                            <i class="fas fa-download"></i> Télécharger
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if(method_exists($invoices, 'links'))
                            <div class="d-flex justify-content-center">
                                {{ $invoices->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-file-invoice-dollar fa-3x text-muted mb-3"></i>
                            <h4 class="text-muted">Aucune facture</h4>
                            <p class="text-muted">Vous n'avez encore aucune facture.</p>
                            <a href="{{ route('client.billing.index') }}" class="btn btn-primary">
                                <i class="fas fa-credit-card"></i> Voir mes abonnements
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function downloadInvoice(invoiceId) {
    // Rediriger vers la route de téléchargement
    window.open(`{{ route('client.billing.index') }}/download-invoice/${invoiceId}`, '_blank');
}
</script>
@endsection 