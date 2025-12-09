<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportMessage extends Model
{
    protected $fillable = [
        'support_request_id',
        'user_id',
        'message',
        'is_admin_reply',
        'attachment_path',
        'attachment_name',
    ];

    /**
     * Get the support request that owns the message.
     */
    public function supportRequest()
    {
        return $this->belongsTo(SupportRequest::class);
    }

    /**
     * Get the user that sent the message.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
