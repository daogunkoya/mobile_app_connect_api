<?php
namespace App\Services\Rate;
use App\Models\mm_commission;
use App\Models\mm_rate;
use App\Services\Helper;

class rate_service{


    //fetch todays rate
    public static function todays_rate(){
       
       
        $user_id =   user_id();
        $res = optional(mm_rate::whereIn('user_id', [$user_id])
                    ->select('main_rate', 'bou_rate', 'sold_rate', 'currency_id as currency')
                    ->orderBy('created_at', 'desc')
                    ->first())->toArray();

                    return $res;
    }

    public function store_rate($input, $rate_model){
    
     
       
       $rate =  $rate_model::create([

            'store_id'=>store_id(),
            'main_rate' => $input['main_rate']??"",
            'user_id'=> $input['user_id']??"",
            'currency_id'=> $input['currency_id']??"",
            'bou_rate'=> $input['bou_rate']??"",
            'sold_rate'=> $input['sold_rate']??"",
            
        ]);

        return $rate->id_rate;
    
    }


    public function update_rate($rate_id, $input, $rate_model){
    
     
       
        $rate =  $rate_model::where('id_rate', $rate_id)->update([
 
            'store_id'=>store_id(),
            'main_rate' => $input['main_rate']??"",
            'user_id'=> $input['user_id']??"",
            'currency_id'=> $input['currency_id']??"",
            'bou_rate'=> $input['bou_rate']??"",
            'sold_rate'=> $input['sold_rate']??"",
         ]);
 
         return $rate;
     
     }

    public function fetch_rate_list( $input, $rate_model, $currency_model, $user_model){
        $select = ['id_rate as rate_id',  'store_id','main_rate','user_id as user', 'currency_id as currency','bou_rate','sold_rate','created_at' ];
        $query = $rate_model::where('store_id', store_id())->where('rate_status', 1)->orderBy('created_at', 'DESC');
        $count = $query->count();
        $limit = $input['limit']??8;
        
        $currencies =  optional($currency_model::where('store_id', store_id())->limit(5)->select('currency_code as value','id_currency as key')->get())->toArray();
        $users =optional($user_model::where('store_id', store_id())->whereIn('user_role_type', [1,2])->select('user_name as value','id_user as key')->get())->toArray();

         ////pagination
         if(!empty($input['cursor'])){
             $rate_id = optional($query->select('id_rate')->get())->toArray();
             $key =  Helper::find_key($rate_id,'id_rate',$input['cursor']);  
             $page_start = $key ===false?0:$key+1;
            // var_dump($page_start);

        }
        
        $rate_list = optional($query->select($select)->skip($page_start??0)->limit($limit)->get())->toArray();
        return [
            'rate_count'=>$count,
            'rate' =>$rate_list,
            'currencies' => $currencies,
            'users'=>$users 

        ];

    }
    public function fetch_rate($rate_id,  $rate_model, $currency_model, $user_model){
        $select = ['id_rate as rate_id',  'store_id','main_rate','user_id', 'currency_id','bou_rate','sold_rate' ,];
        $rate =$rate_model::select($select)->where('store_id', store_id())->where('id_rate', $rate_id)->where('rate_status', 1)->first();
       
        $currencies =  optional($currency_model::where('store_id', store_id())->pluck('currency_code','id_currency'))->toArray();
        $users = optional($user_model::where('store_id', store_id())->whereIn('user_role_type', [1,2])->pluck('user_name','id_user'))->toArray();
      
        $users['0'] = 'All'; 
        $users = collect($users)->reverse()->toArray();

        return [
            'rate' =>$rate,
            'currencies' => $currencies,
            '$users'=>$users 

        ];
    }


    public function delete_rate($rate_id, $rate_model){

        return $rate_model::where('id_rate', $rate_id)->update(['rate_status'=>0]);
    }


}