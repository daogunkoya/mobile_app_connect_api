<?php

namespace App\Http\Requests\Outstanding;

use Illuminate\Foundation\Http\FormRequest;

class OutstandingRequest extends FormRequest
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
           "payment_type" => 'required|in:Transaction,Commission',
           "outstanding_amount" => 'nullable|numeric',
           "outstanding_id" => 'nullable|exists:mm_outstanding_payment,id_outstanding',
           'user_id' => 'required|exists:mm_user,id_user',
        ];
    }
}
