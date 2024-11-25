<?php
namespace App\Http\Middleware;

use Closure;
 use Illuminate\Support\Facades\Log;

class CustomAuthMiddleware extends \Laravel\Passport\Http\Middleware\CheckClientCredentials
{
    public function handle($request, Closure $next, ...$scopes)
    {
        Log::info('Running AuthMiddleware (auth:api)');
        return parent::handle($request, $next, ...$scopes);
    }
}
