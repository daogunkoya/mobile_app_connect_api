<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OutstandingPaymentResource extends JsonResource
{
    public function toArray($request)
    {

        return [
           'outstanding_payment_id' => $this->outstandingPaymentId,
            'created_at' => $this->createdAt,
            'user_id' => $this->userId,
            'sender_name' => ucfirst($this->senderName),
            'receiver_name' => ucfirst($this->receiverName),
            'transaction_id' => $this->transactionId,
            'transaction_code' => $this->transactionCode,
            'currency_id' => $this->currencyId,
            'total_amount' => $this->totalAmount,
            'amount_sent' => $this->amountSent,
            'local_amount' => $this->localAmount,
            'total_commission' => $this->totalCommission,
            'agent_commission' => $this->agentCommission,
            'business_commission' => round(($this->totalCommission - $this->agentCommission), 2),
            'exchange_rate' => round($this->exchangeRate, 2),
            'bou_rate' => round($this->bouRate,2),
            'sold_rate' =>round( $this->soldRate,2),
            'transaction_paid_status' => $this->transactionPaidStatus,
            'commission_paid_status' => $this->commissionPaidStatus

        ];
    }
}
