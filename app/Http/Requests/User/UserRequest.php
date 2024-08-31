<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enum\UserRoleType;
use App\Enum\UserStatus;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // Consider adding real authorization logic here if applicable.
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
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'middle_name' => 'nullable|string', // Added nullable
            'title' => 'nullable|string', // Added nullable
            'dob' => 'nullable|string', // Added nullable
            'email' => 'required|email',
            // 'status' => ['required', Rule::in(array_column(UserStatus::cases(), 'value'))],
            'status' => ['required', Rule::in(array_map(fn($status) => $status->label(), UserStatus::cases()))],
            'user_role_type' => ['required', Rule::in(array_map(fn($status) => $status->label(), UserRoleType::cases()))],
            'address' => 'nullable|string', // Added nullable
            'postcode' => 'nullable|string', // Added nullable
            'metadata' => 'nullable|array', // Added nullable
        ];
    }

    /**
     * Get custom error messages for validation.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'status.in' => 'The selected status is invalid.',
            'user_role_type.in' => 'The selected user role type is invalid.',
        ];
    }

    /**
     * Filters to be applied to the input.
     *
     * @return array
     */
    public function filters()
    {
        return [
            'user_handle' => 'trim|capitalize|escape' // This is currently a placeholder. Implement filtering logic as needed.
        ];
    }

      /**
     * Map the validated data to the appropriate model attributes.
     *
     * @return array
     */
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
            'status' => UserStatus::getStatusEnumInstance($validatedData['status'])->value,
            'user_role_type' => UserRoleType::getRoleTypeEnumInstance($validatedData['user_role_type'])->value,
            'address' => $validatedData['address'] ?? null,
            'postcode' => $validatedData['postcode'] ?? null,
            'metadata' => $validatedData['metadata'] ?? [],
        ];
    }
}
