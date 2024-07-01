<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserCurrencyResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'user_id' => $this->userId,
            'sender_id' => $this->senderId,
            'origin_currency' => new CurrencyResource($this->originCurrency),
            'destination_currency' => new CurrencyResource($this->destinationCurrency),
        ];
    }
}
