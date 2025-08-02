<?php

namespace Modules\Extension\Http\Requests\Answer;

use App\Traits\RequestTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Modules\Extension\Models\Answer;

class StoreAnswer extends FormRequest
{
    use RequestTrait;
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'question_id' => 'required|int',
            'expert_id' => 'required|int|exists:users,id',
            'answer_text' => 'required|array|min:1',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('create', Answer::class);
    }

    public function prepareForValidation()
    {
        $this->merge([
            'expert_id' => $this->user()->id,
            'question_id' => $this->route()->parameter('question')->id,
        ]);
    }
}
