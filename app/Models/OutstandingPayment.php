<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;
use DateTimeInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use App\Filters\BaseQuery;
class OutstandingPayment extends Model
{
    use HasFactory;

    protected $table = "mm_outstanding_payment";
    protected $primaryKey = 'id_outstanding';

    protected $fillable = [
        'store_id',
        'user_id',
        'sender_name',
        'receiver_name',
        'transaction_id',
        'currency_id',
        'total_amount',
        'amount_sent',
        'local_amount',
        'total_commission',
        'agent_commission',
        'exchange_rate',
        'bou_rate',
        'sold_rate',
        'transaction_code'

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
            $model->id_outstanding = (string)Uuid::uuid4();
        });
    }

    public function scopeFilter(Builder $query, BaseQuery $filter): void
    {

    $filter->apply($query);
        
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_id')
        ->select('id_currency', 'currency_country', 'currency_symbol', 'currency_type','default_currency', 'currency_title','currency_status');;
    }

    public function payment()
    {
        return $this->morphOne(Payment::class, 'paymentable');
    }

 
}
