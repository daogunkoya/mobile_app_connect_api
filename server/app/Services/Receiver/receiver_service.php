<?php
namespace App\Services\Receiver;
use App\Models\mm_receiver;
use Illuminate\Support\Facades\DB;
use App\Services\Helper;
use App\Models\mm_log_device;
use Illuminate\Support\Facades\Http;
use App\Models\mm_user;
use Carbon\Carbon;

class receiver_service{


    //fetch receiver
    public  function fetch_receiver($request,$customer_id){


        $user_id = "2bda0c37-4eac-44e5-a014-6c029d76dc62";
        $input = $request->all();
        $select = ['id_receiver as receiver_id','user_id','receiver_title','id_receiver as receiver_name', 'receiver_mname','receiver_fname','receiver_lname','receiver_dob', 'receiver_email', 'receiver_phone', 'receiver_mobile', 'receiver_address','transfer_type','identity_type','account_number','bank' ];
        //$search =!empty($input['search'])? "%".$input['search']."%":'%';
        $search =!empty($input['search']) && $input['search'] !="null" ? "%".$input['search']."%":'%';

        $query = mm_receiver::where('user_id', $user_id)->where('customer_id', $customer_id)->where('receiver_name', 'like', $search)->orderBy('created_at', 'DESC');
        $count  =  $query->count();
        $limit = $input['limit']??20;
        
        ////pagination
        if(!empty($input['cursor'])){
            $receiver_id = optional($query->select('id_receiver')->get())->toArray();
            $key =  Helper::find_key($receiver_id,'id_receiver',$input['cursor']);  
            $page_start = $key ===false?0:$key+1;
        }

        $receivers  =  optional($query->select($select)->skip($page_start??0)->limit($limit)->get())->toArray();
    
            return  ['receiver_count'=>$count,'receiver'=>$receivers];




        // $receiver_query = mm_receiver::where('customer_id', $customer_id);
        // $select = ['id_receiver as receiver_id','user_id','receiver_title','id_receiver as receiver_name', 'receiver_mname','receiver_fname','receiver_lname','receiver_dob', 'receiver_email', 'receiver_phone', 'receiver_mobile', 'receiver_address','transfer_type','identity_type','account_number','bank' ];
        // $receiver  =  optional($receiver_query->select($select)->limit(20)->get())->toArray();
        // $count  =  $receiver_query->count();
        //     return ['receiver_count'=>$count,'receiver'=>$receiver];
    }


    //create new receiver
    public  function create_receiver($input, $customer_id){
        
        if(!empty($input)){
          $new_receiver =   mm_receiver::create([
                "user_id"=> $input['user']['id_user']??'',
                "user_type"=> 1,
                'store_id'=>session()->get('process_store_id')??request()->process_store_id,
                "customer_id"=> $customer_id,
                "receiver_title"=> $input['receiver_title']??'',
                "receiver_name"=>  $input['receiver_name']??'',
                "receiver_mname"=> $input['receiver_name']??'',
                "receiver_fname"=>  $input['receiver_fname']??'',
                "receiver_lname"=>  $input['receiver_lname']??'',
                "receiver_dob"=>  $input['receiver_dob']??'',
                "receiver_email"=>  $input['receiver_email']??'',
                "receiver_phone"=>  $input['receiver_phone']??'',
                "receiver_mobile"=>  $input['receiver_mobile']??'',
                "receiver_address"=>  $input['receiver_address']??'',
                "transfer_type"=>  $input['transfer_type']??'',
                "account_number"=>  $input['account_number']??'',
                "identity_type"=>  $input['identity_type']??'',
                "bank"=>  $input['bank']??'',
                "photo_id"=> ''
            ]);

            return $new_receiver->id_receiver;
            
        }

    }




    //updatereceiver
    public  function update_receiver($input,  $receiver_id){
        
        if(!empty($input)){
          $updated_receiver =   mm_receiver::where('id_receiver', $receiver_id)->update([
                "user_id"=> $input['user']['id_user']??'',
                "user_type"=> 1,
                'store_id'=>session()->get('process_store_id')??request()->process_store_id,
                "receiver_title"=> $input['receiver_title']??'',
                "receiver_name"=>  $input['receiver_name']??'',
                "receiver_mname"=> $input['receiver_name']??'',
                "receiver_fname"=>  $input['receiver_fname']??'',
                "receiver_lname"=>  $input['receiver_lname']??'',
                "receiver_dob"=>  $input['receiver_dob']??'',
                "receiver_email"=>  $input['receiver_email']??'',
                "receiver_phone"=>  $input['receiver_phone']??'',
                "receiver_mobile"=>  $input['receiver_mobile']??'',
                "receiver_address"=>  $input['receiver_address']??'',
                "transfer_type"=>  $input['transfer_type']??'',
                "account_number"=>  $input['account_number']??'',
                "identity_type"=>  $input['identity_type']??'',
                "bank"=>  $input['bank']??''
            ]);

            return 1;
            
        }

        return false;

    }
    
}