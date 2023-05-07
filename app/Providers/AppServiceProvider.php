<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\Auth\LoginServiceInterface;
use App\Interfaces\Auth\RegisterServiceInterface;
use App\Services\Auth\LoginUserService;
use App\Services\Auth\RegisterUserService;
use Illuminate\Support\Facades\Log;
use App\Logging\DatabaseLogger;
use Monolog\Logger;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    
     public function register()
    {
        // $this->app->bind('App\Contracts\LoggerInterface', function ($app) {
        //     return new DatabaseLogger($app['db']->connection(), 'logs', MonologLogger::DEBUG);
        // });
        
        // Log::extend('database', function ($app, $config) {
        //     return $app->make('App\Contracts\LoggerInterface');
        // });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
      
        $this->app->bind(LoginServiceInterface::class, LoginUserService::class);
        $this->app->bind(RegisterServiceInterface::class, RegisterUserService::class);
    }
}
