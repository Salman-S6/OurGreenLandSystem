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
        return $user->hasRole(UserRoles::AgriculturalEngineer) || $user->hasRole(UserRoles::SuperAdmin);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'growth_stage_id' => 'required|exists:crop_growth_stages,id',
            'material' => 'required|array',
            'material.en' => 'required|string|max:255',
            'material.ar' => 'required|string|max:255',
            'quantity' => 'required|numeric|min:1',
            'practice_type' => 'required|in:irrigation,fertilization,pest-control',
            'application_date' => 'required|date',
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
            'growth_stage_id.required' => 'The growth stage is required.',
            'growth_stage_id.exists' => 'The selected growth stage does not exist.',
            'material.required' => 'The material is required.',
            'material.en.required' => 'The English material name is required.',
            'material.en.string' => 'The English material name must be a string.',
            'material.en.max' => 'The English material name cannot exceed 255 characters.',
            'material.ar.required' => 'The Arabic material name is required.',
            'material.ar.string' => 'The Arabic material name must be a string.',
            'material.ar.max' => 'The Arabic material name cannot exceed 255 characters.',
            'quantity.required' => 'The quantity is required.',
            'quantity.numeric' => 'The quantity must be a number.',
            'quantity.min' => 'The quantity must be at least 1.',
            'practice_type.required' => 'The practice type is required.',
            'practice_type.in' => 'The practice type must be one of: irrigation, fertilization, pest-control.',
            'application_date.required' => 'The application date is required.',
            'application_date.date' => 'The application date must be a valid date.',
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
            'growth_stage_id' => 'growth stage',
            'material' => 'material',
            'material.en' => 'English material name',
            'material.ar' => 'Arabic material name',
            'quantity' => 'quantity',
            'practice_type' => 'practice type',
            'application_date' => 'application date',
            'notes' => 'notes',
            'notes.en' => 'English notes',
            'notes.ar' => 'Arabic notes',
        ];
    }
}
