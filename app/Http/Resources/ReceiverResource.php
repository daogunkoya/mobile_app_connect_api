<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReceiverResource extends JsonResource
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
            "receiver_id"=> $this->receiverId,
            "created_at"=>$this->createdAt,
           // "user_id"=>$this->user_id,
            "sender_id"=>$this->senderId,
            "receiver_title"=>$this->receiverTitle,
            "receiver_fname"=>$this->receiverFname,
            "receiver_lname"=>$this->receiverLname,
            "receiver_phone"=>$this->receiverPhone,
            "receiver_address"=>$this->receiverAddress,
            "transfer_type"=>$this->transferType,
            "account_number"=>$this->accountNumber,
            "bank_id"=>$this->bankId,
            "identity_type_id"=>$this->identityTypeId,
            "receiver_name"=>$this->receiverName,
            "receiver_banks"=> $this->bank,
          "receiver_identities"=> $this->identity,
          "receiver_currency"=> new CurrencyResource($this->currency),
        ];
    }
}
