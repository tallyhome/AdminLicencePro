<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Session extends Model
{
    protected $fillable = [
        'client_id',
        'ip_address',
        'user_agent',
        'last_activity',
        'payload'
    ];

    protected $casts = [
        'last_activity' => 'datetime',
        'payload' => 'array'
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
} 