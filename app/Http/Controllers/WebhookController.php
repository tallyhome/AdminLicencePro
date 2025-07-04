<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\Invoice;
use App\Events\NewPayment;
use App\Services\StripeService;
use App\Services\PayPalService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use App\Models\PaymentMethod;
use App\Models\Tenant;
use Stripe\Stripe;
use Stripe\Webhook;
use Exception;

class WebhookController extends Controller
{
    protected $stripeService;
    protected $paypalService;

    public function __construct(StripeService $stripeService, PayPalService $paypalService)
    {
        $this->stripeService = $stripeService;
        $this->paypalService = $paypalService;
    }

    /**
     * Handle Stripe webhooks
     */
    public function stripe(Request $request)
    {
        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');
        $endpoint_secret = config('services.stripe.webhook_secret');

        try {
            // Verify webhook signature
            if ($endpoint_secret) {
                Stripe::setApiKey(config('services.stripe.secret'));
                $event = Webhook::constructEvent($payload, $sig_header, $endpoint_secret);
            } else {
                $event = json_decode($payload, true);
            }

            Log::info('Stripe webhook received', [
                'type' => $event['type'],
                'id' => $event['id'] ?? null
            ]);

            // Handle the event
            switch ($event['type']) {
                case 'payment_intent.succeeded':
                    $this->handlePaymentIntentSucceeded($event['data']['object']);
                    break;
                
                case 'payment_intent.payment_failed':
                    $this->handlePaymentIntentFailed($event['data']['object']);
                    break;
                
                case 'customer.subscription.created':
                    $this->handleSubscriptionCreated($event['data']['object']);
                    break;
                
                case 'customer.subscription.updated':
                    $this->handleSubscriptionUpdated($event['data']['object']);
                    break;
                
                case 'customer.subscription.deleted':
                    $this->handleSubscriptionDeleted($event['data']['object']);
                    break;
                
                case 'invoice.payment_succeeded':
                    $this->handleInvoicePaymentSucceeded($event['data']['object']);
                    break;
                
                case 'invoice.payment_failed':
                    $this->handleInvoicePaymentFailed($event['data']['object']);
                    break;
                
                case 'payment_method.attached':
                    $this->handlePaymentMethodAttached($event['data']['object']);
                    break;
                
                default:
                    Log::info('Unhandled Stripe webhook event type: ' . $event['type']);
            }

            return response()->json(['status' => 'success']);
        } catch (Exception $e) {
            Log::error('Stripe webhook error: ' . $e->getMessage());
            return response()->json(['error' => 'Webhook handler failed'], 400);
        }
    }

    /**
     * Handle PayPal webhooks
     */
    public function paypal(Request $request)
    {
        $payload = $request->all();
        
        try {
            // Verify PayPal webhook signature (implement based on PayPal documentation)
            $this->verifyPaypalWebhook($request);

            Log::info('PayPal webhook received', [
                'event_type' => $payload['event_type'] ?? null,
                'id' => $payload['id'] ?? null
            ]);

            // Handle the event
            switch ($payload['event_type']) {
                case 'BILLING.SUBSCRIPTION.CREATED':
                    $this->handlePaypalSubscriptionCreated($payload);
                    break;
                
                case 'BILLING.SUBSCRIPTION.ACTIVATED':
                    $this->handlePaypalSubscriptionActivated($payload);
                    break;
                
                case 'BILLING.SUBSCRIPTION.CANCELLED':
                    $this->handlePaypalSubscriptionCancelled($payload);
                    break;
                
                case 'PAYMENT.SALE.COMPLETED':
                    $this->handlePaypalPaymentCompleted($payload);
                    break;
                
                case 'PAYMENT.SALE.DENIED':
                    $this->handlePaypalPaymentDenied($payload);
                    break;
                
                default:
                    Log::info('Unhandled PayPal webhook event type: ' . $payload['event_type']);
            }

            return response()->json(['status' => 'success']);
        } catch (Exception $e) {
            Log::error('PayPal webhook error: ' . $e->getMessage());
            return response()->json(['error' => 'Webhook handler failed'], 400);
        }
    }

    /**
     * Handle successful payment intent
     */
    protected function handlePaymentIntentSucceeded($paymentIntent)
    {
        Log::info('Payment intent succeeded', ['payment_intent_id' => $paymentIntent['id']]);
        
        // Update subscription status or create invoice record
        // Implementation depends on your business logic
    }

    /**
     * Handle failed payment intent
     */
    protected function handlePaymentIntentFailed($paymentIntent)
    {
        Log::warning('Payment intent failed', ['payment_intent_id' => $paymentIntent['id']]);
        
        // Notify user of failed payment
        // Implementation depends on your business logic
    }

