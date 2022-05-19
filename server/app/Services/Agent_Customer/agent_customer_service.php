<?php
namespace App\Services\Agent_Customer;
use App\Models\mm_agent_customer;
use Illuminate\Support\Facades\DB;

use App\Models\mm_log_device;
use Illuminate\Support\Facades\Http;
use App\Models\mm_user;
use Carbon\Carbon;

class agent_customer_service{


    //fetch customer
    public  function fetch_agent_customer(){
        $select = ['id_customer as customer_id','user_id','customer_title','customer_name', 'customer_mname','customer_fname','customer_lname','customer_dob', 'customer_email', 'customer_phone', 'customer_mobile', 'customer_address','customer_postcode' ];
        $agent_customer  =  optional(mm_agent_customer::select($select)->limit(20)->get())->toArray();
        $count  =  mm_agent_customer::count();
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