<?php

namespace App\DTO;

use App\Models\Sender;
use App\Models\Transaction;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use App\Enum\TransactionStatus;

class TransactionDto
{
    public function __construct(
        public string $createdAt,
        public string $transactionId,
        public string $transactionCode,
        public string $userId,
        public string $senderId,
        public string $senderAddress,
        public string $receiverAddress,
        public string $currencyId,
        public string $receiverFname,
        public string $receiverLname,
        public string $receiverPhone,
        public string $receiverBankId,
        public ?string $receiverIdentityId,
        public string $receiverAccountNumber,
        public ?string $receiverTransferType,
        public string $amountSent,
        public string $localAmount,
        public string $totalAmount,
        public string $totalCommission,
        public string $agentCommission,
        public string $exchangeRate,
        public string $currencyIncome,
        public string $note,
        public string $transactionType,
        public TransactionStatus $transactionStatus,

    )
    {
    }

    public static function fromEloquentModel(Transaction $transaction): TransactionDto
    {
        return new self(
            $transaction->created_at,
            $transaction->id_transaction,
            $transaction->transaction_code,
            $transaction->user_id,
            $transaction->sender_id,
            $transaction->sender_address,
            $transaction->receiver_address,
            $transaction->currency_id,
            $transaction->receiver_fname,
            $transaction->receiver_lname,
            $transaction->receiver_phone,
            $transaction->receiver_bank_id,
            $transaction->receiver_identity_id,
            $transaction->receiver_account_no,
            $transaction->receiver_transfer_type,
            $transaction->amount_sent,
            $transaction->local_amount,
            $transaction->total_amount,
            $transaction->total_commission,
            $transaction->agent_commission,
            $transaction->exchange_rate,
            $transaction->currency_income,
            $transaction->note,
            $transaction->transaction_type,
            $transaction->transaction_status
        );
    }

//    public static function fromEloquentModelCollection(LengthAwarePaginator $transactionList): Collection
//    {
//        return new Collection([
//            'data' => $transactionList->map(fn(Transaction $transaction) => self::fromEloquentModel($transaction)),
//            'total' => $transactionList->total(),
//            'perPage' => $transactionList->perPage(),
//            'currentPage' => $transactionList->currentPage(),
//            'path' => LengthAwarePaginator::resolveCurrentPath(),
//        ]);
//    }

    public static function fromEloquentModelCollection(LengthAwarePaginator $transactionList):LengthAwarePaginator
    {
        $mappedItems = collect($transactionList->items())
            ->map(fn(Transaction $transaction) => self::fromEloquentModel($transaction));

                return (new LengthAwarePaginator(
                            $mappedItems ,
                            $transactionList->total(),
                            $transactionList->perPage(),
                            $transactionList->currentPage(),
                             ['path' => LengthAwarePaginator::resolveCurrentPath()]
        ));




    }


}
