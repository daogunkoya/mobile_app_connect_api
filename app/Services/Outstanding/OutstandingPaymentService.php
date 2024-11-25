<?php 

namespace App\Services\Outstanding;;

use App\Models\OutstandingPayment;

class OutstandingPaymentService
{
    public function updateTransactionPaymentStatus(string $outstandingId): ?OutstandingPayment
    {
        $outstandingPayment = OutstandingPayment::find($outstandingId);
        if ($outstandingPayment) {
            $outstandingPayment->update([
                'transaction_paid_status' => 1
            ]);
        }

        return $outstandingPayment;
    }

    public function updateCommissionPaymentStatus(string $outstandingId): ?OutstandingPayment
    {
        $outstandingPayment = OutstandingPayment::find($outstandingId);
        if ($outstandingPayment) {
            $outstandingPayment->update([
                'commission_paid_status' => 1
            ]);
        }

        return $outstandingPayment;
    }

    public function processOutstandingTransactionPayment($userId, $outstandingAmount): ?OutstandingPayment
    {
        $userOutstandingTotalPayment = OutstandingPayment::whereUser_id($userId)
            ->where('transaction_paid_status', 0)
            ->sum('total_amount') ?? 0; 

        OutstandingPayment::whereUser_id($userId)->where('transaction_paid_status', 0) ->update(['transaction_paid_status' => 1]);

        if ($outstandingAmount < $userOutstandingTotalPayment) {
            return $this->createPartialOutstandingPayment($userId, $userOutstandingTotalPayment - $outstandingAmount);
        }

       

        return OutstandingPayment::whereUser_id($userId)->where('transaction_paid_status', 1)->first();

    }

    public function processOutstandingCommissionPayment($userId, $outstandingAmount): ?OutstandingPayment
    {
        $userOutstandingTotalCommission = OutstandingPayment::whereUser_id($userId)
            ->where('commission_paid_status', 0)
            ->sum('total_commission') ?? 0;

            OutstandingPayment::whereUser_id($userId)->where('commission_paid_status', 0)->update(['commission_paid_status' => 1]);

        if ($outstandingAmount < $userOutstandingTotalCommission) {
           return  $this->createPartialOutstandingPayment($userId, 0, $userOutstandingTotalCommission - $outstandingAmount);
        } 
        
        return OutstandingPayment::whereUser_id($userId)->where('commission_paid_status', 1)->orderBy('created_at','desc')->first();
    }

    /**
     * Create a partial outstanding payment record for a user
     *
     * @param int $userId
     * @param int $totalAmount
     * @param int $totalCommission
     * @return OutstandingPayment|null
     */
    private function createPartialOutstandingPayment($userId, $totalAmount, $totalCommission = 0): ?OutstandingPayment
    {
        return OutstandingPayment::create([
            'store_id' => store_id(),
            'user_id' => $userId,
            'currency_id' => "",
            'sender_name' => "part payment",
            'receiver_name' => "part payment",
            'transaction_code' => "",
            'transaction_id' => "",
            'total_amount' => $totalAmount,
            'amount_sent' => $totalAmount,
            'local_amount' => 0,
            'total_commission' => $totalCommission,
            'agent_commission' => 0,
            'exchange_rate' => 0,
            'bou_rate' => 0,
            'sold_rate' => 0,
        ]);
    }
}
