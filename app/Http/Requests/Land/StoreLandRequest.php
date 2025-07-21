<?php

namespace App\Http\Requests\Land;

use App\Helpers\ApiResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreLandRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'farmer_id' => 'required|exists:users,id',
            'area' => 'nullable|numeric|min:0',
            'soil_type_id' => 'required|exists:soils,id',
            'damage_level' => 'nullable|in:low,medium,high',
            'gps_coordinates' => 'nullable|array',
            'gps_coordinates.*' => 'numeric',
            'boundary_coordinates' => 'nullable|array',
            'boundary_coordinates.*' => 'array', // [[lat,lng],[lat,lng]]
            'rehabilitation_date' => 'required|date|before_or_equal:today',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            ApiResponse::error(
                'data validation failed',
                422,
                [
                    "errors" => $validator->errors()
                ]
            )
        );
    }

    protected function failedAuthorization()
    {
        throw new HttpResponseException(
            ApiResponse::error(
                'Unauthorized Action.',
                401,
            )
        );
    }
}
