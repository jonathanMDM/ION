<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\LogActivity;
use App\Traits\BelongsToCompany;

class Subcategory extends Model
{
    use LogActivity, HasFactory, BelongsToCompany;
    protected $fillable = ['category_id', 'name'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function assets()
    {
        return $this->hasMany(Asset::class);
    }
}
