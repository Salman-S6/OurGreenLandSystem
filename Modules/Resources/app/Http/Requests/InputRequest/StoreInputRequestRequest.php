<?php

namespace Modules\Resources\Http\Requests\InputRequest;

use App\Enums\UserRoles;
use App\Traits\RequestTrait;
use Illuminate\Foundation\Http\FormRequest;

class StoreInputRequestRequest extends FormRequest
{
    use RequestTrait;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();
        return $user && $user->hasRole(UserRoles::Farmer);
       
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'input_type' => 'required|in:seeds,fertilizers,equipment',
            'description' => 'required|array',
            'description.en' => 'required|string|max:255',
            'description.ar' => 'required|string|max:255',
            'quantity' => 'required|numeric|min:0.01',
            'selected_supplier_id' => 'required|exists:suppliers,id',
        ];
    }

    /**
     * Custom error messages for validation.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'input_type.required' => 'The input type is required.',
            'input_type.in' => 'The input type must be one of: seeds, fertilizers, or equipment.',
            'description.required' => 'The description is required.',
            'description.array' => 'The description must be a translatable object.',
            'description.en.required' => 'The English description is required.',
            'description.en.string' => 'The English description must be a string.',
            'description.ar.required' => 'The Arabic description is required.',
            'description.ar.string' => 'The Arabic description must be a string.',
            'description.en.max'      => 'The description (English) must not exceed 255 characters.',
            'description.ar.max'      => 'The description (Arabic) must not exceed 255 characters.',
            'quantity.required' => 'The quantity is required.',
            'quantity.numeric' => 'The quantity must be a number.',
            'quantity.min' => 'The quantity must be greater than zero.',
            'selected_supplier_id.required' => 'You must select a supplier.',
            'selected_supplier_id.exists' => 'The selected supplier does not exist.',
        ];
    }

    /**
     * Custom attribute names for errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'input_type' => ' Input type',
            'description' => ' Description',
            'description.en' => ' Description (English)',
            'description.ar' => ' Description (Arabic)',
            'quantity' => ' Quantity',
            'selected_supplier_id' => ' Supplier',
        ];
    }
}
