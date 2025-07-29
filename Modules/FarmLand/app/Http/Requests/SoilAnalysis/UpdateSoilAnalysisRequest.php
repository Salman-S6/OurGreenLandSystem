<?php

namespace Modules\FarmLand\Http\Requests\SoilAnalysis;

use App\Traits\RequestTrait;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Modules\FarmLand\Enums\SoilAnalysesFertilityLevel;

class UpdateSoilAnalysisRequest extends FormRequest
{
    use RequestTrait;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
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
            'land_id' => 'sometimes|exists:lands,id',

            'sample_date' => 'sometimes|date|before_or_equal:today',

            'ph_level' => 'sometimes|numeric|between:0,14',
            'salinity_level' => 'sometimes|numeric|between:0,100',

            'fertility_level' => ['sometimes', Rule::enum(SoilAnalysesFertilityLevel::class)],

            'nutrient_content' => 'sometimes|nullable|array',
            'nutrient_content.ar' => 'nullable|string|max:255',
            'nutrient_content.en' => 'nullable|string|max:255',

            'contaminants' => 'sometimes|nullable|array',
            'contaminants.ar' => 'nullable|string|max:255',
            'contaminants.en' => 'nullable|string|max:255',

            'recommendations' => 'sometimes|nullable|array',
            'recommendations.ar' => 'nullable|string|max:500',
            'recommendations.en' => 'nullable|string|max:500',
        ];
    }
}
