<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Session;
use App\Models\bd_store;
use App\Models\bd_domain;
use App\Services\Helper;
    class check_store_exist_middleware
    {
    private $startTime;
    /**
    * Handle an incoming request.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \Closure  $next
    * @return mixed
    */
    public function handle($request, Closure $next)
    {
      
        $response = check_store_exists($request);
      
        if(!$response['status']) return response()->json(['domain'=>['invalid domain '.$response['host_domain']]], 400);
        
        return $next($request->merge($response['data']));


    }
    public function terminate($request, $response)
    {
       

    }
}
