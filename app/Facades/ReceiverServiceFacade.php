<?php

namespace App\Facades;

use App\Services\Receiver\ReceiverServiceInterface;
use Illuminate\Support\Facades\Facade;

class ReceiverServiceFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return ReceiverServiceInterface::class;
    }
}
