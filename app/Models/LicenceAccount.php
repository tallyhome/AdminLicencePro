<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LicenceAccount extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'serial_key_id',
        'domain',
        'ip_address',
        'status',
        'activated_at',
        'last_used_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'activated_at' => 'datetime',
        'last_used_at' => 'datetime',
    ];

    /**
     * Get the serial key that owns the licence account.
     */
    public function serialKey(): BelongsTo
    {
        return $this->belongsTo(SerialKey::class);
    }

    /**
     * Check if the account is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if the account is suspended.
     */
    public function isSuspended(): bool
    {
        return $this->status === 'suspended';
    }

    /**
     * Check if the account is revoked.
     */
    public function isRevoked(): bool
    {
        return $this->status === 'revoked';
    }

    /**
     * Activate the account.
     */
    public function activate(): void
    {
        $this->update([
            'status' => 'active',
            'activated_at' => now(),
        ]);
    }

    /**
     * Suspend the account.
     */
    public function suspend(): void
    {
        $this->update(['status' => 'suspended']);
    }

    /**
     * Revoke the account.
     */
    public function revoke(): void
    {
        $this->update(['status' => 'revoked']);
    }

    /**
     * Update last used timestamp.
     */
    public function updateLastUsed(): void
    {
        $this->update(['last_used_at' => now()]);
    }

    /**
     * Scope to get active accounts.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope to get suspended accounts.
     */
    public function scopeSuspended($query)
    {
        return $query->where('status', 'suspended');
    }

    /**
     * Scope to get revoked accounts.
     */
    public function scopeRevoked($query)
    {
        return $query->where('status', 'revoked');
    }
}
