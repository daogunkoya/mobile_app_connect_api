<?php

namespace App\Exceptions;

class CommissionNotSetException extends \RuntimeException
{
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public static function dueToCommissionNotSet():CommissionNotSetException
    {
        return new self('rate is not set for this user');
    }



}
