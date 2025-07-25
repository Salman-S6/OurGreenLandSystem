<?php

namespace Modules\FarmLand\Http\Requests\AgriculturalInfrastructure;

use App\Traits\RequestTrait;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Modules\FarmLand\Enums\AgriculturalInfrastructuresType;
use Modules\FarmLand\Enums\AgriculturalInfrastructuresStatus;

class StoreAgriculturalInfrastructureRequest extends FormRequest
{
    use RequestTrait;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
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
            'type' => ['required', Rule::enum(AgriculturalInfrastructuresType::class)],
            'status' => ['required', Rule::enum(AgriculturalInfrastructuresStatus::class)],

            'description' => 'nullable|array',
            'description.en' => 'nullable|string|max:500',
            'description.ar' => 'nullable|string|max:500',

            'installation_date' => 'nullable|date|after_or_equal:today',
            'land_ids' => 'required|array',
            'land_ids.*' => 'required|exists:lands,id',
        ];
    }
}
