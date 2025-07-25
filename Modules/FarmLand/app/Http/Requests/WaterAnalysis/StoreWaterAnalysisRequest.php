<?php

namespace Modules\FarmLand\Http\Requests\WaterAnalysis;

use App\Traits\RequestTrait;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Modules\FarmLand\Rules\SampleDateWithinCropPlan;
use Modules\FarmLand\Enums\WaterAnalysesSuitability;

class StoreWaterAnalysisRequest extends FormRequest
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
            'land_id' => 'required|exists:lands,id',
            'performed_by' => 'required|exists:users,id',

            'sample_date' => [
                'required',
                'date',
                'before_or_equal:today',
                new SampleDateWithinCropPlan($this->input('land_id')),
            ],

            'ph_level' => 'required|numeric|between:0,14',
            'salinity_level' => 'required|numeric|between:0,100',

            'water_quality' => 'nullable|string|max:255',
            'suitability' => ['required', Rule::enum(WaterAnalysesSuitability::class)],

            'contaminants' => 'nullable|array',
            'contaminants.ar' => 'nullable|string|min:10|max:255',
            'contaminants.en' => 'nullable|string|min:10|max:255',

            'recommendations' => 'nullable|array',
            'recommendations.ar' => 'nullable|string|max:500',
            'recommendations.en' => 'nullable|string|max:500',
        ];
    }
}
