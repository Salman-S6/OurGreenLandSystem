<?php

namespace Modules\CropManagement\Http\Requests\ProductionEstimation;

use App\Enums\UserRoles;
use App\Traits\RequestTrait;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductionEstimationRequest extends FormRequest
{
    use RequestTrait;

    /**
     * Summary of authorize
     * @return bool
     */
    public function authorize(): bool
    {
        $user = $this->user();
        return $user->hasRole(UserRoles::AgriculturalEngineer) ||
            $user->hasRole(UserRoles::SuperAdmin);
    }

    /**
     * Summary of rules
     * @return array{crop_plan_id: string[], estimation_method: string[], estimation_method.*: string[], expected_quantity: string[]}
     */
    public function rules(): array
    {
        return [
            'crop_plan_id' => ['required', 'exists:crop_plans,id'],
            'expected_quantity' => ['required', 'numeric', 'min:0'],
            'estimation_method' => ['required', 'array'],
            'estimation_method.*' => ['string', 'min:2','max:255'],
        ];
    }
    /**
     * Summary of messages
     * @return array{crop_plan_id.exists: string, crop_plan_id.required: string, estimation_method.*.min: string, estimation_method.*.string: string, estimation_method.array: string, estimation_method.required: string, expected_quantity.min: string, expected_quantity.numeric: string, expected_quantity.required: string}
     */
    public function messages(): array
    {
        return [
            'crop_plan_id.required' => 'The crop plan is required.',
            'crop_plan_id.exists'   => 'The selected crop plan does not exist.',
            'expected_quantity.required' => 'The expected quantity is required.',
            'expected_quantity.numeric'  => 'The expected quantity must be a number.',
            'expected_quantity.min'      => 'The expected quantity must be at least 0.',

            'estimation_method.required' => 'The estimation method is required.',
            'estimation_method.array'    => 'The estimation method must be an array.',
            'estimation_method.*.string' => 'Each estimation method value must be a string.',
            'estimation_method.*.min'    => 'Each estimation method value must be at least 2 characters.',
            'estimation_method.*.max' => 'Each translation of the estimation method must not exceed 255 characters.',
        ];
    }
    /**
     * Summary of attributes
     * @return array{crop_plan_id: string, estimation_method: string, estimation_method.*: string, expected_quantity: string}
     */
    public function attributes(): array
    {
        return [
            'crop_plan_id' => 'crop plan',
            'expected_quantity' => 'expected quantity',
            'estimation_method' => 'estimation method',
            'estimation_method.*' => 'estimation method value',
        ];
    }
}
