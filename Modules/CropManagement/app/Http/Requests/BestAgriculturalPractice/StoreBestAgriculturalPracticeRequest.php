<?php

namespace Modules\CropManagement\Http\Requests\BestAgriculturalPractice;

use App\Enums\UserRoles;
use App\Traits\RequestTrait;
use Illuminate\Foundation\Http\FormRequest;

class StoreBestAgriculturalPracticeRequest extends FormRequest
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

            'growth_stage_id' => 'required|exists:crop_growth_stages,id',
            'material' => 'required|array',
            'quantity' => 'required|numeric|min:1',
            'practice_type' => 'required|in:irrigation,fertilization,pest-control',
            'material.en' => 'required|string|max:255',
            'material.ar' => 'required|string|max:255',
            'application_date' => 'required|date',
            'notes' => 'nullable|array',
            'notes.en' => 'nullable|string|max:1000',
            'notes.ar' => 'nullable|string|max:1000',
        ];
    }
}
