<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SupportTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'client_id',
        'admin_id',
        'ticket_number',
        'subject',
        'description',
        'priority',
        'category',
        'status',
        'assigned_to',
        'last_activity_at',
        'last_reply_by_client_at',
        'last_reply_by_admin_at',
        'last_read_by_client_at',
        'last_read_by_admin_at',
        'closed_at',
        'closed_by_client',
        'closed_by_admin',
        'resolution_notes',
        'tags',
        'estimated_resolution_time',
        'actual_resolution_time',
    ];

    protected $casts = [
        'last_activity_at' => 'datetime',
        'last_reply_by_client_at' => 'datetime',
        'last_reply_by_admin_at' => 'datetime',
        'last_read_by_client_at' => 'datetime',
        'last_read_by_admin_at' => 'datetime',
        'closed_at' => 'datetime',
        'closed_by_client' => 'boolean',
        'closed_by_admin' => 'boolean',
        'tags' => 'array',
        'estimated_resolution_time' => 'integer',
        'actual_resolution_time' => 'integer',
    ];

    /**
     * Status constants
     */
    const STATUS_OPEN = 'open';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_WAITING_CLIENT = 'waiting_client';
    const STATUS_RESOLVED = 'resolved';
    const STATUS_CLOSED = 'closed';

    /**
     * Priority constants
     */
    const PRIORITY_LOW = 'low';
    const PRIORITY_MEDIUM = 'medium';
    const PRIORITY_HIGH = 'high';
    const PRIORITY_URGENT = 'urgent';

    /**
     * Category constants
     */
    const CATEGORY_TECHNICAL = 'technical';
    const CATEGORY_BILLING = 'billing';
    const CATEGORY_GENERAL = 'general';
    const CATEGORY_FEATURE_REQUEST = 'feature_request';
    const CATEGORY_BUG_REPORT = 'bug_report';

    /**
     * Get the tenant that owns the support ticket
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get the client that created the support ticket
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the admin assigned to the support ticket
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'assigned_to');
    }

    /**
     * Get the admin who closed the ticket
     */
    public function closedByAdmin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'closed_by_admin');
    }

    /**
     * Get all replies for the support ticket
     */
    public function replies(): HasMany
    {
        return $this->hasMany(SupportTicketReply::class)->orderBy('created_at');
    }

    /**
     * Get all attachments for the support ticket
     */
    public function attachments(): HasMany
    {
        return $this->hasMany(SupportTicketAttachment::class);
    }

    /**
     * Scope for filtering by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope for filtering by priority
     */
    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * Scope for filtering by category
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope for open tickets
     */
    public function scopeOpen($query)
    {
        return $query->whereIn('status', [self::STATUS_OPEN, self::STATUS_IN_PROGRESS, self::STATUS_WAITING_CLIENT]);
    }

    /**
     * Scope for closed tickets
     */
    public function scopeClosed($query)
    {
        return $query->whereIn('status', [self::STATUS_RESOLVED, self::STATUS_CLOSED]);
    }

    /**
     * Check if the ticket is open
     */
    public function isOpen(): bool
    {
        return in_array($this->status, [self::STATUS_OPEN, self::STATUS_IN_PROGRESS, self::STATUS_WAITING_CLIENT]);
    }

    /**
     * Check if the ticket is closed
     */
    public function isClosed(): bool
    {
        return in_array($this->status, [self::STATUS_RESOLVED, self::STATUS_CLOSED]);
    }

    /**
     * Get the priority badge class
     */
    public function getPriorityBadgeClass(): string
    {
        return match($this->priority) {
            self::PRIORITY_LOW => 'bg-secondary',
            self::PRIORITY_MEDIUM => 'bg-primary',
            self::PRIORITY_HIGH => 'bg-warning',
            self::PRIORITY_URGENT => 'bg-danger',
            default => 'bg-secondary',
        };
    }

    /**
     * Get the status badge class
     */
    public function getStatusBadgeClass(): string
    {
        return match($this->status) {
            self::STATUS_OPEN => 'bg-success',
            self::STATUS_IN_PROGRESS => 'bg-primary',
            self::STATUS_WAITING_CLIENT => 'bg-warning',
            self::STATUS_RESOLVED => 'bg-info',
            self::STATUS_CLOSED => 'bg-secondary',
            default => 'bg-secondary',
        };
    }

    /**
     * Get the priority label
     */
    public function getPriorityLabel(): string
    {
        return match($this->priority) {
            self::PRIORITY_LOW => 'Faible',
            self::PRIORITY_MEDIUM => 'Moyenne',
            self::PRIORITY_HIGH => 'Élevée',
            self::PRIORITY_URGENT => 'Urgente',
            default => 'Inconnue',
        };
    }

    /**
     * Get the status label
     */
    public function getStatusLabel(): string
    {
        return match($this->status) {
            self::STATUS_OPEN => 'Ouvert',
            self::STATUS_IN_PROGRESS => 'En cours',
            self::STATUS_WAITING_CLIENT => 'En attente client',
            self::STATUS_RESOLVED => 'Résolu',
            self::STATUS_CLOSED => 'Fermé',
            default => 'Inconnu',
        };
    }

    /**
     * Get the category label
     */
    public function getCategoryLabel(): string
    {
        return match($this->category) {
            self::CATEGORY_TECHNICAL => 'Technique',
            self::CATEGORY_BILLING => 'Facturation',
            self::CATEGORY_GENERAL => 'Général',
            self::CATEGORY_FEATURE_REQUEST => 'Demande de fonctionnalité',
            self::CATEGORY_BUG_REPORT => 'Rapport de bug',
            default => 'Autre',
        };
    }

    /**
     * Calculate the time since last activity
     */
    public function getTimeSinceLastActivity(): string
    {
        if (!$this->last_activity_at) {
            return 'Aucune activité';
        }

        return $this->last_activity_at->diffForHumans();
    }

    /**
     * Get the latest reply
     */
    public function getLatestReply()
    {
        return $this->replies()->latest()->first();
    }

    /**
     * Count unread replies by client
     */
    public function getUnreadRepliesCountForClient(): int
    {
        if (!$this->last_read_by_client_at) {
            return $this->replies()->where('is_from_client', false)->count();
        }

        return $this->replies()
            ->where('is_from_client', false)
            ->where('created_at', '>', $this->last_read_by_client_at)
            ->count();
    }

    /**
     * Count unread replies by admin
     */
    public function getUnreadRepliesCountForAdmin(): int
    {
        if (!$this->last_read_by_admin_at) {
            return $this->replies()->where('is_from_client', true)->count();
        }

        return $this->replies()
            ->where('is_from_client', true)
            ->where('created_at', '>', $this->last_read_by_admin_at)
            ->count();
    }
} 