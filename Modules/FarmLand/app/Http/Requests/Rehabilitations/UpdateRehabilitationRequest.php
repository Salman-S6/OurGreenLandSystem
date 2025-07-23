<?php

namespace Modules\FarmLand\Http\Requests\Rehabilitations;

use App\Traits\RequestTrait;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRehabilitationRequest extends FormRequest
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
            'event' => 'sometimes|string|max:255',
            'description' => 'sometimes|nullable|string',
            'notes' => 'sometimes|nullable|string',
        ];
    }

}