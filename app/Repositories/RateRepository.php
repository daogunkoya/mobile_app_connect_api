<?php

namespace App\Repositories;

use App\Models\Rate;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use App\Enum\UserRoleType;
use App\Models\Currency;
use Illuminate\Http\Request;
use App\DTO\UserDto;
use App\Filters\RateFilter;

class RateRepository
{

    public function __construct(protected RateFilter $rateFilter)
    {
       
    }

    public function fetchRate(Request $request, UserDto $user)
    {
          

        $isAdmin = $user->userRoleType == UserRoleType::ADMIN;
      
        $rateQuery =   Rate::query();
        $currencyId = $request->input('currency_id') ?? Currency::whereDefaultCurrency(1)->value('id_currency');

        //this two will soon be hidden as there is ratefilter
        // if(empty($request->input('currency_id'))) $rateQuery->where('currency_id', $currencyId);
        // if(!$isAdmin) $rateQuery->where('user_id', $user->userId);
        
       $rateQuery->with(['user','currency'])->filter($this->rateFilter)
            ->select('id_rate', 'main_rate', 'user_id', 'member_user_id','currency_id', 'bou_rate', 'sold_rate', 'created_at');


        $rateQuery->orderBy('created_at', 'DESC')->where('rate_status', 1);

        $page = $request->input('page') ?? 1;
        $limit = $request->input('limit') ?? 6;

        return $rateQuery->paginate($limit, ['*'], 'page', $page);
    }

    public static function fetchTodaysRate($userId = null, $currencyId = null): ?Rate
    {
// Use $userId if not null, otherwise use an empty strin
        return Rate::query()
            ->with('currency')
            ->forUserAndCurrency($userId, $currencyId) // Apply the scope
            ->select('id_rate','main_rate','user_id' ,'bou_rate', 'sold_rate', 'currency_id')
            ->latest('created_at')
            ->first();

    }


    //this is be further review
    public static function todaysRate()
    {
        $userExistOnRate = Rate::where('user_id', auth()->id())->exists();
        // var_dump($user_id);
        return optional(Rate::query()
            ->when($userExistOnRate, function ($query) {
                return $query->where('user_id', auth()->id());
            })
            ->select('main_rate', 'bou_rate', 'sold_rate', 'currency_id as currency')
            ->orderBy('created_at', 'desc')
            ->first())->toArray();
    }

    public function deleteRate($rate_id)
    {

        $rateQuery =   Rate::query();
        return $rateQuery->where('id_rate', $rate_id)->update(['rate_status' => 0]);
    }


}
