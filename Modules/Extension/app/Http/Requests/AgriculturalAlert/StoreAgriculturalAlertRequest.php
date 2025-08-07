<?php

namespace Modules\Extension\Http\Requests\AgriculturalAlert;

use App\Traits\RequestTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Modules\Extension\Enums\AlertLevels;
use Modules\Extension\Enums\AlertTypes;
use Modules\Extension\Models\AgriculturalAlert;

class StoreAgriculturalAlertRequest extends FormRequest
{
    use RequestTrait;
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title' => 'required|array|min:1',
            'message' => 'required|array|min:1',
            'crop_plan_id' => 'required|int',
            'alert_level' => ['required','string', Rule::enum(AlertLevels::class)],
            'alert_type' => ['required','string', Rule::enum(AlertTypes::class)],
            'send_time' => 'required|date',
            'created_by' => 'required|int',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
        // return Gate::allows('create', AgriculturalAlert::class);
    }

    public function prepareForValidation() {
        $this->merge([
            'crop_plan_id' => $this->route()->parameter('crop_plan')->id,
            'send_time' => now(),
            'created_by' => $this->user()->id,
        ]);
    }
}