    /**
     * Handle subscription created
     */
    protected function handleSubscriptionCreated($subscription)
    {
        Log::info('Stripe subscription created', ['subscription_id' => $subscription['id']]);
        
        // Update local subscription record
        $localSubscription = Subscription::where('stripe_subscription_id', $subscription['id'])->first();
        if ($localSubscription) {
            $localSubscription->update([
                'status' => 'active',
                'stripe_status' => $subscription['status']
            ]);
        }
    }

    /**
     * Handle subscription updated
     */
    protected function handleSubscriptionUpdated($subscription)
    {
        Log::info('Stripe subscription updated', ['subscription_id' => $subscription['id']]);
        
        $localSubscription = Subscription::where('stripe_subscription_id', $subscription['id'])->first();
        if ($localSubscription) {
            $localSubscription->update([
                'status' => $this->mapStripeStatus($subscription['status']),
                'stripe_status' => $subscription['status']
            ]);
        }
    }

    /**
     * Handle subscription deleted
     */
    protected function handleSubscriptionDeleted($subscription)
    {
        Log::info('Stripe subscription deleted', ['subscription_id' => $subscription['id']]);
        
        $localSubscription = Subscription::where('stripe_subscription_id', $subscription['id'])->first();
        if ($localSubscription) {
            $localSubscription->update([
                'status' => 'cancelled',
                'canceled_at' => now()
            ]);
        }
    }

    /**
     * Handle invoice payment succeeded
     */
    protected function handleInvoicePaymentSucceeded($invoice)
    {
        Log::info('Invoice payment succeeded', ['invoice_id' => $invoice['id']]);
        
        // Create or update invoice record
        // Send receipt to customer
    }

    /**
     * Handle invoice payment failed
     */
    protected function handleInvoicePaymentFailed($invoice)
    {
        Log::warning('Invoice payment failed', ['invoice_id' => $invoice['id']]);
        
        // Notify customer of failed payment
        // Update subscription status if needed
    }

    /**
     * Handle payment method attached
     */
    protected function handlePaymentMethodAttached($paymentMethod)
    {
        Log::info('Payment method attached', ['payment_method_id' => $paymentMethod['id']]);
        
        // Update local payment method record if needed
    }

    /**
     * Handle PayPal subscription created
     */
    protected function handlePaypalSubscriptionCreated($payload)
    {
        Log::info('PayPal subscription created', ['subscription_id' => $payload['resource']['id']]);
        
        // Update local subscription record
    }

    /**
     * Handle PayPal subscription activated
     */
    protected function handlePaypalSubscriptionActivated($payload)
    {
        Log::info('PayPal subscription activated', ['subscription_id' => $payload['resource']['id']]);
        
        $localSubscription = Subscription::where('paypal_subscription_id', $payload['resource']['id'])->first();
        if ($localSubscription) {
            $localSubscription->update([
                'status' => 'active',
                'paypal_status' => 'ACTIVE'
            ]);
        }
    }

    /**
     * Handle PayPal subscription cancelled
     */
    protected function handlePaypalSubscriptionCancelled($payload)
    {
        Log::info('PayPal subscription cancelled', ['subscription_id' => $payload['resource']['id']]);
        
        $localSubscription = Subscription::where('paypal_subscription_id', $payload['resource']['id'])->first();
        if ($localSubscription) {
            $localSubscription->update([
                'status' => 'cancelled',
                'canceled_at' => now()
            ]);
        }
    }

    /**
     * Handle PayPal payment completed
     */
    protected function handlePaypalPaymentCompleted($payload)
    {
        Log::info('PayPal payment completed', ['payment_id' => $payload['resource']['id']]);
        
        // Create invoice record
        // Send receipt to customer
    }

    /**
     * Handle PayPal payment denied
     */
    protected function handlePaypalPaymentDenied($payload)
    {
        Log::warning('PayPal payment denied', ['payment_id' => $payload['resource']['id']]);
        
        // Notify customer of failed payment
    }

    /**
     * Verify PayPal webhook signature
     */
    protected function verifyPaypalWebhook(Request $request)
    {
        // Implement PayPal webhook verification
        // This is a simplified version - implement according to PayPal docs
        $webhookId = config('services.paypal.webhook_id');
        
        if (!$webhookId) {
            Log::warning('PayPal webhook ID not configured');
            return true; // Skip verification in development
        }

        // Add proper PayPal webhook verification here
        return true;
    }

    /**
     * Map Stripe status to local status
     */
    protected function mapStripeStatus($stripeStatus)
    {
        $statusMap = [
            'active' => 'active',
            'canceled' => 'cancelled',
            'incomplete' => 'pending',
            'incomplete_expired' => 'expired',
            'past_due' => 'past_due',
            'trialing' => 'trial',
            'unpaid' => 'unpaid'
        ];

        return $statusMap[$stripeStatus] ?? 'unknown';
    }
}