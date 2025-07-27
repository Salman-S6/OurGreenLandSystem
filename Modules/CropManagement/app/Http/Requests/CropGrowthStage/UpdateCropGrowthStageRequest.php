<?php

namespace Modules\CropManagement\Http\Requests\CropGrowthStage;

use App\Enums\UserRoles;
use App\Traits\RequestTrait;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCropGrowthStageRequest extends FormRequest
{
    use RequestTrait;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        
        $user = $this->user();
        $cropGrowthStage = $this->route('cropPlan');
        if ($user->hasRole(UserRoles::SuperAdmin)) {
            return true;
        }
        if ($user->hasRole(UserRoles::AgriculturalAlert) && $cropGrowthStage->recorded_by === $user->id) {
            return true;
        }

        return false;
        }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'crop_plan_id' => 'sometimes|exists:crop_plans,id',
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

}
