<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupportTicketNote extends Model
{
    protected $fillable = [
        'support_ticket_id',
        'user_id',
        'note',
        'is_internal',
    ];

    protected $casts = [
        'is_internal' => 'boolean',
    ];

    // Relationships
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(SupportTicket::class, 'support_ticket_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
