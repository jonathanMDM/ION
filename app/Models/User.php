<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Traits\LogActivity;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens, LogActivity, \App\Traits\BelongsToCompany;

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        // Apply CompanyScope and auto-assign company_id from Trait
        static::addGlobalScope(new \App\Scopes\CompanyScope);

        static::creating(function ($model) {
            if (\Illuminate\Support\Facades\Auth::check() && !$model->company_id && !\Illuminate\Support\Facades\Auth::user()->isSuperAdmin()) {
                $model->company_id = \Illuminate\Support\Facades\Auth::user()->company_id;
            }
        });

        // Cascading Disable: If Admin is disabled, disable all company users
        static::updated(function ($user) {
            if ($user->wasChanged('is_active') && !$user->is_active && $user->role === 'admin') {
                // Disable all other users in the same company
                static::withoutEvents(function () use ($user) {
                    static::where('company_id', $user->company_id)
                        ->where('id', '!=', $user->id)
                        ->update(['is_active' => false]);
                });
            }
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'permissions',
        'company_id',
        'preferences',
        'must_change_password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'api_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean', // Kept from original
        'permissions' => 'array', // Kept from original
        'is_superadmin' => 'boolean', // Added from instruction
        'must_change_password' => 'boolean',
        'api_token_expires_at' => 'datetime',
        'preferences' => 'array',
    ];

    /**
     * Get the company that owns the user.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the notifications for the user.
     */
    public function notifications()
    {
        return $this->hasMany(UserNotification::class)->orderBy('created_at', 'desc');
    }

    /**
     * Check if user is a super admin.
     */
    public function isSuperAdmin(): bool
    {
        return $this->is_superadmin;
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is editor
     */
    public function isEditor(): bool
    {
        return $this->role === 'editor';
    }

    /**
     * Check if user is viewer
     */
    public function isViewer(): bool
    {
        return $this->role === 'viewer';
    }

    /**
     * Check if user has a specific permission
     */
    public function hasPermission(string $permission): bool
    {
        // Admin and SuperAdmin have all permissions
        if ($this->isAdmin() || $this->isSuperAdmin()) {
            return true;
        }

        // If user has custom role with specific permissions
        if ($this->role === 'custom' && $this->permissions && is_array($this->permissions)) {
            return in_array($permission, $this->permissions);
        }

        // Use default permissions for the role
        $defaultPermissions = \App\Config\PermissionConfig::getRolePermissions($this->role);
        return in_array($permission, $defaultPermissions);
    }

    /**
     * Check if user can access a specific permission
     */
    public function canAccess(string $permission): bool
    {
        return $this->hasPermission($permission);
    }
}
