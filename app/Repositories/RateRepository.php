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
            ->select('main_rate', 'bou_rate', 'sold_rate', 'currency_id as currency')
            ->where('user_id', $userId ?? '')
            ->latest('created_at')
            ->first();


    }



    //this is be further review
    public static function todaysRate()
    {


        $userExistOnRate = Rate::where('user_id', auth()->id())->exists();
       // var_dump($user_id);
       return  optional(Rate::query()
           ->when($userExistOnRate, function($query){
            return $query->where('user_id', auth()->id());
        })
            ->select('main_rate', 'bou_rate', 'sold_rate', 'currency_id as currency')
            ->orderBy('created_at', 'desc')
            ->first())->toArray();


    }


}
