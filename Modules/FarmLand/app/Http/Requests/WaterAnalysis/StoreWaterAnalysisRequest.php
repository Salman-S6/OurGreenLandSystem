<?php

namespace Modules\FarmLand\Http\Requests\WaterAnalysis;

use App\Traits\RequestTrait;
use Illuminate\Foundation\Http\FormRequest;

class StoreWaterAnalysisRequest extends FormRequest
{
    use RequestTrait;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'land_id' => 'required|exists:lands,id',
            'performed_by' => 'required|exists:users,id',
            'sample_date' => 'required|date',
            'ph_level' => 'required|numeric|between:0,14',
            'salinity_level' => 'required|numeric',
            'water_quality' => 'nullable|string|max:255',
            'suitability' => 'required|in:suitable,limited,unsuitable',

            'contaminants' => 'nullable|array',
            'contaminants.ar' => 'nullable|string|min:10|max:255',
            'contaminants.en' => 'nullable|string|min:10|max:255',

            'recommendations' => 'nullable|array',
            'recommendations.ar' => 'nullable|string|max:255',
            'recommendations.en' => 'nullable|string|max:255',
        ];
    }
}
