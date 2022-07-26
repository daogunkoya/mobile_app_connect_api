<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Webpatser\Uuid\Uuid;
use DateTimeInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;


class mm_currency extends Model
{
    
            protected $table = "mm_currency";
            protected $primaryKey = 'id_currency';

            protected $fillable = [
            'store_id',
            'user_id',
            'currency_origin',
            'currency_origin_symbol',
            'currency_destination',
            'currency_destination_symbol',
            'currency_code',
            'income_category',
            'currency_status',
            'moderation_status' 
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
                    $model->id_currency = (string) Uuid::generate(4);

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

            // public function getCreatedAtAttribute($value){
            //     return \Carbon\Carbon::createFromTimeStamp(strtotime($value))->format('d/m/Y');
            // }

            // public function getCreatedAtAttribute($value){
            //     return \Carbon\Carbon::createFromTimeStamp(strtotime($value))->format('d/m/Y');
            // }

            

}

