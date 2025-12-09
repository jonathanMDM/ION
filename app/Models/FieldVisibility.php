<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FieldVisibility extends Model
{
    protected $fillable = [
        'company_id',
        'field_key',
        'user_id',
        'role',
        'is_visible',
    ];

    protected $casts = [
        'is_visible' => 'boolean',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
