<?php

namespace Modules\Extension\Http\Requests\Question;

use Illuminate\Foundation\Http\FormRequest;

class UpdateQuestion extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
