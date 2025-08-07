<?php

namespace Modules\CropManagement\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PestDiseaseRecommendationResource extends JsonResource
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
            'pest_disease_case_id' => $this->pest_disease_case_id,
            'recommendation_name' => $this->recommendation_name,
            'recommended_dose' => $this->recommended_dose,
            'application_method' => $this->application_method,
            'safety_notes' => $this->safety_notes,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
