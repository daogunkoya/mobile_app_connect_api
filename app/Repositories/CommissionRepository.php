<?php

namespace App\Repositories;


use App\Models\Commission;
use App\Repositories\RateRepository;
use App\Services\Commission\CommissionService;
use App\Services\Rate\RateService;

class CommissionRepository
{

//    public static function fetchCommissionValue($amount)
//    {
//
//        self::$user_currency = user_currency();
//        $currency_id = self::$user_currency;
//        $user_id = store_user_id();
//        // var_dump($user_id);
//        $commission_data =   optional(Commission::
//        whereIn('user_id', [$user_id])
//            ->whereIn('currency_id', [$currency_id])
//            ->whereRaw('? between start_from and end_at', [$amount])
//            ->select('value', 'agent_quota')
//            ->orderBy('start_from', 'asc')
//            ->orderBy('end_at', 'asc')
//            ->first())->toArray();
//
//
//        // return $commission_data;
//        return [
//            'value' =>number_format( $commission_data['value'],2) ?? 0,
//            'agent_quota' => $commission_data['agent_quota'] ?? 50,
//            'commission_value'=> number_format($commission_data['value'],2) ?? 0,
//            'total_amount'=> number_format($commission_data['value'] + $amount,2),
//            'amount_from' => number_format($amount,2),
//            'amount_to' => number_format($amount * $commission_data['value'], 2)
//        ];
//    }

    public function fetchCommissionValue($input):array
    {
        $sendAmount = (float)$input['amount'] ?? 0;
        $conversionType = (integer)$input['conversion_type'] ?? 1;
        $todaysRate = RateRepository::fetchTodaysRate();
        $rate = $todaysRate['main_rate'] ?? 0;

        // Evaluate based on convert type - 1 = sending amount and 2  = local amount conversion
        $localAmount =  ($conversionType == 2) ? $sendAmount: $sendAmount * $rate;
        $sendAmount = ($conversionType == 2) ? $sendAmount / $rate : $sendAmount;

        $commissionValueData = $this->getCommissionValue($sendAmount);

        // Commission value defined based on % of the amount if commission value is decimal
        $commissionValue = ($commissionValueData['value'] < 1) ?
            number_format($commissionValueData['value'] * $sendAmount, 2) :
            number_format($commissionValueData['value'], 2);

        return [
            'rate' => number_format($rate, 2),
            'local' => number_format($localAmount, 2),
            //'value' => number_format($commissionValueData['value'] ?? 0, 2),
            'agent_quota' => $commissionValueData['agent_quota'] ?? 50,
            'commission_value' => $commissionValue,
            'total_amount' => number_format($commissionValue + $sendAmount, 2),
            'send_amount' => number_format($sendAmount, 2),
            'conversion_type' => $conversionType,
        ];
    }

    public function getCommissionValue($amount, $userId = null, $currencyId = null): array
    {
        $query = Commission::query()
            ->when(!is_null($currencyId), fn($query) => $query->whereIn('currency_id', [$currencyId]))
            ->when(!is_null($userId), fn($query) => $query->whereIn('user_id', [$userId]))
            ->when(!is_null($amount), fn($query) => $query->whereRaw('? between start_from and end_at', [$amount]))
            ->select('value', 'agent_quota')
            ->orderBy('start_from', 'asc')
            ->orderBy('end_at', 'asc')
            ->first();

        $commissionData = optional($query)->toArray();

        return [
            'value' => $commissionData['value'] ?? 0,
            'agent_quota' => $commissionData['agent_quota'] ?? 50,
            'commission_value' =>$commissionData['value'] ?? 0,
            'total_amount' => $commissionData['value'] + $amount,
            'amount_from' => $amount,
            'amount_to' =>$amount * $commissionData['value']
        ];
    }

}
