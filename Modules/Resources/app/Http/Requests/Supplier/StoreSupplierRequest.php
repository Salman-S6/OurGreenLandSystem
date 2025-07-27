<?php

namespace Modules\Resources\Http\Requests\Supplier;

use App\Traits\RequestTrait;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Resources\Enums\SupplierType;

class StoreSupplierRequest extends FormRequest
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
        'user_id' => ['required', 'exists:users,id'],
        'supplier_type' => ['required', 'array'],
        'supplier_type.en' => ['required', 'string'],
        'supplier_type.ar' => ['required', 'string'],
        'phone_number' => ['required', 'string'],
        'license_number' => ['required', 'string','unique:suppliers,license_number'],
        ];
    }

        public function messages(): array
    {
        return [
            'supplier_type.required' => 'Supplier type is required.',
            'supplier_type.array' => 'Supplier type must be an object with translations.',
        ];
    }

}


