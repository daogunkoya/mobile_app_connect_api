<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
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
            'created_at' => $this->createdAt,
            'id_transaction' => $this->transactionId,
            'transaction_code' => $this->transactionCode,
            'user_id' => $this->userId,
            'sender_id' => $this->senderId,
            'sender_address' => $this->senderAddress,
            'receiver_address' => $this->receiverAddress,
            'currency_id' => $this->currencyId,
            'receiver_fname' => $this->receiverFname,
            'receiver_lname' => $this->receiverLname,
            'receiver_phone' => $this->receiverPhone,
            'receiver_bank_id' => $this->receiverBankId,
            'receiver_identity_id' => $this->receiverIdentityId,
            'receiver_account_no' => $this->receiverAccountNumber,
            'receiver_transfer_type' => $this->receiverTransferType,
            'amount_sent' => $this->amountSent,
            'total_amount' => $this->totalAmount,
            'local_amount' => $this->localAmount,
            'total_commission'=> $this->totalCommission,
            'agent_commission' => $this->agentCommission,
            'exchange_rate' => $this->exchangeRate,
            'note' => $this->note,
            'currency_income' => $this->currencyIncome,
            'transaction_type' => $this->transactionType,
            'transaction_status' => $this->transactionStatus->label()
        ];
    }
}
