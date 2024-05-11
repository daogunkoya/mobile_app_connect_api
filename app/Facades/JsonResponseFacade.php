<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/** @see JsonResponseFacade */
class JsonResponseFacade extends Facade
{
    /**
     * Get the registered facade name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'json-response';
    }
}
