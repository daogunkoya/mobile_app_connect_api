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
        if ($this->resource instanceof \stdClass) {

            return [
            ];
        }

        //return parent=>=>toArray($request);
        return [
            "sender_id" => $this->sender_id??$this->id_sender ,
            "user_id"=> $this->user_id,
            "sender_title"=> $this->sender_title,
            "created_at"=> $this->created_at,
            "sender_name"=> $this->sender_name,
            "sender_mname"=> $this->sender_mname,
            "sender_fname"=> $this->sender_fname,
            "sender_lname"=> $this->sender_lname,
            "sender_dob"=> $this->sender_dob,
            "sender_email"=> $this->sender_email,
            "sender_phone"=> $this->sender_phone,
            "sender_mobile"=>$this->sender_mobile,
            "sender_address"=> $this->sender_address,
            "sender_postcode"=> $this->sender_postcode,
            "count_sender_receivers"=>$this->count_sender_receivers,
        ];
    }
}
