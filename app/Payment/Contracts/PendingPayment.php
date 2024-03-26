<?php

namespace App\Payment\Contracts;

use App\Payment\PaymentGateway;

readonly class PendingPayment
{

    /**
     * @param  PaymentGateway  $paymentGateway
     * @param string $paymentToken
     */
    public function __construct(
        public PaymentGateway $paymentGateway,
        public string $paymentToken
    )
    {
    }
}
