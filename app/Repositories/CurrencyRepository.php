<?php

namespace App\Repositories;

use App\models\Currency;
use App\Models\User;
use App\Filters\CurrencyFilter;

class CurrencyRepository
{

    public function __construct(protected CurrencyFilter $currencyFilter)
    {
        
    }

    public function fetchCurrencies($input)
    {
        
      
        $currencyQuery =   Currency::query();
       
       $currencyQuery->filter($this->currencyFilter)
            ->select('id_currency', 'currency_country', 'currency_title', 'currency_symbol', 'currency_type', 'default_currency', 'currency_status');


        $currencyQuery->orderBy('created_at', 'DESC');

        $page = $input['page'] ?? 1;
        $limit = $input['limit'] ?? 15;

        return $currencyQuery->paginate($limit, ['*'], 'page', $page);
    }

    public function toggleCurrencyStatus($currency)
    {
        // Toggle the currency status
        $statusUpdated = $currency->update(['currency_status' => $currency->currency_status == 1 ? 0 : 1]);
    
        // If update was successful, return the fresh instance of the model
        if ($statusUpdated) {
            return $currency->fresh();
        }
    
        // If the update failed, you might want to return null or handle the error as needed
        return null;
    }
    
    public function fetchUserCurrencyId(string $userId = null): ?string
    {
        if (!empty($userId)) {
            return User::where('id_user', $userId)->value('active_currency_id');
        }
        return Currency::where('default_currency', 1)->value('id_currency');
    }

}
