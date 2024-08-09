<?php

namespace App\DTO;

use App\Models\AcceptableIdentity;
use App\Models\Bank;
use App\Models\Rate;
use App\Models\Receiver;
use App\Models\Sender;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;


class RateDto
{
    public function __construct(
        public string $createdAt,
        public string $rateId,
        public string $userId,
        public ?string $memberUserId,
        public string $mainRate,
        public ?string $bouRate,
        public ?string $soldRate,
        public CurrencyDto | null $currency,

    )
    {
    }

    public static function fromEloquentModel(Rate $rate): self
    {
        return new self(
            $rate->created_at,
            $rate->id_rate,
            $rate->user_id,
            $rate->member_user_id,
            $rate->main_rate,
            $rate->bou_rate,
            $rate->sold_rate,
            $rate->currency ? CurrencyDto::fromEloquentModel($rate->currency) : null, 

        );
    }
   

    public static function fromEloquentModelCollection(LengthAwarePaginator $rateList): LengthAwarePaginator
    {

        return new LengthAwarePaginator(
            collect($rateList->items())->map(fn( Rate $rate) => self::fromEloquentModel($rate)),
            $rateList->total(),
            $rateList->perPage(),
            $rateList->currentPage(),
            ['path' => LengthAwarePaginator::resolveCurrentPath()]
        );


    }



}
