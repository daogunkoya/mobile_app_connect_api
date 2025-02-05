<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserDocumentRequest extends FormRequest
{
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
            'id_image' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120', // 5MB max
            'selfie_image' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ];
    }
}
