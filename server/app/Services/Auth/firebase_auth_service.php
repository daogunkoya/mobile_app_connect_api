<?php
namespace App\Services\Auth;

use App\Models\mm_user;
use App\Services\Contracts\AuthContract;
use App\Services\sqs\sqs_service;
use Illuminate\Support\Facades\Auth;
use illuminate\Support\Facades\Log;
use Webpatser\Uuid\Uuid;
use App\Services\User_Device\user_device_service;
use App\Services\Helpers\image_helper;
use App\Services\images\image_service;
use App\Services\Helper;
use App\Models\mm_user_device;


class firebase_auth_service implements AuthContract
{

    public function register($request)
    {
        //Multi-sign-in function check user email already exists
        if (mm_user::where('user_email', $request->user_email)->exists()) {
            $user_exist = mm_user::where('user_email', $request->user_email)->first();
            $new_list = ['access_type' => $request->access_type,'user_access_id' => $user_exist->user_access_id, 'created_at' => \Carbon\Carbon::now()->toDateTimeString()];

            $list = mm_user::where('user_email', $request->user_email)->value('list_access');
            $list = json_decode($list, true);

            $list = $list??[];
            $list[] = $new_list;

            $list = json_encode($list, JSON_PRETTY_PRINT);

            if (!empty($request->access_id)) {
                mm_user::where('user_email', $request->user_email)->update(
                    ['user_access_id' => $request->access_id,
                        'user_access_type' => $request->access_type,
                        'list_access' => $list]);
            }
//redirect to login
            return $this->login($request, 2);
        };

        //check user access_id is not empty

        if (empty($request->access_id)) {
            // user founds
            return response()->json(['errors'=>['access_id'=>['access_id is required']]],400);
        }
        if (mm_user::where('user_access_id', $request->access_id)->exists()) {      //user access_id exists
            return response()->json(['errors'=>['access_id'=>['access_id already exists']]], 400);
        }

        //$name = preg_split('/\s+/', $request->user_name);

        // token for verificatin
        $bd_verify_token = Uuid::generate(4);

        $request_user_name = $request->user_name=='Undefined'?Helper::random_string():$request->user_name;
     
        $user_name = strtolower(preg_replace('/[^A-Za-z0-9\-]/', '', $request_user_name)??'');
        $user_handle = $user_name;
        if (strlen($user_handle) > 12) $user_handle = substr($user_handle, 0, 12);
        $user_handle = $user_handle.mt_rand(10,100);
//unique user_handle
        while(mm_user::where('user_handle',$user_handle)->exists()) $user_handle = preg_replace('/\s+/', '', $user_handle).'_'.mt_rand(10,100);
        //create new user
        $user = mm_user::create([
            'user_first_name' => 1,
            'user_name' => $user_name??'',
            'user_handle' => $user_handle,
            'user_email' => $request->user_email ?? '',
            'user_access_id' => $request->access_id,
            'user_password_token' => $bd_verify_token,
            'list_access'=>json_encode([['access_type'=>$request->access_type,'created_at'=>\Carbon\Carbon::now()->toDateTimeString() ]]),
            'user_access_type' => $request->access_type,
            'user_last_active' => \Carbon\Carbon::now()->toDateTimeString(),
        ]);

        //Log::info('a user just registred');

        $token = auth()
            ->claims(['id_user' => $user->id_user])
            ->setTTL(config('betterdeal.auth_token_expire')) //min
            ->login($user);

        //send email via SQS to verify user email
        //self::send_email($request, $bd_verify_token, $user);

        //this will be sent to sqs
        $image_id =  image_helper::image_source_tos3($user->id_user, $request->user_image_url, 'user/profile');
        $this->process_image($user->id_user, $image_id);


        
        $notification_status =  mm_user_device::where('user_id',$user->id_user)->where('device_code',$request->device_code)->value('device_push_token');
        $notification_status = !empty($notification_status)?1:0;
        $user_data['user_role_type'] = 1;
        $user_data['user_handle'] = $user_handle;
        $user_data['user_status'] = 1;
        $user_data['image_url'] = config('betterdeal.image_url').'user/profile/small/'.$image_id;
        $user_data['user_access_type'] = $request->access_type;
        $user_data['device_code'] = $request->device_code;
        $user_data['notification_status'] =$notification_status;


        //log device
        user_device_service::log_device($request,['user_id'=>$user->id_user,'access_token'=>$token]);

        //$this->mailchimp_job
        $this->mailchimp_job($request); 
        
        
        //log activity;
        $this->log_activity($user->id_user);

        return $this->respondWithToken($token, $user->id_user,1,$user_data);
    }

