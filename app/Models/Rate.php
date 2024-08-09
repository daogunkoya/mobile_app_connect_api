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
class Rate extends Model
{
    use HasFactory;

    protected $table = "mm_rate";
    protected $primaryKey = 'id_rate';

    protected $fillable = [
        'store_id',
        'main_rate',
        'user_id',
        'member_user_id',
        'currency_id',
        'bou_rate',
        'sold_rate',
        'moderation_status',
        'rate_status',

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
            $model->id_rate = (string)Uuid::uuid4();
        });
    }

    public function scopeFilter(Builder $query, BaseQuery $filter): void
    {

         $filter->apply($query);

        // $query
        // ->when($filter['search'] ?? false, function ($query, $search) use($filter) {
        //     $search = trim($filter['search']);
        //     $query->where('sender_fname', 'like', '%' . $search . '%')
        //         ->orWhere('sender_lname', 'like', '%' . $search . '%')
        //        ;
        // })
        // ->when($filter['userId'] ?? false, fn($query) => $query->where('user_id', $filter['userId']))
        // ->when($filter['currencyId'] ?? false, fn($query) => $query->where('currency_id', $filter['currencyId']));
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

    public function getMainRateAttribute($value)
    {
        return number_format($value, 2);
    }


    // public function getCurrencyAttribute($currency_id)
    // {

    //     return optional(Currency::where('id_currency', $currency_id)->select('id_currency as currency_id', 'currency_code')->first())->toArray();
    // }

    public function getCreatedAtAttribute($value)
    {
        return \Carbon\Carbon::createFromTimeStamp(strtotime($value))->format('d/m/Y');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_id')
        ->select('id_currency', 'currency_country', 'currency_symbol', 'currency_type','default_currency', 'currency_title');;
    }

    public function scopeForUserAndCurrency($query, $userId = null, $currencyId = null)
    {
        return $query
            ->when(!is_null($currencyId)  &&  self::where('currency_id', $currencyId)->exists(), function ($query) use ($currencyId) {
                return $query->whereIn('currency_id', [$currencyId]);
            })
            ->when(!is_null($userId) &&  self::where('user_id', $userId)->exists(), function ($query) use ($userId) {
                return $query->whereIn('user_id', [$userId]);
            });
    }
}
