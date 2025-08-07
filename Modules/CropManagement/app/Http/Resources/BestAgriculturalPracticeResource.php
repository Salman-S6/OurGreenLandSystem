<?php

namespace Modules\CropManagement\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BestAgriculturalPracticeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'growth_stage_id'=> $this->growth_stage_id,
            'expert_id'=> $this->expert_id,
            'practice_type'=> $this->practice_type,
            'material'=> $this->material,
            'quantity'=> $this->quantity,
            'application_date'=> $this->application_date,
            'notes'=> $this->notes,
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
} 