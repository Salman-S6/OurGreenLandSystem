<?php

namespace Modules\CropManagement\Http\Requests\CropGrowthStage;

use App\Enums\UserRoles;
use App\Traits\RequestTrait;
use Illuminate\Foundation\Http\FormRequest;
use Modules\CropManagement\Models\CropPlan;

class UpdateCropGrowthStageRequest extends FormRequest
{
    use RequestTrait;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();
        $cropGrowthStage = $this->route('cropGrowthStage');
        if ($user->hasRole(UserRoles::SuperAdmin)) {
            return true;
        }
        if ($user->hasRole(UserRoles::AgriculturalEngineer) && $cropGrowthStage->recorded_by === $user->id) {
            return true;
        }

        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'crop_plan_id' => [
                'sometimes',
                'exists:crop_plans,id',
                function ($attribute, $value, $fail) {
                    $cropPlan = CropPlan::find($value);
                    if ($cropPlan && $cropPlan->status !== 'in-progress') {
                        $fail('The crop plan must be in-progress to update a growth stage.');
                    }
                },
            ],
            'name' => 'sometimes|array',
            'name.en' => 'sometimes|string|max:255',
            'name.ar' => 'sometimes|string|max:255',
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date|after_or_equal:start_date',
            'notes' => 'nullable|array',
            'notes.en' => 'nullable|string|max:1000',
            'notes.ar' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'crop_plan_id.exists' => 'The selected crop plan does not exist.',
            'name.en.string' => 'The English growth stage name must be a string.',
            'name.en.max' => 'The English growth stage name cannot exceed 255 characters.',
            'name.ar.string' => 'The Arabic growth stage name must be a string.',
            'name.ar.max' => 'The Arabic growth stage name cannot exceed 255 characters.',
            'start_date.date' => 'The start date must be a valid date.',
            'end_date.date' => 'The end date must be a valid date.',
            'end_date.after_or_equal' => 'The end date must be on or after the start date.',
            'notes.array' => 'The notes must be provided as an array.',
            'notes.en.string' => 'The English notes must be a string.',
            'notes.en.max' => 'The English notes cannot exceed 1000 characters.',
            'notes.ar.string' => 'The Arabic notes must be a string.',
            'notes.ar.max' => 'The Arabic notes cannot exceed 1000 characters.',
        ];
    }

    /**
     * Custom attribute names.
     */
    public function attributes(): array
    {
        return [
            'crop_plan_id' => 'crop plan',
            'name' => 'growth stage name',
            'name.en' => 'English growth stage name',
            'name.ar' => 'Arabic growth stage name',
            'start_date' => 'start date',
            'end_date' => 'end date',
            'notes' => 'notes',
            'notes.en' => 'English notes',
            'notes.ar' => 'Arabic notes',
        ];
    }
}
