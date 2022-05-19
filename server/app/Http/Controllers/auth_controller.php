<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\user_register_validation;
use App\Http\Requests\Auth\user_login_validation;
use App\Services\Auth\auth_service;
use App\Services\User\user_service;
use App\Models\mm_user;
use App\Services\Helper;
use App\Models\mm_user_confirm;
use App\Services\sqs\sqs_service;

class auth_controller extends Controller
{
    public $service;


  
    
    /**
     * register new user.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function register(user_register_validation $request)
    {
    if($request->access_type==1 && (!isset($request->user_password )))  return response()->json(['message'=>'user password require'],400);   
    if($request->access_type==1 && (strlen($request->user_name) < 5 || strlen($request->user_name) > 15 ))  return response()->json(['message'=>'user name should be between 5 and 15'],400);   
       
    $service = new auth_service((int)$request->access_type);
       $response =  $service->register($request);

       return response()->json($response[0]??'', $response[1]??200);
    }





    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
        ]);
    }

    /**
     * Log the user in .
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function login(user_login_validation $request)
        {
            // var_dump(session()->get('process_store_id')??request()->process_store_id);
           
           
           //check if the right person is navigating to the right domain/store
           if(!Helper::check_email_on_store($request->user_email))return response()->json(['errors'=>['user_email'=>["This user_email never exists"]]], 422);;

            $service = new auth_service($request->access_type);
        $response =  $service->login($request);
        return response()->json($response[0]??'', $response[1]??200);
        //return $request->bearerToken();

        }

    

    /** //
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    { 
        $token = $request->token;
        $service = new auth_service(2);

       // return $service->logout($request,$token);
        $response =  $service->logout($request,$token);

        return response()->json($response[0]??'', $response[1]??200);
    }


    public function verify($id){
            //$timestamp = \Carbon\Carbon::now() 
           
            //update mm_user    
         if($user = mm_user_confirm::where('confirm_token',$id)->first()){

                mm_user::where('user_email',$user->user_email)
                            ->update(['user_status' => 1,
                                    'user_email_status'=>1,
                                    'user_email'=>$user->user_email]);
                //update mm_user_confirm table
                mm_user_confirm::where('confirm_token',$id)
                                        ->update(['user_status' => 1,
                                                'confirm_token'=>0,
                                                'confirm_status'=>1]);

           
                    
                if($user) return response()->json(['message'=>'Confirmation email was successfully updated']);
            return "something went wrong with the information you supplied";
         }
                  

                  
  
      }




//return email from users/email with user_id as parameter
      public function email_confirm(Request $request){
        //$timestamp = \Carbon\Carbon::now() 
        $store_id = session()->get('process_store_id')??request()->process_store_id;
        
        if(!isset($request->confirm_token)) return response()->json(['errors'=>['confirm_token'=>['The confirm token field is required']]],422); 
        
            $user = mm_user_confirm::where('confirm_token',$request->confirm_token)->where('store_id', $store_id)->first();
            if(!$user)  return response()->json(['errors'=>['confirm_token'=>['no record of this user']]], 404);


            //old email for mailchimp, to delist /remove
            $user_data =  mm_user::where('id_user',$user->user_id)->where('store_id', $store_id)->select('user_name','user_email')->first();
            $user_data = !empty($user_data)?$user_data->toArray():[];

            // $update_from_mailchimp =user_service::mailchimp_job(['user_name'=>$user_data['user_name'],'user_email'=>$user->user_email], 0);


            // $member =[
            //     'email_address' => $user_data['user_email'],
            //     'status' => 'unsubscribed',
            //     "merge_fields" => [
            //         "FNAME" => $user_data['user_name'],
            //         "LNAME" => $user_data['user_name'],
            //     ],
            // ];
          

                    //unsubscribe to mail via sqs
                sqs_service::job([
                    'type'=>'mailchimp_unsubscribe_user',
                    'operation'=>'mailchimp_unsubscribe_user',
                    'id'=>$user_data['user_email'],
                    'data'=> [
                        'email_address' => $user->user_email,
                        'status' => 'subscribed',
                        "merge_fields" => [
                            "FNAME" => $user_data['user_name'],
                            "LNAME" => $user_data['user_name'],
                        ],
                    ]  ]);


               mm_user::where('id_user',$user->user_id)->where('store_id', $store_id)
              ->update(['user_email' => $user->user_email,'user_email_status'=>1]);
            
              

              mm_user_confirm::where('confirm_token',$request->confirm_token)->where('store_id', $store_id)
              ->update(['confirm_status' => 1,'confirm_token'=>'']);


             
        
        //log item activity to   bd_log_activity
       // \App\Services\Activity\activity_service::prepare(['item_type'=>5,'modified_item_id' => $user->user_id,'old_item'=>$user_data,'user_id'=>$user->user_id ,'log_type'=>'user']);
         sqs_service::job(['type' => 'log_item_activity','data'=>['item_type'=>5,'modified_item_id' => $user->user_id,'old_item'=>$user_data,'user_id'=>$user->user_id ,'log_type'=>'user']]);

              
    
             
              if($user) return response()->json(['message'=>'User email was successfully updated']);
           

  }

      public function check(Request $request){

        //return \App\Services\images\image_service::process(0,$request->image_url,'user/profile/');
        return \App\Services\images\image_service::process([
            'section' => 3,
            'location'=> 'user/profile/',
            'user_id'=>1,
            'need_processing'=>1,
            'image'=>$request->image_url
        ]);
        
        //return $request->all();
      }


      public function getAuthorizationHeader(){
        $headers = null;
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        }
        else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            //print_r($requestHeaders);
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        return $headers;
    }


/**
 * get access token from header
 * */
public function getBearerToken() {
    $headers = $this->getAuthorizationHeader();
    // HEADER: Get the access token from the header
    if (!empty($headers)) {
        if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
            return $matches[1];
        }
    }
    return null;
}

    
 

}
