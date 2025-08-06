<?php

namespace Modules\Extension\Http\Requests\AgriculturalGuidance;

use App\Models\User;
use App\Traits\RequestTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rules\File;
use Modules\Extension\Models\AgriculturalGuidance;

class StoreAgriculturalGuidance extends FormRequest
{
    use RequestTrait;
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            "title" => "required|array|min:1",
            "summary" => "required|array|min:1",
            "category" => "required|array|min:1",
            "added_by_id" => "required|int",
            "tags" => "sometimes|string",
            "file" => ["sometimes", File::default()
                ->max(100000)
                ->rules([
                    "mimetypes:image/jpeg,image/png,application/pdf,text/plain",
                    "extensions:jpg,png,pdf,txt"
                ])]
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows("create", AgriculturalGuidance::class);
    }

    public function prepareForValidation()
    {
        $this->merge([
            "added_by_id" => $this->user()->id,
        ]);
    }
}
