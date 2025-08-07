<?php

namespace Modules\FarmLand\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RehabilitationResource extends JsonResource
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
         'event'       => $this->getTranslations('event'),
        'description' => $this->getTranslations('description'),
        'notes'       => $this->getTranslations('notes'),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
       
        ];
    }
}
