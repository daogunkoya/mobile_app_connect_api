<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RateResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'created_at' => $this->createdAt,
            'rate_id' => $this->rateId,
            'user_id' => $this->userId,
            'member_user_id' => $this->memberUserId,
            'main_rate' => $this->mainRate,
            'bou_rate' => $this->bouRate,
            'sold_rate' => $this->soldRate,
            'currency' => new CurrencyResource($this->currency),
        ];
    }
}
