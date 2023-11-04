<?php

namespace App\Repositories;

use App\models\Currency;
use App\Models\User;

class CurrencyRepository
{

    public function fetchCurrencies()
    {
        return Currency::optional(query()->where('status', 1)
            ->select('code', 'destination')->get())
            ->toArray();
    }

    public function fetchUserCurrencyId(string $userId = null): ?string
    {
        if (!empty($userId)) {
            return User::where('id_user', $userId)->value('active_currency_id');
        }
        return Currency::where('default_currency', 1)->value('id_currency');
    }

}
