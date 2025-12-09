<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\BelongsToCompany;
use App\Traits\LogActivity;
use App\Models\Asset;

class Location extends Model
{
    use LogActivity, HasFactory, BelongsToCompany;
    protected $fillable = ['name', 'address'];

    public function assets()
    {
        return $this->hasMany(Asset::class);
    }
}
