<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\LogActivity;
use App\Traits\BelongsToCompany;

class Asset extends Model
{
    use LogActivity, HasFactory, BelongsToCompany;
    protected $fillable = [
        'company_id',
        'custom_id',
        'name',
        'description',
        'category_id',
        'subcategory_id',
        'location_id',
        'supplier_id',
        'status',
        'purchase_date',
        'purchase_price',
        'value',
        'quantity',
        'minimum_quantity',
        'model',
        'serial_number',
        'image',
        'image_public_id',
        'specifications',
        'custom_attributes',
        'municipality_plate',
        'next_maintenance_date',
        'maintenance_frequency_days',
        // Financial fields
        'depreciation_method',
        'useful_life_years',
        'salvage_value',
        'depreciation_start_date',
        'accumulated_depreciation',
        'cost_center_id',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'next_maintenance_date' => 'date',
        'custom_attributes' => 'array',
        'value' => 'decimal:2',
        'depreciation_start_date' => 'date',
        'salvage_value' => 'decimal:2',
        'accumulated_depreciation' => 'decimal:2',
    ];

    /**
     * Check if asset is low on stock
     */
    public function isLowStock()
    {
        return $this->minimum_quantity > 0 && $this->quantity <= $this->minimum_quantity;
    }

    /**
     * Scope to get assets with low stock
     */
    public function scopeLowStock($query)
    {
        return $query->whereColumn('quantity', '<=', 'minimum_quantity')
                     ->where('minimum_quantity', '>', 0);
    }

    public function scopeDueForMaintenance($query, $days = 7)
    {
        return $query->whereNotNull('next_maintenance_date')
                     ->where('next_maintenance_date', '<=', now()->addDays($days));
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function maintenances()
    {
        return $this->hasMany(Maintenance::class);
    }

    public function movements()
    {
        return $this->hasMany(AssetMovement::class)->orderBy('moved_at', 'desc');
    }

    public function assignments()
    {
        return $this->hasMany(AssetAssignment::class)->orderBy('assigned_date', 'desc');
    }

    public function currentAssignment()
    {
        return $this->hasOne(AssetAssignment::class)->where('status', 'active')->latest();
    }

    public function isAssigned()
    {
        return $this->assignments()->where('status', 'active')->exists();
    }

    /**
     * Get the cost center of this asset.
     */
    public function costCenter()
    {
        return $this->belongsTo(CostCenter::class);
    }

    /**
     * Get all costs associated with this asset.
     */
    public function costs()
    {
        return $this->hasMany(AssetCost::class)->orderBy('date', 'desc');
    }

    /**
     * Get total costs for this asset.
     */
    public function getTotalCostsAttribute()
    {
        return $this->costs()->sum('amount');
    }

    /**
     * Calculate current book value.
     */
    public function getBookValueAttribute()
    {
        if ($this->depreciation_method === 'none' || !$this->purchase_price) {
            return $this->purchase_price ?? 0;
        }

        return max(0, $this->purchase_price - $this->accumulated_depreciation);
    }

    /**
     * Calculate annual depreciation amount.
     */
    public function calculateAnnualDepreciation()
    {
        if ($this->depreciation_method === 'none' || !$this->purchase_price || !$this->useful_life_years) {
            return 0;
        }

        $depreciableAmount = $this->purchase_price - ($this->salvage_value ?? 0);

        return match($this->depreciation_method) {
            'straight_line' => $depreciableAmount / $this->useful_life_years,
            'declining_balance' => $this->book_value * (2 / $this->useful_life_years),
            'units_of_production' => 0, // Requires additional data
            default => 0,
        };
    }

    /**
     * Calculate depreciation up to a specific date.
     */
    public function calculateDepreciationToDate($date = null)
    {
        $date = $date ?? now();
        
        if ($this->depreciation_method === 'none' || !$this->depreciation_start_date || !$this->purchase_price) {
            return 0;
        }

        $startDate = $this->depreciation_start_date;
        if ($date < $startDate) {
            return 0;
        }

        $yearsElapsed = $startDate->diffInYears($date, true);
        $annualDepreciation = $this->calculateAnnualDepreciation();
        
        $totalDepreciation = $annualDepreciation * $yearsElapsed;
        $maxDepreciation = $this->purchase_price - ($this->salvage_value ?? 0);

        return min($totalDepreciation, $maxDepreciation);
    }

    /**
     * Update accumulated depreciation.
     */
    public function updateDepreciation()
    {
        $this->accumulated_depreciation = $this->calculateDepreciationToDate();
        $this->save();
    }

    /**
     * Get depreciation percentage.
     */
    public function getDepreciationPercentageAttribute()
    {
        if (!$this->purchase_price || $this->purchase_price == 0) {
            return 0;
        }

        return ($this->accumulated_depreciation / $this->purchase_price) * 100;
    }

    /**
     * Check if asset is fully depreciated.
     */
    public function isFullyDepreciated()
    {
        $maxDepreciation = $this->purchase_price - ($this->salvage_value ?? 0);
        return $this->accumulated_depreciation >= $maxDepreciation;
    }
}
