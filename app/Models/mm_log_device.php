<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class mm_log_device extends Model
{
    //
    protected $table = "mm_log_device";
    protected $primaryKey = 'id_device';

    protected $fillable = [
        'device_type',
        'device_name',
        'device_status',
        'user_id',
    ];

    protected $keyType = 'string';

    public $incrementing = false;

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->id_device = (string) Uuid::uuid4();
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
