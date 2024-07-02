<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'user_id' => $this->userId,
            'user_currency' => new UserCurrencyResource($this->userCurrency),
            
        ];
    }
}