<?php

namespace App\Models;

use App\Enum\Bank\TransferType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;
use DateTimeInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;

class Receiver extends Model
{
    protected $table = "mm_receiver";
    protected $primaryKey = 'id_receiver';

    protected $fillable = [
        'store_id',
        'user_id',
        'user_type',
        'sender_id',
        'receiver_title',
        'receiver_name',
        'receiver_slug',
        'receiver_fname',
        'receiver_mname',
        'receiver_lname',
        'receiver_dob',
        'currency_id',
        'receiver_email',
        'receiver_phone',
        'receiver_address',
        'receiver_postcode',
        'account_number',
        'bank_id',
        'identity_type_id',
        'transfer_type',
        'transfer_type_key',
//        'identity_type',
//        'bank',
        'receiver_status',
        'moderation_status'

    ];

    protected $appends = ['bank','identity_type'];
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
            $model->id_receiver = (string)Uuid::uuid4();


            // generate uniqu slug
            $slug = $slug = Str::slug($model->receiver_name . mt_rand(100, 1000));
            while (Receiver::where('receiver_slug', $slug)->exists()) {
                $slug = Str::slug($model->receiver_name . mt_rand(100, 1000));
            }
            $model->receiver_slug = $slug;
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


    public function getReceiverNameAttribute($value)
    {

        return $this->receiver_fname . ' ' . $this->receiver_lname;
    }

    public function sender()
    {
        return $this->belongsTo(Sender::class);
    }

    public function banks()
    {
        return $this->hasOne(Bank::class, 'id_bank','bank_id');


    }

    public function getIdentityTypeAttribute($value)
    {
        if(!$this->banks()->exists()) return ['key'=>"", 'value'=>""];
        return $this->banks()->select('id_bank as key', 'name as value')->first()->toArray();
    }


    public function getBankAttribute($value)
    {
        if(!$this->banks()->exists()) return ['key'=>"", 'value'=>""];
        return $this->banks()->select('id_bank as key', 'name as value')->first()->toArray();
    }

    public function getTransferTypeAttribute($value): ?TransferType

    {
       // return TransferType::Bank;
        return TransferType::tryFrom($value) ?? TransferType::None;
    }
}
