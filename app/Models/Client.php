<?php

namespace App\Models;

use App\Traits\HasRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'email',
        'password',
        'company_name',
        'phone',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'tenant_id',
        'status',
        'last_login_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Client status constants
     */
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_SUSPENDED = 'suspended';

    /**
     * Get the projects associated with the client.
     */
    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    /**
     * Get the serial keys associated with the client.
     */
    public function serialKeys()
    {
        return $this->hasMany(SerialKey::class);
    }

    /**
     * Get the support tickets associated with the client.
     */
    public function supportTickets()
    {
        return $this->hasMany(SupportTicket::class);
    }

    /**
     * Get the tenant associated with the client.
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get the invoices associated with the client.
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * Get the sessions associated with the client.
     */
    public function sessions(): HasMany
    {
        return $this->hasMany(Session::class);
    }

    /**
     * Scope a query to only include active clients.
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Scope a query to only include inactive clients.
     */
    public function scopeInactive($query)
    {
        return $query->where('status', self::STATUS_INACTIVE);
    }

    /**
     * Scope a query to only include suspended clients.
     */
    public function scopeSuspended($query)
    {
        return $query->where('status', self::STATUS_SUSPENDED);
    }
}