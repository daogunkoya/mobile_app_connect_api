<?php

namespace App\Repositories;

use App\Models\Rate;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class RateRepository
{

    public static function fetchTodaysRate($useId = null): ?Rate
    {
// Use $userId if not null, otherwise use an empty strin
        return Rate::query()
            ->where('user_id', $userId ?? '')
            ->latest('created_at')
            ->first();


    }


}
