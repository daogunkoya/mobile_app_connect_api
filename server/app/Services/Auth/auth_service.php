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

class auth_service implements AuthContract
{

    public $service;

    public function __construct($access_type){

        switch($access_type){
            case 1:
            $this->service =  new \App\Services\Auth\main_auth_service;
            break;
            case 2:
            $this->service =  new \App\Services\Auth\firebase_auth_service;
            break;
            case 3:
            $this->service =  new \App\Services\Auth\firebase_auth_service;
            break;
            
            case 4:
            $this->service =  new \App\Services\Auth\firebase_auth_service;
            break;
            default:
            return response()->json(['status' => 400, 'message' => 'Access id '.$access_type.' not valid']);
        }

    }

    public function register($request)
    {
        return $this->service->register($request);

    }

    public function respondWithToken($token,$user_id = null , $user_redirect=null)
    {
        return $this->service->respondWithToken($token,$user_id,$user_redirect);
    }

    public function login($request,$user_redirect = null)
    {
        return $this->service->login($request,$user_redirect);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return $this->service->me($request);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout($request,$token)
    {
        return $this->service->logout($request,$token);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->service->refresh($request);
       
    }
    public function username()
{
    return $this->service->username($request);
    
}

    

}
