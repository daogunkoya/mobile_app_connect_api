<?php

namespace App\Services\ThirdPartyServices\IdealPostCodeService\Contracts\AddressVerificationService;

interface AddressVerificationService
{
    public function execute();

    public function setRequestParams(array $params);
}
