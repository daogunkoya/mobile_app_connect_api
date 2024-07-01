<?php

namespace App\Http\Requests\Transactions;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\TransactionStatusValidateRule;
use App\Enum\TransactionStatus;

class TransactionRequest extends FormRequest
{
    public function rules()
    {
        return [
            'status' => [ new TransactionStatusValidateRule(TransactionStatus::class)],
            // other rules...
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->has('status')) {
            // Normalize status to be in uppercase for the enum matching
            $this->merge([
                'status' => strtoupper($this->input('status'))
            ]);
        }
    }

    // public function passedValidation()
    // {
    //     if ($this->has('status')) {
    //         $this->merge([
    //             'status' => TransactionStatus::from($this->input('status'))
    //         ]);
    //     }
    // }
}
