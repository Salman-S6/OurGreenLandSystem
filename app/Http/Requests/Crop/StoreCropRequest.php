<?php

namespace App\Http\Requests\Crop;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
class StoreCropRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = Auth::user();
        return $user->hasRole('Farmer') || $user->hasRole('SuperAdmin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required','unique:crops,name', 'string', 'max:255', 'min:5', 'regex:/^[\p{L}\s]+$/u'],
            'description' => ['required', 'string', 'max:255', 'min:30'],
        ];
    }

    /**
     * Custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [

            'name.unique'=>' The crop name must be unique.',
            'name.required' => 'The crop name is required.',
            'name.string' => 'The crop name must be a string.',
            'name.min' => 'The crop name must be at least :min characters.',
            'name.max' => 'The crop name must not exceed :max characters.',
            'name.regex' => 'The crop name may only contain letters and spaces. No numbers or symbols are allowed.',

            'description.required' => 'The crop description is required.',
            'description.string' => 'The crop description must be a string.',
            'description.min' => 'The crop description must be at least :min characters.',
            'description.max' => 'The crop description must not exceed :max characters.',
        ];
    }

    /**
     * Custom attribute names.
     */
    public function attributes(): array
    {
        return [
            'name' => 'crop name',
            'description' => 'crop description',
        ];
    }


    /**
     * Summary of failedValidation
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     * @return never
     */
    public  function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => false,
            'message' => 'Validation errors occurred.',
            'errors' => $validator->errors()
        ], 422));
    }


}
