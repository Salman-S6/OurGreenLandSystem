<?php

namespace Modules\CropManagement\Http\Requests\PestDiseaseRecommendation;

use App\Enums\UserRoles;
use App\Traits\RequestTrait;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePestDiseaseRecommendationRequest extends FormRequest
{
    use RequestTrait;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();
        $pestDiseaseRecommendationResource = $this->route('pestDiseaseRecommendationResource');
        if ($user->hasRole(UserRoles::SuperAdmin)) {
            return true;
        }
        if ($user->hasRole(UserRoles::AgriculturalEngineer) && $pestDiseaseRecommendationResource->recommended_by === $user->id) {
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
            'pest_disease_case_id' => ['sometimes', 'exists:pest_disease_cases,id'],
            'recommended_dose' => ['sometimes', 'string', 'min:3', 'max:255'],
            'safety_notes' => ['sometimes', 'array'],
            'safety_notes.en' => ['sometimes', 'string', 'min:3', 'max:255'],
            'safety_notes.ar' => ['sometimes', 'string', 'min:3', 'max:255'],
            'recommendation_name' => ['sometimes', 'array'],
            'recommendation_name.en' => ['sometimes', 'string', 'min:10', 'max:255'],
            'recommendation_name.ar' => ['sometimes', 'string', 'min:10', 'max:255'],
            'application_method' => ['sometimes', 'array'],
            'application_method.en' => ['sometimes', 'string', 'min:10', 'max:255'],
            'application_method.ar' => ['sometimes', 'string', 'min:10', 'max:255'],
        ];
    }

    /**
     * Custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'pest_disease_case_id.exists' => 'The selected pest/disease case does not exist.',
            'recommended_dose.min' => 'The recommended dose must be at least 3 characters.',
            'recommended_dose.max' => 'The recommended dose cannot exceed 255 characters.',
            'safety_notes.en.min' => 'English safety notes must be at least 3 characters.',
            'safety_notes.en.max' => 'English safety notes cannot exceed 255 characters.',
            'safety_notes.ar.min' => 'Arabic safety notes must be at least 3 characters.',
            'safety_notes.ar.max' => 'Arabic safety notes cannot exceed 255 characters.',
            'recommendation_name.en.min' => 'English recommendation name must be at least 10 characters.',
            'recommendation_name.en.max' => 'English recommendation name cannot exceed 255 characters.',
            'recommendation_name.ar.min' => 'Arabic recommendation name must be at least 10 characters.',
            'recommendation_name.ar.max' => 'Arabic recommendation name cannot exceed 255 characters.',
            'application_method.en.min' => 'English application method must be at least 10 characters.',
            'application_method.en.max' => 'English application method cannot exceed 255 characters.',
            'application_method.ar.min' => 'Arabic application method must be at least 10 characters.',
            'application_method.ar.max' => 'Arabic application method cannot exceed 255 characters.',
        ];
    }

    /**
     * Custom attribute names.
     */
    public function attributes(): array
    {
        return [
            'pest_disease_case_id' => 'pest/disease case',
            'recommended_dose' => 'recommended dose',
            'safety_notes' => 'safety notes',
            'safety_notes.en' => 'English safety notes',
            'safety_notes.ar' => 'Arabic safety notes',
            'recommendation_name' => 'recommendation name',
            'recommendation_name.en' => 'English recommendation name',
            'recommendation_name.ar' => 'Arabic recommendation name',
            'application_method' => 'application method',
            'application_method.en' => 'English application method',
            'application_method.ar' => 'Arabic application method',
        ];
    }
}
