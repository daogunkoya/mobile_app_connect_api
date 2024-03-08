<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SenderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
//        if ($this->resource instanceof \stdClass) {
//
//            return [
//            ];
//        }

        //return parent=>=>toArray($request);
        return [
            "sender_id" => $this->senderId ,
            "user_id"=> $this->userId,
            "sender_title"=> $this->senderTitle,
            "created_at"=> $this->createdAt,
            "sender_name"=> $this->senderName,
            "sender_mname"=> $this->senderMname,
            "sender_fname"=> $this->senderFname,
            "sender_lname"=> $this->senderLname,
            "sender_dob"=> $this->senderDob,
            "sender_email"=> $this->senderEmail,
            "sender_phone"=> $this->senderPhone,
            "sender_mobile"=>$this->senderMobile,
            "sender_address"=> $this->senderAddress,
            "sender_postcode"=> $this->senderPostcode,
            "count_sender_receivers"=>$this->countSenderReceiver,
        ];
    }
}
