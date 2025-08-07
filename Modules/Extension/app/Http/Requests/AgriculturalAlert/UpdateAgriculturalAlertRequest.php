<?php

namespace Modules\Extension\Http\Requests\AgriculturalAlert;

use App\Traits\RequestTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Modules\Extension\Enums\AlertLevels;
use Modules\Extension\Enums\AlertTypes;
use Modules\Extension\Models\AgriculturalAlert;

class UpdateAgriculturalAlertRequest extends FormRequest
{
    use RequestTrait;
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title' => 'sometimes|array|min:1',
            'message' => 'sometimes|array|min:1',
            'alert_level' => ['sometimes','string', Rule::enum(AlertLevels::class)],
            'alert_type' => ['sometimes','string', Rule::enum(AlertTypes::class)],
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $agriculturalAlert = $this->route()->parameter('agricultural_alert');
        return Gate::allows('update', [AgriculturalAlert::class, $agriculturalAlert]);
    }
}
