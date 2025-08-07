<?php

namespace Modules\CropManagement\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CropGrowthStageResource extends JsonResource
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
            'crop_plan_id' => $this->crop_plan_id,
            'name' => $this->getTranslations('name'),
            'notes' => $this->getTranslations('notes'),
            'start_date' => $this->start_date?->toDateString(),
            'end_date' => $this->end_date?->toDateString(),
            'recorded_by' => $this->recorded_by,
            'recorder' => $this->whenLoaded('recorder'),
            'crop_plan' => $this->whenLoaded('cropPlan'),
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
} 