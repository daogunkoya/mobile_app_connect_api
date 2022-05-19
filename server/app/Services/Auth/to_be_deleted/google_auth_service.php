<?php
namespace App\Services\Auth;

use App\Models\mm_user;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Services\Contracts\AuthContract;
use illuminate\Support\Facades\Log;
use App\Services\sqs\sqs_service;
use Webpatser\Uuid\Uuid;
use App\Services\images\image_service;

class google_auth_service implements AuthContract
{

    public function register($request)
    {

        if (empty($request->access_id) ) {
            // user found
            return response()->json(['status' => 400, 'message' => 'Access id is required for this type of login']);
        } 

        $name = preg_split('/\s+/', $request->user_name);

        $bd_verify_token =Uuid::generate(4); 
        
        //create new user
            $user = mm_user::create([
                'user_first_name' => 1,
                'user_first_name' => $name[0]??'',
                'user_last_name' => $name[1]??'',
                'user_handle' => md5(microtime()),
                'user_email' => $request->user_email??'',
                'user_access_id' => $bd_verify_token,
                'user_access_type' => 3,
                'user_last_active' => \Carbon\Carbon::now()->toDateTimeString(),
                'user_status' => 0,

            ]);


        //Log::info('a user just registred');

        $token = auth()
            ->claims(['id_user' => $user->id_user])
            ->setTTL(config('betterdeal.auth_token_expire')) //min
            ->login($user);

        
        //send email via SQS to verify user email
        self::send_email($request,$bd_verify_token,$user);  

//this will be sent to sqs
$this->process_image($user->id_user,$request->image_url);




        //log activities to log factory class
       \App\Services\Auth\auth_log_service::logging($request,$user,$token);
   

        
        return $this->respondWithToken($token,$user->id_user);
    }

    public function process_image($user_id,$image_url){

        return sqs_service::job(['type' => 'process_image',
        'operation' => 'process_image',
        'section' => 3,
        'location'=> 'user/profile/',
        'owner_id'=>$user_id,
        'user_id'=>$user_id,
        'need_processing'=>1,
        'image'=>$image_url]);
    
        
        }

        public static function send_email($request,$bd_verify_token,$user){
            $link_address = config('betterdeal.url').'/'
            .config('betterdeal.version')
            .'/activate-member/'.$bd_verify_token;
    
                $send_email =  isset($request->user_email)?
                sqs_service::job([
                'type'=>'email',
                'operation'=>'general_message',
                'content'=>["username"=>$user['user_handle'],
                            "button_name"=>"Verify",
                            'link'=>$link_address,
                            'subject'=>'Email Verification',
                            'header'=>"Email Verification",
                            'body'=>"Please confirm your email, by clicking on the button link button.
                            Alternatively,Click on this link .\n".$link_address],
                'link'=>$link_address,
                'email'=>$request->user_email??'']):null;

                return;
        }

    public function respondWithToken($token,$user_id=null)
    {
        return response()->json([
            'user_id'=>$user_id,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
        ]);
    }

    public function login($request)
    {
        if (empty($request->access_id) ) {
            // user found
            return response()->json(['status' => 400, 'message' => 'Access id is required for this type of login']);
        } 

        $user = \App\Models\mm_user::where('user_access_id',$request->access_id)->first();
        if(!$user) return response()->json(['status'=>404,'message'=>'user not found']);
        
        $token = auth()->setTTL(config('betterdeal.auth_token_expire'))->login($user);
       

        \App\Services\Auth\auth_log_service::logging($request,$request->user,$token);
        return $this->respondWithToken($token,$user->id_user);
        

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
    public function logout($request)
    {
        auth()->logout();
        $user =$request->user;
        
        //find device details from token
        $device = \App\Models\mm_user_device::where('user_id',$user->id_user)->first();
        $request->device_name = $device->device_name;
        $request->device_type = $device->device_type;

        \App\Services\Auth\auth_log_service::logging($request,$user,null);
        return response()->json(['message' => 'Successfully logged out']);
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

    

}
