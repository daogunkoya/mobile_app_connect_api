<?php

namespace App\DTO;

use App\Models\AcceptableIdentity;
use App\Models\Bank;
use App\Models\Sender;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;


class BankAcceptableIdentityDto
{
    public function __construct(
        public Collection $banks,
        public Collection $acceptableIdentities,

    ) {
    }


    public static function fromEloquentModelCollection(
        EloquentCollection $banks,
        EloquentCollection $acceptableIdentities
    ): BankAcceptableIdentityDto {
        return new self(
            collect($banks)->map(fn (Bank $bank) => BankDto::fromEloquentModel($bank)),
            collect($acceptableIdentities)->map(fn (AcceptableIdentity $identity) => AcceptabeIdentityDto::fromEloquentModel($identity)),
        );
    }
}
