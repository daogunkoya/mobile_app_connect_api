<?php

namespace App\Services\Transaction;

use App\Repositories\CommissionRepository;
use App\Services\Commission\CommissionService;
use App\Services\Rate\RateService;
use App\Models\transaction;
use App\Models\sender;
use App\Models\receiver;
use App\Services\Helper;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;



class TransactionService
{

    public function __construct(public CommissionRepository $commissionRepository)
    {

    }

    //fetch latest transaction & filters
    public function fetchTransaction($input)
    {

        $user = optional(Auth::user())->toArray();
        $user_id = Auth::id();
        //$user_id = "2bda0c37-4eac-44e5-a014-6c029d76dc62";

        $select = ['created_at', 'id_transaction as transaction_id', 'user_id', 'sender_id', 'sender_address', 'receiver_address',
            'currency_id', 'id_transaction as sender_name', 'id_transaction as receiver_name', 'receiver_phone', 'receiver_bank',
            'receiver_bank_id', 'receiver_identity', 'receiver_identity_id', 'receiver_account_no', 'receiver_transfer_type', 'receiver_transfer_type_key',
            'amount_sent', 'total_amount', 'local_amount', 'total_commission', 'agent_commission', 'exchange_rate',
            'bou_rate', 'sold_rate', 'note', 'currency_income', 'transaction_status',];


        // $input_query = !empty($input['query']) ?json_decode($input['query'],  true): [];
        $input_query = !empty($input['query']) ? (is_string($input['query']) ? json_decode($input['query'], true) : $input['query'])
            : [];


        $whereUser = $user['user_role_type'] == 1 ? [['transaction.user_id', '=', $user_id]] : [];
        $limit = $input['limit'] ?? 10;
        // $search =!empty($input_query['search'])? "%".$input_query['search']."%":'%';

        $moderation_status = !empty($input_query['moderation_status']) ? [$input_query['moderation_status']] : [1, 2, 3, 4];


        [$date_start, $date_end] = $this->dateFinder($input);


        //$search =!empty($input['search'])? "%".$input['search']."%":'%';
        $search = !empty($input_query['search']) && $input_query['search'] != "null" ? "%" . $input_query['search'] . "%" : '%';

        $query = transaction::
        where('transaction_status', 1)
            ->where(function ($query) use ($search) {
                $query->orWhere('receiver_fname', 'like', $search)->orWhere('receiver_lname', 'like', $search)->orWhere('sender_lname', 'like', $search)->orWhere('sender_fname', 'like', $search);
            })
            ->whereIn('moderation_status', $moderation_status)
            ->where($whereUser)
            ->whereBetween('created_at', [$date_start . " 00:00:00", $date_end . " 23:59:59"])
            ->orderBy('created_at', 'DESC');

        $total_sent = $query->sum('total_amount');
        $count = $query->count();
        $limit = $input['limit'] ?? 20;


        ////pagination
        if (!empty($input['cursor'])) {
            $transaction_id = optional($query->select('id_transaction')->get())->toArray();
            $key = Helper::find_key($transaction_id, 'id_transaction', $input['cursor']);
            $page_start = $key === false ? 0 : $key + 1;
        }

        $transactions = optional($query->select($select)->skip($page_start ?? 0)->limit($limit)->get())->toArray();

        return [['transaction_count' => $count, 'total_sent' => number_format($total_sent, 2), 'transaction' => $transactions], 200];
    }


