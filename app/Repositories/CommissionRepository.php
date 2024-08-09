<?php

namespace App\Repositories;


use App\Models\Commission;
use App\Repositories\RateRepository;
use App\Services\Commission\CommissionService;
use App\Services\Rate\RateService;
use illuminate\Http\Request;
use App\DTO\UserDto;
use App\Enum\UserRoleType;
use App\Models\Currency;
use App\Filters\CommissionFilter;   

class CommissionRepository
{

    public function __construct(protected CommissionFilter $commissionFilter)
    {
        
    }

    public function fetchCommissions(Request $request, UserDto $user)
    {
          

        $isAdmin = $user->userRoleType == UserRoleType::ADMIN;
      
        $commissionQuery =   Commission::query();
        $currencyId = $request->input('currency_id') ?? Currency::whereDefaultCurrency(1)->value('id_currency');

        if(empty($request->input('currency_id'))) $commissionQuery->where('currency_id', $currencyId);
        if(!$isAdmin) $commissionQuery->where('user_id', $user->userId);
        
       $commissionQuery->with(['currency', 'member_user'])->filter($this->commissionFilter)
            ->select('id_commission', 'start_from', 'end_at', 'value', 'agent_quota','user_id', 'created_at', 'currency_id','member_user_id', 'commission_status');


        $commissionQuery->orderBy('start_from', 'asc')->where('commission_status', 1);

        $page = $request->input('page') ?? 1;
        $limit = $request->input('limit') ?? 6;

        return $commissionQuery->paginate($limit, ['*'], 'page', $page);
    }
    public function deleteRate($commissionId)
    {
        return Commission::query()->where('id_commission', $commissionId)->update(['commission_status' => 0]);
    }


    public function fetchCommissionValue($input):array
    {
        $sendAmount = (float)$input['amount'] ?? 0;
        $conversionType = (integer)$input['conversion_type'] ?? 1;
        $todaysRate = RateRepository::fetchTodaysRate();
        $rate = $todaysRate['main_rate'] ?? 0;

        // Evaluate based on convert type - 1 = sending amount and 2  = local amount conversion
        $localAmount =  ($conversionType == 2) ? $sendAmount: $sendAmount * $rate;
        $sendAmount = ($conversionType == 2) ? $sendAmount / $rate : $sendAmount;

        $commissionValueData = self::getCommissionValue($sendAmount);

        // Commission value defined based on % of the amount if commission value is decimal
        $commissionValue = ($commissionValueData->value < 1) ?
            number_format($commissionValueData->value * $sendAmount, 2) :
            number_format($$commissionValueData->value, 2);

        return [
            'rate' => number_format($rate, 2),
            'local' => number_format($localAmount, 2),
            //'value' => number_format($commissionValueData['value'] ?? 0, 2),
            'agent_quota' => $commissionValueData->agent_quota ?? 50,
            'commission_value' => $commissionValue,
            'total_amount' => number_format($commissionValue + $sendAmount, 2),
            'send_amount' => number_format($sendAmount, 2),
            'conversion_type' => $conversionType,
        ];
    }

    public static function getCommissionValue($amount, $userId = null, $currencyId = null): ?Commission
    {
        return Commission::query()
            ->forUserAndCurrency($userId, $currencyId) // Apply the scope
            ->whereRaw('? between start_from and end_at', [$amount]) // Include $amount condition directly
            ->select('id_commission', 'user_id', 'start_from', 'end_at', 'value', 'agent_quota')
            ->orderBy('start_from', 'asc')
            ->orderBy('end_at', 'asc')
            ->first();
    }


}
