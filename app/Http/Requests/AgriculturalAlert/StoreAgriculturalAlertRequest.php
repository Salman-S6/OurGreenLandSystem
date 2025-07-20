<?php

namespace App\Http\Requests\AgriculturalAlert;

use App\Helpers\ApiResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreAgriculturalAlertRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
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
            //
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
