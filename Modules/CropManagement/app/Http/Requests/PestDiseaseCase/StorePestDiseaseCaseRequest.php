<?php

namespace Modules\CropManagement\Http\Requests\PestDiseaseCase;

use App\Enums\UserRoles;
use App\Traits\RequestTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Modules\CropManagement\Models\CropGrowthStage;
use Modules\CropManagement\Models\CropPlan;

class StorePestDiseaseCaseRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'crop_growth_id' => ['required', 'exists:crop_growth_stages,id'],
            'case_type' => ['required', 'in:pest,disease'],
            'case_name' => ['required', 'array'],
            'case_name.*' => ['required', 'string', 'min:5', 'max:255'],
            'severity' => ['required', 'in:high,medium,low'],
            'description' => ['required', 'array'],
            'description.*' => ['required', 'string', 'min:5', 'max:255'],
            'discovery_date' => ['required', 'date'],
            'location_details' => ['required', 'array'],
            'location_details.*' => ['required', 'string', 'min:5', 'max:255'],
        ];
    }

    /**
     * Custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'crop_growth_id.required' => 'crop_growth_stages ID is required.',
            'crop_growth_id.exists' => 'The selected crop_growth_stages does not exist.',
            'case_type.required' => 'Case type (pest or disease) is required.',
            'case_type.in' => 'Case type must be either "pest" or "disease".',
            'case_name.required' => 'Case name is required.',
            'case_name.array' => 'Case name must be an array for translations.',
            'case_name.*.required' => 'All translations of case name are required.',
            'case_name.*.string' => 'All translations of case name must be strings.',
            'case_name.*.min' => 'Each translation of the case name must be at least 5 characters.',
            'case_name.*.max' => 'Each translation of the case name must not exceed 255 characters.',
            'severity.required' => 'Severity is required.',
            'severity.in' => 'Severity must be one of: high, medium, low.',
            'description.required' => 'Description is required.',
            'description.array' => 'Description must be an array for translations.',
            'description.*.required' => 'All translations of description are required.',
            'description.*.string' => 'All translations of description must be strings.',
            'description.*.min' => 'Each translation of the description must be at least 5 characters.',
            'description.*.max' => 'Each translation of the description must not exceed 255 characters.',
            'discovery_date.required' => 'Discovery date is required.',
            'discovery_date.date' => 'Discovery date must be a valid date.',
            'location_details.required' => 'Location details are required.',
            'location_details.array' => 'Location details must be an array for translations.',
            'location_details.*.required' => 'All translations of location details are required.',
            'location_details.*.string' => 'All translations of location details must be strings.',
            'location_details.*.min' => 'Each translation of the location details must be at least 5 characters.',
            'location_details.*.max' => 'Each translation of the location details must not exceed 255 characters.',
        ];
    }

    /**
     * Custom attribute names for validator errors.
     */
    public function attributes(): array
    {
        return [
            'crop_growth_id' => 'crop_growth_stages Id',
            'case_type' => 'Case Type',
            'case_name' => 'Case Name',
            'case_name.*' => 'Translation',
            'severity' => 'Severity',
            'description' => 'Description',
            'description.*' => 'Translation',
            'discovery_date' => 'Discovery Date',
            'location_details' => 'Location Details',
            'location_details.*' => 'Translation',
        ];
    }



    /**
     * Summary of withValidator
     * @param mixed $validator
     * @return void
     */
    public function withValidator($validator): void
    {
        $validator->after(function (Validator $validator) {
            $data = $this->validated();

            if (!isset($data['crop_growth_id'], $data['discovery_date'])) {
                return;
            }

            $cropGrowthStage = CropGrowthStage::find($data['crop_growth_id']);

            if (!$cropGrowthStage) {
                $validator->errors()->add('crop_growth_id', 'The selected crop growth stage does not exist.');
                return;
            }

            $discoveryDate = \Carbon\Carbon::parse($data['discovery_date']);
            $startDate = \Carbon\Carbon::parse($cropGrowthStage->start_date);
            $endDate = $cropGrowthStage->end_date ? \Carbon\Carbon::parse($cropGrowthStage->end_date) : null;

            if ($discoveryDate->lt($startDate) || ($endDate && $discoveryDate->gt($endDate))) {
                $validator->errors()->add(
                    'discovery_date',
                    'The discovery date must be between the growth stage start (' . $startDate->format('Y-m-d') .
                        ') and end (' . ($endDate ? $endDate->format('Y-m-d') : 'N/A') . ') dates.'
                );
            }

            $cropPlan = $cropGrowthStage->cropPlan;

            if (
                $cropPlan &&
                $cropPlan->actual_planting_date &&
                \Carbon\Carbon::parse($cropPlan->actual_planting_date)->gt($discoveryDate)
            ) {
                $validator->errors()->add(
                    'discovery_date',
                    'The discovery date must be after or equal to the crop actual planting date (' .
                        \Carbon\Carbon::parse($cropPlan->actual_planting_date)->format('Y-m-d') . ').'
                );
            }
        });
    }
}
