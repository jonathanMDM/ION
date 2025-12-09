<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'nit' => $this->nit,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'is_active' => $this->status === 'active',
            'subscription_status' => $this->subscription_expires_at && $this->subscription_expires_at->isPast() ? 'expired' : 'active',
            'subscription_expires_at' => $this->subscription_expires_at?->format('Y-m-d'),
            
            // Statistics (when loaded)
            'statistics' => $this->when(isset($this->users_count), [
                'users_count' => $this->users_count ?? 0,
                'assets_count' => $this->assets_count ?? 0,
            ]),
            
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
