<?php

namespace Modules\CropManagement\Http\Requests\PestDiseaseCase;

use App\Traits\RequestTrait;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePestDiseaseCaseRequest extends FormRequest
{
    use RequestTrait;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }

}
