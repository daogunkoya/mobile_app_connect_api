<?php

namespace Modules\Payment\Actions;

use Modules\Payment\Exceptions\PaymentFailedException;
use Modules\Payment\Payment;
use Modules\Payment\PaymentDetails;
use Modules\Payment\PaymentGateway;

class CreatePaymentForOrder implements CreatePaymentForOrderInterface
{


    /**
     * @throws PaymentFailedException
     */
    public function handle(
        PaymentGateway $paymentGateway,
        string $paymentToken,
        int $orderTotalInCents ,
        int $userId,
        int $orderId):Payment
    {


            $charge =  $paymentGateway->charge(
               new PaymentDetails( $paymentToken, $orderTotalInCents, 'modularization')
            );

      return  Payment::query()->create([
            'total_in_cents' => $orderTotalInCents,
            'status' => 'paid',
            'payment_gateway' => $charge->paymentProvider,
            'payment_id' => $charge->id,
            'order_id' => $orderId,
            'user_id' => $userId
        ]);


    }
}
