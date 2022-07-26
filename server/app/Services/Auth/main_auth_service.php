<?php
namespace App\Services\Auth;

use App\Models\mm_user;
use App\Models\mm_user_confirm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Services\Contracts\AuthContract;
use illuminate\Support\Facades\Log;
use App\Services\sqs\sqs_service;
use Webpatser\Uuid\Uuid;
use App\Services\User_Device\user_device_service;
use App\Models\mm_user_device;
use App\Models\bd_order_item;
use App\Services\Helper;

class main_auth_service implements AuthContract
{
    public function __construct(){
        
    }
    

    public function register($request)
    {
            $store_id = session()->get('process_store_id')??request()->process_store_id;
        //$name = preg_split('/\s+/', $request->user_name);

        $bd_verify_token = Uuid::generate(4); 

        //check user email and user password are not the same
         if($request->user_email == $request->user_password)return  [['errors'=>['user_email'=>['user email and user password must be different']]],422];
         
         //check email verified
        // if(!mm_user_confirm::where('user_email',$request->user_email)->where('confirm_status', 1)->where('store_id', $store_id)->exists()) return  [['errors'=>['user_email'=>['the user email supplied is not verified']]],400];
        
        //check user exists within store
        if(mm_user::where('user_email',$request->user_email)->where('store_id', $store_id)->exists()) return  [['errors'=>['user_email'=>['email already exists']]],400];
         
        $user_name = strtolower(preg_replace('/[^A-Za-z0-9\-]/', '', $request->user_name)??'');
        $user_handle = $user_name;
        if (strlen($user_handle) > 12) $user_handle = substr($user_handle, 0, 12);
        $user_handle = $user_handle.mt_rand(10,100);
       
//unique user_handle
        while(mm_user::where('user_handle',$user_handle)->where('store_id', $store_id)->exists())
        {
            $user_handle = preg_replace('/\s+/', '', $user_handle).'_'.mt_rand(10,100);
        }

        $user = mm_user::create([
                'user_first_name' => 1,
                'user_name' => $user_name??'',
                'user_handle' => $user_handle??'',
                'user_email' => $request->user_email,
                'user_password_hash' => Hash::make($request->user_password),
                'user_password_salt' => Hash::make($request->user_password),
                'user_access_id' => $request->access_id,
                'user_password_token'=>$bd_verify_token,
                'list_access'=>json_encode([['access_type'=>1,'created_at'=>\Carbon\Carbon::now()->toDateTimeString() ]]),
                'user_access_type' => 1,
                'user_last_active' => \Carbon\Carbon::now()->toDateTimeString(),
                'store_id'=>$store_id
            ]);

      //create token
        $token = auth()
            ->claims(['id_user' => $user->id_user])
            ->setTTL(180000) //min
            ->login($user);

        
    //send email via SQS to verify user email
    // self::send_email($request,$bd_verify_token,$user);
        
       //self::register_user_device($user->id_user, $request);
       $notification_status =  mm_user_device::where('user_id',$user->id_user)->where('device_code',$request->device_code)->value('device_push_token');
       $notification_status = !empty($notification_status)?1:0;
        $user_data['user_status'] = 1;
        $user_data['user_role_type'] = 1;
        $user_data['user_handle'] = $user_handle;
        $user_data['user_name'] = $user_name;
        $user_data['image_url'] = image_url().'user/profile/small/'.'default.png';
        $user_data['user_handle'] = $user_handle;
        $user_data['user_access_type'] = 1;
        $user_data['device_code'] = $request->device_code;
        $user_data['notification_status'] = $notification_status;
    
            //sqs_service::job(['type'=>'add_mailchimp','operation'=>'add_mailchimp','user_email'=>$request->user_email,'user_name'=>$user_name]);
           
            //$this->mailchimp_job
            $this->mailchimp_job($request);  
            
            //log device
            user_device_service::log_device($request,['user_id'=>$user->id_user,'access_token'=>$token]);

            $this->log_activity($user->id_user);
            
             return $this->respondWithToken($token,$user->id_user,null,$user_data);
    }

//generate token
    public function respondWithToken($token,$user_id = null,$user_redirect = 1, Array $user_data = null)
    {
        // $notification_status = mm_user_device::where('user_id',$user_id)->where('device_code',$user_data['device_code'])->exists();
        // $notification_status =  $notification_status?1:0;
        
       // $total_order_quantity = bd_order_item::where('user_id',$user_id)->where('item_status',1)->where('order_item_mod_status', 1)->sum('item_quantity');
        $now = \Carbon\Carbon::now();

        $response_token = [
            'user_id'=>$user_id,
            'user_status'=>$user_data['user_status'],
           // 'total_order_quantity'=>$total_order_quantity,
            'notification_status'=>$notification_status??0,
            'store_url' => session()->get('process_store_url'),
            'store_name' => session()->get('process_store_name'),
            'store_version' => session()->get('process_store_version'),
            'deactivated_at'=>!empty($user_data['updated_at'])?$user_data['updated_at']->format('Y:m:d:h:i:s'):'',
            'removed_at'=>!empty($user_data['updated_at'])?$user_data['updated_at']->addDays(30)->format('Y:m:d:h:i:s'):'' ];

        if($user_data['user_status']===1)
                $response_token = [
                                    'user_id'=>$user_id,
                                    'user_role_type'=>$user_data['user_role_type'],
                                    'user_handle'=>$user_data['user_handle'],
                                    'user_name'=>$user_data['user_name'],
                                    'user_status'=>$user_data['user_status'],
                                    'user_image_url'=>$user_data['image_url'],
                                    'user_access_type'=>$user_data['user_access_type'],
                                    'access_status'=>$user_redirect??1,
                                    'notification_status'=>$user_data['notification_status']??0,
                                   // 'total_order_quantity'=>$total_order_quantity,
                                    'store_url' => session()->get('process_store_url'),
                                    'store_name' => session()->get('process_store_name'),
                                    'store_version' => session()->get('process_store_version'),
                                    'access_token' => $token,
                                    'token_type' => 'bearer',
                                    'expires_in' =>$now->addSeconds(auth()->factory()->getTTL())->timestamp
                                    //'expires_in' => auth()->factory()->getTTL() * 60,
                                ];

                
        
                  //  return response()->json($response_token);
                    return [$response_token, 200];
                    
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

            // sqs_service::job([
            //     'type'=>'mailchimp_subscribe_user',
            //     'operation'=>'mailchimp_subscribe_user',
            //     'data'=>$member]);

            return;

    }

//send sqs message
    public static function send_email($request,$bd_verify_token,$user){
        $store_url  = session()->get('process_store_url')??request()->process_store_url;

        $link_address = $store_url
        .'/accounts/signup/confirm?id='.$bd_verify_token;
               $send_email =  isset($request->user_email)?
               sqs_service::job([
               'type'=>'email',
               'operation'=>'general_message',
               'content'=>["username"=>$user['user_handle'],
                           "button_name"=>"Confirm email address",
                           'link'=>$link_address,
                           'subject'=>'Confirm your email address',
                           'header'=>"Confirm your email address",
                           'body'=>"Once you've confirmed ".$request->user_email
                            ." is your email address, we'll help you find better deals."],
               'link'=>$link_address,
               'email'=>$request->user_email??'']):null;

               return;
    }