    public function check_user_exist($request)
    {

    }

    public function process_image($user_id, $image_id)
    {

        return sqs_service::job(['type' => 'process_image',
            'data'=>[
                'operation' => 'process_image',
                'section' => 3,
                'location' => 'user/profile/',
                'owner_id' => $user_id,
                'user_id' => $user_id,
                'need_processing' => 1,
                'image_id' => $image_id
            ]
            ]);

                    //  image_service::process([
                    //     'operation' => 'process_image',
                    //     'section' => 3,
                    //     'location' => 'user/profile/',
                    //     'owner_id' => $user_id,
                    //     'user_id' => $user_id,
                    //     'need_processing' => 1,
                    //     'image_id' => $image_id
                    // ]);
                    // return;

    }

    public static function send_email($request, $bd_verify_token, $user)
    {
        $link_address = config('betterdeal.url')
            . '/accounts/signup/confirm?id=' . $bd_verify_token;

        $send_email = isset($request->user_email) ?
        sqs_service::job([
            'type' => 'email',
            'operation' => 'general_message',
            'content' => ["username" => $user['user_handle'],
                "button_name" => "Confirm email address",
                'link' => $link_address,
                'subject' => 'Confirm your email address',
                'header' => "Confirm your email address",
                'body' => "Once you've confirmed " . $request->user_email
                . " is your email address, we'll help you find better deals."],
            'link' => $link_address,
            'email' => $request->user_email ?? '']) : null;
        return;
    }

    public function respondWithToken($token, $user_id = null, $user_redirect = 1, Array $user_data = null)
    {

        // $notification_status = mm_user_device::where('user_id',$user_id)->value('device_push_token');
       
        // $notification_status = !empty($notification_status)?1:0;

        $now = \Carbon\Carbon::now();

        if($user_data['user_status']===1)  
                { return response()->json([
                            'user_id' => $user_id ?? null,
                            'user_role_type'=>$user_data['user_role_type'],
                            'user_handle'=>$user_data['user_handle'],
                            'user_status'=>$user_data['user_status'],
                            'user_image_url'=>$user_data['image_url'],
                            'user_access_type'=>$user_data['user_access_type'],
                            'access_status' => $user_redirect ?? 1,
                            'notification_status'=>$user_data['notification_status']??0,
                            //'user_connect_facebook'=>$user_data['user_access_type']===2?1:0,
                            //'user_connect_google'=>$user_data['user_access_type']===3?1:0,
                            'access_token' => $token,
                            'token_type' => 'bearer',
                            'expires_in' => $now->addSeconds(auth()->factory()->getTTL())->timestamp,
                        ], 200);
                }
                        return response()->json([
                            'user_id'=>$user_id,
                            'user_status'=>$user_data['user_status'],
                            'notification_status'=>$notification_status??0,
                            'deactivated_at'=>$user_data['updated_at']->format('Y:m:d:h:i:s'),
                            'removed_at'=>$user_data['updated_at']->addDays(30)->format('Y:m:d:h:i:s') ]);
        //expires_on' =>$now->addSeconds(auth()->factory()->getTTL())->format('Y-m-d H:i:s')
    }


