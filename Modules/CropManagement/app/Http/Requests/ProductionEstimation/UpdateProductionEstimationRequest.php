<?php

namespace Modules\CropManagement\Http\Requests\ProductionEstimation;

use App\Enums\UserRoles;
use App\Traits\RequestTrait;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProductionEstimationRequest extends FormRequest
{
    use RequestTrait;

    /**
     * Summary of authorize
     * @return bool
     */
    public function authorize(): bool
    {
        $productionEstimation = $this->route('productionEstimation');
        return (
            ($this->user()->hasRole(UserRoles::AgriculturalAlert) &&
                $this->user()->id === $productionEstimation->reported_by)
            || $this->user()->hasRole(UserRoles::SuperAdmin)
        );
    }

    /**
     * Summary of rules
     * @return array{actual_quantity: string[], crop_quality: string[], estimation_method: string[], estimation_method.*: string[], expected_quantity: string[], notes: string[], notes.*: string[]}
     */
    public function rules(): array
    {
        return [
            'expected_quantity' => ['sometimes', 'required', 'numeric', 'min:0'],
            'estimation_method' => ['sometimes', 'required', 'array'],
            'estimation_method.*' => ['string', 'min:2'],

            'actual_quantity' => ['sometimes', 'required', 'numeric', 'min:0'],
            'crop_quality' => ['sometimes', 'required', 'in:excellent,average,poor'],
            'notes' => ['sometimes', 'required', 'array'],
            'notes.*' => ['string', 'min:2'],
        ];
    }

    /**
     * Summary of messages
     * @return array{actual_quantity.min: string, actual_quantity.numeric: string, actual_quantity.required: string, crop_quality.in: string, crop_quality.required: string, estimation_method.*.min: string, estimation_method.*.string: string, estimation_method.array: string, estimation_method.required: string, expected_quantity.min: string, expected_quantity.numeric: string, expected_quantity.required: string, notes.*.min: string, notes.*.string: string, notes.array: string, notes.required: string}
     */
    public function messages(): array
    {
        return [
            'expected_quantity.required' => 'The expected quantity is required.',
            'expected_quantity.numeric'  => 'The expected quantity must be a number.',
            'expected_quantity.min'      => 'The expected quantity must be at least 0.',

            'estimation_method.required' => 'The estimation method is required.',
            'estimation_method.array'    => 'The estimation method must be an array.',
            'estimation_method.*.string' => 'Each estimation method value must be a string.',
            'estimation_method.*.min'    => 'Each estimation method value must be at least 2 characters.',

            'actual_quantity.required' => 'The actual quantity is required.',
            'actual_quantity.numeric'  => 'The actual quantity must be a number.',
            'actual_quantity.min'      => 'The actual quantity must be at least 0.',

            'crop_quality.required' => 'The crop quality is required.',
            'crop_quality.in'       => 'The crop quality must be one of: excellent, average, or poor.',

            'notes.required' => 'The notes field is required.',
            'notes.array'    => 'The notes must be an array.',
            'notes.*.string' => 'Each note must be a string.',
            'notes.*.min'    => 'Each note must be at least 2 characters.',
        ];
    }

    /**
     * Summary of attributes
     * @return array{actual_quantity: string, crop_quality: string, estimation_method: string, estimation_method.*: string, expected_quantity: string, notes: string, notes.*: string}
     */
    public function attributes(): array
    {
        return [
            'expected_quantity' => 'expected quantity',
            'estimation_method' => 'estimation method',
            'estimation_method.*' => 'estimation method value',
            'actual_quantity' => 'actual quantity',
            'crop_quality' => 'crop quality',
            'notes' => 'notes',
            'notes.*' => 'note value',
        ];
    }
    /**
     * Summary of withValidator
     * @param mixed $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $plan = $this->route('productionEstimation')->cropPlan;

            if ($plan->status === 'completed') {
                if ($this->has('expected_quantity') || $this->has('estimation_method')) {
                    $validator->errors()->add('expected_quantity', 'Cannot update expected quantity for a completed plan.');
                    $validator->errors()->add('estimation_method', 'Cannot update estimation method for a completed plan.');
                }
            }

            if ($plan->status === 'in-progress') {
                if ($this->has('actual_quantity') || $this->has('crop_quality') || $this->has('notes')) {
                    $validator->errors()->add('actual_quantity', 'Cannot update actual data for a plan in progress.');
                }
            }
        });
    }
}
