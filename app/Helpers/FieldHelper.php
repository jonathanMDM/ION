<?php

namespace App\Helpers;

use App\Models\FieldConfig;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class FieldHelper
{
    /**
     * Check if a field is visible for the current user.
     *
     * @param string $fieldKey
     * @param User|null $user Override user check (optional)
     * @return bool
     */
    public static function isVisible(string $fieldKey, ?\App\Models\User $user = null): bool
    {
        $user = $user ?? Auth::user();

        if (!$user) {
            return false;
        }

        // Superadmins always see everything
        if ($user->isSuperAdmin()) {
            return true;
        }

        $companyId = $user->company_id;
        $role = $user->role;
        $userId = $user->id;

        // Cache key based on user, company and field
        $cacheKey = "field_visibility_{$companyId}_{$userId}_{$fieldKey}";

        return Cache::remember($cacheKey, 60, function () use ($companyId, $userId, $role, $fieldKey) {
            // 1. Check specific user setting
            $userSetting = \App\Models\FieldVisibility::where('company_id', $companyId)
                ->where('field_key', $fieldKey)
                ->where('user_id', $userId)
                ->first();

            if ($userSetting) {
                return $userSetting->is_visible;
            }

            // 2. Check role setting
            $roleSetting = \App\Models\FieldVisibility::where('company_id', $companyId)
                ->where('field_key', $fieldKey)
                ->where('role', $role)
                ->whereNull('user_id')
                ->first();

            if ($roleSetting) {
                return $roleSetting->is_visible;
            }

            // 3. Default to visible (or check global company setting if implemented)
            // For now, default is visible unless explicitly hidden
            return true;
        });
    }

    /**
     * Clear field visibility cache.
     */
    public static function clearCache()
    {
        Cache::flush(); // Simple flush for now, could be more targeted
    }
}
