<?php

namespace App\Http\Requests\Auth;

use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
 
use Illuminate\Foundation\Http\FormRequest;

class user_login_validation extends FormRequest
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
        return [
            'user_email' => 'required|email|exists:mm_user,user_email',
            //'access_id' => 'exists:mm_user,user_access_id',
            'access_type' => 'required|numeric|min:1|max:4',
            'device_type' => 'required|numeric',
            'device_code' => 'required|string|max:200',          
            'device_name' => 'required|string',          
        ];
    }

    public function messages()
    {
        return [
            //'user_email.required' => 'Email is required!',
            //'user_confirm_email.required' => 'user confirm email field is required!',
            //'user_password.required' => 'User Password is required!',
            //'access_type.required' => 'Access type is required!',
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
