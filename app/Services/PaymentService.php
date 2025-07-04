<?php

namespace App\Services;

use App\Models\PaymentMethod;
use App\Models\Tenant;
use App\Models\Subscription;
use App\Models\Plan;
use Exception;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    /**
     * Create a new payment method for a tenant
     */
    public function createPaymentMethod(Tenant $tenant, array $data): PaymentMethod
    {
        // Set other payment methods as non-default if this is default
        if ($data['is_default'] ?? false) {
            $tenant->paymentMethods()->update(['is_default' => false]);
        }

        return $tenant->paymentMethods()->create($data);
    }

    /**
     * Update a payment method
     */
    public function updatePaymentMethod(PaymentMethod $paymentMethod, array $data): PaymentMethod
    {
        // Set other payment methods as non-default if this is default
        if ($data['is_default'] ?? false) {
            $paymentMethod->tenant->paymentMethods()
                ->where('id', '!=', $paymentMethod->id)
                ->update(['is_default' => false]);
        }

        $paymentMethod->update($data);
        return $paymentMethod;
    }

    /**
     * Delete a payment method
     */
    public function deletePaymentMethod(PaymentMethod $paymentMethod): bool
    {
        try {
            // Check if this payment method is used by active subscriptions
            $activeSubscriptions = $paymentMethod->subscriptions()
                ->where('status', 'active')
                ->count();

            if ($activeSubscriptions > 0) {
                throw new Exception('Cette méthode de paiement est utilisée par des abonnements actifs.');
            }

            // If this was the default payment method, set another one as default
            if ($paymentMethod->is_default) {
                $nextDefault = $paymentMethod->tenant->paymentMethods()
                    ->where('id', '!=', $paymentMethod->id)
                    ->first();
                
                if ($nextDefault) {
                    $nextDefault->update(['is_default' => true]);
                }
            }

            // Delete from payment provider if needed
            if ($paymentMethod->isStripe() && $paymentMethod->stripe_payment_method_id) {
                $this->deleteStripePaymentMethod($paymentMethod->stripe_payment_method_id);
            } elseif ($paymentMethod->isPaypal() && $paymentMethod->paypal_billing_agreement_id) {
                $this->deletePaypalBillingAgreement($paymentMethod->paypal_billing_agreement_id);
            }

            return $paymentMethod->delete();
        } catch (Exception $e) {
            Log::error('Error deleting payment method: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Create a subscription with a payment method
     */
    public function createSubscription(Tenant $tenant, Plan $plan, PaymentMethod $paymentMethod): Subscription
    {
        try {
            // Cancel any existing active subscriptions
            $tenant->subscriptions()
                ->where('status', 'active')
                ->update(['status' => 'cancelled', 'canceled_at' => now()]);

            // Create new subscription
            $subscription = $tenant->subscriptions()->create([
                'plan_id' => $plan->id,
                'payment_method_id' => $paymentMethod->id,
                'status' => 'active',
                'starts_at' => now(),
                'ends_at' => now()->addMonth(),
                'billing_cycle' => $plan->billing_cycle,
                'renewal_price' => $plan->price,
                'auto_renew' => true
            ]);

            // Process payment based on payment method type
            if ($paymentMethod->isStripe()) {
                $this->processStripeSubscription($subscription);
            } elseif ($paymentMethod->isPaypal()) {
                $this->processPaypalSubscription($subscription);
            }

            return $subscription;
        } catch (Exception $e) {
            Log::error('Error creating subscription: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Process Stripe subscription
     */
    protected function processStripeSubscription(Subscription $subscription): void
    {
        // Here you would integrate with Stripe API
        // For now, we'll just log it
        Log::info('Processing Stripe subscription', [
            'subscription_id' => $subscription->id,
            'plan_id' => $subscription->plan_id,
            'amount' => $subscription->plan->price
        ]);

        // Update subscription with Stripe subscription ID
        $subscription->update([
            'stripe_subscription_id' => 'sub_' . uniqid(),
            'payment_method' => 'stripe'
        ]);
    }

    /**
     * Process PayPal subscription
     */
    protected function processPaypalSubscription(Subscription $subscription): void
    {
        // Here you would integrate with PayPal API
        // For now, we'll just log it
        Log::info('Processing PayPal subscription', [
            'subscription_id' => $subscription->id,
            'plan_id' => $subscription->plan_id,
            'amount' => $subscription->plan->price
        ]);

        // Update subscription with PayPal subscription ID
        $subscription->update([
            'paypal_subscription_id' => 'I-' . uniqid(),
            'payment_method' => 'paypal'
        ]);
    }

    /**
     * Delete Stripe payment method
     */
    protected function deleteStripePaymentMethod(string $stripePaymentMethodId): void
    {
        // Here you would call Stripe API to delete the payment method
        Log::info('Deleting Stripe payment method', ['payment_method_id' => $stripePaymentMethodId]);
    }

    /**
     * Delete PayPal billing agreement
     */
    protected function deletePaypalBillingAgreement(string $paypalBillingAgreementId): void
    {
        // Here you would call PayPal API to cancel the billing agreement
        Log::info('Deleting PayPal billing agreement', ['billing_agreement_id' => $paypalBillingAgreementId]);
    }

    /**
     * Get payment methods for a tenant
     */
    public function getPaymentMethods(Tenant $tenant)
    {
        return $tenant->paymentMethods()->orderBy('is_default', 'desc')->get();
    }

    /**
     * Get default payment method for a tenant
     */
    public function getDefaultPaymentMethod(Tenant $tenant): ?PaymentMethod
    {
        return $tenant->paymentMethods()->where('is_default', true)->first();
    }

    /**
     * Set payment method as default
     */
    public function setAsDefault(PaymentMethod $paymentMethod): PaymentMethod
    {
        // Set other payment methods as non-default
        $paymentMethod->tenant->paymentMethods()
            ->where('id', '!=', $paymentMethod->id)
            ->update(['is_default' => false]);

        // Set this one as default
        $paymentMethod->update(['is_default' => true]);

        return $paymentMethod;
    }
} 