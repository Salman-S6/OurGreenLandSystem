<?php

namespace Modules\Extension\Http\Requests\Answer;

use App\Traits\RequestTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Modules\Extension\Models\Answer;

class UpdateAnswer extends FormRequest
{
    use RequestTrait;
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'answer_text' => 'required|array|min:1',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $answer = $this->route()->parameter('answer');        
        return Gate::allows('update', [Answer::class, $answer]);
    }

}
