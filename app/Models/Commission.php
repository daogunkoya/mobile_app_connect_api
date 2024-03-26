<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;
use DateTimeInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;

class Commission extends Model
{
    use HasFactory;

    protected $table = "mm_commission";
    protected $primaryKey = 'id_commission';

    protected $fillable = [
        'store_id',
        'user_id',
        'start_from',
        'end_at',
        'value',
        'agent_quota',
        'user_id',
        'currency_id',
        'moderation_status',
        'commission_status'
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
            $model->id_commission = (string)Uuid::uuid4();
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

    public function getUserAttribute($user_id)
    {
        return optional(User::where('id_user', $user_id)->select('id_user as user_id', 'user_name', 'user_handle', 'email as user_email', 'created_at')->first())->toArray();
    }


    public function getCurrencyAttribute($currency_id)
    {

        return optional(Currency::where('id_currency', $currency_id)->select('id_currency as currency_id', 'currency_code')->first())->toArray();
    }

    // public function getCreatedAtAttribute($value){
    //     return \Carbon\Carbon::createFromTimeStamp(strtotime($value))->format('d/m/Y');
    // }

    // public function getCreatedAtAttribute($value){
    //     return \Carbon\Carbon::createFromTimeStamp(strtotime($value))->format('d/m/Y');
    // }

    public function scopeForUserAndCurrency($query, $userId = null, $currencyId = null)
    {
        return $query
            ->when(!is_null($currencyId) && self::where('currency_id')->exists(), function ($query) use ($currencyId) {
                return $query->whereIn('currency_id', [$currencyId]);
            })
            ->when(!is_null($userId) && self::where('user_id')->exists(), function ($query) use ($userId) {
                return $query->whereIn('user_id', [$userId]);
            });
    }
}
