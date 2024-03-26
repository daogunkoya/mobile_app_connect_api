<?php

namespace App\Payment;

use App\Enum\PaymentProvider;

class SuccessfulPayment
{
    public function __construct(
        public string $id,
        public int$amountInCents,
        public PaymentProvider $paymentProvider
    )
    {

    }


}
