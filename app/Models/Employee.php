<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\LogActivity;
use App\Traits\BelongsToCompany;

class Employee extends Model
{
    use HasFactory, BelongsToCompany, LogActivity;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'department',
        'position',
        'status',
    ];

    // Accessor para nombre completo
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function assignments()
    {
        return $this->hasMany(AssetAssignment::class);
    }
}
