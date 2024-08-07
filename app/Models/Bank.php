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

class Bank extends Model
{
    use HasFactory;

    protected $table = "mm_bank";
    protected $primaryKey = 'id';

    protected $fillable = [
        'store_id',
        'name',
        //'transfer_type',
//        'transfer_type_key',
//        'bank_proof_identity',
//        'bank_category'


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
            $model->id = (string)Uuid::uuid4();
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

    public function scopeFilter($query, array $filters){

        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', '%'.$search.'%');
            });
        });
    }

    public function getUserAttribute($user_id)
    {
        return optional(User::where('id_user', $user_id)->select('id_user as user_id', 'user_name', 'user_handle', 'user_email', 'created_at')->first())->toArray();
    }

    public function getMainRateAttribute($value)
    {
        return number_format($value, 2);
    }


    public function getCurrencyAttribute($currency_id)
    {

        return optional(Currency::where('id_currency', $currency_id)->select('id_currency as currency_id', 'currency_code')->first())->toArray();
    }

    public function getCreatedAtAttribute($value)
    {
        return \Carbon\Carbon::createFromTimeStamp(strtotime($value))->format('d/m/Y');
    }

    public function receiver($value)
    {
        return $this->belongsTo(Receiver::class, 'bank_id');
    }
}
