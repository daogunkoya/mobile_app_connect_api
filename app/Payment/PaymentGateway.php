<?php

namespace App\Payment;

use App\Enum\PaymentProvider;

interface PaymentGateway
{
public function charge(PaymentDetails $paymentDetails):SuccessfulPayment;
public function id():PaymentProvider;
}
