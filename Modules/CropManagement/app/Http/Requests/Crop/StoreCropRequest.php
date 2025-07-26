<?php

namespace Modules\CropManagement\Http\Requests\Crop;

use App\Traits\RequestTrait;
use Illuminate\Foundation\Http\FormRequest;

use Modules\CropManagement\Models\Crop;

class StoreCropRequest extends FormRequest
{
    use RequestTrait;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user =  $this->user();
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
            'name' => ['required', 'array'],
            'name.en' => ['required', 'string', 'min:3', 'max:255'],
            'name.ar' => ['required', 'string', 'min:3', 'max:255'],

            'description' => ['required', 'array'],
            'description.en' => ['required', 'string', 'min:10', 'max:255'],
            'description.ar' => ['required', 'string', 'min:10', 'max:255'],
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
            $enName = $this->input('name.en');
            $arName = $this->input('name.ar');

            if ($enName && Crop::where('name->en', $enName)->exists()) {
                $validator->errors()->add('name.en', 'The English crop name has already been taken.');
            }

            if ($arName && Crop::where('name->ar', $arName)->exists()) {
                $validator->errors()->add('name.ar', 'The Arabic crop name has already been taken.');
            }
        });
    }




    /**
     * Custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [

            'name.required' => 'The name field is required.',
            'name.array' => 'The name must be a translatable array.',

            'name.en.required' => 'The English name is required.',
            'name.en.string' => 'The English name must be a string.',
            'name.en.min' => 'The English name must be at least :min characters.',
            'name.en.max' => 'The English name may not be greater than :max characters.',

            'name.ar.required' => 'The Arabic name is required.',
            'name.ar.string' => 'The Arabic name must be a string.',
            'name.ar.min' => 'The Arabic name must be at least :min characters.',
            'name.ar.max' => 'The Arabic name may not be greater than :max characters.',

            'description.required' => 'The description field is required.',
            'description.array' => 'The description must be a translatable array.',

            'description.en.required' => 'The English description is required.',
            'description.en.string' => 'The English description must be a string.',
            'description.en.min' => 'The English description must be at least :min characters.',
            'description.en.max' => 'The English description may not be greater than :max characters.',

            'description.ar.required' => 'The Arabic description is required.',
            'description.ar.string' => 'The Arabic description must be a string.',
            'description.ar.min' => 'The Arabic description must be at least :min characters.',
            'description.ar.max' => 'The Arabic description may not be greater than :max characters.',
        ];
    }

    /**
     * Custom attribute names.
     */
    public function attributes(): array
    {
        return [
            'name.en' => 'English crop name',
            'name.ar' => 'Arabic crop name',
            'description.en' => 'English crop description',
            'description.ar' => 'Arabic crop description',
        ];
    }
}
