<?php

namespace Modules\CropManagement\Http\Requests\CropPlan;

use App\Enums\UserRoles;
use App\Traits\RequestTrait;
use Illuminate\Foundation\Http\FormRequest;


class StoreCropPlanRequest extends FormRequest
{
    use RequestTrait;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();
        return $user->hasRole(UserRoles::AgriculturalEngineer) ||
            $user->hasRole(UserRoles::SuperAdmin);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'crop_id' => ['required', 'exists:crops,id'],
            'land_id' => ['required', 'exists:lands,id'],
            'planned_planting_date' => ['required', 'date'],
            'planned_harvest_date' => ['required', 'date', 'after_or_equal:planned_planting_date'],

            'seed_type' => ['required', 'array'],
            'seed_type.en' => ['required', 'string', 'min:2','max:255'],
            'seed_type.ar' => ['required', 'string', 'min:2','max:255'],

            'seed_quantity' => ['required', 'numeric', 'min:0.01'],
            'seed_expiry_date' => ['required', 'date', 'after:' . now()->addYears(2)->toDateString()],

            'area_size' => ['required', 'numeric', 'min:0.01'],
        ];
    }

    /**
     * Custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'crop_id.required' => 'The crop field is required.',
            'crop_id.exists' => 'The selected crop does not exist.',

            'land_id.required' => 'The land field is required.',
            'land_id.exists' => 'The selected land does not exist.',

            'planned_planting_date.required' => 'The planned planting date is required.',
            'planned_planting_date.date' => 'The planned planting date must be a valid date.',

            'planned_harvest_date.required' => 'The planned harvest date is required.',
            'planned_harvest_date.date' => 'The planned harvest date must be a valid date.',
            'planned_harvest_date.after_or_equal' => 'The planned harvest date must be after or equal to the planting date.',

            'seed_type.array' => 'The seed type must be an array.',
            'seed_type.en.required' => 'The seed type (English) is required.',
            'seed_type.en.string' => 'The seed type (English) must be a string.',
            'seed_type.en.min' => 'The seed type (English) must be at least :min characters.',
            'seed_type.ar.required' => 'The seed type (Arabic) is required.',
            'seed_type.ar.string' => 'The seed type (Arabic) must be a string.',
            'seed_type.ar.min' => 'The seed type (Arabic) must be at least :min characters.',
            'seed_type.en.max'      => 'The seed type (English) must not exceed 255 characters.',
            'seed_type.ar.max'      => 'The seed type (Arabic) must not exceed 255 characters.',

            'seed_quantity.required' => 'The seed quantity is required.',
            'seed_quantity.numeric' => 'The seed quantity must be a number.',
            'seed_quantity.min' => 'The seed quantity must be greater than 0.',

            'seed_expiry_date.required' => 'The seed expiry date is required.',
            'seed_expiry_date.date' => 'The seed expiry date must be a valid date.',
            'seed_expiry_date.after' => 'The seed expiry date must be at least 2 years from today.',

            'area_size.required' => 'The area size is required.',
            'area_size.numeric' => 'The area size must be a number.',
            'area_size.min' => 'The area size must be greater than 0.',
        ];
    }


    /**
     * Custom attribute names.
     */
    public function attributes(): array
    {
        return [
            'crop_id' => 'crop',
            'land_id' => 'land',
            'planned_planting_date' => 'planned planting date',
            'planned_harvest_date' => 'planned harvest date',
            'seed_type.en' => 'seed type (English)',
            'seed_type.ar' => 'seed type (Arabic)',
            'seed_quantity' => 'seed quantity',
            'seed_expiry_date' => 'seed expiry date',
            'area_size' => 'area size',
        ];
    }
}
