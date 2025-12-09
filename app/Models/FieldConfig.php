<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FieldConfig extends Model
{
    protected $fillable = [
        'role',
        'field_name',
        'is_visible',
        'label',
    ];

    protected $casts = [
        'is_visible' => 'boolean',
    ];
}
