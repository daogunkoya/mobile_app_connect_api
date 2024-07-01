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
        public string $rateId,
        public string $userId,
        public string $mainRate,
        public ?string $bouRate,
        public ?string $soldRate,
        public ?string $currencyId,

    )
    {
    }

    public static function fromEloquentModel(Rate $rate): self
    {
        return new self(
            $rate->id_rate,
            $rate->user_id,
            $rate->main_rate,
            $rate->bou_rate,
            $rate->sold_rate,
            $rate->currency_id,

        );
    }



}
