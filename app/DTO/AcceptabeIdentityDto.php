<?php

namespace App\DTO;

use App\Models\AcceptableIdentity;


class AcceptabeIdentityDto
{
    public function __construct(
        public string $id,
        public string $name,

    ) {
    }

    public static function fromEloquentModel(AcceptableIdentity $acceptableIdentity): AcceptabeIdentityDto
    {
        return new self(
            $acceptableIdentity->id,
            $acceptableIdentity->name,
        );
    }
}
