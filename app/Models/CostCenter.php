<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CostCenter extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'code',
        'name',
        'description',
        'budget',
        'manager_id',
        'is_active',
    ];

    protected $casts = [
        'budget' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get the company that owns the cost center.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the manager of the cost center.
     */
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    /**
     * Get the assets assigned to this cost center.
     */
    public function assets()
    {
        return $this->hasMany(Asset::class);
    }

    /**
     * Get total value of assets in this cost center.
     */
    public function getTotalAssetValueAttribute()
    {
        return $this->assets()->sum('purchase_price');
    }

    /**
     * Get total book value of assets in this cost center.
     */
    public function getTotalBookValueAttribute()
    {
        return $this->assets()->get()->sum(function ($asset) {
            return $asset->book_value;
        });
    }

    /**
     * Check if budget is exceeded.
     */
    public function isBudgetExceeded()
    {
        if (!$this->budget) {
            return false;
        }
        return $this->total_asset_value > $this->budget;
    }
}
