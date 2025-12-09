<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class CompanyScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        if (Auth::hasUser()) {
            $user = Auth::user();

            // Superadmins can see everything, normal users only see their company's data
            if (!$user->isSuperAdmin()) {
                $builder->where('company_id', $user->company_id);

                // If querying the User model, hide superadmins
                if ($model instanceof \App\Models\User) {
                    $builder->where('is_superadmin', false);
                }
            }
        }
    }
}
