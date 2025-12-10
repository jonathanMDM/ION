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
        'model',
        'serial_number',
        'image',
        'image_public_id',
        'specifications',
        'custom_attributes',
        'municipality_plate',
        'next_maintenance_date',
        'maintenance_frequency_days',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'next_maintenance_date' => 'date',
        'custom_attributes' => 'array',
        'value' => 'decimal:2',
    ];

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
}
