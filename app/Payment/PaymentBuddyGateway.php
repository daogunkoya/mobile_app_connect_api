<?php

namespace App\Payment;

use App\Enum\PaymentProvider;
use App\Payment\Exceptions\PaymentFailedException;
use RuntimeException;

class PaymentBuddyGateway implements PaymentGateway
{
    public function __construct(
        protected PayBuddySdk $payBuddySdk
    ) {
    }

    /**
     * @param  PaymentDetails  $details
     * @return SuccessfulPayment
     * @throws  PaymentFailedException
     */
    public function charge(PaymentDetails $details): SuccessfulPayment
    {
        try{

        $charge = $this->payBuddySdk->charge(
            $details->token,
            $details->amountInCents,
            $details->statementDescription
        );
        }catch(RuntimeException $exception)
        {
                throw new PaymentFailedException($exception->getMessage());

        }

        return new SuccessfulPayment(
            $charge['id'],
            $charge['amount_in_cents'],
            $this->id()
        );

    }

    public function id(): PaymentProvider
    {
        return PaymentProvider::PayBuddy;

    }
}