    //fetch date by today, yesterday, a month ago or between days
    public function dateFinder($input)
    {

        $input_query = !empty($input['query']) ? (is_string($input['query']) ? json_decode($input['query'], true) : $input['query'])
            : [];

        $date_length = $input_query['date_length'] ?? '';

        //convert string date in english to string in america standard

        $date_start = !empty($input_query['date_start']) ? Carbon::createFromFormat('d/m/Y', $input_query['date_start'])->format('Y/m/d') : "1970/01/01";

        $date_end = !empty($input_query['date_end']) ? Carbon::createFromFormat('d/m/Y', $input_query['date_end'])->format('Y/m/d') : Carbon::now()->format('Y/m/d');


        // $date_start =$input_query['date_start']??"1970/01/01";
        // $date_end= $input_query['date_end']??Carbon::now()->format('Y/m/d');


        if ($date_length == 'today') {
            $date_start = Carbon::today()->format('Y/m/d');
            $date_end = Carbon::today()->format('Y/m/d');
        }

        if ($date_length == 'yesterday') {
            $date_start = Carbon::today()->subDays(1)->format('Y/m/d');
            $date_end = Carbon::today()->subDays(1)->format('Y/m/d');
        }

        if ($date_length == 'a_month') {
            $date_start = Carbon::today()->subDays(31)->format('Y/m/d');
            $date_end = Carbon::today()->format('Y/m/d');
        }

        return [$date_start, $date_end];
    }

//add a new transaction
    public function storeTransaction($input)
    {
        $userId = $input['user']['id_user'] ?? '';

        $calculateResult = $this->calculateAmountToSend(['conversion_type' => 1, 'send_amount' => $input['amount_sent']]);
        $totalAmount = (float) ($calculateResult['total'] ?? 0);
        $localAmount = (float) ($calculateResult['local'] ?? 0);
        $totalCommission = (float) ($calculateResult['commission'] ?? 0);
        $agentCommission = (float) $totalCommission * $calculateResult['agent_quota'] / 100;
        $exchangeRate = (float) ($calculateResult['rate'] ?? 0);
        $bouRate = 0;
        $soldRate = 0;

        $receiver = optional(
            receiver::where('id_receiver', $input['receiver_id'])
                ->select(
                    'sender_id',
                    'receiver_fname',
                    'receiver_lname',
                    'receiver_address',
                    'receiver_fname',
                    'receiver_lname',
                    'receiver_phone',
                    'bank_id',
                    'account_number',
                    'transfer_type',
                    'currency_id',
                    'bank_id',
                    'identity_type_id',
                )->first()
        )->toArray();

        $sender = optional(
            sender::where('id_sender', $receiver['sender_id'])
                ->select('sender_fname', 'sender_lname', 'sender_address')
                ->first()
        )->toArray();

        if (!empty($input)) {
            $receiverPhone = $receiver['receiver_phone'] ?? $receiver['receiver_mobile'];

            $transaction = transaction::create([
                'store_id' => store_id(),
                'user_id' => $userId,
                'transaction_code' => $this->generateUniqueTransactionCode(),
                'currency_id' => $receiver['currency_id'] ?? '',
                'sender_id' => $receiver['sender_id'] ?? '',
                'receiver_id' => $input['receiver_id'],
                'sender_fname' => $sender['sender_fname'] ?? '',
                'sender_lname' => $sender['sender_lname'] ?? '',
                'receiver_fname' => $receiver['receiver_fname'],
                'receiver_lname' => $receiver['receiver_lname'],
                'receiver_address' => $receiver['receiver_address'],
                'receiver_bank_id' => $receiver['bank_id'],
                'receiver_identity_id' => $receiver['identity_type_id'],
                'receiver_account_no' => $receiver['account_number'],
                'receiver_transfer_type' => $receiver['transfer_type'],
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

            return [['transaction_id' => $transaction->id_transaction], 200];
        }

        return [['error' => 'Something went wrong'], 422];
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
    //update transaction

    public function updateTransaction($input, $transactionId)
    {
        $parts = preg_split('/\s+/', $input['receiver_name']);
        $receiverFname = $parts[0] ?? '';
        $receiverLname = $parts[1] ?? '';

        if (!empty($input) && transaction::where('id_transaction', $transactionId)->exists()) {
            $transaction = transaction::where('id_transaction', $transactionId)->update([
                'receiver_fname' => $receiverFname,
                'receiver_lname' => $receiverLname,
                'receiver_bank' => $input['receiver_bank'],
                'receiver_bank_id' => $input['receiver_bank_id'],
                'receiver_identity_id' => $input['receiver_identity_id'] ?? '',
                'receiver_account_no' => $input['receiver_account_no'],
                'receiver_transfer_type' => $input['receiver_transfer_type'],
                'receiver_phone' => $input['receiver_phone'] ?? '',
            ]);

            return [['transaction_id' => $transactionId], 200];
        }

        return [["something went wrong"], 422];
    }




    public function showAmountBreakdown($input)
    {

        $res = $this->calculateAmountToSend($input);

          return [
            'rate' => $res['rate'],
            'local' => number_format($res['local'], 2),
            'commission' => number_format($res['commission'], 2),
            'total' => number_format($res['total'], 2),
            'send_amount' => number_format($res['send_amount'], 2),
            'conversion_type' => $res['conversion_type'],
            'agent_quota' => $res['agent_quota']
          ];
    }



    //calculate local,commission,total from amount

    //converter type --> actual total =  1  or local  = 2
    public function calculateAmountToSend($input)
    {
        $sendAmount = $input['send_amount'] ?? 0;
        $conversionType = $input['conversion_type'] ?? 1;

        $todaysRate = RateService::todaysRate();
        $rate = $todaysRate['main_rate'] ?? 0;

        // Evaluate based on convert type
        $sendAmount = $conversionType == 1 ? $sendAmount : $sendAmount / $rate;

        $resCommission = $this->commissionRepository->fetchCommissionValue(['amount' => $sendAmount, 'conversion_type' => $conversionType]);
        $commissionValue = $resCommission['commission_value'] < 1 ? $resCommission['value'] * $sendAmount : $resCommission['commission_value'];

        $agentQuota = $resCommission['agent_quota'];

        $commission = $commissionValue ?? 0;
        $total =  $sendAmount + $commission;
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

}
