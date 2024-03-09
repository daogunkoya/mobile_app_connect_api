<?php

namespace App\Actions;

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

    public function handle($input, ReceiverDto $receiver)
    {
     $userId = $input['user']['id_user'] ?? '';

        $calculateResult = $this->calculateAmountToSend(['conversion_type' => 1, 'send_amount' => $input['amount_sent']]);
        $totalAmount = (float)($calculateResult['total'] ?? 0);
        $localAmount = (float)($calculateResult['local'] ?? 0);
        $totalCommission = (float)($calculateResult['commission'] ?? 0);
        $agentCommission = (float)$totalCommission * $calculateResult['agent_quota'] / 100;
        $exchangeRate = (float)($calculateResult['rate'] ?? 0);
        $bouRate = 0;
        $soldRate = 0;

//
var_dump($receiver->sender);
        $sender = optional(
            sender::where('id_sender', $receiver->senderId)
                ->select('sender_fname', 'sender_lname', 'sender_address')
                ->first()
        )->toArray();

        if (!empty($input)) {
            $receiverPhone = $receiver->receiverPhone ?? $receiver->receiverMobile;

            $transaction = transaction::create([
                'store_id' => store_id(),
                'user_id' => $userId,
                'transaction_code' => $this->generateUniqueTransactionCode(),
                'currency_id' => $receiver->currencyId ,
                'sender_id' => $receiver->senderId,
                'receiver_id' => $input['receiver_id'],
                'sender_fname' => $sender['sender_fname'] ?? '',
                'sender_lname' => $sender['sender_lname'] ?? '',
                'receiver_fname' => $receiver->receiverFname,
                'receiver_lname' => $receiver->receiverLname,
                'receiver_address' => $receiver->receiverAddress,
                'receiver_bank_id' => $receiver->bankId,
                'receiver_identity_id' => $receiver->identityTypeId,
                'receiver_account_no' => $receiver->accountNumber,
                'receiver_transfer_type' => $receiver->transferType,
                'sender_address' => $sender['sender_address'],
                'agent_payment_id' => '',
                'receiver_phone' => $receiverPhone ?? '',
                'amount_sent' => $input['amount_sent'],
                'total_amount' => $totalAmount,
                'local_amount' => $localAmount,
                'total_commission' => $totalCommission,
                'agent_commission' => $agentCommission,
                'exchange_rate' => $exchangeRate,
                'bou_rate' => $bouRate,
                'sold_rate' => $soldRate,
                'note' => '',
                'currency_income' => 1,
                'transaction_status' => 1,
                'transaction_type' => 1,
                'moderation_status' => 1,
            ]);

            return $transaction;
        }

        return [['error' => 'Something went wrong'], 422];
    }


    public function calculateAmountToSend($input)
    {
        $sendAmount = $input['send_amount'] ?? 0;
        $conversionType = $input['conversion_type'] ?? 1;

        $rateToday = RateRepository::fetchTodaysRate();
        $rate = $rateToday->main_rate??0;

        if(!$rate)  throw new RateNotSetException("no rate is provided");


        // Evaluate based on convert type
        $sendAmount = $conversionType == 1 ? $sendAmount : $sendAmount / $rate;

        $resCommission = $this->fetchUserCommission->handle($sendAmount, $conversionType);
        $commissionValue = $resCommission['commission_value'] < 1 ?
            $resCommission['value'] * $sendAmount
            : $resCommission['commission_value'];

        $agentQuota = $resCommission['agent_quota'];

        $commission = $commissionValue ?? 0;
        $total = $sendAmount + $commission;
        $local = $rate * $sendAmount;

        return [
            'rate' => $rate,
            'local' => $local,
            'commission' => $commission,
            'total' => $total,
            'send_amount' => $sendAmount,
            'conversion_type' => $conversionType,
            'agent_quota' => $agentQuota
        ];
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
