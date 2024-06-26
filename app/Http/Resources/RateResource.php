<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RateResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'rate_id' => $this->rateId,
            'user_id' => $this->userId,
            'main_rate' => $this->mainRate,
            'bou_rate' => $this->bouRate,
            'sold_rate' => $this->soldRate,
            'currency_id' => $this->currencyId
        ];
    }
}
