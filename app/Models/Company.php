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
    ];

    protected $casts = [
        'subscription_expires_at' => 'date',
        'low_stock_alerts_enabled' => 'boolean',
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
}
