<?php
namespace App\Services\Currency;

use App\Services\Helper;

class CurrencyService{

    public function storeCurrency($input, $currency_model){



       $currency =  $currency_model::create([

          'store_id'=>store_id(),
            'user_id'=>$input['user']['id_user']??'',
            'currency_origin' =>$input['currency_origin']??'',
            'currency_origin_symbol' => $input['currency_origin_symbol']??'',
            'currency_destination' => $input['currency_destination']??'',
            'currency_destination_symbol'  => $input['currency_destination_symbol']??'',
            'currency_code'  => $input['currency_code']??'',
            'income_category'  => $input['income_category']??'',



        ]);




        return $currency->id_currency;

    }


    public function updateCurrency($currency_id, $input, $currency_model){



        $currency =  $currency_model::where('id_currency', $currency_id)->update([
            'store_id'=>store_id(),
            'user_id'=>$input['user']['id_user']??'',
            'currency_origin' =>$input['currency_origin']??'',
            'currency_origin_symbol' => $input['currency_origin_symbol']??'',
            'currency_destination' => $input['currency_destination']??'',
            'currency_destination_symbol'  => $input['currency_destination_symbol']??'',
            'currency_code'  => $input['currency_code']??'',
            'income_category'  => $input['income_category']??'',

         ]);

         return $currency;

     }

    public function fetchCurrencyList($input, $currency_model){
        $select = [ 'user_id','currency_origin', 'currency_origin_symbol','currency_destination', 'currency_destination_symbol','currency_code','income_category',];

        $query = $currency_model::where('store_id', store_id())->where('currency_status', 1)->orderBy('created_at', 'DESC');
        $count = $query->count();
        $limit = $input['limit']??8;



         ////pagination
         if(!empty($input['cursor'])){
            $currency_id = optional($query->select('id_currency')->get())->toArray();
            $key =  Helper::find_key($currency_id,'id_currency',$input['cursor']);
            $page_start = $key ===false?0:$key+1;
        }

        $currency_list = optional($query->select($select)->skip($page_start??0)->limit($limit)->get())->toArray();
        return [
            'currency_count'=>$count,
            'currency' =>$currency_list,



        ];

    }
    public function fetchCurrency($currency_id, $currency_model){
        $select = [ 'user_id','currency_origin', 'currency_origin_symbol','currency_destination', 'currency_destination_symbol','currency_code','income_category',];
        $currency =$currency_model::select($select)->where('store_id', store_id())->where('id_currency', $currency_id)->where('currency_status', 1)->first();



        return [
            'currency' =>$currency,
        ];
    }


    public function deleteCurrency($currency_id, $currency_model){

        return $currency_model::where('id_currency', $currency_id)->update(['currency_status'=>0]);
    }


}
