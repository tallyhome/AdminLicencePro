<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tenant_id',
        'type',
        'stripe_payment_method_id',
        'paypal_billing_agreement_id',
        'last_four',
        'brand',
        'exp_month',
        'exp_year',
        'is_default',
        'metadata'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_default' => 'boolean',
        'metadata' => 'array',
        'exp_month' => 'integer',
        'exp_year' => 'integer'
    ];

    /**
     * Payment method types
     */
    const TYPE_STRIPE = 'stripe';
    const TYPE_PAYPAL = 'paypal';

    /**
     * Get the tenant that owns the payment method.
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get the subscriptions that use this payment method.
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Scope a query to only include Stripe payment methods.
     */
    public function scopeStripe($query)
    {
        return $query->where('type', self::TYPE_STRIPE);
    }

    /**
     * Scope a query to only include PayPal payment methods.
     */
    public function scopePaypal($query)
    {
        return $query->where('type', self::TYPE_PAYPAL);
    }

    /**
     * Scope a query to only include default payment methods.
     */
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    /**
     * Check if the payment method is Stripe.
     */
    public function isStripe()
    {
        return $this->type === self::TYPE_STRIPE;
    }

    /**
     * Check if the payment method is PayPal.
     */
    public function isPaypal()
    {
        return $this->type === self::TYPE_PAYPAL;
    }

    /**
     * Get a displayable representation of the payment method.
     */
    public function getDisplayNameAttribute(): string
    {
        if ($this->isStripe()) {
            return ucfirst($this->brand) . ' •••• ' . $this->last_four;
        } elseif ($this->isPaypal()) {
            return 'PayPal';
        }
        
        return 'Méthode de paiement';
    }

    /**
     * Get the icon for the payment method
     */
    public function getIconAttribute(): string
    {
        if ($this->isStripe()) {
            return 'fas fa-credit-card';
        } elseif ($this->isPaypal()) {
            return 'fab fa-paypal';
        }
        
        return 'fas fa-money-bill';
    }

    /**
     * Check if the payment method is expired
     */
    public function isExpired(): bool
    {
        if (!$this->exp_month || !$this->exp_year) {
            return false;
        }
        
        $now = now();
        $expiry = $now->copy()->setYear($this->exp_year)->setMonth($this->exp_month)->endOfMonth();
        
        return $now->isAfter($expiry);
    }

    /**
     * Scope to get payment methods by type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }
}