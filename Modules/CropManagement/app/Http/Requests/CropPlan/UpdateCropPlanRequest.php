<?php

namespace Modules\CropManagement\Http\Requests\CropPlan;

use App\Traits\RequestTrait;
use Illuminate\Foundation\Http\FormRequest;

use Modules\CropManagement\Models\CropPlan;

class UpdateCropPlanRequest extends FormRequest
{
    use RequestTrait;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();
        $cropPlan = $this->route('cropPlan');
        if ($user->hasRole('SuperAdmin')) {
            return true;
        }
        if ($user->hasRole('AgriculturalEngineer') && $cropPlan->planned_by === $user->id) {
            return true;
        }

        return false;
    }


    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'crop_id' => ['nullable', 'exists:crops,id'],
            'land_id' => ['nullable', 'exists:lands,id'],
            'planned_planting_date' => ['nullable', 'date'],
            'planned_harvest_date' => ['nullable', 'date', 'after_or_equal:planned_planting_date'],

            'seed_type' => ['nullable', 'array'],
            'seed_type.en' => ['nullable', 'string', 'min:2'],
            'seed_type.ar' => ['nullable', 'string', 'min:2'],

            'seed_quantity' => ['nullable', 'numeric', 'min:0.01'],
            'seed_expiry_date' => ['nullable', 'date', 'after:' . now()->addYears(2)->toDateString()],

            'area_size' => ['nullable', 'numeric', 'min:0.01'],

            'actual_planting_date' => ['nullable', 'date'],
            'actual_harvest_date' => ['nullable', 'date', 'after_or_equal:actual_planting_date'],
        ];
    }

    /**
     * Custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'crop_id.exists' => 'The selected crop does not exist.',
            'land_id.exists' => 'The selected land does not exist.',
            'planned_planting_date.date' => 'The planned planting date must be a valid date.',
            'planned_harvest_date.after_or_equal' => 'The harvest date must be after or equal to planting date.',
            'seed_type.array' => 'Seed type must be an array.',
            'seed_type.en.min' => 'Seed type (English) must be at least :min characters.',
            'seed_type.ar.min' => 'Seed type (Arabic) must be at least :min characters.',
            'seed_quantity.numeric' => 'Seed quantity must be numeric.',
            'seed_expiry_date.after' => 'Seed expiry date must be at least 2 years from today.',
            'area_size.numeric' => 'Area size must be numeric.',
            'actual_harvest_date.after_or_equal' => 'Harvest date must be after or equal to actual planting date.',
        ];
    }

    /**
     * Summary of attributes
     * @return array{actual_harvest_date: string, actual_planting_date: string, area_size: string, crop_id: string, land_id: string, planned_harvest_date: string, planned_planting_date: string, seed_expiry_date: string, seed_quantity: string, seed_type.ar: string, seed_type.en: string}
     */
    public function attributes(): array
    {
        return [
            'crop_id' => 'crop',
            'land_id' => 'land',
            'planned_planting_date' => 'planned planting date',
            'planned_harvest_date' => 'planned harvest date',

            'actual_planting_date' => 'actual planting date',
            'actual_harvest_date' => 'actual harvest date',

            'seed_type.en' => 'seed type (English)',
            'seed_type.ar' => 'seed type (Arabic)',
            'seed_quantity' => 'seed quantity',
            'seed_expiry_date' => 'seed expiry date',
            'area_size' => 'area size',
        ];
    }
}
