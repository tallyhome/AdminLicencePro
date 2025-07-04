<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PaymentMethodController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Display a listing of the payment methods.
     */
    public function index()
    {
        $client = Auth::guard('client')->user();
        $tenant = $client->tenant;

        if (!$tenant) {
            return redirect()->route('client.dashboard')
                ->with('error', 'Aucun tenant associé.');
        }

        $paymentMethods = $this->paymentService->getPaymentMethods($tenant);

        return view('client.payment-methods.index', compact('paymentMethods', 'tenant'));
    }

    /**
     * Show the form for creating a new payment method.
     */
    public function create()
    {
        $client = Auth::guard('client')->user();
        $tenant = $client->tenant;

        if (!$tenant) {
            return redirect()->route('client.dashboard')
                ->with('error', 'Aucun tenant associé.');
        }

        return view('client.payment-methods.create', compact('tenant'));
    }

    /**
     * Store a newly created payment method in storage.
     */
    public function store(Request $request)
    {
        $client = Auth::guard('client')->user();
        $tenant = $client->tenant;

        if (!$tenant) {
            return redirect()->route('client.dashboard')
                ->with('error', 'Aucun tenant associé.');
        }

        $validator = Validator::make($request->all(), [
            'type' => 'required|in:stripe,paypal',
            'stripe_payment_method_id' => 'required_if:type,stripe',
            'paypal_billing_agreement_id' => 'required_if:type,paypal',
            'last_four' => 'nullable|string|size:4',
            'brand' => 'nullable|string',
            'exp_month' => 'nullable|integer|min:1|max:12',
            'exp_year' => 'nullable|integer|min:' . date('Y'),
            'is_default' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $paymentMethod = $this->paymentService->createPaymentMethod($tenant, $request->all());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Méthode de paiement ajoutée avec succès.',
                    'payment_method' => $paymentMethod
                ]);
            }

            return redirect()->route('client.payment-methods.index')
                ->with('success', 'Méthode de paiement ajoutée avec succès.');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de l\'ajout de la méthode de paiement : ' . $e->getMessage()
                ], 422);
            }

            return redirect()->back()
                ->with('error', 'Erreur lors de l\'ajout de la méthode de paiement : ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified payment method.
     */
    public function show(PaymentMethod $paymentMethod)
    {
        $client = Auth::guard('client')->user();
        $tenant = $client->tenant;

        if (!$tenant || $paymentMethod->tenant_id !== $tenant->id) {
            return redirect()->route('client.payment-methods.index')
                ->with('error', 'Méthode de paiement non trouvée.');
        }

        return view('client.payment-methods.show', compact('paymentMethod', 'tenant'));
    }

    /**
     * Show the form for editing the specified payment method.
     */
    public function edit(PaymentMethod $paymentMethod)
    {
        $client = Auth::guard('client')->user();
        $tenant = $client->tenant;

        if (!$tenant || $paymentMethod->tenant_id !== $tenant->id) {
            return redirect()->route('client.payment-methods.index')
                ->with('error', 'Méthode de paiement non trouvée.');
        }

        return view('client.payment-methods.edit', compact('paymentMethod', 'tenant'));
    }

    /**
     * Update the specified payment method in storage.
     */
    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        $client = Auth::guard('client')->user();
        $tenant = $client->tenant;

        if (!$tenant || $paymentMethod->tenant_id !== $tenant->id) {
            return redirect()->route('client.payment-methods.index')
                ->with('error', 'Méthode de paiement non trouvée.');
        }

        $validator = Validator::make($request->all(), [
            'is_default' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $this->paymentService->updatePaymentMethod($paymentMethod, $request->all());

            return redirect()->route('client.payment-methods.index')
                ->with('success', 'Méthode de paiement mise à jour avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la mise à jour : ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified payment method from storage.
     */
    public function destroy(PaymentMethod $paymentMethod)
    {
        $client = Auth::guard('client')->user();
        $tenant = $client->tenant;

        if (!$tenant || $paymentMethod->tenant_id !== $tenant->id) {
            return redirect()->route('client.payment-methods.index')
                ->with('error', 'Méthode de paiement non trouvée.');
        }

        try {
            $this->paymentService->deletePaymentMethod($paymentMethod);

            return redirect()->route('client.payment-methods.index')
                ->with('success', 'Méthode de paiement supprimée avec succès.');
        } catch (\Exception $e) {
            return redirect()->route('client.payment-methods.index')
                ->with('error', 'Erreur lors de la suppression : ' . $e->getMessage());
        }
    }

    /**
     * Set payment method as default.
     */
    public function setDefault(PaymentMethod $paymentMethod)
    {
        $client = Auth::guard('client')->user();
        $tenant = $client->tenant;

        if (!$tenant || $paymentMethod->tenant_id !== $tenant->id) {
            return redirect()->route('client.payment-methods.index')
                ->with('error', 'Méthode de paiement non trouvée.');
        }

        try {
            $this->paymentService->setAsDefault($paymentMethod);

            return redirect()->route('client.payment-methods.index')
                ->with('success', 'Méthode de paiement définie comme par défaut.');
        } catch (\Exception $e) {
            return redirect()->route('client.payment-methods.index')
                ->with('error', 'Erreur lors de la mise à jour : ' . $e->getMessage());
        }
    }

    /**
     * Handle Stripe payment method setup
     */
    public function stripeSetup(Request $request)
    {
        $client = Auth::guard('client')->user();
        $tenant = $client->tenant;

        if (!$tenant) {
            return response()->json(['error' => 'Aucun tenant associé.'], 400);
        }

        // Here you would integrate with Stripe Elements
        // For now, we'll return a mock response
        return response()->json([
            'client_secret' => 'pi_mock_client_secret',
            'public_key' => config('services.stripe.key')
        ]);
    }

    /**
     * Handle PayPal payment method setup
     */
    public function paypalSetup(Request $request)
    {
        $client = Auth::guard('client')->user();
        $tenant = $client->tenant;

        if (!$tenant) {
            return response()->json(['error' => 'Aucun tenant associé.'], 400);
        }

        // Here you would integrate with PayPal SDK
        // For now, we'll return a mock response
        return response()->json([
            'approval_url' => 'https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=mock_token',
            'client_id' => config('services.paypal.client_id')
        ]);
    }
} 