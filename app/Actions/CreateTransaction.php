<?php

namespace App\Actions;

use App\Collections\TransactionCollection;
use App\DTO\ReceiverDto;
use App\Exceptions\RateNotSetException;
use App\Models\Receiver;
use App\Models\Sender;
use App\Models\Transaction;
use App\Repositories\RateRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CreateTransaction
{

    public function __construct(public FetchUserCommission $fetchUserCommission)
    {
    }

    public function handle(TransactionCollection $transactionCollection, ReceiverDto $receiver, $user)
    {




            $receiverPhone = $receiver->receiverPhone ?? $receiver->receiverMobile;

            $transaction = Transaction::create([
                'store_id' => store_id(),
                'user_id' => $user->userId,
                'transaction_code' => $this->generateUniqueTransactionCode(),
                'currency_id' => $receiver->currencyId ,
                'sender_id' => $receiver->senderId,
                'receiver_id' => $receiver->receiverId,
                'sender_fname' => $receiver->sender->senderFname,
                'sender_lname' => $receiver->sender->senderLname,
                'receiver_fname' => $receiver->receiverFname,
                'receiver_lname' => $receiver->receiverLname,
                'receiver_address' => $receiver->receiverAddress,
                'receiver_bank_id' => $receiver->bankId,
                'receiver_identity_id' => $receiver->identityTypeId,
                'receiver_account_no' => $receiver->accountNumber,
                'receiver_transfer_type' => $receiver->transferType,
                'sender_address' => $receiver->sender->senderAddress,
                'agent_payment_id' => '',
                'receiver_phone' => $receiverPhone ?? '',
                'amount_sent' =>  $transactionCollection->amountSent,
                'total_amount' => $transactionCollection->totalAmount,
                'local_amount' => $transactionCollection->localAmount,
                'total_commission' => $transactionCollection->totalCommission,
                'agent_commission' => $transactionCollection->agentCommission,
                'exchange_rate' => $transactionCollection->exchangeRate,
                'bou_rate' => $transactionCollection->bouRate,
                'sold_rate' => $transactionCollection->soldRate,
                'note' => '',
                'currency_income' => 1,
                'transaction_status' => 1,
                'transaction_type' => 1,
                'moderation_status' => 1,
            ]);

            return $transaction;


    }



    public function generateUniqueTransactionCode()
    {
        $maxLength = 9;
        $transactionCode = '';
        do {
            $transactionCode = Str::random($maxLength) . Auth::id();
        } while (Transaction::where('transaction_code', $transactionCode)->exists());

        // Trim the transaction code to ensure it doesn't exceed the maximum length
        $transactionCode = substr($transactionCode, 0, $maxLength);

        return $transactionCode;

    }


}
