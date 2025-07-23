<?php

namespace Modules\FarmLand\Http\Requests\SoilAnalysis;

use App\Traits\RequestTrait;
use Illuminate\Foundation\Http\FormRequest;

class StoreSoilAnalysisRequest extends FormRequest
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
            'salinity_level' => 'required|numeric|min:0',
            'fertility_level' => 'required|in:high,medium,low',

            'nutrient_content' => 'nullable|array',
            'nutrient_content.ar' => 'nullable|string',
            'nutrient_content.en' => 'nullable|string',

            'contaminants' => 'nullable|array',
            'contaminants.ar' => 'nullable|string',
            'contaminants.en' => 'nullable|string',

            'recommendations' => 'nullable|array',
            'recommendations.ar' => 'nullable|string',
            'recommendations.en' => 'nullable|string',
        ];
    }
}
