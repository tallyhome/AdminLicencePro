<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class SerialKey extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'serial_key',
        'status',
        'licence_type',
        'max_accounts',
        'used_accounts',
        'project_id',
        'domain',
        'ip_address',
        'expires_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'expires_at' => 'datetime',
    ];

    /**
     * Get the project that owns the serial key.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the licence accounts for this serial key.
     */
    public function accounts(): HasMany
    {
        return $this->hasMany(LicenceAccount::class, 'serial_key_id');
    }

    /**
     * Get the active licence accounts for this serial key.
     */
    public function activeAccounts(): HasMany
    {
        return $this->hasMany(LicenceAccount::class)->where('licence_accounts.status', 'active');
    }

    /**
     * Generate a unique serial key.
     */
    public static function generateUniqueKey(): string
    {
        $key = strtoupper(Str::random(4) . '-' . Str::random(4) . '-' . Str::random(4) . '-' . Str::random(4));
        
        // Ensure the key is unique
        while (self::where('serial_key', $key)->exists()) {
            $key = strtoupper(Str::random(4) . '-' . Str::random(4) . '-' . Str::random(4) . '-' . Str::random(4));
        }
        
        return $key;
    }

    /**
     * Check if the serial key is valid.
     */
    public function isValid(): bool
    {
        return $this->status === 'active' && 
               ($this->expires_at === null || $this->expires_at->isFuture());
    }

    /**
     * Check if this is a single licence.
     */
    public function isSingle(): bool
    {
        return $this->licence_type === 'single';
    }

    /**
     * Check if this is a multi licence.
     */
    public function isMulti(): bool
    {
        return $this->licence_type === 'multi';
    }

    /**
     * Check if the licence can accept a new account.
     */
    public function canAcceptNewAccount(): bool
    {
        if ($this->isSingle()) {
            // Pour les licences single, vérifier si domaine/IP non définis
            return empty($this->domain) && empty($this->ip_address);
        }
        
        // Pour les licences multi, vérifier si pas de dépassement
        return $this->used_accounts < $this->max_accounts;
    }

    /**
     * Get available slots for multi licence.
     */
    public function getAvailableSlots(): int
    {
        if ($this->isSingle()) {
            return $this->canAcceptNewAccount() ? 1 : 0;
        }
        
        return max(0, $this->max_accounts - $this->used_accounts);
    }

    /**
     * Add a new account for multi licence.
     */
    public function addAccount(string $domain, string $ipAddress = null): ?LicenceAccount
    {
        if (!$this->canAcceptNewAccount()) {
            return null;
        }

        if ($this->isSingle()) {
            // Pour les licences single, mettre à jour directement la clé
            $this->update([
                'domain' => $domain,
                'ip_address' => $ipAddress,
                'used_accounts' => 1
            ]);
            return null; // Les licences single n'utilisent pas la table accounts
        }

        // Pour les licences multi, créer un nouveau compte
        $account = $this->accounts()->create([
            'domain' => $domain,
            'ip_address' => $ipAddress,
            'status' => 'active',
            'activated_at' => now(),
        ]);

        // Mettre à jour le compteur
        $this->increment('used_accounts');

        return $account;
    }

    /**
     * Remove an account for multi licence.
     */
    public function removeAccount(string $domain): bool
    {
        if ($this->isSingle()) {
            if ($this->domain === $domain) {
                $this->update([
                    'domain' => null,
                    'ip_address' => null,
                    'used_accounts' => 0
                ]);
                return true;
            }
            return false;
        }

        $account = $this->accounts()->where('domain', $domain)->first();
        if ($account) {
            $account->delete();
            $this->decrement('used_accounts');
            return true;
        }

        return false;
    }

    /**
     * Check if the domain is authorized for this key.
     */
    public function isDomainAuthorized(string $domain): bool
    {
        if ($this->isSingle()) {
            return $this->domain === null || $this->domain === $domain;
        }

        // Pour les licences multi, vérifier dans les comptes
        return $this->activeAccounts()->where('domain', $domain)->exists();
    }

    /**
     * Check if the IP address is authorized for this key.
     */
    public function isIpAuthorized(string $ipAddress): bool
    {
        if ($this->isSingle()) {
            return $this->ip_address === null || $this->ip_address === $ipAddress;
        }

        // Pour les licences multi, vérifier dans les comptes
        return $this->activeAccounts()->where('ip_address', $ipAddress)->exists();
    }

    /**
     * Relation avec l'historique
     */
    public function history()
    {
        return $this->hasMany(SerialKeyHistory::class);
    }
}