<?php

namespace App\Http\Requests\Attachment;

use App\Models\Attachment;
use App\Traits\RequestTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rules\File;

class StoreAttachmentRequest extends FormRequest
{
    use RequestTrait;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows("create", Attachment::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "file" => ["required", File::default()
                ->max(100000)
                ->rules([
                    "mimetypes:image/jpeg,image/png,application/pdf,text/plain",
                    "extensions:jpg,png,pdf,txt"
                ])]
        ];
    }
    
    public function messages()
    {
        return [
            'file.mimetypes' => 'Only JPEG, PNG, PDF, and TXT files are allowed.',
            'file.max' => 'File must not exceed 100MB.',
            'file.extensions' => 'Invalid file extension.',
        ];
    }
}
