<?php

namespace App\DTO;

use App\Models\Sender;
use App\Models\UserCurrency;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;


class SenderDto
{
    public function __construct(
        public string $senderId,
        public string $userId,
        public string|null $senderTitle,
        public string $createdAt,
        public string $senderName,
        public string|null $senderMname,
        public string $senderFname,
        public string $senderLname,
        public string $senderDob,
        public string $senderEmail,
        public string $senderPhone,
        public string|null $senderMobile,
        public string $senderAddress,
        public string $senderPostcode,
        public array|null  $metadata,
        public UserCurrencyDto | null $userCurrencies, 
        public        $countSenderReceiver
    )
    {
    }

    public static function fromEloquentModel(Sender $sender): SenderDto
    {
        return new self(
            $sender->id_sender,
            $sender->user_id,
            $sender->sender_title,
            $sender->created_at,
            $sender->sender_name,
            $sender->sender_mname,
            $sender->sender_fname,
            $sender->sender_lname,
            $sender->sender_dob,
            $sender->sender_email,
            $sender->sender_phone,
            $sender->sender_mobile,
            $sender->sender_address,
            $sender->sender_postcode,
            $sender->metadata,
            $sender->latestSenderCurrency ? UserCurrencyDto::fromEloquentModel($sender->latestSenderCurrency) : null,
            $sender->receiver->count(),
        );
    }

    public static function fromEloquentModelCollection(LengthAwarePaginator $senderList): LengthAwarePaginator
    {

        return new LengthAwarePaginator(
            collect($senderList->items())->map(fn( Sender $sender) => self::fromEloquentModel($sender)),
            $senderList->total(),
            $senderList->perPage(),
            $senderList->currentPage(),
            ['path' => LengthAwarePaginator::resolveCurrentPath()]
        );


    }

}
