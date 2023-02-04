<?php

namespace App\Http\Middleware;

use Closure;
use Exception;


class IsAdminMiddleware
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
            if( $request->user->user_role_type ==3 ){
                return $next($request);
            }
            return response()->json(['errors'=>['user_role'=> ['Access to this resource is forbidden']]],403);
    }
    
}
