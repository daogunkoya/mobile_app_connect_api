<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommissionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'created_at' => $this->createdAt,
            'commission_id' => $this->commissionId,
            'start_from' => $this->startFrom,
            'end_at' => $this->endAt,
            'value' => $this->value,
            'user_id' => $this->userId,
            'member_user_id' => $this->memberUserId,
            'agent_quota' => $this->agentQuota,
            'commission_agent_quota' => $this->agentQuota,
            'currency' => new CurrencyResource($this->currency),
            'member_user' => new UserResource($this->memberUser),
        ];
    }
}
