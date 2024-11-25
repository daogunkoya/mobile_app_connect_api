<?php

namespace App\Payment;

use App\Payment\Exceptions\PaymentFailedException;
use App\Models\Payment;
use App\Models\Transaction;
use App\Payment\PaymentDetails;
use App\Payment\PaymentGateway;

class CreatePaymentForTransaction implements CreatePaymentForTransactionInterface
{


    /**
     * @throws PaymentFailedException
     */
    public function handle(
        PaymentGateway $paymentGateway,
        string         $paymentToken,
        int            $transactionTotalInPence,
        string         $userId,
        string         $transactionId): Payment
    {


        $charge = $paymentGateway->charge(
            new PaymentDetails($paymentToken, $transactionTotalInPence, 'modularization')
        );

        
      return  Transaction::find($transactionId)->payment()->create([
            'total_in_pence' => $transactionTotalInPence,
            'status' => 'paid',
            'payment_gateway' => $charge->paymentProvider,
            'payment_id' => $charge->id,
            'transaction_id' => $transactionId,
            'user_id' => $userId
        ]);
        

        // return Payment::query()->create([
        //     'total_in_pence' => $transactionTotalInPence,
        //     'status' => 'paid',
        //     'payment_gateway' => $charge->paymentProvider,
        //     'payment_id' => $charge->id,
        //     'transaction_id' => $transactionId,
        //     'user_id' => $userId
        // ]);


    }
}
