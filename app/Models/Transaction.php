<?php

namespace App\Models;

use App\Enum\Bank\TransferType;
use App\Enum\TransactionStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;
use DateTimeInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;
use App\Filters\BaseQuery;

class Transaction extends Model
{
    use HasFactory;

    protected $table = "mm_transaction";
    protected $primaryKey = 'id_transaction';
    protected $casts
    = [
        'transaction_status' => TransactionStatus::class
    ];

    // protected $appends = [ 'receiver_name'];

    protected $fillable = [
        'id_transaction',
        'transaction_code',
        'store_id',
        'user_id',
        'origin_currency_id',
        'destination_currency_id',
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
        'sold_rate',
        'note',
        'currency_income',
        'transaction_status',
        'transaction_type',
        'sender_address',
        'receiver_address',
        'moderation_status',
        'receiver_bank_id',
        'receiver_identity_id',
        'receiver_transfer_type',
        'receiver_account_no',


    ];


    protected $keyType = 'string';

    public $incrementing = false;

    public function scopeFilter(Builder $query, BaseQuery $filter): void
    {
         $filter->apply($query);
    }

    // public function scopeFilter(Builder $query, array $filter): void
    // {
    //     $query
    //         ->when($filter['search'] ?? false, function ($query, $search) use($filter) {
    //             $search = trim($filter['search']);
    //             $query->where('sender_fname', 'like', '%' . $search . '%')
    //                 ->orWhere('sender_lname', 'like', '%' . $search . '%')
    //                 ->orWhere('receiver_fname', 'like', '%' . $search . '%')
    //                 ->orWhere('receiver_lname', 'like', '%' . $search . '%');
    //         })
    //         ->when($filter['senderId'] ?? false, fn($query) => $query->where('sender_id', $filter['senderId']))
    //         ->when($filter['search'] ?? false, fn($query, $search) => $query->where('sender_fname', 'like', '%' . $search . '%')
    //                 ->orWhere('sender_lname', 'like', '%' . $search . '%')
    //                 ->orWhere('receiver_lname', 'like', '%' . $search . '%')
    //                 ->orWhere('sender_fname', 'like', '%' . $search . '%')
    //     )
    //         ->when($filter['status'] ?? false, fn($query) => $query->where('transaction_status', TransactionStatus::getStatusEnumInstance($filter['status'])))
    //         ->when(($filter['userId']) ?? false, fn ($query) => $query->where('user_id', $filter['userId']))    
    //         ->when($filter['date'] ?? false, function ($query,$search)use($filter) {
    //             $searchDate = $filter['date'];
    //             $query->when($searchDate, function ($query, $date) {
    //                 return $query->where(fn ($query) => match ($date) {
    //                     'today' => $query->whereDate('created_at', today()),
    //                     'yesterday' => $query->whereDate('created_at', today()->subDay()),
    //                     'week' => $query->whereBetween('created_at', [now()->subDays(7)->startOfDay(), now()->endOfDay()]),
    //                     'month' => $query->whereBetween('created_at', [now()->subDays(30)->startOfDay(), now()->endOfDay()]),
    //                     default => $query->whereBetween('created_at', [
    //                         Carbon::createFromFormat('d/m/Y', $date['from'])->startOfDay(),
    //                         Carbon::createFromFormat('d/m/Y', $date['to'])->endOfDay(),
    //                     ]),
    //                 });
    //             });
    //         });
    // }

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->id_transaction = (string)Uuid::uuid4();
            $model->store_id = request()->process_store_id ?? '2bda0c37-4eac-44e5-a014-6c029d76dc62';
        });
    }

    //date serialization undo
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }


    public function getRouteKeyName()
    {
        return 'uuid';
    }


    public function getKeyType()
    {
        return 'string';
    }

    public function bank()
    {
        return $this->hasOne(Bank::class, 'id', 'receiver_bank_id');
    }

    public function identity()
    {
        return $this->hasOne(AcceptableIdentity::class, 'id', 'identity_type_id');
    }

    public function receiver():BelongsTo
    {
        return $this->belongsTo(Receiver::class,'receiver_id');
    }
    
    public function sender():BelongsTo
    {
        return $this->belongsTo(Sender::class,'sender_id');
    }


    public function statusChangeLogs()
    {
        return $this->morphMany(StatusChangeLog::class, 'loggable');
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

        return number_format($value, 2);
    }

    public function getTransactionTypeAttribute($value): string
    {
        return $value == 1 ? 'Agent' : 'Customer';
    }


    public function getLocalAmountAttribute($value)
    {

        return number_format($value, 2);
    }

    public function getTotalAmountAttribute($value)
    {

        return number_format($value, 2);
    }

    public function getExchangeRateAttribute($value)
    {

        return number_format($value, 2);
    }

    public function getTotalCommissionAttribute($value)
    {

        return number_format($value, 2);
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


    public function setReceiverIdentityTypeIdAttribute($name_value)
    {

        return Bank::where('name_bank', $name_value)->value('id_bank');
    }


    public function getIdentityTypeAttribute($value)
    {
        if (!$this->identity()->exists()) return ['key' => "", 'value' => ""];
        return $this->identity()->select('id as key', 'name as value')->first()->toArray();
    }


    // public function getBankAttribute($value)
    // {
    //     if (!$this->banks()->exists()) return ['key' => "", 'value' => ""];
    //     return $this->banks()->select('id as key', 'name as value')->first()->toArray();
    // }

    public function getTransferTypeAttribute($value): ?TransferType

    {
        // return TransferType::Bank;
        return TransferType::tryFrom($value) ?? TransferType::None;
    }

    public function payment()
    {
        return $this->morphOne(Payment::class, 'paymentable');
    }
}
