<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Webpatser\Uuid\Uuid;
use DateTimeInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;


class mm_rate extends Model
{
    
            protected $table = "mm_rate";
            protected $primaryKey = 'id_rate';

            protected $fillable = [
            'store_id',
            'main_rate',
            'user_id',
            'currency_id',
            'bou_rate',
            'sold_rate' ,
            'moderation_status' ,
            'rate_status' ,
           
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
                    $model->id_rate = (string) Uuid::generate(4);

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

            public function getUserAttribute($user_id){
                return optional(mm_user::where('id_user', $user_id)->select('id_user as user_id', 'user_name', 'user_handle', 'user_email', 'created_at')->first())->toArray();
            }

            public function getMainRateAttribute($value){
                return  number_format($value, 2);
            }


            public function getCurrencyAttribute($currency_id){
                
                return optional(mm_currency::where('id_currency', $currency_id)->select('id_currency as currency_id', 'currency_code')->first())->toArray();
            }

            public function getCreatedAtAttribute($value){
                return \Carbon\Carbon::createFromTimeStamp(strtotime($value))->format('d/m/Y');
            }

            

}

