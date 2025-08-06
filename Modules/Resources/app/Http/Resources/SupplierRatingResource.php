<?php

namespace Modules\Resources\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SupplierRatingResource extends JsonResource
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
            'rating' => $this->rating,
            'comment' => $this->comment,
            'supplier' => [
                'id' => $this->supplier->id ?? null,
                'phone_number' => $this->supplier->phone_number ?? null,
                'supplier_type' => json_decode($this->supplier->supplier_type ?? '{}', true),
            ],
            'reviewer' => [
                'id' => $this->reviewer->id ?? null,
                'name' => $this->reviewer->name ?? null,
                'email' => $this->reviewer->email ?? null,
            ],
        ];
    }
}
