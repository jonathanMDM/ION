<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Announcement extends Model
{
    protected $fillable = [
        'title',
        'message',
        'type',
        'is_active',
        'start_date',
        'end_date',
        'target_audience',
        'company_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    /**
     * Get the company that owns the announcement.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Scope to get only active announcements.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('start_date')
                  ->orWhere('start_date', '<=', Carbon::now());
            })
            ->where(function ($q) {
                $q->whereNull('end_date')
                  ->orWhere('end_date', '>=', Carbon::now());
            });
    }

    /**
     * Scope to get announcements for a specific user.
     */
    public function scopeForUser($query, $user)
    {
        return $query->where(function ($q) use ($user) {
            $q->where('target_audience', 'all')
              ->orWhere(function ($q2) use ($user) {
                  $q2->where('target_audience', 'admins_only')
                     ->where(function ($q3) use ($user) {
                         $q3->where(function ($q4) use ($user) {
                             // User is admin
                             return $user->isAdmin() || $user->isSuperAdmin();
                         });
                     });
              })
              ->orWhere(function ($q2) use ($user) {
                  $q2->where('target_audience', 'specific_company')
                     ->where('company_id', $user->company_id);
              });
        });
    }

    /**
     * Check if announcement is currently visible.
     */
    public function isVisible(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $now = Carbon::now();

        if ($this->start_date && $this->start_date->gt($now)) {
            return false;
        }

        if ($this->end_date && $this->end_date->lt($now)) {
            return false;
        }

        return true;
    }
}
