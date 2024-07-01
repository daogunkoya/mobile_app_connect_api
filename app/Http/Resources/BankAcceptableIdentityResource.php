<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BankAcceptableIdentityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request):array
    {
   
        return [
            "banks"=>$this->banks,
            "acceptable_id"=>$this->acceptableIdentities,
        ];
    }
}
