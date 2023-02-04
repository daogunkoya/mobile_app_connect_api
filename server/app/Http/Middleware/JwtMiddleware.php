<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Exception;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use App\Services\Helper;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
                //if($request->access_type == 1){
                   
                    $user = JWTAuth::parseToken()->authenticate();
                    $token = JWTAuth::getToken();
                    
                //}else{  
                    $user = JWTAuth::parseToken()->authenticate();
                    // $user = \App\Models\mm_user::where('user_access_id',$request->access_id)->first();
                    if(!$user) return response()->json(['errors'=>'The user does\'t exists'], 404);
                    
                    // $token = auth()->login($user);
                    // return response()->json(['status' => $token]);
                //}
                
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                return response()->json(['errors' => ['Token'=>['Invalid Token']]], 422);
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                return response()->json(['errors' => ['access_token'=>'Token Expired']],401);
            }else{
                return response()->json(['errors' => ['access_token'=>'Authorization Token not found']],401);
            }
        }

        $store = Helper::initialize_store_info($user->id_user);
        
        return $next($request->merge(["user"=>$user,
                                                        "store_url"=>$store['store_url'], 
                                                        'store_id'=>$store['store_id'],
                                                         'store_name'=>$store['store_name'],
                                                         'token'=>$token]));
    }
    
}
