<?php

namespace App\DTO;

use App\Models\AcceptableIdentity;
use App\Models\Bank;
use App\Models\Receiver;
use App\Models\Sender;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;


class ReceiverDto
{
    public function __construct(
        public string $createdAt,
        public string $receiverId,
        public string $senderId,
        public string $receiverTitle,
        public string $receiverFname,
        public string $receiverLname,
        public string $receiverName,
        public string $receiverPhone,
        public string $receiverAddress,
        public string $accountNumber,
        public string $transferType,
        public string $identityTypeId,
        public string $bankId,
        public string $currencyId,
        public ?SenderDto $sender,
        public ?Bank $bank,
        public ?AcceptableIdentity  $identity,
    )
    {
    }

    public static function fromEloquentModel(Receiver $receiver): ReceiverDto
    {
        return new self(
            $receiver->created_at,
            $receiver->id_receiver,
            $receiver->sender_id,
            $receiver->receiver_title,
            $receiver->receiver_fname,
            $receiver->receiver_lname,
            $receiver->receiver_name,
            $receiver->receiver_phone,
            $receiver->receiver_address,
            $receiver->account_number,
            $receiver->transfer_type,
            $receiver->identity_type_id,
            $receiver->bank_id,
            $receiver->currency_id,
            $receiver->sender ? SenderDto::fromEloquentModel($receiver->sender) : null,
            $receiver->bank??null,
            $receiver->identity??null,
        );
    }

    public static function fromEloquentModelCollection( LengthAwarePaginator $receiverList): LengthAwarePaginator
    {
        $items = $receiverList->items();

        $transformedItems = collect($items)->map(fn ($receiver)  => self::fromEloquentModel($receiver) );

        $transformedPaginator = new LengthAwarePaginator(
            $transformedItems,
            $receiverList->total(),
            $receiverList->perPage(),
            $receiverList->currentPage(),
            ['path' => LengthAwarePaginator::resolveCurrentPath()]
        );

        return $transformedPaginator;
    }

}
