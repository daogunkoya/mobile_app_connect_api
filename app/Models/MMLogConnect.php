<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class MMLogConnect extends Model
{
    //
    protected $table = "mm_log_connect";
    protected $primaryKey = 'id_log';

    protected $fillable = [
        'user_id',
        'store_id',
        'store_name',
        'request_type',
        'request_message',
        'response_message',
        'content',
        'count_last_request',
        'request_destination',
        'request_origin',
        'response_code',
        'request_method',
       
        
    ];

    protected $keyType = 'string';

    public $incrementing = false;

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->id_log = (string) Uuid::uuid4();
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
