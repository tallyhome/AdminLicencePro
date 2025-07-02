<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class SupportTicketAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'support_ticket_id',
        'reply_id',
        'filename',
        'original_filename',
        'path',
        'size',
        'mime_type',
        'uploaded_by_client',
        'uploaded_by_admin',
    ];

    protected $casts = [
        'size' => 'integer',
    ];

    /**
     * Get the support ticket that owns the attachment
     */
    public function supportTicket(): BelongsTo
    {
        return $this->belongsTo(SupportTicket::class);
    }

    /**
     * Get the reply that owns the attachment (if attached to a reply)
     */
    public function reply(): BelongsTo
    {
        return $this->belongsTo(SupportTicketReply::class, 'reply_id');
    }

    /**
     * Get the client who uploaded the attachment
     */
    public function clientUploader(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'uploaded_by_client');
    }

    /**
     * Get the admin who uploaded the attachment
     */
    public function adminUploader(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'uploaded_by_admin');
    }

    /**
     * Get the uploader (client or admin)
     */
    public function getUploaderAttribute()
    {
        return $this->uploaded_by_client ? $this->clientUploader : $this->adminUploader;
    }

    /**
     * Get the uploader name
     */
    public function getUploaderNameAttribute(): string
    {
        if ($this->uploaded_by_client && $this->clientUploader) {
            return $this->clientUploader->name;
        }

        if ($this->uploaded_by_admin && $this->adminUploader) {
            return $this->adminUploader->name;
        }

        return 'Utilisateur supprimé';
    }

    /**
     * Get the file size in human readable format
     */
    public function getFormattedSizeAttribute(): string
    {
        $bytes = $this->size;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Get the file extension
     */
    public function getExtensionAttribute(): string
    {
        return pathinfo($this->filename, PATHINFO_EXTENSION);
    }

    /**
     * Check if the file is an image
     */
    public function isImage(): bool
    {
        return in_array(strtolower($this->extension), ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp']);
    }

    /**
     * Check if the file is a document
     */
    public function isDocument(): bool
    {
        return in_array(strtolower($this->extension), ['pdf', 'doc', 'docx', 'txt', 'rtf']);
    }

    /**
     * Check if the file is an archive
     */
    public function isArchive(): bool
    {
        return in_array(strtolower($this->extension), ['zip', 'rar', '7z', 'tar', 'gz']);
    }

    /**
     * Get the file icon class
     */
    public function getIconClassAttribute(): string
    {
        if ($this->isImage()) {
            return 'fas fa-image text-primary';
        }

        if ($this->isDocument()) {
            return 'fas fa-file-alt text-danger';
        }

        if ($this->isArchive()) {
            return 'fas fa-file-archive text-warning';
        }

        return match(strtolower($this->extension)) {
            'pdf' => 'fas fa-file-pdf text-danger',
            'xls', 'xlsx' => 'fas fa-file-excel text-success',
            'ppt', 'pptx' => 'fas fa-file-powerpoint text-orange',
            'mp3', 'wav', 'ogg' => 'fas fa-file-audio text-info',
            'mp4', 'avi', 'mov' => 'fas fa-file-video text-purple',
            default => 'fas fa-file text-secondary',
        };
    }

    /**
     * Get the download URL
     */
    public function getDownloadUrlAttribute(): string
    {
        return route('client.support.download-attachment', [
            'ticket' => $this->support_ticket_id,
            'attachment' => $this->id
        ]);
    }

    /**
     * Check if the file exists
     */
    public function exists(): bool
    {
        return Storage::disk('public')->exists($this->path);
    }

    /**
     * Get the full file path
     */
    public function getFullPathAttribute(): string
    {
        return Storage::disk('public')->path($this->path);
    }

    /**
     * Get the public URL
     */
    public function getUrlAttribute(): string
    {
        return Storage::disk('public')->url($this->path);
    }

    /**
     * Delete the physical file when the model is deleted
     */
    public static function boot()
    {
        parent::boot();

        static::deleting(function ($attachment) {
            if ($attachment->exists()) {
                Storage::disk('public')->delete($attachment->path);
            }
        });
    }

    /**
     * Scope for attachments uploaded by clients
     */
    public function scopeUploadedByClient($query)
    {
        return $query->whereNotNull('uploaded_by_client');
    }

    /**
     * Scope for attachments uploaded by admins
     */
    public function scopeUploadedByAdmin($query)
    {
        return $query->whereNotNull('uploaded_by_admin');
    }

    /**
     * Scope for images
     */
    public function scopeImages($query)
    {
        return $query->whereIn('mime_type', [
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/bmp',
            'image/webp'
        ]);
    }

    /**
     * Scope for documents
     */
    public function scopeDocuments($query)
    {
        return $query->whereIn('mime_type', [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'text/plain'
        ]);
    }
} 