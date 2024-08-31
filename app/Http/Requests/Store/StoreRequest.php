<?php

namespace App\Http\Requests\Store;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'store_name' => 'required|string',
            'store_email' => 'required|email',
            'store_phone' => 'required|string',
            'store_mobile' => 'string',
            'store_slogan' => 'string',
            'store_url' => 'required|url',
            'enable_sms' => 'between:0,1|integer',
            'enable_credit' => 'between:0,1|integer',
            'enable_multiple_receipt' => 'between:0,1|integer',
            'store_business_name' => 'string',
            'store_address' => 'string',
            'store_city' => 'string',
            'store_postcode' => 'string',
            'store_country' => 'string',
          

        ];
    }
}
