<?php

namespace Modules\CropManagement\Http\Requests\CropGrowthStage;

use App\Enums\UserRoles;
use App\Traits\RequestTrait;
use Illuminate\Foundation\Http\FormRequest;

class StoreCropGrowthStageRequest extends FormRequest
{
    use RequestTrait;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();
        return $user->hasRole(UserRoles::AgriculturalAlert) || $user->hasRole(UserRoles::SuperAdmin);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'crop_plan_id' => 'required|exists:crop_plans,id',
            'name' => 'required|array',
            'name.en' => 'required|string|max:255',
            'name.ar' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'notes' => 'nullable|array',
            'notes.en' => 'nullable|string|max:1000',
            'notes.ar' => 'nullable|string|max:1000',
        ];
    }
}
