<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class LogApiRequests
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        
        Log::channel('api')->info('API request received', [
            'method' => $request->getMethod(),
            'path' => $request->getPathInfo(),
            'query' => $request->query(),
            'body' => $request->except(['password']),
            'ip' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
            'status' => $response->getStatusCode(),
            'response' => $response->getContent(),
        ]);

        return  $response;
    }
}
