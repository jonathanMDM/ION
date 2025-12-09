<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\BelongsToCompany;
use App\Traits\LogActivity;

class Supplier extends Model
{
    use HasFactory, BelongsToCompany;
    protected $fillable = [
        'name',
        'nit',
        'contact_name',
        'email',
        'phone',
        'address',
    ];

    /**
     * Get the assets for this supplier
     */
    public function assets()
    {
        return $this->hasMany(Asset::class);
    }
}
