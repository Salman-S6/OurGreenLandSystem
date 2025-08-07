<?php

namespace Modules\Resources\Http\Requests\Supplier;

use App\Traits\RequestTrait;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSupplierRequest extends FormRequest
{
    use RequestTrait;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
        'user_id' => ['sometimes', 'exists:users,id'],
        'phone_number' => ['sometimes'],
        'license_number' => ['sometimes', 'unique:suppliers,license_number'],
        'supplier_type' => ['required', 'array'],
        'supplier_type.en' => ['required', 'string'],
        'supplier_type.ar' => ['required', 'string'],
        ];
    }

}
