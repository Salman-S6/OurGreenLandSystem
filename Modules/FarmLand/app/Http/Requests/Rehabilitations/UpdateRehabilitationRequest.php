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
            return array_merge(
            $this->translatableRule('event'),
            $this->translatableRule('description', 'nullable|string'),
            $this->translatableRule('notes', 'nullable|string'),
            [
                'lands' => ['sometimes', 'array', 'min:1'],
                'lands.*.land_id' => ['sometimes', 'exists:lands,id'],
                'lands.*.performed_by' => ['nullable', 'exists:users,id'],
                'lands.*.performed_at' => ['nullable', 'date'],
            ]
        );
    }

    /**
     * Helper method to generate rules for translated fields like "event_ar" & "event_en"
     */
    protected function translatableRule(string $field, string $rule = 'sometimes|string|max:255'): array
    {
        return [
            "{$field}_ar" => $rule,
            "{$field}_en" => $rule,
        ];
    }
}