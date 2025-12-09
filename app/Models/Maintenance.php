<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\BelongsToCompany;

class Maintenance extends Model
{
    use HasFactory, BelongsToCompany;
    protected $fillable = ['asset_id', 'description', 'date', 'cost'];

    protected $casts = [
        'date' => 'date',
        'cost' => 'decimal:2',
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }
}
