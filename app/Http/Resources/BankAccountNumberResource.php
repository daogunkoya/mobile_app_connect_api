<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BankAccountNumberResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request):array
    {
        //return parent::toArray($request);
        return [
            "receiver_name"=>$this->accountName,
            "receiver_first_name"=>$this->receiverFirstName,
            "receiver_last_name"=>$this->receiverLastName,
            "receiver_middle_name"=>$this->receiverMiddleName,
            "account_number"=>$this->accountNumber,
            "bank_name"=>$this->bankName,
            'bank_code' => $this->bankCode,

        ];
    }
}
