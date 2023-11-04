<?php

namespace  App\Repositories;

use App\Models\Transaction;
use App\Services\Helper;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class TransactionRepository{


    public function fetchTransaction($input)
    {
        $user = optional(Auth::user())->toArray();
        $user_id = Auth::id();

        $select = ['created_at','id_transaction as transaction_id','user_id','sender_id','sender_address','receiver_address',
            'currency_id','id_transaction as sender_name','id_transaction as receiver_name','receiver_phone','receiver_bank',
            'receiver_bank_id','receiver_identity','receiver_identity_id', 'receiver_account_no', 'receiver_transfer_type','receiver_transfer_type_key',
            'amount_sent','total_amount','local_amount',  'total_commission','agent_commission','exchange_rate',
            'bou_rate','sold_rate' ,'note','currency_income','transaction_status'];

        $input_query = !empty($input['query']) ? (is_string($input['query']) ? json_decode($input['query'], true) : $input['query']) : [];

        $whereUser =  $user['user_role_type'] == 1 ? [['transaction.user_id','=', $user_id ]] : [];
        $limit = $input['limit'] ?? 10;
        $moderation_status = !empty($input_query['moderation_status']) ? [ $input_query['moderation_status'] ] : [1,2,3,4];

        [$date_start, $date_end] = $this->dateFinder($input);
        $search = !empty($input_query['search']) && $input_query['search'] != "null" ? "%" . $input_query['search'] . "%" : '%';

        $query = transaction::
        where('transaction_status', 1)
            ->where(function ($query) use ($search) {
                $query->orWhere('receiver_fname', 'like', $search)
                    ->orWhere('receiver_lname', 'like', $search)
                    ->orWhere('sender_lname', 'like', $search)
                    ->orWhere('sender_fname', 'like', $search);
            })
            ->whereIn('moderation_status', $moderation_status)
            ->where($whereUser)
            ->whereBetween('created_at', [$date_start . " 00:00:00",$date_end . " 23:59:59"])
            ->orderBy('created_at', 'DESC');

        $total_sent = $query->sum('total_amount');
        $count = $query->count();
        $limit = $input['limit'] ?? 6;

        // Pagination
        $page = $input['page'] ?? 1;
        $perPage = $limit;
        $page_start = ($page - 1) * $perPage;

        $transactions = $query->select($select)
            ->skip($page_start)
            ->limit($limit)
            ->get()
            ->toArray();

        $lastPage = ceil($count / $perPage);

        $chatData = $this->fetchChartData($query)
 ;       return [
            'transaction_count' => $count,
            'chart_data' =>$chatData,
            'total_sent' => number_format($total_sent, 2),
            'transaction' => $transactions,
            'current_page' => $page,
            'last_page' => $lastPage,
            'total' => $count,
            'per_page' => $perPage,
        ];
    }


    public function dateFinder($input)
    {

        $input_query = !empty($input['query']) ? (is_string($input['query']) ? json_decode($input['query'], true) : $input['query'])
            : [];

        $date_length = $input_query['date_length'] ?? '';

        //convert string date in english to string in america standard

        $date_start = !empty($input_query['date_start']) ? Carbon::createFromFormat('d/m/Y', $input_query['date_start'])      ->format('Y/m/d') : "1970/01/01";

        $date_end = !empty($input_query['date_end']) ? Carbon::createFromFormat('d/m/Y', $input_query['date_end'])      ->format('Y/m/d') : Carbon::now()->format('Y/m/d');


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


    public function fetchChartData($query):array{

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
