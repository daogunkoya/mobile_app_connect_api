<?php
namespace App\Payment;

use App\Models\OutstandingPayment;

class CreatePaymentForOutstanding
{
    public function createPaymentForOutstanding(OutstandingPayment $outstandingPayment): void
    {
        $outstandingPayment->payment()->create([
            'total_in_pence' => $outstandingPayment->total_amount,
            'status' => 'paid',
            'payment_gateway' => "in_person",
            'payment_id' => "",
            'transaction_id' => "",
            'user_id' => ""
        ]);
    }
}
