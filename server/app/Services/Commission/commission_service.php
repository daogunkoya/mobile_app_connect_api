<?php
namespace App\Services\Commission;
use App\Models\mm_commission;
use App\Services\Helper;

class commission_service{

    public function store_commission($input, $commission_model){
    
     
       
       $commission =  $commission_model::create([

            'store_id'=>store_id(),
            'start_from' => $input['start_from']??"",
            'end_at'=> $input['end_at']??"",
            'value'=> $input['value']??"",
            'agent_quota'=> $input['agent_quota']??"",
            'user_id'=> $input['user']['id_user']??"",
            'currency_id' => $input['currency_id']??"",
        ]);

        return $commission->id_commission;
    
    }


    public function update_commission($commission_id, $input, $commission_model){
    
     
       
       $commission =  $commission_model::where('id_commission', $commission_id)->update([

            'store_id'=>store_id(),
            'start_from' => $input['start_from']??"",
            'end_at'=> $input['end_at']??"",
            'value'=> $input['value']??"",
            'agent_quota'=> $input['agent_quota']??"",
            'user_id'=> $input['user']['id_user']??"",
            'currency_id' => $input['currency_id']??"",
        ]);

        return $commission;
    
    }

    

    public function fetch_commission_list( $input, $commission_model, $currency_model, $user_model){
        $select = ['id_commission as commission_id','store_id','user_id','start_from','end_at','value','agent_quota','user_id', 'currency_id',];
        $query = $commission_model::where('store_id', store_id())->where('commission_status', 1)->orderBy('created_at', 'DESC');
        $count = $query->count();
        $limit = $input['limit']??8;
        
        $currencies =  optional($currency_model::where('store_id', store_id())->pluck('currency_code','id_currency'))->toArray();
        $users =optional($user_model::where('store_id', store_id())->whereIn('user_role_type', [1,2])->pluck('user_name','id_user'))->toArray();

         ////pagination
         if(!empty($input['cursor'])){
            $commission_id = optional($query->select('id_commission')->get())->toArray();
            $key =  Helper::find_key($commission_id,'id_commission',$input['cursor']);  
            $page_start = $key ===false?0:$key+1;
        }
        
        $commission_list = optional($query->select($select)->skip($page_start??0)->limit($limit)->get())->toArray();
        return [
            'commission_count'=>$count,
            'commission' =>$commission_list,
            'currencies' => $currencies,
            'users'=>$users 

        ];

    }
    public function fetch_commission($commission_id,  $commission_model, $currency_model, $user_model){
        $select = ['id_commission as commission_id','store_id','user_id','start_from','end_at','value','agent_quota','user_id', 'currency_id',];
        $commission =$commission_model::select($select)->where('store_id', store_id())->where('id_commission', $commission_id)->where('commission_status', 1)->first();
       
        $currencies =  optional($currency_model::where('store_id', store_id())->pluck('currency_code','id_currency'))->toArray();
        $users = optional($user_model::where('store_id', store_id())->whereIn('user_role_type', [1,2])->pluck('user_name','id_user'))->toArray();
      
        $users['0'] = 'All'; 
        $users = collect($users)->reverse()->toArray();

        return [
            'commission' =>$commission,
            'currencies' => $currencies,
            '$users'=>$users 

        ];
    }


    public function delete_commission($commission_id, $commission_model){

        return $commission_model::where('id_commission', $commission_id)->update(['commission_status'=>0]);
    }


}