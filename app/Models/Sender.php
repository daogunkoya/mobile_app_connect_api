<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;
use DateTimeInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sender extends Model
{
    use HasFactory;

    protected $table = "mm_sender";
    protected $primaryKey = 'id_sender';
    protected $appends = ['count_sender_receivers', 'sender_name'];

    protected $fillable = [
        'store_id',
        'user_id',
        'sender_title',
        //  'sender_name',
        'sender_slug',
        'sender_fname',
        'sender_mname',
        'sender_lname',
        'sender_dob',
        'currency_id',
        'sender_email',
        'sender_phone',
        'sender_mobile',
        'sender_address',
        'sender_postcode',
        'photo_id',
        'sender_status',
        'moderation_status'

    ];


    protected $keyType = 'string';

    public $incrementing = false;

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->id_sender = (string)Uuid::uuid4();
            $model->store_id = request()->process_store_id ?? '2bda0c37-4eac-44e5-a014-6c029d76dc62';


            // generate uniqu slug
            $slug = Str::slug($model->sender_name . mt_rand(100, 1000));
            while (Sender::where('sender_slug', $slug)->exists()) {
                $slug = Str::slug($model->sender_name . mt_rand(100, 1000));
            }
            $model->sender_slug = $slug;
        });
    }

    public function scopeFilter(Builder $query, array $filter): void
    {

        $defaultSelect = [
            'id_sender as sender_id',
            'user_id',
            'sender_title',
            'created_at',
            //  'sender_name',
            'sender_mname',
            'sender_fname',
            'sender_lname',
            'sender_dob',
            'sender_email',
            'sender_phone',
            'sender_mobile',
            'sender_address',
            'sender_postcode'
        ];

        $autoCompleteSelect = [
            'id_sender as sender_id',
            'sender_name',
            'sender_phone'];

        $query->select($defaultSelect)
            ->when($filter['search'] ?? false, fn($query, $search) => $query->where('sender_fname', 'like', '%' . $search . '%')
                ->orWhere('sender_lname', 'like', '%' . $search . '%')
            )
            ->when($filter['all'] ?? false, fn() => $query->select($autoCompleteSelect)
            );

    }

//    public function scopeAll(Builder $query): void
//    {
//
//        $query->when(request('fetchall') ?? false, fn() => $query->select('id_sender as sender_id',
//            'sender_name',
//            'sender_phone')
//        );
//
//    }

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

    public function getCreatedAtAttribute($value)
    {
        return \Carbon\Carbon::createFromTimeStamp(strtotime($value))->format('d/m/Y');
    }

    public function getSenderNameAttribute()
    {
        return "{$this->sender_fname} {$this->sender_lname}";
    }

    public function getCountSenderReceiversAttribute()
    {
        // var_dump($this->receiver());
        return $this->receiver()->where('receiver_status', 1)->count();
    }


    public function receiver()
    {
        return $this->hasMany(Receiver::class, 'sender_id', 'sender_id');
    }

    public function address()
    {
        return $this->hasOne(Address::class, 'id', 'address_id');
    }

    public function getTransferTypeAttribute()
    {
        return [

        ];
    }
}
