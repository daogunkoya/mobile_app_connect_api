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
            "receiver_id"=>$this->receiver_id?? $this->id_receiver,
            "created_at"=>$this->created_at,
            "user_id"=>$this->user_id,
            "sender_id"=>$this->sender_id,
            "receiver_title"=>$this->receiver_title,
            "receiver_fname"=>$this->receiver_fname,
            "receiver_lname"=>$this->receiver_lname,
            "receiver_phone"=>$this->receiver_phone,
            "receiver_address"=>$this->receiver_address,
            "transfer_type"=>$this->transfer_type,
            "account_number"=>$this->account_number,
            "bank_id"=>$this->bank_id,
            "identity_type_id"=>$this->identity_type_id,
            "receiver_name"=>$this->receiver_name,
            "bank"=> new BankResource($this->whenLoaded('bank')),
            "identity"=> new AcceptableIdentityResource($this->whenLoaded('identity')),
        ];
    }
}
