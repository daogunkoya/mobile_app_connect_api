<?php

namespace App\Services\User_Device;

use App\Models\userDevice;
use Illuminate\Support\Facades\DB;
use App\Models\mm_log_device;
use Illuminate\Support\Facades\Http;
use App\Models\MMUser;
use App\Services\sqs\sqs_service;
use Carbon\Carbon;

class user_device_service
{
    public static function log_device($request, $response)
    {
        $store_id = session()->get('process_store_id') ?? request()->process_store_id;
        $input = is_array($request) ? $request : $request->all();

        $user_id = $response['user_id'];
        $device_code = $input['device_code'];

        $user_device = userDevice::where('user_id', $user_id)->where('device_code', $device_code)->first();
        $user_device = !empty($user_device) ? $user_device->toArray() : null;

        //if user device info already exists
        $stored_push_token = $user_device['device_push_token'] ?? '';
        $stored_push_type = $user_device['device_push_type'] ?? 1;
        $stored_device_name = $user_device['device_name'] ?? '';
        $stored_access_id = $user_device['user_access_id'] ?? '';
        $stored_access_type = $user_device['device_type'] ?? 1;


       // var_dump($response);

        $device_type = $input['device_type'];
        $access_type = $input['access_type'] ?? $stored_access_type ;
        $access_id = $input['access_id'] ?? $stored_access_id;
        $device_name = $input['device_name'] ?? $stored_device_name ;
        $device_token = $response['access_token'];
        $device_push_token = $input['device_push_token'] ?? $stored_push_token;
        $device_push_type = $input['device_push_type'] ?? $stored_push_type ;

       //  //var_dump('time',Carbon\Carbon::now()->toDateTimeString());


        if (!empty($user_device)) {
            userDevice::where('store_id', $store_id)->where('user_id', $user_id)
            ->where('device_code', $device_code)
            ->update([
                'device_access_token' => $device_token,
                'device_last_active' => Carbon::now()->toDateTimeString(),
                'device_status' => 1,
                'device_push_type' => $device_push_type,
                'device_push_token' => $device_push_token,
                'record_count_update' => DB::raw('record_count_update + 1')
            ]);
        } else {
            $device_location = self::location_change_email($request, $user_id, $device_name);

            userDevice::create([
                'store_id' => $store_id,
                'user_id' => $user_id,
                'device_type' => $device_type,
                'device_name' => $device_name,
                'device_ip' => $device_location['ip'] ?? '',
                'device_location' => $device_location['location'] ?? '',
                'device_code' => $device_code,
                'user_access_id' => $access_id,
                'device_access_token' => $device_token,
                'device_last_active' => Carbon::now()->toDateTimeString(),
                'device_status' => 1,
                'device_push_type' => $device_push_type,
                'device_push_token' => $device_push_token,
                'record_count_update' => DB::raw('record_count_update + 1')

            ]);
        }

        self::device_activty($user_id, $device_type, $device_name, 1);

        return;
    }


    public static function location_change_email($request, $user_id, $device_name)
    {

        $user = MMUser::where('id_user', $user_id)->select('user_email', 'user_handle')->first();
        $user = !empty($user) ? $user->toArray() : [];


        //$ip =  trim(shell_exec("dig +short myip.opendns.com @resolver1.opendns.com"));
        $ip =  $request->ip() ?? '';

        if ($ip == '127.0.0.1') {
            $location = "localhost";
        } else {
            $url = config('api.ipstack_url') . $ip . '?access_key=' . config('api.ipstack_token');
            $ipstack_response = Http::get($url);
            $ipstack_response =  $ipstack_response->json();

            $location  = $ipstack_response['city'] . ', ' . $ipstack_response['country_name'];
        }
        $sent_when = Carbon::now()->format('d M Y, h:i A');

        $sqs_data = [
            'type' => 'email',
            'operation' => 'general_message',
            'content' => ["username" => $user['user_handle'] ?? '',
                        "page_type" => 'signin_location',
                        'device_name' => $device_name,
                        'device_location' => $location,
                        'date' => $sent_when,
                         "button_name" => '',
                         'link' => '',
                        'subject' => 'New sign in to your account',
                        'header' => 'New sign in to your account',
                    ],
            'link' => '',
            'email' => $user['user_email'] ?? ''];


       // sqs_service::job($sqs_data);

           // \App\Jobs\email_job::process($sqs_data);

       //var_dump( $location);

        return ['ip' => $ip ?? '', 'location' => $location ?? ''];
    }


    public static function logout_device($request, $token)
    {

        $user = !empty($request['user']) ? $request['user'] : null;
        $user = $user->toArray();
        $user_id = !empty($user['id_user']) ? $user['id_user'] : null;
        //$token = $request['token'];
        //var_dump('token',$token);


        $user_device =  userDevice::where('user_id', $user_id)->where('device_access_token', $token)->first();
        $user_device = !empty($user_device) ? $user_device->toArray() : null;


       // var_dump('token',$token);



        if (!empty($token) && !empty($user_id)) {
               userDevice::where('user_id', $user_id)
               ->where('device_access_token', $token)
               ->update([
                   'device_access_token' => '',
                   'device_status' => 0,
                   'device_last_active' => Carbon::now()->toDateTimeString(),
                   'record_count_update' => DB::raw('record_count_update + 1')
               ]);
        }


    //    \App\Models\bd_log_connect::create([
    //     'request_type' => 1,
    //     'request_message' => "user_id=".$user_id."<-->"."token==".$token,
    //     'response_message' =>  "posted",
    //     'user_id' => $user_id,
    // ]);

      //  the above should be replaced with bd_log_event

        //log user activity
        if (!empty($user_device)) {
            $device_name = $user_device['device_name'];
            $device_type = $user_device['device_type'];
            self::device_activty($user_id, $device_type, $device_name, 0);
        }



        return;
    }

    public static function device_activty($user_id, $device_type, $device_name, $device_status)
    {
        mm_log_device::create([
            'user_id' => $user_id,
            'device_type' => $device_type,
            'device_name' => $device_name,
            'device_status' => $device_status]);


        return;
    }
}
