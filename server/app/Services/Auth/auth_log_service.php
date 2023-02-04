<?php
namespace App\Services\Auth;
use App\Models\mm_user;
use App\Models\mm_user_device;
use Illuminate\Http\Request;


class auth_log_service 
{

public static function logging($request,$user,$token){

//     $user = mm_user_device::where('user_id',$user->id_user)->exists();
//    if(!$user) {
//    mm_user_device::create([
//         'user_id' =>$user->id_user??'',
//         'device_type'=>$request->device_type??'',
//         'device_name'=>$request->device_name??'',
//         'device_last_active'=>\Carbon\Carbon::now()->toDateTimeString(),
//         'device_status'=>$token !==null?1:0,
//     ]);
//    }else{
//     \App\Models\mm_user_device::where('user_id',$user->id_user)
//                                     ->update(['device_status'=>0]);
//    }
   
        \App\Models\bd_log_device::create([
            'device_type'=>$request->device_type??'',
            'device_name'=>$request->device_name??'',
            'device_status'=>$token !==null?1:0,
            'user_id'=>$user->id_user??'',
        ]);

        // \App\Models\bd_log_connect::create([
        //     'request_type'=>1,
        //     'request_message'=>json_encode($request),
        //     'response_message'=>json_encode(self::respondWithToken($token)),
        //     'user_id'=>$user->id_user??'',
        // ]);

        // \App\Models\bd_log_event::create([
        //     'connect_address'=>'sqs-sdk',
        //     'connect_type'=>2,
        //     'connect_version'=>2,
        //     'request_url'=>'sqs-sdk',
        //     'request_method'=>'post',
        //     'request_message'=>"",
        //     'response_code'=>200,
        //     'response_message'=>''
        // ]);

        return;
    }

    public static function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
        ]);
    }
    

}
