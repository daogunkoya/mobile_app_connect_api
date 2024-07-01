<?php

namespace App\Exceptions;

class RateNotSetException extends \RuntimeException
{
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public static function dueToRateNotSet():RateNotSetException
    {
        return new self('rate is not set for this user');
    }



}
