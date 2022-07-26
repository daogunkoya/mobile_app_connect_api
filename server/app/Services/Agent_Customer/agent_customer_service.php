<?php
namespace App\Services\Agent_Customer;
use App\Models\mm_agent_customer;
use Illuminate\Support\Facades\DB;
use App\Services\Helper;
use App\Models\mm_log_device;
use Illuminate\Support\Facades\Http;
use App\Models\mm_user;
use Carbon\Carbon;

class agent_customer_service{


    //fetch customer
    public  function fetch_agent_customer($request){
        $user_id = "2bda0c37-4eac-44e5-a014-6c029d76dc62";
        $input = $request->all();
        $search =!empty($input['search']) && $input['search'] !="null" ? "%".$input['search']."%":'%';
       
        $select = ['id_customer as customer_id','id_customer as count_customer_receiver','user_id','customer_title','customer_name', 'customer_mname','customer_fname','customer_lname','customer_dob', 'customer_email', 'customer_phone', 'customer_mobile', 'customer_address','customer_postcode' ];
        
        $query = mm_agent_customer::where('user_id', $user_id)->where('customer_name', 'like', $search)->orderBy('created_at', 'DESC');
        $count  =  $query->count();
        $limit = $input['limit']??8;
        
        ////pagination
        if(!empty($input['cursor'])){
            $customer_id = optional($query->select('id_customer')->get())->toArray();
            $key =  Helper::find_key($customer_id,'id_customer',$input['cursor']);  
            $page_start = $key ===false?0:$key+1;
        }

        $agent_customer  =  optional($query->select($select)->skip($page_start??0)->limit($limit)->get())->toArray();
            return  ['agent_customer_count'=>$count,'agent_customer'=>$agent_customer];
    }


    //create new customer
    public  function create_customer($input, $user_id){
        
        if(!empty($input)){
          $new_customer =   mm_agent_customer::create([
                "user_id"=> $user_id,
                'store_id'=>session()->get('process_store_id')??request()->process_store_id,
                "customer_title"=> $input['customer_title']??'',
                "customer_name"=>  $input['customer_name']??'',
                "customer_mname"=> $input['customer_name']??'',
                "customer_fname"=>  $input['customer_fname']??'',
                "customer_lname"=>  $input['customer_lname']??'',
                "customer_dob"=>  $input['customer_dob']??'',
                "customer_email"=>  $input['customer_email']??'',
                "customer_phone"=>  $input['customer_phone']??'',
                "customer_mobile"=>  $input['customer_mobile']??'',
                "customer_address"=>  $input['customer_address']??'',
                "customer_postcode"=>  $input['customer_postcode']??'',
                "photo_id"=> ''
            ]);

            return $new_customer->id_customer;
            
        }

    }


    //update customer
    public  function update_customer($input, $user_id, $customer_id){
        
        if(!empty($input)){
          $update_customer =   mm_agent_customer::where('id_customer', $customer_id)->update([
                "user_id"=> $user_id,
                'store_id'=>session()->get('process_store_id')??request()->process_store_id,
                "customer_title"=> $input['customer_title']??'',
                "customer_name"=>  $input['customer_name']??'',
                "customer_mname"=> $input['customer_name']??'',
                "customer_fname"=>  $input['customer_fname']??'',
                "customer_lname"=>  $input['customer_lname']??'',
                "customer_dob"=>  $input['customer_dob']??'',
                "customer_email"=>  $input['customer_email']??'',
                "customer_phone"=>  $input['customer_phone']??'',
                "customer_mobile"=>  $input['customer_mobile']??'',
                "customer_address"=>  $input['customer_address']??'',
                "customer_postcode"=>  $input['customer_postcode']??'',
                "photo_id"=> ''
            ]);

            return $update_customer;
            
        }

    }
    
}