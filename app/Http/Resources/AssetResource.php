<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssetResource extends JsonResource
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
            'code' => $this->custom_id,
            'serial_number' => $this->serial_number,
            'model' => $this->model,
            'brand' => $this->brand,
            'description' => $this->description,
            'purchase_date' => $this->purchase_date?->format('Y-m-d'),
            'purchase_price' => $this->purchase_price,
            'current_value' => $this->current_value,
            'status' => $this->status,
            'condition' => $this->condition,
            'warranty_expiration' => $this->warranty_expiration?->format('Y-m-d'),
            'notes' => $this->notes,
            'qr_code_path' => $this->qr_code_path ? url($this->qr_code_path) : null,
            'image_path' => $this->image_path ? url($this->image_path) : null,
            
            // Relationships
            'category' => [
                'id' => $this->category?->id,
                'name' => $this->category?->name,
            ],
            'subcategory' => [
                'id' => $this->subcategory?->id,
                'name' => $this->subcategory?->name,
            ],
            'location' => [
                'id' => $this->location?->id,
                'name' => $this->location?->name,
                'address' => $this->location?->address,
            ],
            'supplier' => [
                'id' => $this->supplier?->id,
                'name' => $this->supplier?->name,
            ],
            'employee' => [
                'id' => $this->employee?->id,
                'name' => $this->employee?->full_name,
            ],
            
            // Timestamps
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
