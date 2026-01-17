<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\BelongsToCompany;
use App\Traits\LogActivity;

class AssetAssignment extends Model
{
    use HasFactory, BelongsToCompany, LogActivity;

    protected $fillable = [
        'asset_id',
        'employee_id',
        'user_id',
        'assigned_date',
        'return_date',
        'expected_return_date',
        'is_loan',
        'status',
        'notes',
    ];

    protected $casts = [
        'assigned_date' => 'date',
        'return_date' => 'date',
        'expected_return_date' => 'date',
        'is_loan' => 'boolean',
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeReturned($query)
    {
        return $query->where('status', 'returned');
    }
}
