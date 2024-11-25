<?php

namespace App\Payment;

use App\Models\Payment;
use App\Payment\PaymentGateway;

interface CreatePaymentForTransactionInterface
{
    public function handle(
        PaymentGateway $paymentGateway,
        string         $paymentToken,
        int            $transactionTotalInPence,
        string            $userId,
        string           $transactionId):Payment;

}
