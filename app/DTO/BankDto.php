<?php

namespace App\DTO;

use App\Models\Bank;
use App\Models\Sender;
use Illuminate\Support\Collection;


class BankDto
{
    public function __construct(
        public string $id,
        public string $name,

    )
    {
    }

    public static function fromEloquentModel(Bank $bank): BankDto
    {
        return new self(
            $bank->id,
            $bank->name,
        );
    }


}
