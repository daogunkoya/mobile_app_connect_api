<?php

namespace App\Http\Requests\Sender;

use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class sender_validation extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $store_id = session()->get('process_store_id') ?? request()->process_store_id;
        return [
       // 'item_group' => ['required','array','filled',Rule::exists('bd_group','id_group')->where(function ($query)use($store_id) {$query->where('store_id', $store_id); })],
       //'list_group' => 'required|array|filled|exists:bd_group,id_group',
        'sender_title' => 'string',
        'sender_fname' => 'required|string',
        'sender_lname' => 'required|string',
        'sender_mname' => 'string',
        'sender_dob' => 'required',
        'sender_email' => 'required|email',
        'sender_address' => 'required',
        'sender_postcode' => 'required',
        'metadata' => 'required',
        'sender_phone' => 'required',
       'sender_mobile' => 'string',
        'photo_id' => 'string',


        ];
    }

    public function messages()
    {
        return [

            'customer_title.required' => 'title is required!',
            'customer_fname.required' => 'first name is required!',
            'customer_lname.required' => 'last name is required!',
            'customer_email.required' => 'email is required!',
            'customer_dob.required' => 'Date of birth is required!',
            'customer_address.required' => 'address is required!',
            'customer_postcode.required' => 'postcode is required!',

        ];
    }

      /**
     *  Filters to be applied to the input.
     *
     * @return array
     */
    public function filters()
    {
        return [
           // 'email' => 'trim|lowercase',
            'user_handle' => 'trim|capitalize|escape'
        ];
    }

     /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        throw new HttpResponseException(response()->json(['errors' => $errors
        ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY));
    }
}
