<?php

namespace App\Repositories;

use App\Enum\UserRoleType;
use App\Exceptions\RateNotSetException;
use App\Models\Receiver;
use App\Models\Sender;
use App\Models\Transaction;
use App\Services\Helper;
use App\Services\Rate\RateService;
use Carbon\Carbon;
use Dotenv\Exception\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TransactionRepository
{

    public function __construct(
        protected CommissionRepository $commissionRepository,
        protected BankRepository       $bankRepository
    ) {
    }

    public function fetchTransaction($input, $user)
    {
            
            // Transaction::query() :
            // Sender::find(request('sender_id'))->transaction();

            $isAdmin = $user->userRoleType == UserRoleType::ADMIN;

            $transactionQuery =   Transaction::query();

        $query = $transactionQuery->withCount('receiver')
            ->select(self::transactionSelectList())
            ->filter([
                'userId' => !$isAdmin?$user->userId: null,
                'search' => $input['search'] ?? '',
                'date' => $input['date'] ?? ''
            ])
            ->orderBy('created_at', 'DESC');


        $page = request('page') ?? 1;
        $limit = request('limit') ?? 6;

        return [
            $query->paginate($limit, ['*'], 'page', $page),
            $query->sum('total_amount')
        ];
    }


    public function calculateTotalAmount($input, $user)
    {
        // Your logic to calculate the total amount for the entire result set
        $transactionQuery = $user->user_role_type == UserRoleType::ADMIN ?
            Transaction::query() :
            Sender::find($input['sender_id'])->transaction();

        return $transactionQuery->sum('total_amount');
    }

    public static function transactionSelectList()
    {
        return [
            'created_at',
            'id_transaction',
            'transaction_code',
            'user_id',
            'sender_id',
            'sender_address',
            'receiver_address',
            'currency_id',
            'receiver_fname',
            'receiver_lname',
            'receiver_phone',
            'receiver_bank_id',
            'receiver_identity_id',
            'receiver_account_no',
            'receiver_transfer_type',
            'amount_sent',
            'total_amount',
            'local_amount',
            'total_commission',
            'agent_commission',
            'exchange_rate',
            'note',
            'currency_income',
            'transaction_type',
            'transaction_status'
        ];
    }

    //    public function fetchTransactionOld($input)
    //    {
    //        $user = optional(Auth::user())->toArray();
    //        $user_id = Auth::id();
    //
    //        $select = [
    //            'created_at', 'id_transaction as transaction_id', 'transaction_code', 'user_id', 'sender_id', 'sender_address', 'receiver_address',
    //            'currency_id', 'id_transaction as sender_name', 'id_transaction as receiver_name', 'receiver_fname', 'receiver_lname', 'receiver_phone',
    //            'receiver_bank_id', 'receiver_identity_id', 'receiver_account_no', 'receiver_transfer_type',
    //            'amount_sent', 'total_amount', 'local_amount', 'total_commission', 'agent_commission', 'exchange_rate',
    //            'bou_rate', 'sold_rate', 'note', 'currency_income', 'transaction_type', 'transaction_status'
    //        ];
    //
    //        $input_query = !empty($input['query']) ? (is_string($input['query']) ? json_decode($input['query'], true) : $input['query']) : [];
    //
    //        $whereUser = $user['user_role_type'] == 1 ? [['transaction.user_id', '=', $user_id]] : [];
    //        $limit = $input['limit'] ?? 10;
    //        $moderation_status = !empty($input_query['moderation_status']) ? [$input_query['moderation_status']] : [1, 2, 3, 4];
    //
    //        [$date_start, $date_end] = $this->dateFinder($input);
    //        $search = !empty($input_query['search']) && $input_query['search'] != "null" ? "%" . $input_query['search'] . "%" : '%';
    //
    //        $query = transaction::
    //        with(['bank:id,name', 'identity:id,name'])
    //            ->where('transaction_status', 1)
    //            ->where(function ($query) use ($search) {
    //                $query->orWhere('receiver_fname', 'like', $search)
    //                    ->orWhere('receiver_lname', 'like', $search)
    //                    ->orWhere('sender_lname', 'like', $search)
    //                    ->orWhere('sender_fname', 'like', $search);
    //            })
    //            ->whereIn('moderation_status', $moderation_status)
    //          //  ->where($whereUser)
    //            ->whereBetween('created_at', [$date_start . " 00:00:00", $date_end . " 23:59:59"])
    //            ->orderBy('created_at', 'DESC');
    //
    //        $total_sent = $query->sum('total_amount');
    //        $count = $query->count();
    //        $limit = $input['limit'] ?? 6;
    //
    //        // Pagination
    //        $page = $input['page'] ?? 1;
    //        $perPage = $limit;
    //        $page_start = ($page - 1) * $perPage;
    //
    //        $transactions = $query->select($select)
    //            ->skip($page_start)
    //            ->limit($limit)
    //            ->get()
    //            ->toArray();
    //
    //        $lastPage = ceil($count / $perPage);
    //
    //        $chatData = $this->fetchChartData($query);
    //        return [
    //            'transaction_count' => $count,
    //            'total_sent' => number_format($total_sent, 2),
    //            'transaction' => $transactions,
    //            'current_page' => $page,
    //            'last_page' => $lastPage,
    //            'total' => $count,
    //            'per_page' => $perPage,
    //            'chart_data' => $chatData,
    //            'banks_id_list' => $this->bankRepository->fetchBanksIdentityTypesList()
    //        ];
    //    }


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
        $totalAmount = (float)($calculateResult['total'] ?? 0);
        $localAmount = (float)($calculateResult['local'] ?? 0);
        $totalCommission = (float)($calculateResult['commission'] ?? 0);
        $agentCommission = (float)$totalCommission * $calculateResult['agent_quota'] / 100;
        $exchangeRate = (float)($calculateResult['rate'] ?? 0);
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

    public function updateTransaction($input, $transactionId)
    {
        //        $parts = preg_split('/\s+/', $input['receiver_name']);
        //        $receiverFname = $parts[0] ?? '';
        //        $receiverLname = $parts[1] ?? '';

        if (!empty($input) && transaction::where('id_transaction', $transactionId)->exists()) {
            $transaction = transaction::where('id_transaction', $transactionId)->update([
                'receiver_fname' => $input['receiver_fname'] ?? '',
                'receiver_lname' => $input['receiver_lname'] ?? '',
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

        $rateToday = RateRepository::fetchTodaysRate();
        $rate = $rateToday->main_rate ?? 0;

        if (!$rate)  throw new RateNotSetException("no rate is provided");


        // Evaluate based on convert type
        $sendAmount = $conversionType == 1 ? $sendAmount : $sendAmount / $rate;

        $resCommission = $this->commissionRepository->fetchCommissionValue(
            [
                'amount' => $sendAmount,
                'conversion_type' => $conversionType
            ]
        );
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


    public function fetchChartData($query): array
    {

        $transactions = $query
            ->select('created_at')
            ->get();

        // Group transactions by month using Laravel's collection helpers
        $groupedByMonth = $transactions->groupBy(function ($transaction) {
            $createdAt = \Carbon\Carbon::createFromFormat('d/m/Y', $transaction['created_at']);

            // Now use the Carbon instance to format the date
            return $createdAt->format('M Y'); // Group by year-month
        });

        $transactionCountPerMonth = $groupedByMonth->map(function ($transactions, $month) {
            return count($transactions); // Count transactions per month
        });

        // Generate labels and data arrays for the chart
        $labels = $transactionCountPerMonth->keys()->toArray(); // Array of month labels
        $data = $transactionCountPerMonth->values()->toArray(); // Array of transaction counts


        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }
}
