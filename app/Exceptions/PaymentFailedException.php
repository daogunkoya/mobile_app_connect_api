<?php

namespace Modules\Payment\Exceptions;

class PaymentFailedException extends \RuntimeException
{

    public static function dueToInvalidToken():PaymentFailedException
    {
        return new self('the given payemnt token is invalid');
    }
}
