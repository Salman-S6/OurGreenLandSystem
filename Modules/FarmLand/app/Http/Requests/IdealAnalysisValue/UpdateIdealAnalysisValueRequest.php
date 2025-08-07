<?php

namespace Modules\FarmLand\Http\Requests\IdealAnalysisValue;

use App\Traits\RequestTrait;
use Illuminate\Validation\Rule;
use Modules\FarmLand\Enums\AnalysisType;
use Illuminate\Foundation\Http\FormRequest;
use Modules\FarmLand\Enums\SoilAnalysesFertilityLevel;

class UpdateIdealAnalysisValueRequest extends FormRequest
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
            'type' => ['sometimes', Rule::enum(AnalysisType::class)],
            'crop_id' => 'sometimes|exists:crops,id',

            'ph_min' => 'sometimes|numeric|lt:ph_max|between:0,14',
            'ph_max' => 'sometimes|numeric|gt:ph_min|between:0,14',

            'salinity_min' => 'sometimes|numeric|lt:salinity_max|between:0,100',
            'salinity_max' => 'sometimes|numeric|gt:salinity_min|between:0,100',

            'fertility_level' => ['sometimes', 'nullable', Rule::enum(SoilAnalysesFertilityLevel::class)],
            'water_quality' => 'sometimes|nullable|string|max:50',

            'notes' => 'sometimes|nullable|array',
            'notes.en' => 'nullable|string|max:255',
            'notes.ar' => 'nullable|string|max:255',
        ];
    }
}
