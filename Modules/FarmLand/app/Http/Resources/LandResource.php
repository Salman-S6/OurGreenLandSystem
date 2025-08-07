<?php

namespace Modules\FarmLand\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LandResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'owner_id'          => $this->owner_id,
            'farmer_id'         => $this->farmer_id,
            'area'              => $this->area,
            'soil_type'         => $this->soilType?->name, 
            'damage_level'      => $this->damage_level,
            'gps_coordinates'   => $this->gps_coordinates,
            'boundary_coords'   => $this->boundary_coordinates,
            'created_at'        => $this->created_at?->toDateString(),
            'rehabilitation_date' => $this->rehabilitation_date,
            'region'             => $this->region,
    
        ];
    }
}
