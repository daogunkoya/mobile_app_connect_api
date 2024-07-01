<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransferBreakDownResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request):array
    {
        
        // amountSent: $amountSent,
        // exchangeRate: $userRate->mainRate,
        // localAmount: $totalAmount + $userCommission->value,
        // totalAmount: $totalAmount,
        // totalCommission: $userCommission->value,
        // agentCommission: $agentCommission,
        // bouRate: $userRate->bouRate,
        // soldRate: $userRate->soldRate

        return [
           "amount_sent"=> number_format($this->amountSent,2),
           "local_amount"=>number_format($this->localAmount,2),
           "exchange_rate"=>number_format($this->exchangeRate,2),
           "total_amount"=>number_format($this->totalAmount,2),
           "total_commission"=>number_format($this->totalCommission,2),
           "agent_commission"=> number_format($this->agentCommission,2),
           "bou_rate"=>number_format($this->bouRate,2),
           "sold_rate"=> number_format($this->soldRate,2),
           
        ];
    }
}
