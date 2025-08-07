<?php

namespace Modules\FarmLand\Http\Requests\IdealAnalysisValue;

use App\Traits\RequestTrait;
use Illuminate\Validation\Rule;
use Modules\FarmLand\Enums\AnalysisType;
use Illuminate\Foundation\Http\FormRequest;
use Modules\FarmLand\Enums\SoilAnalysesFertilityLevel;

class StoreIdealAnalysisValueRequest extends FormRequest
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
            'type' => ['required', Rule::enum(AnalysisType::class)],
            'crop_id' => 'required|exists:crops,id',

            'ph_min' => 'required|numeric|lt:ph_max|between:0,14',
            'ph_max' => 'required|numeric|gt:ph_min|between:0,14',

            'salinity_min' => 'required|numeric|lt:salinity_max|between:0,100',
            'salinity_max' => 'required|numeric|gt:salinity_min|between:0,100',

            'fertility_level' => ['nullable', Rule::enum(SoilAnalysesFertilityLevel::class)],
            'water_quality' => 'nullable|string|max:50',

            'notes' => 'nullable|array',
            'notes.en' => 'nullable|string|max:255',
            'notes.ar' => 'nullable|string|max:255',
        ];
    }
}
