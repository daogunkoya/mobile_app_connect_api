<?php

namespace App\Repositories;

use App\Models\AcceptableIdentity;

class IdentityRepository
{

    public static function fetchIdentityList(): array
    {


        $proof_id_list = optional(
            AcceptableIdentity::select(
                'id',
                'name',
            )->get()
        )->toArray();

        return ['proof_id' => $proof_id_list];
    }
}
