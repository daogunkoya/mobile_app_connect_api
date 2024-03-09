<?php

namespace App\Actions;

use App\Models\Rate;

class FetchUserRate
{

    public function handle($userId = null): Rate
    {

        return Rate::query()
            ->select('main_rate', 'bou_rate', 'sold_rate', 'currency_id as currency')
            ->where('user_id', $userId ?? '')
            ->latest('created_at')
            ->first();

    }

}
