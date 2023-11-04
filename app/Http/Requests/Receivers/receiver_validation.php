<?php

namespace App\Http\Requests\Receivers;

use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class receiver_validation extends FormRequest
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
        'receiver_title' => 'required',
        'receiver_fname' => 'required',
        'receiver_lname' => 'required',
//        'receiver_dob' => 'required',
//        'receiver_email' => 'required|email',
        'receiver_address' => 'required',
//        'receiver_postcode' => 'required',
        'transfer_type_key' => 'required',
        'account_number' => 'required_if:transfer_type_key,1',
        'identity_type' => 'required',
        'transfer_type' => 'required',


        ];
    }

    public function messages()
    {
        return [
            'receiver_title.required' => 'title is required!',
            'receiver_fname.required' => 'first name is required!',
            'receiver_lname.required' => 'last name is required!',
            'receiver_email.required' => 'email is required!',
            'receiver_dob.required' => 'Date of birth is required!',
            'receiver_address.required' => 'address is required!',
            'receiver_postcode.required' => 'postcode is required!',
            'identity_type.required' => 'postcode is required!',
            'transfer_type.required' => 'transfer_type is required!',
            'account_number.required' => 'account_number is required!',

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