    public function login($request,$user_redirect = 1)
    {

      

        //if user role is admin with the email initialize admin store for processing
        // if(mm_user::where('user_role_type',3)->where('user_email', $request->user_email)->exists()){
        //     $user_id = mm_user::where('user_role_type',3)->where('user_email',$request->user_email)->value('id_user');
        //     $store = Helper::initialize_store_info($user_id);
         
        // }
        $store_id = session()->get('process_store_id')??request()->process_store_id;
        
       


        if(!isset($request->user_email) || !isset($request->user_password)) return [['errors'=>['errors'=>['user_email'=>['email | password is required']]]], 400];
      // var_dump($request->user_password,$request->user_email);
        $user = mm_user::where('user_email',$request->user_email)->where('store_id', $store_id)->first();
        
        if(empty($user)) return [['errors'=>['user_email'=>['user_email never exists ']]], 400]; 
        
        //multi-sign-in: check if is an old access
        $user_access_type = $user->user_access_type;
        if($user_access_type != 1){

            $new_list =['access_type'=>1,'created_at'=>\Carbon\Carbon::now()->toDateTimeString() ]; 
            
            $list = mm_user::where('store_id', $store_id)->where('user_email',$request->user_email)->value('list_access');
            $list = json_decode($list,true);
               
            $list = $list??[];
            $list[] = $new_list;

            $list = json_encode($list, JSON_PRETTY_PRINT);
            mm_user::where('store_id', $store_id)->where('user_email',$request->user_email)->update(['user_access_type' => 1,'list_access'=>$list]);
        }
        $user_redirect = $user_access_type != 1?2:1;
     
        $token = auth()
                ->setTTL(180000)
                ->attempt(['user_email' => $request->user_email, 'password' => $request->user_password, 'store_id'=>$store_id]);

        
                if(!$token) return [['errors'=>['user_email and user_password did\'t match on ']], 401];


        $notification_status =  mm_user_device::where('store_id', $store_id)->where('user_id',$user->id_user)->where('device_code',$request->device_code)->value('device_push_token');
        $notification_status = !empty($notification_status)?1:0;
        
        $user_data['updated_at'] = $user->updated_at;
        $user_data['user_status'] = $user->user_status;
        $user_data['user_role_type'] = $user->user_role_type;
        $user_data['user_handle'] = $user->user_handle;
        $user_data['user_name'] = $user->user_name;
        $user_data['image_url'] = image_url().'user/profile/small/'.(!empty($user['list_image'])?$user->list_image:'default.png');
        $user_data['user_access_type'] = $user->user_access_type;
        $user_data['device_code'] = $request->device_code;
        $user_data['notification_status'] = $notification_status ;


         //log device
         user_device_service::log_device($request,['user_id'=>$user->id_user,'access_token'=>$token]);


         //rebuild coupon item in Helper 
       //  Helper::rebuild_coupon_item($user->id_user);

       // return $this->respondWithToken($token,$user->id_user,$user_redirect,$user_data);
        return $this->respondWithToken($token,$user->id_user,$user_redirect,$user_data);
        

    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return [auth()->user(),200];
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
            user_device_service::logout_device($request->all(),$token);

        return [['message' => 'User was successfully signed out'],200];
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

public static function  register_user_device($user_id, $request){
        $user = mm_user_device::where('user_id',$user->id_user)->exists();
   if($user) {
   mm_user_device::where('id_user',$user_id)->update([
        'user_id' =>$user->id_user??'',
        'device_type'=>$request->device_type??'',
        'device_name'=>$request->device_name??'',
        'device_last_active'=>\Carbon\Carbon::now()->toDateTimeString(),
        'device_status'=>$token !==null?1:0,
    ]);
   }else{
    mm_user_device::create([
        'user_id' => $user_id,
        'device_type'=>$request->device_type??'',
        'device_name'=>$request->device_name??'',
        'device_last_active'=>\Carbon\Carbon::now()->toDateTimeString(),
        'device_status'=>$token !==null?1:0,
    ]);
   }
   return;
}

public function log_activity($user_id){
    if(!empty($user_id)){

        $sqs_data = ['modified_item_id' => $user_id,'user_id'=>$user_id,'log_type'=>'submit','item_type'=>5];
        //log item activity to   bd_log_activity
      // \App\Services\Activity\activity_service::prepare($sqs_data);
     // sqs_service::job(['type' => 'log_item_activity','data'=>$sqs_data]);
    }

    return;
}

    

}
