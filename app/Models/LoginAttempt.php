<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginAttempt extends Model
{
    protected $fillable = [
        'email',
        'user_id',
        'ip_address',
        'success',
        'user_agent',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
