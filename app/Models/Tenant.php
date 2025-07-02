<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'licence_id',
        'name',
        'description',
        'domain',
        'database',
        'status',
        'settings',
        'subscription_id',
        'subscription_status',
        'subscription_ends_at',
        'trial_ends_at',
        'serial_key_id',
        'is_primary',
        'licence_features',
        'usage_stats',
        'max_clients',
        'max_projects',
        'licence_mode',
        'licence_expires_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'settings' => 'array',
        'licence_features' => 'array',
        'usage_stats' => 'array',
        'subscription_ends_at' => 'datetime',
        'trial_ends_at' => 'datetime',
        'licence_expires_at' => 'datetime',
        'is_primary' => 'boolean',
    ];

    /**
     * Tenant status constants
     */
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_SUSPENDED = 'suspended';

    /**
     * Subscription status constants
     */
    const SUBSCRIPTION_ACTIVE = 'active';
    const SUBSCRIPTION_CANCELED = 'canceled';
    const SUBSCRIPTION_EXPIRED = 'expired';
    const SUBSCRIPTION_TRIAL = 'trial';

    /**
     * Get the clients associated with the tenant.
     */
    public function clients()
    {
        return $this->hasMany(Client::class);
    }

    /**
     * Get the projects associated with the tenant.
     */
    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    /**
     * Get the serial keys associated with the tenant.
     */
    public function serialKeys()
    {
        return $this->hasManyThrough(SerialKey::class, Project::class);
    }

    /**
     * Get the support tickets associated with the tenant.
     */
    public function supportTickets()
    {
        return $this->hasMany(SupportTicket::class);
    }

    /**
     * Get the subscriptions associated with the tenant.
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Get the API keys associated with the tenant.
     */
    public function apiKeys()
    {
        return $this->hasMany(ApiKey::class);
    }

    /**
     * Get the invoices associated with the tenant.
     */
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * Get the sessions associated with the tenant.
     */
    public function sessions()
    {
        return $this->hasMany(Session::class);
    }

    /**
     * Scope a query to only include active tenants.
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Scope a query to only include inactive tenants.
     */
    public function scopeInactive($query)
    {
        return $query->where('status', self::STATUS_INACTIVE);
    }

    /**
     * Scope a query to only include suspended tenants.
     */
    public function scopeSuspended($query)
    {
        return $query->where('status', self::STATUS_SUSPENDED);
    }

    /**
     * Check if the tenant is on a trial period.
     */
    public function isOnTrial()
    {
        return $this->subscription_status === self::SUBSCRIPTION_TRIAL && $this->trial_ends_at && $this->trial_ends_at->isFuture();
    }

    /**
     * Check if the tenant's subscription is active.
     */
    public function hasActiveSubscription()
    {
        return $this->subscription_status === self::SUBSCRIPTION_ACTIVE && 
               ($this->subscription_ends_at === null || $this->subscription_ends_at->isFuture());
    }
}