<?php

namespace App\DTO;

use App\Models\Sender;
use App\Models\Transaction;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use App\Enum\TransactionStatus;
use App\DTO\BankDto;
use App\Models\Bank;
use Illuminate\Contracts\Support\Arrayable;
use ReflectionClass;

class TransactionDto implements Arrayable
{
    public function __construct(
        public string $createdAt,
        public string $transactionId,
        public string $transactionCode,
        public string $userId,
        public string $senderId,
        public string $senderAddress,
        public ?string $receiverAddress,
        public ?string $originCurrencyId,
        public string $destinationCurrencyId,
        public string $senderFname,
        public string $senderLname,
        public string $receiverFname,
        public string $receiverLname,
        public string $receiverPhone,
        public BankDto $receiverBank,
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
            $transaction->origin_currency_id,
            $transaction->destination_currency_id,
            $transaction->sender_fname,
            $transaction->sender_lname,
            $transaction->receiver_fname,
            $transaction->receiver_lname,
            $transaction->receiver_phone,
            BankDto::fromEloquentModel($transaction->bank),
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

public static function fromEloquentModelCollection($transactionList)
{
    if ($transactionList instanceof LengthAwarePaginator) {
        $mappedItems = collect($transactionList->items())
            ->map(fn(Transaction $transaction) => self::fromEloquentModel($transaction));

        return new LengthAwarePaginator(
            $mappedItems,
            $transactionList->total(),
            $transactionList->perPage(),
            $transactionList->currentPage(),
            ['path' => LengthAwarePaginator::resolveCurrentPath()]
        );
    }

    if ($transactionList instanceof EloquentCollection) {
        return $transactionList->map(fn(Transaction $transaction) => self::fromEloquentModel($transaction));
    }

    throw new \InvalidArgumentException('Unsupported collection type');
}

    public function toArray(): array
    {
        $reflectionClass = new ReflectionClass($this);
        $properties = $reflectionClass->getProperties();
        $array = [];

        foreach ($properties as $property) {
            $propertyName = $property->getName();
            $propertyValue = $this->{$propertyName};

            // Check if the property is an object and has a toArray method
            if (is_object($propertyValue) && method_exists($propertyValue, 'toArray')) {
                $array[$propertyName] = $propertyValue->toArray();
            } else {
                $array[$propertyName] = $propertyValue;
            }
        }

        return $array;
    }

    public static function toArrayCollection($input): array
    {
        if ($input instanceof Collection || $input instanceof EloquentCollection || $input instanceof LengthAwarePaginator) {
            return $input->map(fn($item) => $item->toArray())->toArray();
        }

        throw new \InvalidArgumentException('Unsupported input type');
    }

   


}
