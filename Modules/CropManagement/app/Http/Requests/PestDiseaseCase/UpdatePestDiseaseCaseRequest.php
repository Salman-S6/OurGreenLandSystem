<?php

namespace Modules\CropManagement\Http\Requests\PestDiseaseCase;

use App\Enums\UserRoles;
use App\Traits\RequestTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Modules\CropManagement\Models\CropGrowthStage;

class UpdatePestDiseaseCaseRequest extends FormRequest
{
    use RequestTrait;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();
        $pestDiseaseCase = $this->route('pestDiseaseCase');
        return $user->hasRole(UserRoles::AgriculturalAlert) &&
            $pestDiseaseCase->reported_by === $user->id;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'case_type' => ['sometimes', 'in:pest,disease'],
            'case_name' => ['sometimes', 'array'],
            'case_name.*' => ['required_with:case_name', 'string'],
            'severity' => ['sometimes', 'in:high,medium,low'],
            'description' => ['sometimes', 'array'],
            'description.*' => ['required_with:description', 'string'],
            'discovery_date' => ['sometimes', 'date'],
            'location_details' => ['sometimes', 'array'],
            'location_details.*' => ['required_with:location_details', 'string'],
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'case_type.in' => 'The case type must be either pest or disease.',
            'case_name.array' => 'The case name must be an array of translations.',
            'case_name.*.required_with' => 'Each case name translation is required when case name is present.',
            'case_name.*.string' => 'Each case name translation must be a string.',
            'severity.in' => 'The severity must be high, medium, or low.',
            'description.array' => 'The description must be an array of translations.',
            'description.*.required_with' => 'Each description translation is required when description is present.',
            'description.*.string' => 'Each description translation must be a string.',
            'discovery_date.date' => 'The discovery date must be a valid date.',
            'location_details.array' => 'The location details must be an array of translations.',
            'location_details.*.required_with' => 'Each location detail translation is required when location details are present.',
            'location_details.*.string' => 'Each location detail translation must be a string.',
        ];
    }

    /**
     * Custom attribute names for validation errors.
     */
    public function attributes(): array
    {
        return [
            'case_type' => 'case type',
            'case_name' => 'case name',
            'case_name.*' => 'case name translation',
            'severity' => 'severity',
            'description' => 'description',
            'description.*' => 'description translation',
            'discovery_date' => 'discovery date',
            'location_details' => 'location details',
            'location_details.*' => 'location detail translation',
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
            $pestDiseaseCase = $this->route('pestDiseaseCase');
            if (!$pestDiseaseCase) {
                $validator->errors()->add('pestDiseaseCase', 'The pest disease case does not exist.');
                return;
            }
            $cropGrowthStage = $pestDiseaseCase->cropGrowthStage;
            if (!$cropGrowthStage) {
                $validator->errors()->add('crop_growth_id', 'The selected crop growth stage does not exist.');
                return;
            }
            $discoveryDateStr = $data['discovery_date'] ?? $pestDiseaseCase->discovery_date;

            try {
                $discoveryDate = \Carbon\Carbon::parse($discoveryDateStr);
            } catch (\Exception $e) {
                $validator->errors()->add('discovery_date', 'The discovery date is not a valid date.');
                return;
            }
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
