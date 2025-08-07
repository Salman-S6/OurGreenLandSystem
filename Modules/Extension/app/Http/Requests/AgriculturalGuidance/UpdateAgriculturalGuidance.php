<?php

namespace Modules\Extension\Http\Requests\AgriculturalGuidance;

use App\Models\User;
use App\Traits\RequestTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Modules\Extension\Models\AgriculturalGuidance;

class UpdateAgriculturalGuidance extends FormRequest
{
    use RequestTrait;
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            "title" => "sometimes|array|min:1",
            "summary" => "sometimes|array|min:1",
            "category" => "sometimes|array|min:1",
            "tags" => "sometimes|string"
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $agriculturalGuidance = $this->route()->parameter("agricultural_guidance");
        return Gate::allows("update", [AgriculturalGuidance::class, $agriculturalGuidance]);
    }


}
