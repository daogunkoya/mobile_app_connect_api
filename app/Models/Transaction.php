<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;
use DateTimeInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;

class Transaction extends Model
{
            protected $table = "mm_transaction";
            protected $primaryKey = 'id_transaction';

            protected $fillable = [
            'id_transaction',
            'store_id',
            'user_id',
            'currency_id',
            'sender_id',
            'receiver_id',
            'sender_fname',
            'sender_lname',
            'receiver_fname',
            'receiver_lname',
            'agent_payment_id',
            'receiver_phone',
            'amount_sent',
            'total_amount',
            'local_amount',
            'total_commission',
            'agent_commission',
            'exchange_rate',
            'bou_rate',
            'sold_rate' ,
            'note',
            'currency_income',
            'transaction_status',
            'transaction_type',
            'sender_address',
            'receiver_address',
            'moderation_status' ,
            'receiver_bank',
            'receiver_bank_id',
            'receiver_identity',
            'receiver_identity_id',
            'receiver_transfer_type',
            'receiver_transfer_type_key',
            'receiver_account_no',


            ];


                protected $keyType = 'string';

                public $incrementing = false;

            //date serialization undo
            protected function serializeDate(DateTimeInterface $date)
            {
                return $date->format('Y-m-d H:i:s');
            }


            public static function boot()
            {
                parent::boot();
                self::creating(function ($model) {
                    $model->id_transaction = (string) Uuid::uuid4();
                });
            }


            public function getRouteKeyName()
            {
                return 'uuid';
            }


            public function getKeyType()
            {
                return 'string';
            }

            public function getCreatedAtAttribute($value)
            {
                return \Carbon\Carbon::createFromTimeStamp(strtotime($value))->format('d/m/Y');
            }

            public function getCountSenderReceiverAttribute($customer_id)
            {
                return receiver::where('customer_id', $customer_id)->count();
            }



            public function getUserAttribute($user_id)
            {
                return optional(mm_user::where('id_user', $user_id)->select('id_user as user_id', 'user_name', 'user_handle', 'user_email', 'created_at')->first())->toArray();
            }




            public function getCurrencyAttribute($currency_id)
            {

                return optional(Currency::where('id_currency', $currency_id)->select('id_currency as currency_id', 'currency_code')->first())->toArray();
            }

            public function getAmountSentAttribute($value)
            {

                return  number_format($value, 2);
            }


            public function getLocalAmountAttribute($value)
            {

                return  number_format($value, 2);
            }

            public function getTotalAmountAttribute($value)
            {

                return  number_format($value, 2);
            }

            public function getExchangeRateAttribute($value)
            {

                return  number_format($value, 2);
            }
            public function getTotalCommissionAttribute($value)
            {

                return  number_format($value, 2);
            }
            public function getSenderNameAttribute($transaction_id)
            {
                $fname = Transaction::where('id_transaction', $transaction_id)->value('sender_fname');
                $lname = Transaction::where('id_transaction', $transaction_id)->value('sender_lname');
                return $fname . ' ' . $lname;
            }

            public function getReceiverNameAttribute($transaction_id)
            {

                $fname = Transaction::where('id_transaction', $transaction_id)->value('receiver_fname');
                $lname = Transaction::where('id_transaction', $transaction_id)->value('receiver_lname');
                return $fname . ' ' . $lname;
            }

            // public function getReceiverTransferTypeAttribute($value){

            //    return $value == 1? 'Bank':"Pickup";
            // }

            // public function setReceiverTransferTypeAttribute($value){

            //    return $value == 'Bank'?1:2;
            // }

            public function setReceiverIdentityTypeIdAttribute($name_value)
            {

                return Bank::where('name_bank', $name_value)->value('id_bank');
            }
}
