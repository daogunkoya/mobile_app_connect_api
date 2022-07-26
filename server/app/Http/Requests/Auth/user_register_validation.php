<?php

namespace App\Http\Requests\Auth;

use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Waavi\Sanitizer\Laravel\SanitizesInput;
use Illuminate\Foundation\Http\FormRequest;

class user_register_validation extends FormRequest
{
    //use SanitizesInput;
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
    { //|unique:mm_user,user_access_id
        //|unique:mm_user,user_email
        return [
            //'user_email' => 'required|email|same:user_confirm_email|unique:mm_user',
            'access_type' => 'required|numeric|in:1,2,3,4',
            'user_email' => 'required_if:access_type,1|email|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/',
            'access_id' => 'bail|required_if:access_type,2|required_if:access_type,3|required_if:access_type,4',
            'user_name' => 'required|string|min:5|max:15',
            //'user_name' => 'required_if:access_type,2|required_if:access_type,3|string|min:1|max:200',
           // 'user_password' => 'required_if:access_type,1',ss
            //'user_password' => 'required_if:access_type,1|min:8|regex:/^(?=.*[A-Za-z0-9])(?=.*[<>?,.!$@%^&*()_+~=\`{}\[\]:";])$/',
           // 'user_password' => 'required_if:access_type,1|min:8|regex:/^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$/', with uppecase
           // 'user_password' => 'required_if:access_type,1|min:8|max:20|regex:/^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[\d])(?=\S*[\W])\S*$/', at least one lettr,number,special xter
            'user_password' => 'required_if:access_type,1|min:8|max:20|regex:/^\S*(?=\S{8,})(?=.*[a-zA-Z0-9\W]).*$/',
            //'user_password' => 'required_if:access_type,1|min:8|different:user_email|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\X]).*$/',
            
            'user_image_url' =>'required_if:access_type,2|required_if:access_type,3',
        //    'user_password' => 'min:8|different:user_email|
        //    regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9] | [-!$%^&*()_+|~=`{}\[\]:";'<>?,.\\\/\s]+B).*$/',
            
            'device_type' => 'required|numeric|in:1,2,3',
            'device_code' => 'required|string|min:1|max:200',
            'device_name' => 'required|string|min:1|max:200',
        
        ];
    }

    public function filters()
        {
            return [
                'user_name'  => 'trim|capitalize',
                'user_email' => 'trim',
            ];
        }

    public function messages()
    {
        return [
           'user_email.required_if' => 'Email is required!',

            'user_password.required_if' => 'User Password is required!',
           // 'access_type.required' => 'Access type is required!',
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
