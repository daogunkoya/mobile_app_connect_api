<?php

namespace Modules\Payment\Actions;

use Illuminate\Support\Str;
use Modules\Payment\Exceptions\PaymentFailedException;
use Modules\Payment\Payment;
use Modules\Payment\PaymentDetails;
use Modules\Payment\PaymentGateway;
use Modules\Payment\PaymentProvider;

class CreatePaymentForOrderInMemory implements CreatePaymentForOrderInterface
{
/**  @var  Payment[] */
public array $payments = [];
protected  bool $shouldFail = false;


    public function handle(
        PaymentGateway $paymentGateway,
        string $paymentToken,
        int $orderTotalInCents ,
        int $userId,
        int $orderId):Payment
    {

        if($this->shouldFail){
            return throw new PaymentFailedException();
        }


            $charge =  $paymentGateway->charge(
               new PaymentDetails( $paymentToken, $orderTotalInCents, 'modularization')
            );

      $payment =  new Payment([
          'order_id' => $orderId,
          'user_id' => $userId,
           'total_in_cents' => $orderTotalInCents,
            'payment_gateway' => PaymentProvider::InMemory,
            'payment_id' => (string) Str::uuid(),

        ]);

       $this->payments[] = $payment;

       return $payment;


    }

    public function shouldFail():void
    {
        $this->shouldFail = true;
    }
}
