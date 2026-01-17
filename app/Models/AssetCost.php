<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AssetCost extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_id',
        'cost_type',
        'amount',
        'description',
        'date',
        'invoice_number',
        'vendor',
        'document_path',
        'created_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'date' => 'date',
    ];

    /**
     * Get the asset that owns the cost.
     */
    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    /**
     * Get the user who created this cost record.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get formatted cost type.
     */
    public function getFormattedCostTypeAttribute()
    {
        return match($this->cost_type) {
            'maintenance' => 'Mantenimiento',
            'repair' => 'Reparación',
            'insurance' => 'Seguro',
            'spare_parts' => 'Repuestos',
            'upgrade' => 'Mejora',
            'other' => 'Otro',
            default => $this->cost_type,
        };
    }
}
