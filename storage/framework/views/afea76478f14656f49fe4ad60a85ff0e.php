<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Paiement</h1>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Formulaire de paiement Stripe -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-2xl font-semibold mb-6">Carte bancaire</h2>
            <form id="payment-form" class="space-y-4">
                <div id="card-element" class="p-4 border rounded-lg"></div>
                <div id="card-errors" class="text-red-600 text-sm"></div>
                <div class="flex items-center space-x-2 mt-4">
                    <input type="checkbox" id="default-payment" name="default-payment" class="rounded text-blue-600">
                    <label for="default-payment">Définir comme moyen de paiement par défaut</label>
                </div>
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg transition duration-200">
                    Enregistrer la carte
                </button>
            </form>
        </div>

        <!-- Moyens de paiement enregistrés -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-2xl font-semibold mb-6">Moyens de paiement enregistrés</h2>
            <div id="payment-methods" class="space-y-4">
                <?php $__currentLoopData = $paymentMethods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $method): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex items-center justify-between p-4 border rounded-lg">
                        <div class="flex items-center space-x-4">
                            <?php if($method->provider === 'stripe'): ?>
                                <i class="fas fa-credit-card text-gray-600"></i>
                            <?php else: ?>
                                <i class="fab fa-paypal text-blue-600"></i>
                            <?php endif; ?>
                            <div>
                                <p class="font-medium"><?php echo e($method->card_brand); ?> •••• <?php echo e($method->card_last4); ?></p>
                                <p class="text-sm text-gray-600">Expire le <?php echo e($method->card_exp_month); ?>/<?php echo e($method->card_exp_year); ?></p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <?php if($method->is_default): ?>
                                <span class="text-sm text-green-600">Par défaut</span>
                            <?php endif; ?>
                            <button onclick="deletePaymentMethod('<?php echo e($method->id); ?>')" class="text-red-600 hover:text-red-800">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        <!-- PayPal -->
        <div class="bg-white rounded-lg shadow-lg p-6 lg:col-span-2">
            <h2 class="text-2xl font-semibold mb-6">PayPal</h2>
            <div id="paypal-button-container"></div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script src="https://js.stripe.com/v3/"></script>
<script src="https://www.paypal.com/sdk/js?client-id=<?php echo e(config('services.paypal.client_id')); ?>&currency=EUR"></script>
<script>
    // Stripe Elements
    const stripe = Stripe('<?php echo e(config("services.stripe.key")); ?>');
    const elements = stripe.elements();
    const cardElement = elements.create('card');
    cardElement.mount('#card-element');

    // Gestion des erreurs Stripe
    cardElement.addEventListener('change', function(event) {
        const displayError = document.getElementById('card-errors');
        if (event.error) {
            displayError.textContent = event.error.message;
        } else {
            displayError.textContent = '';
        }
    });

    // Soumission du formulaire Stripe
    const form = document.getElementById('payment-form');
    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        const isDefault = document.getElementById('default-payment').checked;

        try {
            const { setupIntent } = await fetch('/payment/setup-intent', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                }
            }).then(r => r.json());

            const { error, paymentMethod } = await stripe.confirmCardSetup(setupIntent.client_secret, {
                payment_method: { card: cardElement }
            });

            if (error) {
                document.getElementById('card-errors').textContent = error.message;
                return;
            }

            // Enregistrement du moyen de paiement
            await fetch('/payment/methods', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                },
                body: JSON.stringify({
                    payment_method_id: paymentMethod.id,
                    is_default: isDefault
                })
            });

            window.location.reload();
        } catch (e) {
            console.error(e);
            document.getElementById('card-errors').textContent = 'Une erreur est survenue.';
        }
    });

    // Suppression d'un moyen de paiement
    async function deletePaymentMethod(id) {
        if (!confirm('Voulez-vous vraiment supprimer ce moyen de paiement ?')) return;

        try {
            await fetch(`/payment/methods/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                }
            });
            window.location.reload();
        } catch (e) {
            console.error(e);
            alert('Une erreur est survenue lors de la suppression.');
        }
    }

    // Configuration PayPal
    paypal.Buttons({
        createOrder: async () => {
            const response = await fetch('/payment/paypal/create-order', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                },
                body: JSON.stringify({
                    subscription_id: '<?php echo e($subscription->id ?? null); ?>'
                })
            });
            const data = await response.json();
            return data.id;
        },
        onApprove: async (data) => {
            const response = await fetch('/payment/paypal/capture-order', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                },
                body: JSON.stringify({
                    order_id: data.orderID
                })
            });
            const orderData = await response.json();
            if (orderData.error) {
                alert('Une erreur est survenue lors du paiement.');
            } else {
                window.location.reload();
            }
        }
    }).render('#paypal-button-container');
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH R:\Adev\200  -  test\AdminLicence-4.5.1\resources\views\subscriptions\payment.blade.php ENDPATH**/ ?>