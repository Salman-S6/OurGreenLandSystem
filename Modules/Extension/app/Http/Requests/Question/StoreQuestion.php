<?php

namespace Modules\Extension\Http\Requests\Question;

use App\Traits\RequestTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Modules\Extension\Models\Question;

class StoreQuestion extends FormRequest
{
    use RequestTrait;
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'farmer_id' => 'required|int|exists:users,id',
            'title' => 'required|array|min:1',
            'description' => 'required|array|min:1'
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('create', Question::class);
    }

    public function prepareForValidation()
    {
        $this->merge([
            'farmer_id' => $this->user()->id,
        ]);
    }
}
