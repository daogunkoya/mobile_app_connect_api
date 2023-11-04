<?php

namespace App\Services\Commission;

use App\Models\Commission;
use App\Services\Helper;

class CommissionService
{
    private static $user_currency;

    public function storeCommission($input, $commission_model)
    {



        $commission =  $commission_model::create([

            'store_id' => store_id(),
            'start_from' => $input['start_from'] ?? "",
            'end_at' => $input['end_at'] ?? "",
            'value' => $input['value'] ?? "",
            'agent_quota' => $input['agent_quota'] ?? "",
            'user_id' => $input['user_id'] ?? "",
            'currency_id' => $input['currency_id'] ?? "",
        ]);

        return $commission->id_commission;
    }



    //update commission
    public function updateCommission($commission_id, $input, $commission_model)
    {



        $commission =  $commission_model::where('id_commission', $commission_id)->update([

            'store_id' => store_id(),
            'start_from' => $input['start_from'] ?? "",
            'end_at' => $input['end_at'] ?? "",
            'value' => $input['value'] ?? "",
            'agent_quota' => $input['agent_quota'] ?? "",
            'user_id' => $input['user_id'] ?? "",
            'currency_id' => $input['currency_id'] ?? "",
        ]);

        return $commission;
    }



    //fetch list of commission

    public function fetchCommissionList($input, $commission_model, $currency_model, $user_model)
    {
        $select = ['id_commission as commission_id','store_id','user_id as user', 'currency_id as currency','start_from','end_at','value','agent_quota','user_id', 'currency_id',];
        $query = $commission_model::where('store_id', store_id())->where('commission_status', 1)->orderBy('start_from');
        $count = $query->count();
        $limit = $input['limit'] ?? 8;

        // $currencies =  optional($currency_model::where('store_id', store_id())->pluck('currency_code','id_currency'))->toArray();
        // $users =optional($user_model::where('store_id', store_id())->whereIn('user_role_type', [1,2])->pluck('user_name','id_user'))->toArray();

        $currencies =  optional($currency_model::where('store_id', store_id())->limit(5)->select('currency_code as value', 'id_currency as key')->get())->toArray();
        $users = optional($user_model::where('store_id', store_id())->whereIn('user_role_type', [1,2])->select('user_name as value', 'id_user as key')->get())->toArray();

         ////pagination
        if (!empty($input['cursor'])) {
            $commission_id = optional($query->select('id_commission')->get())->toArray();
            $key =  Helper::find_key($commission_id, 'id_commission', $input['cursor']);
            $page_start = $key === false ? 0 : $key + 1;
        }

        $commission_list = optional($query->select($select)->skip($page_start ?? 0)->limit($limit)->get())->toArray();
        return [
            'commission_count' => $count,
            'commission' => $commission_list,
            'currencies' => $currencies,
            'users' => $users

        ];
    }

// fetch a commission
    public function fetchCommission($commission_id, $commission_model, $currency_model, $user_model)
    {
        $select = ['id_commission as commission_id','store_id','user_id','start_from','end_at','value','agent_quota','user_id', 'currency_id',];
        $commission = $commission_model::select($select)->where('store_id', store_id())->where('id_commission', $commission_id)->where('commission_status', 1)->first();

        $currencies =  optional($currency_model::where('store_id', store_id())->pluck('currency_code', 'id_currency'))->toArray();
        $users = optional($user_model::where('store_id', store_id())->whereIn('user_role_type', [1,2])->pluck('user_name', 'id_user'))->toArray();

        $users['0'] = 'All';
        $users = collect($users)->reverse()->toArray();

        return [
            'commission' => $commission,
            'currencies' => $currencies,
            '$users' => $users

        ];
    }





    //delete commission  row
    public function deleteCommission($commission_id, $commission_model)
    {

        return $commission_model::where('id_commission', $commission_id)->update(['commission_status' => 0]);
    }











    public static function fetchCommissionValue($amount)
    {


        self::$user_currency = user_currency();
        $currency_id = self::$user_currency;
        $user_id = store_user_id();
      // var_dump($user_id);
                $commission_data =   optional(Commission::
                                                whereIn('user_id', [$user_id])
                                                ->whereIn('currency_id', [$currency_id])
                                                ->whereRaw('? between start_from and end_at', [$amount])
                                                ->select('value', 'agent_quota')
                                                ->orderBy('start_from', 'asc')
                                                ->orderBy('end_at', 'asc')
                                                ->first())->toArray();


       // return $commission_data;
        return [
            'value' =>number_format( $commission_data['value'],2) ?? 0,
            'agent_quota' => $commission_data['agent_quota'] ?? 50,
            'commission_value'=> number_format($commission_data['value'],2) ?? 0,
            'total_amount'=> number_format($commission_data['value'] + $amount,2),
            'amount_from' => number_format($amount,2),
            'amount_to' => number_format($amount * $commission_data['value'], 2)
        ];
    }
}
