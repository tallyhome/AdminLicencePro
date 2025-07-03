<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SupportTicketReply extends Model
{
    use HasFactory;

    protected $table = 'ticket_replies';

    protected $fillable = [
        'support_ticket_id',
        'user_type',
        'user_id',
        'message',
        'attachments',
    ];

    protected $casts = [
        'attachments' => 'array',
    ];

    /**
     * Get the support ticket that owns the reply
     */
    public function supportTicket(): BelongsTo
    {
        return $this->belongsTo(SupportTicket::class);
    }

    /**
     * Get the client that created the reply
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'user_id');
    }

    /**
     * Get the admin that created the reply
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'user_id');
    }

    /**
     * Get all attachments for the reply
     */
    public function attachments(): HasMany
    {
        return $this->hasMany(SupportTicketAttachment::class, 'reply_id');
    }

    /**
     * Get the author of the reply (client or admin)
     */
    public function getAuthorAttribute()
    {
        return $this->user_type === 'client' ? $this->client : $this->admin;
    }

    /**
     * Get the author name
     */
    public function getAuthorNameAttribute(): string
    {
        if ($this->user_type === 'client') {
            if (!$this->relationLoaded('client')) {
                $this->load('client');
            }
            return $this->client ? $this->client->name : 'Client supprimé';
        }

        if ($this->user_type === 'admin') {
            if (!$this->relationLoaded('admin')) {
                $this->load('admin');
            }
            return $this->admin ? $this->admin->name : 'Admin supprimé';
        }

        if ($this->user_type === 'system') {
            return 'Système';
        }

        return 'Utilisateur supprimé';
    }

    /**
     * Get the author avatar
     */
    public function getAuthorAvatarAttribute(): string
    {
        if ($this->user_type === 'client') {
            if (!$this->relationLoaded('client')) {
                $this->load('client');
            }
            return $this->client ? ($this->client->avatar ?? '/images/default-avatar.png') : '/images/default-avatar.png';
        }

        if ($this->user_type === 'admin') {
            if (!$this->relationLoaded('admin')) {
                $this->load('admin');
            }
            return $this->admin ? ($this->admin->avatar ?? '/images/default-admin-avatar.png') : '/images/default-admin-avatar.png';
        }

        return '/images/default-avatar.png';
    }

    /**
     * Check if the reply is from a client
     */
    public function getIsFromClientAttribute(): bool
    {
        return $this->user_type === 'client';
    }

    /**
     * Scope for replies from clients
     */
    public function scopeFromClient($query)
    {
        return $query->where('user_type', 'client');
    }

    /**
     * Scope for replies from admins
     */
    public function scopeFromAdmin($query)
    {
        return $query->where('user_type', 'admin');
    }

    /**
     * Scope for system replies
     */
    public function scopeFromSystem($query)
    {
        return $query->where('user_type', 'system');
    }
} 