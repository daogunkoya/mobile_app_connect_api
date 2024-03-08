<?php

namespace Modules\Payment\Actions;

use Modules\Payment\Payment;
use Modules\Payment\PaymentGateway;

interface CreatePaymentForOrderInterface
{
    public function handle(
        PaymentGateway $paymentGateway,
        string $paymentToken,
        int $orderTotalInCents ,
        int $userId,
        int $orderId):Payment;

}
