<?php

namespace App\Traits;

use App\Http\Responses\ApiResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

trait RequestTrait
{
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            ApiResponse::error(
                "data validation failed",
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
                "Unauthorized Action.",
                401,
            )
        );
    }
}
