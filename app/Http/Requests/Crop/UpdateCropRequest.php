<?php

namespace App\Http\Requests\Crop;

use App\Traits\RequestTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateCropRequest extends FormRequest
{
<<<<<<< HEAD
    use RequestTrait;
=======
   
>>>>>>> crop-management
    /**
     * Summary of authorize
     * @return bool
     */
    public function authorize(): bool
    {
         
        $crop = $this->route('crop');
        $user = Auth::user();

        if ($user->hasRole('SuperAdmin')) {
            return true;
        }

        if ($user->hasRole('Farmer') && $crop && $user->id === optional($crop)->farmer_id) {
            return true;
        }

        return false;
    }

    /**
     * Summary of rules
     * @return array{description: string[], name: array<string|\Illuminate\Validation\Rules\Unique>}
     */
    public function rules(): array
    {
        $crop = $this->route('crop');

        return [
            'name' => [
                'sometimes',
                'string',
                'max:255',
                'min:5',
                'regex:/^[\p{L}\s\-\']+$/u',
                Rule::unique('crops', 'name')->ignore(optional($crop)->id),
            ],
            'description' => ['sometimes', 'string', 'max:255', 'min:30'],
        ];
    }

<<<<<<< HEAD
=======
    /**
     * Summary of messages
     * @return array{description.max: string, description.min: string, description.string: string, name.max: string, name.min: string, name.regex: string, name.string: string, name.unique: string}
     */
    public function messages(): array
    {
        return [
            'name.string' => 'The :attribute must be a string.',
            'name.min' => 'The :attribute must be at least :min characters.',
            'name.max' => 'The :attribute must not exceed :max characters.',
            'name.regex' => 'The :attribute may only contain letters, spaces, hyphens, or apostrophes.',
            'name.unique' => 'The :attribute has already been taken.',

            'description.string' => 'The :attribute must be a string.',
            'description.min' => 'The :attribute must be at least :min characters.',
            'description.max' => 'The :attribute must not exceed :max characters.',
        ];
    }

    /**
     * Summary of attributes
     * @return array{description: string, name: string}
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
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => false,
            'message' => 'Validation errors occurred.',
            'errors' => $validator->errors()
        ], 422));
    }
>>>>>>> crop-management
}
