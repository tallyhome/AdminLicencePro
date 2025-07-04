@extends('layouts.client')

@section('title', 'Méthodes de paiement')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-credit-card mr-2"></i>
                        Mes Méthodes de Paiement
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('client.payment-methods.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Ajouter une méthode
                        </a>
                        <a href="{{ route('client.billing.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Retour à la facturation
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($paymentMethods && $paymentMethods->count() > 0)
                        <div class="row">
                            @foreach($paymentMethods as $paymentMethod)
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="card {{ $paymentMethod->is_default ? 'border-success' : '' }}">
                                        <div class="card-header">
                                            <h6 class="card-title mb-0">
                                                <i class="{{ $paymentMethod->icon }}"></i>
                                                {{ $paymentMethod->display_name }}
                                            </h6>
                                            @if($paymentMethod->is_default)
                                                <span class="badge badge-success">Par défaut</span>
                                            @endif
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-2">
                                                <strong>Type :</strong> 
                                                @if($paymentMethod->isStripe())
                                                    <span class="badge badge-info">Stripe</span>
                                                @elseif($paymentMethod->isPaypal())
                                                    <span class="badge badge-warning">PayPal</span>
                                                @endif
                                            </div>
                                            
                                            @if($paymentMethod->isStripe() && $paymentMethod->exp_month && $paymentMethod->exp_year)
                                                <div class="mb-2">
                                                    <strong>Expire :</strong> {{ $paymentMethod->exp_month }}/{{ $paymentMethod->exp_year }}
                                                    @if($paymentMethod->isExpired())
                                                        <span class="badge badge-danger ml-1">Expirée</span>
                                                    @endif
                                                </div>
                                            @endif
                                            
                                            <div class="mb-2">
                                                <small class="text-muted">
                                                    Ajoutée le {{ $paymentMethod->created_at->format('d/m/Y') }}
                                                </small>
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <div class="btn-group btn-group-sm w-100">
                                                @if(!$paymentMethod->is_default)
                                                    <form method="POST" action="{{ route('client.payment-methods.set-default', $paymentMethod) }}" style="display: inline;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-outline-success">
                                                            <i class="fas fa-star"></i> Définir par défaut
                                                        </button>
                                                    </form>
                                                @endif
                                                
                                                <button type="button" class="btn btn-outline-danger" 
                                                        onclick="confirmDelete({{ $paymentMethod->id }})">
                                                    <i class="fas fa-trash"></i> Supprimer
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-credit-card fa-3x text-muted mb-3"></i>
                            <h4 class="text-muted">Aucune méthode de paiement</h4>
                            <p class="text-muted">Ajoutez une méthode de paiement pour gérer vos abonnements.</p>
                            <a href="{{ route('client.payment-methods.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Ajouter une méthode de paiement
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation de suppression -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmer la suppression</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer cette méthode de paiement ?</p>
                <p class="text-warning">Cette action est irréversible.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(paymentMethodId) {
    const form = document.getElementById('deleteForm');
    form.action = `{{ route('client.payment-methods.index') }}/${paymentMethodId}`;
    $('#deleteModal').modal('show');
}
</script>
@endsection 