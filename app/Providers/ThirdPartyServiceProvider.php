<?php

namespace App\Providers;

use App\Managers\AddressVerificationApiManager;
use App\Managers\PhoneVerificationApiManager;
use App\Services\ThirdPartyServices\Data8Service\Contracts\PhoneVerification\PhoneVerificationService;
use App\Services\ThirdPartyServices\IdealPostCodeService\Contracts\AddressVerificationService\AddressVerificationService;
use App\Services\ThirdPartyServices\Pay360Service;
use App\Services\ThirdPartyServices\Proclaim\ProclaimCaseService;
use App\Services\ThirdPartyServices\Proclaim\ProclaimLoginService;
use App\Services\ThirdPartyServices\PropertyDataService;
use App\Services\ThirdPartyServices\RightmoveService;
use App\Services\ThirdPartyServices\StripoService;
use App\Services\Utility\SwitchBreaker\SwitchBreaker;
use App\Services\Utility\SwitchBreaker\SwitchBreakerInterface;
use Illuminate\Support\ServiceProvider;

class ThirdPartyServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        
        $this->app->bind(AddressVerificationService::class, fn ($app) => $app->make(AddressVerificationApiManager::class)->driver());

        // $this->app->bind(PhoneVerificationService::class, fn ($app) => $app->make(PhoneVerificationApiManager::class)->driver());
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
