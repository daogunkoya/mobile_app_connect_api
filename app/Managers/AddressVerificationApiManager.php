<?php

namespace App\Managers;

use App\Services\ThirdPartyServices\IdealPostCodeService\IdealPostCodeService;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Manager;

class AddressVerificationApiManager extends Manager
{
    /**
     * Get the default driver name.
     */
    public function getDefaultDriver(): string
    {
        return config('address-verification.default');
    }

    /**
     * @throws BindingResolutionException
     */
    public function createIdealPostcodeDriver(): mixed
    {
        return $this->container->make(IdealPostCodeService::class);
    }
}
