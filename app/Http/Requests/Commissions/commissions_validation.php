<?php

namespace App\Http\Requests\Commissions;

use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class commissions_validation extends FormRequest
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
        $store_id = session()->get('process_store_id')??request()->process_store_id;



        return [
       // 'item_group' => ['required','array','filled',Rule::exists('bd_group','id_group')->where(function ($query)use($store_id) {$query->where('store_id', $store_id); })],
       //'list_group' => 'required|array|filled|exists:bd_group,id_group',

       //'user_id'=>'exists:mm_user,id_user',
       'start_from'=>'required',
       'end_at'=>'required',
       'value'=>'required',
       'agent_quota'=>'required',
       'currency_id'=>'required|exists:mm_currency,id_currency',




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
