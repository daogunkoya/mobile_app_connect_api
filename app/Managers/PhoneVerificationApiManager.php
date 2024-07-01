<?php

namespace App\Managers;

use App\Services\ThirdPartyServices\Data8Service\Data8Service;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Manager;

class PhoneVerificationApiManager extends Manager
{
    /**
     * Get the default driver name.
     */
    public function getDefaultDriver(): string
    {
        return config('phone-verification.default');
    }

    /**
     * @throws BindingResolutionException
     */
    public function createData8Driver(): mixed
    {
        return $this->container->make(Data8Service::class);
    }
}
