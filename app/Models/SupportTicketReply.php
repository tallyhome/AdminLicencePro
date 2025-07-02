<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SupportTicketReply extends Model
{
    use HasFactory;

    protected $fillable = [
        'support_ticket_id',
        'client_id',
        'admin_id',
        'message',
        'is_from_client',
        'is_internal_note',
        'read_at',
    ];

    protected $casts = [
        'is_from_client' => 'boolean',
        'is_internal_note' => 'boolean',
        'read_at' => 'datetime',
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
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the admin that created the reply
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
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
        return $this->is_from_client ? $this->client : $this->admin;
    }

    /**
     * Get the author name
     */
    public function getAuthorNameAttribute(): string
    {
        if ($this->is_from_client && $this->client) {
            return $this->client->name;
        }

        if (!$this->is_from_client && $this->admin) {
            return $this->admin->name;
        }

        return 'Utilisateur supprimé';
    }

    /**
     * Get the author avatar
     */
    public function getAuthorAvatarAttribute(): string
    {
        if ($this->is_from_client && $this->client) {
            return $this->client->avatar ?? '/images/default-avatar.png';
        }

        if (!$this->is_from_client && $this->admin) {
            return $this->admin->avatar ?? '/images/default-admin-avatar.png';
        }

        return '/images/default-avatar.png';
    }

    /**
     * Check if the reply has been read
     */
    public function isRead(): bool
    {
        return !is_null($this->read_at);
    }

    /**
     * Mark the reply as read
     */
    public function markAsRead(): void
    {
        if (!$this->isRead()) {
            $this->update(['read_at' => now()]);
        }
    }

    /**
     * Scope for replies from clients
     */
    public function scopeFromClient($query)
    {
        return $query->where('is_from_client', true);
    }

    /**
     * Scope for replies from admins
     */
    public function scopeFromAdmin($query)
    {
        return $query->where('is_from_client', false);
    }

    /**
     * Scope for public replies (not internal notes)
     */
    public function scopePublic($query)
    {
        return $query->where('is_internal_note', false);
    }

    /**
     * Scope for internal notes
     */
    public function scopeInternalNotes($query)
    {
        return $query->where('is_internal_note', true);
    }

    /**
     * Scope for unread replies
     */
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    /**
     * Scope for read replies
     */
    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }
} 