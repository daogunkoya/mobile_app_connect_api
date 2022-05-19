<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Webpatser\Uuid\Uuid;
use DateTimeInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;


class mm_agent_customer extends Model
{
    
            protected $table = "mm_agent_customer";
            protected $primaryKey = 'id_customer';

            protected $fillable = [
            'store_id',
            'user_id',
            'customer_title',
            'customer_name',
            'customer_slug',
            'customer_fname',
            'customer_mname',
            'customer_lname',
            'customer_dob',
            'currency_id', 
            'customer_email',
            'customer_phone',
            'customer_mobile',
            'customer_address',
            'customer_postcode' ,
            'photo_id'    
            
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
                    $model->id_customer = (string) Uuid::generate(4);



                      // generate uniqu slug
                      $slug = str_slug($model->customer_name).mt_rand(100,1000);
                      while(mm_agent_customer::where('customer_slug',$slug)->exists() ) $slug = str_slug($model->customer_name).mt_rand(100,1000);
                      $model->customer_slug =$slug;
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

            public function getCreatedAtAttribute($value){
                return \Carbon\Carbon::createFromTimeStamp(strtotime($value))->format('d/m/Y');
            }

            // public function getCreatedAtAttribute($value){
            //     return \Carbon\Carbon::createFromTimeStamp(strtotime($value))->format('d/m/Y');
            // }

            

}

