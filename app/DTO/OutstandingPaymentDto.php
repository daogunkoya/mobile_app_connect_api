<?php

namespace App\DTO;

use App\Models\AcceptableIdentity;
use App\Models\Bank;
use App\Models\OutstandingPayment;
use App\Models\Rate;
use App\Models\Receiver;
use App\Models\Sender;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;


class OutstandingPaymentDto
{
    public function __construct(
        public ?string $outstandingPaymentId,
        public string $createdAt,
        public string $senderName,
        public string $receiverName,
        public string $userId,
        public string $transactionId,
        public string $transactionCode,
        public ?string $currencyId,
        public string $totalAmount,
        public string $amountSent,
        public string $localAmount,
        public ?string $totalCommission,
        public ?string $agentCommission,
        public ?string $exchangeRate,
        public ?string $bouRate,
        public ?string $soldRate,
        public int $transactionPaidStatus,
        public int $commissionPaidStatus

    )
    {
    }

    public static function fromEloquentModel(OutstandingPayment $outstandingPayment): self
    {
        return new self(
            $outstandingPayment->id_outstanding,
            $outstandingPayment->created_at,
            $outstandingPayment->sender_name,
            $outstandingPayment->receiver_name,
            $outstandingPayment->user_id,
            $outstandingPayment->transaction_id,
            $outstandingPayment->transaction_code,
            $outstandingPayment->currency_id,
            $outstandingPayment->total_amount,
            $outstandingPayment->amount_sent,
            $outstandingPayment->local_amount,
            $outstandingPayment->total_commission,
            $outstandingPayment->agent_commission,
            $outstandingPayment->exchange_rate,
            $outstandingPayment->bou_rate,
            $outstandingPayment->sold_rate,
            $outstandingPayment->transaction_paid_status,
            $outstandingPayment->commission_paid_status

        );
    }
   

    public static function fromEloquentCollection(LengthAwarePaginator $outstandingPaymentList): LengthAwarePaginator
    {

        return new LengthAwarePaginator(
            collect($outstandingPaymentList->items())->map(fn( OutstandingPayment $outstandingPayment) => self::fromEloquentModel($outstandingPayment)),
            $outstandingPaymentList->total(),
            $outstandingPaymentList->perPage(),
            $outstandingPaymentList->currentPage(),
            ['path' => LengthAwarePaginator::resolveCurrentPath()]
        );


    }



}
