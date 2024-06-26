<?php

namespace App\Actions;

use App\Collections\TransactionCollection;
use App\DTO\ReceiverDto;
use App\DTO\TransactionDto;
use App\DTO\UserDto;
use App\Exceptions\RateNotSetException;
use App\Models\Receiver;
use App\Models\Sender;
use App\Models\Transaction;
use App\Payment\CreatePaymentForTransactionInterface;
use App\Repositories\RateRepository;
use Illuminate\Database\DatabaseManager;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Payment\Contracts\PendingPayment;
use App\Enum\UserRoleType;
use App\Actions\GenerateUniqueTransactionCode;

class CreateTransaction
{


    public function __construct(
        protected DatabaseManager $databaseManager,
        protected CreatePaymentForTransactionInterface $createPaymentForTransaction,
         protected Dispatcher $events,
         protected GenerateUniqueTransactionCode $generateUniqueTransactionCode
    )
    {
    }

    public function handle(
        TransactionCollection $transactionCollection,
                       PendingPayment $pendingPayment,
                       ReceiverDto $receiver,
                       UserDto $userDto)
    {



        $transaction = $this->databaseManager->transaction(function () use (
            $transactionCollection,
             $pendingPayment,
            $receiver,
            $userDto
        ) {

            $userRoleType = $userDto->userRoleType;
            $senderId = $userRoleType === UserRoleType::CUSTOMER ? $userDto->userId : $receiver->senderId;
            $senderFname = $userRoleType === UserRoleType::CUSTOMER ? $userDto->firstName : $receiver->sender->senderFname;
            $senderLname = $userRoleType === UserRoleType::CUSTOMER ? $userDto->lastName : $receiver->sender->senderLname;  
            $senderAddress = $userRoleType === UserRoleType::CUSTOMER ? $userDto->address : $receiver->sender->senderAddress;

            $transaction =  Transaction::create([
                'store_id' => store_id(),
                'user_id' => $userDto->userId,
                'transaction_code' => ($this->generateUniqueTransactionCode)(),
                'origin_currency_id' => $transactionCollection->originCurrencyId,
                'destination_currency_id' => $transactionCollection->destinationCurrencyId,
                'sender_id' => $senderId,
                'receiver_id' => $receiver->receiverId,
                'sender_fname' => $senderFname,
                'sender_lname' => $senderLname,
                'receiver_fname' => $receiver->receiverFname,
                'receiver_lname' => $receiver->receiverLname,
                'receiver_address' => $receiver->receiverAddress,
                'receiver_bank_id' => $receiver->bankId,
                'receiver_identity_id' => $receiver->identityTypeId,
                'receiver_account_no' => $receiver->accountNumber,
                'receiver_transfer_type' => $receiver->transferType,
                'sender_address' => $senderAddress,
                'agent_payment_id' => '',
                'receiver_phone' => $receiver->receiverPhone ?? $receiver->receiverMobile,
                'amount_sent' => $transactionCollection->amountSent,
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

            $transaction = TransactionDto::fromEloquentModel($transaction);

            $this->createPaymentForTransaction->handle(
                $pendingPayment->paymentGateway,
                $pendingPayment->paymentToken,
                $transactionCollection->totalAmount,
                $userDto->userId,
                $transaction->transactionId
            );

            return  $transaction;
        });

        $this->events->dispatch(
            new TransactionFulfilled(
                $transaction,
                $userDto
            )

        );
            return $transaction;


    }



    // public function generateUniqueTransactionCode()
    // {
    //     $maxLength = 9;
    //     $transactionCode = '';
    //     do {
    //         $transactionCode = Str::random($maxLength) . Auth::id();
    //     } while (Transaction::where('transaction_code', $transactionCode)->exists());

    //     // Trim the transaction code to ensure it doesn't exceed the maximum length
    //     $transactionCode = substr($transactionCode, 0, $maxLength);

    //     return $transactionCode;

    // }


}
