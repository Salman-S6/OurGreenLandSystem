<?php

namespace Modules\FarmLand\Http\Requests\AgriculturalInfrastructure;

use App\Traits\RequestTrait;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Modules\FarmLand\Rules\LandsBelongToUser;
use Modules\FarmLand\Enums\AgriculturalInfrastructuresType;
use Modules\FarmLand\Enums\AgriculturalInfrastructuresStatus;

class UpdateAgriculturalInfrastructureRequest extends FormRequest
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
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $rules = [
            'type' => ['sometimes', Rule::enum(AgriculturalInfrastructuresType::class)],
            'status' => ['sometimes', Rule::enum(AgriculturalInfrastructuresStatus::class)],

            'description' => 'sometimes|nullable|array',
            'description.en' => 'nullable|string|max:500',
            'description.ar' => 'nullable|string|max:500',

            'installation_date' => 'sometimes|nullable|date|after_or_equal:today',
            'land_ids' => ['sometimes', 'array'],
            'land_ids.*' => 'sometimes|required|exists:lands,id',
        ];

        if ($user && !$user->hasRole('AgriculturalEngineer')) {
            $rules['land_ids'][] = new LandsBelongToUser($user);
        }

        return $rules;
    }
}
