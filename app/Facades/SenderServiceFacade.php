<?php

namespace App\Facades;

use App\Services\Sender\SenderServiceInterface;
use Illuminate\Support\Facades\Facade;

class SenderServiceFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return SenderServiceInterface::class;
    }
}
