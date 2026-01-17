<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'nit',
        'address',
        'phone',
        'email',
        'logo',
        'status',
        'user_limit',
        'subscription_expires_at',
        'low_stock_alerts_enabled',
        'enabled_modules',
    ];

    protected $casts = [
        'subscription_expires_at' => 'date',
        'low_stock_alerts_enabled' => 'boolean',
        'enabled_modules' => 'array',
    ];

    /**
     * Get the users for the company.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the assets for the company.
     */
    public function assets()
    {
        return $this->hasMany(Asset::class);
    }

    /**
     * Get the invoices for the company.
     */
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * Check if subscription is expired
     */
    public function isExpired(): bool
    {
        if (!$this->subscription_expires_at) {
            return false;
        }
        return $this->subscription_expires_at->isPast();
    }

    /**
     * Check if user limit is reached
     */
    public function hasReachedUserLimit(): bool
    {
        return $this->users()->count() >= $this->user_limit;
    }

    /**
     * Check if a module is enabled for this company
     */
    public function hasModule(string $module): bool
    {
        if (!$this->enabled_modules) {
            return false;
        }
        
        return $this->enabled_modules[$module] ?? false;
    }

    /**
     * Get default enabled modules
     */
    public static function getDefaultModules(): array
    {
        return [
            'financial_control' => true,
            'depreciation' => true,
            'cost_centers' => true,
            'asset_costs' => true,
            'transfers' => false,
            'loans' => false,
            'disposals' => false,
            'advanced_audit' => false,
            'compliance' => false,
        ];
    }

    /**
     * Get module display names
     */
    public static function getModuleNames(): array
    {
        return [
            'financial_control' => 'Control Financiero',
            'depreciation' => 'Depreciación de Activos',
            'cost_centers' => 'Centros de Costo',
            'asset_costs' => 'Costos Asociados',
            'transfers' => 'Transferencias de Activos',
            'loans' => 'Préstamos Temporales',
            'disposals' => 'Gestión de Bajas',
            'advanced_audit' => 'Auditoría Avanzada',
            'compliance' => 'Cumplimiento Normativo',
        ];
    }
}
