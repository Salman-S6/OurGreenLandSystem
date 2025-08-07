<?php

namespace Modules\Extension\Http\Requests\Question;

use App\Traits\RequestTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Modules\Extension\Models\Question;

class UpdateQuestion extends FormRequest
{
    use RequestTrait;
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title' => 'sometimes|array|min:1',
            'description' => 'sometimes|array|min:1'
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $question = $this->route()->parameter('question');  
        return Gate::allows('update', [Question::class, $question]);
    }
}
