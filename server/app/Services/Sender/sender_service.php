<?php
namespace App\Services\Sender;
use App\Models\mm_sender;
use Illuminate\Support\Facades\DB;
use App\Services\Helper;
use App\Models\mm_log_device;
use Illuminate\Support\Facades\Http;
use App\Models\mm_user;
use Carbon\Carbon;

class sender_service{


    //fetch customer
    public  function fetch_agent_customer($request){
       // $user_id = "2bda0c37-4eac-44e5-a014-6c029d76dc62";
       $user_id = $request->user->id_user;
        $input = $request->all();
        $search =!empty($input['search']) && $input['search'] !="null" ? "%".$input['search']."%":'%';
       
        $select = ['id_sender as sender_id','id_sender as count_sender_receiver','user_id','sender_title','sender_name', 'sender_mname','sender_fname','sender_lname','sender_dob', 'sender_email', 'sender_phone', 'sender_mobile', 'sender_address','sender_postcode' ];
        
        $query = mm_sender::where('user_id', $user_id)->where('sender_name', 'like', $search)->orderBy('created_at', 'DESC');
        $count  =  $query->count();
        $limit = $input['limit']??8;
        
        ////pagination
        if(!empty($input['cursor'])){
            $sender_id = optional($query->select('id_sender')->get())->toArray();
            $key =  Helper::find_key($sender_id,'id_sender',$input['cursor']);  
            $page_start = $key ===false?0:$key+1;
        }

        $agent_sender  =  optional($query->select($select)->skip($page_start??0)->orderBy('created_at', 'desc')->limit($limit)->get())->toArray();
            return  ['sender_count'=>$count,'sender'=>$agent_sender];
    }


    //create new customer
    public  function create_customer($input, $user_id){
        
        if(!empty($input)){
          $new_sender =   mm_sender::create([
                "user_id"=> $user_id,
                'store_id'=>session()->get('process_store_id')??request()->process_store_id,
                "sender_title"=> $input['sender_title']??'',
                "sender_name"=>  $input['sender_name']??'',
                "sender_mname"=> $input['sender_name']??'',
                "sender_fname"=>  $input['sender_fname']??'',
                "sender_lname"=>  $input['sender_lname']??'',
                "sender_dob"=>  $input['sender_dob']??'',
                "sender_email"=>  $input['sender_email']??'',
                "sender_phone"=>  $input['sender_phone']??'',
                "sender_mobile"=>  $input['sender_mobile']??'',
                "sender_address"=>  $input['sender_address']??'',
                "sender_postcode"=>  $input['sender_postcode']??'',
                "photo_id"=> ''
            ]);

            return $new_sender->id_sender;
            
        }

    }


    //update customer
    public  function update_customer($input, $user_id, $sender_id){
        
        if(!empty($input)){
          $update_sender =   mm_sender::where('id_sender', $sender_id)->update([
                "user_id"=> $user_id,
                'store_id'=>session()->get('process_store_id')??request()->process_store_id,
                "sender_title"=> $input['sender_title']??'',
                "sender_name"=>  $input['sender_name']??'',
                "sender_mname"=> $input['sender_name']??'',
                "sender_fname"=>  $input['sender_fname']??'',
                "sender_lname"=>  $input['sender_lname']??'',
                "sender_dob"=>  $input['sender_dob']??'',
                "sender_email"=>  $input['sender_email']??'',
                "sender_phone"=>  $input['sender_phone']??'',
                "sender_mobile"=>  $input['sender_mobile']??'',
                "sender_address"=>  $input['sender_address']??'',
                "sender_postcode"=>  $input['sender_postcode']??'',
                "photo_id"=> ''
            ]);

            return $update_sender;
            
        }

    }
    
}