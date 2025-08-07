<?php

namespace Modules\CropManagement\Http\Requests\BestAgriculturalPractice;

use App\Enums\UserRoles;
use App\Traits\RequestTrait;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBestAgriculturalPracticeRequest extends FormRequest
{
    use RequestTrait;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();
        $bestAgriculturalPractice = $this->route('bestAgriculturalPractice');
        if ($user->hasRole(UserRoles::SuperAdmin)) {
            return true;
        }
        if ($user->hasRole(UserRoles::AgriculturalAlert) && $bestAgriculturalPractice->expert_id === $user->id) {
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
            'growth_stage_id' => 'sometimes|exists:crop_growth_stages,id',
            'material' => 'sometimes|array',
            'quantity' => 'sometimes|numeric|min:1',
            'practice_type' => 'sometimes|in:irrigation,fertilization,pest-control',
            'material.en' => 'sometimes|string|max:255',
            'material.ar' => 'sometimes|string|max:255',
            'application_date' => 'sometimes|date',
            'notes' => 'nullable|array',
            'notes.en' => 'nullable|string|max:1000',
            'notes.ar' => 'nullable|string|max:1000',
        ];
    }
}
