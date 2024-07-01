<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;
use DateTimeInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;

class Address extends Model
{
    protected $table = "mm_address";
    protected $primaryKey = 'id';

    protected $fillable = [
        'store_id',
        'address1',
        'address2',
        'address3',
        'city',
        'post_code',
        'country',
        'user_id',


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


    public function getCreatedAtAttribute($value)
    {
        return \Carbon\Carbon::createFromTimeStamp(strtotime($value))->format('d/m/Y');
    }

    public function sender()
    {
        return $this->hasOne(Sender::class, 'address_id');
    }
}
