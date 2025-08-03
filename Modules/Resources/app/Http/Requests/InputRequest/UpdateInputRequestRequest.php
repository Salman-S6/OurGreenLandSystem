<?php

namespace Modules\Resources\Http\Requests\InputRequest;

use App\Enums\UserRoles;
use App\Traits\RequestTrait;
use Illuminate\Foundation\Http\FormRequest;

class UpdateInputRequestRequest extends FormRequest
{
    use RequestTrait;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $inputRequest = $this->route('inputRequest');
        $user = $this->user();
        if ($user->hasRole(UserRoles::Farmer) && $inputRequest->requested_by === $user->id) {
            return true;
        } elseif ($user->hasRole(UserRoles::Supplier) && $user->id === $inputRequest->selected_supplier_id) {
            return true;
        } elseif ($user->hasRole(UserRoles::SuperAdmin)) {
            return true;
        } else {
            return  false;
        }
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'input_type' => 'sometimes|in:seeds,fertilizers,equipment',
            'description' => 'sometimes|array',
            'description.en' => 'required_with:description|string',
            'description.ar' => 'required_with:description|string',
            'quantity' => 'sometimes|numeric|min:0.01',
            'selected_supplier_id' => 'sometimes|exists:suppliers,id',

            'status' => 'sometimes|string|in:pending,approved,rejected,in-progress,delivered,received',
            'approval_date' => 'sometimes|date',
            'delivery_date' => 'sometimes|date',
            'notes' => 'sometimes|array',
            'notes.en' => 'required_with:notes|string',
            'notes.ar' => 'required_with:notes|string',
            'status_notes' => 'sometimes|array',

            'status_notes.ar' => 'required_with:status_notes|string',
            'status_notes.en' => 'required_with:status_notes|string',
            'rejection_reason' => 'sometimes|array',
            'rejection_reason.ar' => 'required_with:rejection_reason|string',
            'rejection_reason.en' => 'required_with:rejection_reason|string'
        ];
    }

    /**
     * Custom error messages.
     */
    public function messages(): array
    {
        return [
            'input_type.in' => 'Input type must be one of: seeds, fertilizers, equipment.',
            'description.array' => 'Description must be an object with translations.',
            'description.en.required_with' => 'English description is required when description is provided.',
            'description.ar.required_with' => 'Arabic description is required when description is provided.',
            'quantity.numeric' => 'Quantity must be a numeric value.',
            'quantity.min' => 'Quantity must be at least 0.01.',
            'selected_supplier_id.exists' => 'Selected supplier does not exist.',

            'status.in' => 'Status must be one of: pending, approved, rejected, in-progress, delivered, received.',
            'approval_date.date' => 'Approval date must be a valid date.',
            'delivery_date.date' => 'Delivery date must be a valid date.',
            'notes.array' => 'Notes must be an object with translations.',
            'notes.en.required_with' => 'English note is required when notes are provided.',
            'notes.ar.required_with' => 'Arabic note is required when notes are provided.',

            'status_notes.ar.required_with' => 'The Arabic status notes field is required when status notes are present.',
            'status_notes.ar.string' => 'The Arabic status notes field must be a string.',
            'status_notes.en.required_with' => 'The English status notes field is required when status notes are present.',
            'status_notes.en.string' => 'The English status notes field must be a string.',
            'rejection_reason.ar.required_with' => 'The Arabic rejection reason field is required when rejection reason is present.',
            'rejection_reason.ar.string' => 'The Arabic rejection reason field must be a string.',
            'rejection_reason.en.required_with' => 'The English rejection reason field is required when rejection reason is present.',
            'rejection_reason.en.string' => 'The English rejection reason field must be a string.',
        ];
    }

    /**
     * Custom attribute names.
     */
    public function attributes(): array
    {
        return [
            'input_type' => 'input type',
            'description' => 'description',
            'description.en' => 'description (English)',
            'description.ar' => 'description (Arabic)',
            'quantity' => 'quantity',
            'selected_supplier_id' => 'selected supplier',

            'status' => 'status',
            'approval_date' => 'approval date',
            'delivery_date' => 'delivery date',
            'notes' => 'notes',
            'notes.en' => 'notes (English)',
            'notes.ar' => 'notes (Arabic)',


            'status_notes' => 'status notes',
            'status_notes.ar' => 'status notes(Arabic)',
            'status_notes.en' => 'status notes(English)',
            'rejection_reason' => 'rejection reason',
            'rejection_reason.ar' => 'rejection reason(Arabic)',
            'rejection_reason.en' => 'rejection reason(English)'
        ];
    }
}
