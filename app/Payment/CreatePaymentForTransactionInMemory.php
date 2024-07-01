<?php

namespace App\Payment;

use App\Enum\PaymentProvider;
use App\Models\Payment;
use App\Payment\Exceptions\PaymentFailedException;
use Illuminate\Support\Str;

class CreatePaymentForTransactionInMemory implements CreatePaymentForTransactionInterface
{
/**  @var  Payment[] */
public array $payments = [];
protected  bool $shouldFail = false;


    public function handle(
        PaymentGateway $paymentGateway,
        string         $paymentToken,
        int            $transactionTotalInPence ,
        string            $userId,
        string            $transactionId):Payment
    {

        if($this->shouldFail){
            return throw new PaymentFailedException();
        }


            $charge =  $paymentGateway->charge(
               new PaymentDetails( $paymentToken, $transactionTotalInPence, 'modularization')
            );

      $payment =  new Payment([
          'order_id' => $transactionId,
          'user_id' => $userId,
           'total_in_pence' => $transactionTotalInPence,
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
