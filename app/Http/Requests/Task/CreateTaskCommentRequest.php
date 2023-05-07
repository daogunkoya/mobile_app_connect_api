<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;


class CreateTaskCommentRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            
            'email' => ['required', 'string', 'email'],
            'password' => ['required'],
        ];
    }



     /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function messages()
    {
        return [
            
            'email.required' => 'The email field is required.',
            'password.required' => 'The password field is required.',
            
        ];
    }

    //This will customize the response format when validation fails, and return a JSON response with the validation errors.
    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();

        // You can customize the response format here
        $response = [
            'message' => 'The given data was invalid.',
            'errors' => $errors->toArray(),
        ];

        throw new HttpResponseException(response()->json($response, 422));
    }
}
