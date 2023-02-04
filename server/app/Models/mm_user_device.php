<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class mm_user_device extends Model
{
    //
    protected $table = "mm_user_device";
    protected $primaryKey = 'id_device';

    protected $fillable = [
        'user_id',
        'store_id',
        'device_push_type',
        'device_type',
        'device_code',
        'device_name',
        'device_location',
        'device_ip',
        'device_access_token',
        'device_push_token',
        'device_last_active',
        'device_status',
        'record_count_update',
        'record_count_process',
        'record_note',
        'temp_daniel_expiration_time',
        'user_access_id'
    ];

    protected $keyType = 'string';

    public $incrementing = false;

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->id_device = (string) Uuid::generate(4);
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


}
