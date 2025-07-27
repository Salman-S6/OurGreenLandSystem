<?php

namespace Modules\CropManagement\Http\Requests\Crop;

use App\Traits\RequestTrait;
use Illuminate\Foundation\Http\FormRequest;

use Modules\CropManagement\Models\Crop;

class UpdateCropRequest extends FormRequest
{
    use RequestTrait;

    /**
     * Summary of authorize
     * @return bool
     */
    public function authorize(): bool
    {
        $crop = $this->route('crop');
        $user =  $this->user();

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
     * @return array{description: string[], description.ar: string[], description.en: string[], name.ar: string[], name.en: string[]}
     */
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'array'],
            'name.en' => [
                'sometimes',
                'string',
                'max:255',
                'min:5',
                'regex:/^[\p{L}\s\-\']+$/u',
            ],
            'name.ar' => [
                'sometimes',
                'string',
                'max:255',
                'min:5',
                'regex:/^[\p{Arabic}\s\-\']+$/u',
            ],

            'description' => ['sometimes', 'array'],
            'description.en' => ['sometimes', 'string', 'max:255', 'min:10'],
            'description.ar' => ['sometimes', 'string', 'max:255', 'min:10'],
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
            $crop = $this->route('crop');
            $enName = $this->input('name.en');
            $arName = $this->input('name.ar');

            $query = Crop::where('id', '!=', optional($crop)->id);

            if ($enName) {
                $existsEn = (clone $query)->where('name->en', $enName)->exists();
                if ($existsEn) {
                    $validator->errors()->add('name.en', 'The English crop name has already been taken.');
                }
            }

            if ($arName) {
                $existsAr = (clone $query)->where('name->ar', $arName)->exists();
                if ($existsAr) {
                    $validator->errors()->add('name.ar', 'The Arabic crop name has already been taken.');
                }
            }
        });
    }

    /**
     * Summary of messages
     * @return array{description.ar.max: string, description.ar.min: string, description.ar.string: string, description.array: string, description.en.max: string, description.en.min: string, description.en.string: string, name.ar.max: string, name.ar.min: string, name.ar.regex: string, name.ar.string: string, name.en.max: string, name.en.min: string, name.en.regex: string, name.en.string: string}
     */
    public function messages(): array
    {
        return [
            'name.en.string' => 'The :attribute must be a string.',
            'name.en.min' => 'The :attribute must be at least :min characters.',
            'name.en.max' => 'The :attribute must not exceed :max characters.',
            'name.en.regex' => 'The :attribute may only contain letters, spaces, hyphens, or apostrophes.',

            'name.ar.string' => 'The :attribute must be a string.',
            'name.ar.min' => 'The :attribute must be at least :min characters.',
            'name.ar.max' => 'The :attribute must not exceed :max characters.',
            'name.ar.regex' => 'The :attribute may only contain Arabic letters, spaces, hyphens, or apostrophes.',

            'description.array' => 'The description must be a translatable array.',
            'description.en.string' => 'The English description must be a string.',
            'description.en.min' => 'The English description must be at least :min characters.',
            'description.en.max' => 'The English description must not exceed :max characters.',

            'description.ar.string' => 'The Arabic description must be a string.',
            'description.ar.min' => 'The Arabic description must be at least :min characters.',
            'description.ar.max' => 'The Arabic description must not exceed :max characters.',
        ];
    }

    /**
     * Summary of attributes
     * @return array{description.ar: string, description.en: string, name.ar: string, name.en: string}
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
