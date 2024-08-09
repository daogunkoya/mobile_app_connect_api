<?php

namespace App\Http\Requests\Commissions;

use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\CommissionRangeOverlap;

class CommissionsValidation extends FormRequest
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

        $store_id = session()->get('process_store_id') ?? request()->process_store_id;
        $currency_id = $this->input('currency_id');
        $member_user_id = $this->input('member_user_id');
        $start_from = $this->input('start_from');
        $end_at = $this->input('end_at');

        return [
      
       'member_user_id'=>'nullable|exists:mm_user,id_user',
        'start_from' => ['required',new CommissionRangeOverlap($currency_id, $member_user_id, $start_from, $end_at, $store_id)],
        'currency_id' => 'required|exists:mm_currency,id_currency',
        'end_at' => 'numeric|required',
        'value' => 'numeric|required',
        'agent_quota' => 'numeric|required',

        ];
    }

    public function messages()
    {
        return [
            'receiver_title.required' => 'title is required!',


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
