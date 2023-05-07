<?php
namespace App\Services\Transaction;


use App\Services\Commission\CommissionService;
use App\Services\Rate\RateService;
use App\Models\transaction;
use App\Models\sender;
use App\Models\receiver;
use App\Services\Helper;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class TransactionService{



    //fetch latest transaction & filters
    public function fetchTransaction($input){

        $user = optional(Auth::user())->toArray();
         $user_id = Auth::id();
        //$user_id = "2bda0c37-4eac-44e5-a014-6c029d76dc62";

        $select = ['created_at','id_transaction as transaction_id','user_id','sender_id','sender_address','receiver_address',
        'currency_id','id_transaction as sender_name','id_transaction as receiver_name','receiver_phone','receiver_bank',
        'receiver_bank_id','receiver_identity','receiver_identity_id', 'receiver_account_no', 'receiver_transfer_type','receiver_transfer_type_key',
        'amount_sent','total_amount','local_amount',  'total_commission','agent_commission','exchange_rate',
        'bou_rate','sold_rate' ,'note','currency_income','transaction_status', ];


        // $input_query = !empty($input['query']) ?json_decode($input['query'],  true): [];
        $input_query = !empty($input['query']) ?(is_string($input['query'])?json_decode($input['query'],  true) : $input['query'])
                                                : [];


        $whereUser =  $user['user_role_type'] == 1?[['transaction.user_id','=', $user_id ]]:[];
        $limit = $input['limit']??10;
        // $search =!empty($input_query['search'])? "%".$input_query['search']."%":'%';

        $moderation_status = !empty($input_query['moderation_status'])?[ $input_query['moderation_status'] ]:[1,2,3,4];


        [$date_start, $date_end] = $this->dateFinder($input);


        //$search =!empty($input['search'])? "%".$input['search']."%":'%';
        $search =!empty($input_query['search']) && $input_query['search'] !="null" ? "%".$input_query['search']."%":'%';

        $query = transaction::
                                    where('transaction_status', 1)
                                    ->where(function($query)use($search){
                                        $query->orWhere('receiver_fname', 'like', $search)->orWhere('receiver_lname', 'like', $search)->orWhere('sender_lname', 'like', $search)->orWhere('sender_fname', 'like', $search);
                                            })
                                    ->whereIn('moderation_status',$moderation_status )
                                    ->where($whereUser )
                                    ->whereBetween('created_at',[$date_start." 00:00:00",$date_end." 23:59:59"] )
                                    ->orderBy('created_at', 'DESC');

        $total_sent =  $query->sum('total_amount');
        $count  =  $query->count();
        $limit = $input['limit']??20;


        ////pagination
        if(!empty($input['cursor'])){
            $transaction_id = optional($query->select('id_transaction')->get())->toArray();
            $key =  Helper::find_key($transaction_id,'id_transaction',$input['cursor']);
            $page_start = $key ===false?0:$key+1;
        }

        $transactions  =  optional($query->select($select)->skip($page_start??0)->limit($limit)->get())->toArray();

            return  [['transaction_count'=>$count,'total_sent'=>number_format($total_sent, 2),'transaction'=>$transactions], 200];


    }


    //fetch date by today, yesterday, a month ago or between days
    public function dateFinder($input){

        $input_query = !empty($input['query']) ?(is_string($input['query'])?json_decode($input['query'],  true) : $input['query'])
                                                : [];

        $date_length = $input_query['date_length']??'';

        //convert string date in english to string in america standard

        $date_start = !empty($input_query['date_start'])?Carbon::createFromFormat('d/m/Y', $input_query['date_start'])      ->format('Y/m/d'):"1970/01/01";

        $date_end = !empty($input_query['date_end'])?Carbon::createFromFormat('d/m/Y', $input_query['date_end'])      ->format('Y/m/d'):Carbon::now()->format('Y/m/d');


        // $date_start =$input_query['date_start']??"1970/01/01";
        // $date_end= $input_query['date_end']??Carbon::now()->format('Y/m/d');


        if($date_length =='today'){
            $date_start = Carbon::today()->format('Y/m/d');
            $date_end = Carbon::today()->format('Y/m/d');}

        if($date_length =='yesterday'){
            $date_start = Carbon::today()->subDays(1)->format('Y/m/d');
            $date_end = Carbon::today()->subDays(1)->format('Y/m/d');}

        if($date_length =='a_month'){
            $date_start = Carbon::today()->subDays(31)->format('Y/m/d');
            $date_end = Carbon::today()->format('Y/m/d');}

            return [$date_start, $date_end];

    }

//add a new transaction
    public function storeTransaction($input){

        $user_id = $input['user']['id_user']??'';
       //var_dump($input);
        $calculate_res = $this->calculateAmountToSend(['conversion_type'=>1,'send_amount'=>$input['amount_sent'] ]);
       // var_dump( $calculate_res);
        $total_amount = (float) $calculate_res['total']??0;
        $local_amount = (float)$calculate_res['local']??0;
        $total_commission = (float)$calculate_res['commission']??0;
        $agent_commission = (float)$total_commission * $calculate_res['agent_quota']/100;
        $exchange_rate = (float)$calculate_res['rate']??0;
        $bou_rate = 0;
        $sold_rate = 0;
        $receiver = optional(receiver::where('id_receiver', $input['receiver_id'])
                    ->select('sender_id', 'receiver_fname', 'receiver_lname','receiver_address','receiver_fname','receiver_lname',
                    'receiver_phone','bank','account_number','transfer_type_key','transfer_type','currency_id', 'bank_id', 'identity_type_id', 'identity_type')->first())->toArray();

        $sender = optional(sender::where('id_sender', $receiver['sender_id'])->select('sender_fname','sender_lname','sender_address' )->first())->toArray();
        if(!empty($input)){
            $receiver_phone = $receiver['receiver_phone']??$receiver['receiver_mobile'];
//var_dump($receiver['currency_id']);
           $transaction =  transaction::create([

                'store_id'=>store_id(),
                'user_id' => $user_id,
                'currency_id' => $receiver['currency_id']??'',
                'sender_id'=> $receiver['sender_id']??'',
                'receiver_id' => $input['receiver_id'],
                'sender_fname' => $sender['sender_fname']??'',
                'sender_lname' => $sender['sender_lname']??'',
                'receiver_fname' => $receiver['receiver_fname'],
                'receiver_lname' => $receiver['receiver_lname'],
                'receiver_address' => $receiver['receiver_address'],
                'receiver_bank' => $receiver['bank'],
                'receiver_bank_id' => $receiver['bank_id'],
                'receiver_identity_id' => $receiver['identity_type_id'],
                'receiver_identity' => $receiver['identity_type'],
                'receiver_identity_type' => $receiver['identity_type'],
                'receiver_account_no' => $receiver['account_number'],
                'receiver_transfer_type' => $receiver['transfer_type'],
                'receiver_transfer_type_key' => $receiver['transfer_type_key'],
                'sender_address' => $sender['sender_address'],
                'agent_payment_id' => '',
                'receiver_phone' => $receiver_phone??'',
                'amount_sent' =>$input['amount_sent'],
                'total_amount' => $total_amount,
                'local_amount' => $local_amount,
                'total_commission'  => $total_commission,
                'agent_commission' => $agent_commission,
                'exchange_rate'=> $exchange_rate,
                'bou_rate' => $bou_rate,
                'sold_rate' => $sold_rate,
                'note' => '',
                'currency_income' => 1,                     //this needs work from calculate amount to send
                'transaction_status'=> 1 ,
                'transaction_type' => 1,
                'moderation_status' => 1

            ]);

            return [['transaction_id'=>$transaction->id_transaction], 200];
        }

        return [['error'=> ' something went wrong'], 422];


    }


    //update transaction

    public function updateTransaction($input, $transaction_id){

        $parts = preg_split('/\s+/', $input['receiver_name']);
        $receiver_fname = $parts[0]??'';
        $receiver_lname = $parts[1]??'';


        if(!empty($input) && transaction::where('id_transaction', $transaction_id)->exists() ){
            //var_dump($receiver['currency_id']);
                       $transaction =  transaction::where('id_transaction', $transaction_id)->update([

                            'receiver_fname' => $receiver_fname,
                            'receiver_lname' => $receiver_lname,
                            'receiver_bank' => $input['receiver_bank'],
                            'receiver_bank_id' => $input['receiver_bank_id'],
                            'receiver_identity_id' => $input['receiver_identity_id']??'',
                            'receiver_identity' => $input['receiver_identity']??'',
                            'receiver_account_no' => $input['receiver_account_no'],
                            'receiver_transfer_type' => $input['receiver_transfer_type'],
                            'receiver_transfer_type_key' => (int)$input['receiver_transfer_type_key'],
                            'receiver_phone' => $input['receiver_phone']??'',
                        ]);

                        return [['transaction_id'=>$transaction_id], 200];
                    }

                    return [["something went wrong"], 422];
    }



    public function showAmountBreakdown($input){

        $res = $this->calculateAmountToSend($input);

          return [
            'rate'=>$res['rate'],
            'local'=> number_format($res['local'],2),
            'commission'=>number_format($res['commission'],2),
            'total' =>number_format($res['total'],2),
            'send_amount'=>number_format($res['send_amount'],2),
            'conversion_type'=>$res['conversion_type'],
            'agent_quota' => $res['agent_quota']
        ];

    }



    //calculate local,commission,total from amount

    //converter type --> actual total =  1  or local  = 2
    public  function calculateAmountToSend($input){

        $send_amount = $input['send_amount']??0;
        $conversion_type = $input['conversion_type']??1;
        //var_dump($conversion_type);
        $todays_rate = RateService::todays_rate();
        $rate = $todays_rate['main_rate']??0;

        //evaluate based on convert type
        $send_amount = $conversion_type ==1 ? $send_amount: $send_amount / $rate;

        $res_commission = CommissionService::fetchCommissionValue($send_amount);
        $comission_value = $res_commission['value'] < 1?$res_commission['value'] * $send_amount : $res_commission['value'];
        $agent_quota = $res_commission['agent_quota'];
        //var_dump('commission',$res_commission);

        $commission = $comission_value??0;
        $total =  $send_amount + $commission;
        $local = $rate * $send_amount;


        return [
            'rate'=>$rate,
            'local'=> $local,
            'commission'=>$commission,
            'total' => $total,
            'send_amount'=>$send_amount,
            'conversion_type'=>$conversion_type,
            'agent_quota' => $agent_quota
        ];
    }


}