    //adding new user to mailchimp api 
    public function mailchimp_job($user_data){
        //$mailchimp = new \App\Services\MailChimpService;     
       $member = [
           'email_address' => $user_data->user_email,
           'status' => 'subscribed',
           "merge_fields" => [
               "FNAME" => $user_data->user_name,
               "LNAME" => $user_data->user_name,
           ],
       ];

       sqs_service::job([
           'type'=>'mailchimp_subscribe_user',
           'operation'=>'mailchimp_subscribe_user',
           'data'=>$member]);

 //\App\Services\mailchimp_service::create($member);

       return;

}

    public function login($request, $user_redirect = 1)
    {
        if (empty($request->access_id)) {
            // user found
            return response()->json(['errors'=>['access_id'=>['access_id field is require']]],400);
        }

        //check if is an old access type
        $user = mm_user::where('user_access_id', $request->access_id)
            ->orWhere('user_email', $request->user_email)
            ->select('user_access_id', 'user_email', 'user_access_type')->first();

        //multi-signin -- check for change of access type, update access_if if needed
        if ($user->user_access_type != $request->access_type) {
            if ($user->user_email == $request->user_email) {
                mm_user::where('user_email', $request->user_email)
                    ->update(['user_access_id' => $request->access_id, 'user_access_type' => $request->access_type]);

            }

            $new_list = ['access_type' => $request->access_type, 'user_access_id'=>$user->user_access_id,'created_at' => \Carbon\Carbon::now()->toDateTimeString()];

            $list = mm_user::where('user_access_id', $request->access_id)->value('list_access');
            $list = json_decode($list, true);

            $list = $list??[];
            $list[] = $new_list;
            
            $list = json_encode($list, JSON_PRETTY_PRINT);
            mm_user::where('user_access_id', $request->access_id)->update(['user_access_type' => $request->access_type, 'list_access' => $list]);
            $user_redirect = 2;
        }
        // $user_redirect = $user_access_type !== 2 || $user_access_type !== 3?2:1;
        //multi sign in ends here

        $user = \App\Models\mm_user::where('user_access_id', $request->access_id)->first();
        if (!$user) {
            return response()->json(['errors'=>[['access_id'=>'user with the access_id not found']]], 404);
        }

        $token = auth()->setTTL(config('betterdeal.auth_token_expire'))->login($user);

       

//user data for login
        $notification_status =  mm_user_device::where('user_id',$user->id_user)->where('device_code',$request->device_code)->value('device_push_token');
        $notification_status = !empty($notification_status)?1:0;
        $user_data['updated_at'] = $user->updated_at;
        $user_data['user_role_type'] = $user->user_role_type;
        $user_data['user_status'] = $user->user_status ;
        $user_data['user_handle'] = $user->user_handle;
        $user_data['image_url'] =  config('betterdeal.image_url').'user/profile/small/'.(!empty($user['list_image'])?$user->list_image:'default.png');; 
        $user_data['user_access_type'] = $request->access_type;
        $user_data['device_code'] = $request->device_code;
        $user_data['notification_status'] = $notification_status;


         //log device
         user_device_service::log_device($request,['user_id'=>$user->id_user,'access_token'=>$token]);
        

        return $this->respondWithToken($token, $user->id_user, $user_redirect,$user_data);

    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout($request,$token)
    {
            auth()->logout();
      
         //log device
         $input  = $request->all();
         user_device_service::logout_device($input,$token);

        return response()->json(['message' => 'User was successfully signed out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }
    public function username()
    {
        return 'user_email';
    }


    public function log_activity($user_id){
        if(!empty($user_id)){

            $sqs_data = ['modified_item_id' => $user_id,'user_id'=>$user_id,'log_type'=>'submit','item_type'=>5];
            //log item activity to   bd_log_activity
           //\App\Services\Activity\activity_service::prepare($sqs_data);
          sqs_service::job(['type' => 'log_item_activity','data'=>$sqs_data]);
        }
    
        return;
    }

}
