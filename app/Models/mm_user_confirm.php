<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class mm_user_confirm extends Model
{
    //
    protected $table = 'mm_user_confirm';
    protected $primaryKey = 'id_confirm';

    protected $fillable = [
        'user_id',
        'store_id',
        'user_email',
        'confirm_type',
        'confirm_code',
        'confirm_token',
        'confirm_status',
        'record_count_update',
        'record_count_process',
        'record_note'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $keyType = 'string';

    public $incrementing = false;

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->id_confirm = (string) Uuid::uuid4();
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
