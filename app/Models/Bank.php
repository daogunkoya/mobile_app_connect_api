<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;
use DateTimeInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use App\Filters\BaseQuery;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bank extends Model
{
    use HasFactory;

    protected $table = "mm_bank";
    protected $primaryKey = 'id';

    protected $fillable = [
        'store_id',
        'name',
        'bank_code',
        'currency_id',
        'bank_category',
        'transfer_type',
        'bank_status',
        'moderation_status',
        'transfer_type_key',
        'bank_proof_identity',
];

    protected $guarded = [];


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
            $model->id = (string)Uuid::uuid4();
        });
    }
    public function scopeFilter(Builder $query, BaseQuery $filter): void
    {

         $filter->apply($query);
    }

    public function getRouteKeyName()
    {
        return 'id';
    }


    public function getKeyType()
    {
        return 'string';
    }

    // public function scopeFilter($query, array $filters){

    //     $query->when($filters['search'] ?? null, function ($query, $search) {
    //         $query->where(function ($query) use ($search) {
    //             $query->where('name', 'like', '%'.$search.'%');
    //         });
    //     });
    // }

    public function getUserAttribute($user_id)
    {
        return optional(User::where('id_user', $user_id)->select('id_user as user_id', 'user_name', 'user_handle', 'user_email', 'created_at')->first())->toArray();
    }

    public function getMainRateAttribute($value)
    {
        return number_format($value, 2);
    }


  

    public function getCreatedAtAttribute($value)
    {
        return \Carbon\Carbon::createFromTimeStamp(strtotime($value))->format('d/m/Y');
    }

    public function receiver($value)
    {
        return $this->belongsTo(Receiver::class, 'bank_id');
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_id')
        ->select('id_currency', 'currency_country', 'currency_symbol', 'currency_type','default_currency', 'currency_title', 'currency_status');;
    }
}
