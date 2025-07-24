<?php

namespace App\Http\Requests\User;

use App\Models\User;
use App\Traits\RequestTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateUserRequest extends FormRequest
{
    use RequestTrait;
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
            "name" => "sometimes|string",
            "email"=> "sometimes|email|unique:users,email",
            "password"=> "sometimes|confirmed",
        ];
    }
}
