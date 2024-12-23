<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Hash;

class RegisterUserRequest extends FormRequest
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
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],

            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'middle_name' => 'nullable|string', // Added nullable
            'title' => 'nullable|string', // Added nullable
            'dob' => 'nullable|string', // Added nullable
            'email' => 'required|email',
            'phone' => 'nullable|string', // Added nullable
            'address' => 'nullable|string', // Added nullable
            'postcode' => 'nullable|string', // Added nullable
            'metadata' => 'nullable|array', // Added nullable
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
            'first_name.required' => 'The first name field is required.',
            'last_name.required' => 'The last name field is required.',
            'email.required' => 'The email field is required.',
            'email.unique' => 'The email has already been taken.',
            'password.required' => 'The password field is required.',
            'password.confirmed' => 'The password confirmation does not match.',
            'password.min' => 'The password must be at least :min characters.',
            'password.letters' => 'The password must contain at least one letter.',
            'password.numbers' => 'The password must contain at least one number.',
        ];
    }

    public function mapToAttributes()
    {
        $validatedData = $this->validated();

        // Here, you can transform or map the data as needed for your model.
        return [
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'middle_name' => $validatedData['middle_name'] ?? null,
            'title' => $validatedData['title'] ?? null,
            'dob' => $validatedData['dob'] ?? null,
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'phone' => $validatedData['phone'] ?? null,
            // 'status' => UserStatus::getStatusEnumInstance($validatedData['status'])->value,
            // 'user_role_type' => UserRoleType::getRoleTypeEnumInstance($validatedData['user_role_type'])->value,
            'address' => $validatedData['address'] ?? null,
            'postcode' => $validatedData['postcode'] ?? null,
            'metadata' => $validatedData['metadata'] ?? [],
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
