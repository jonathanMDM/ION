<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\LogActivity;
use App\Traits\BelongsToCompany;

class Category extends Model
{
    use LogActivity, HasFactory, BelongsToCompany;
    protected $fillable = ['name'];

    public function subcategories()
    {
        return $this->hasMany(Subcategory::class);
    }

    public function assets()
    {
        return $this->hasManyThrough(Asset::class, Subcategory::class);
    }
    //
}
