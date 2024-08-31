<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;
use DateTimeInterface;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Store extends Model
{
    use HasFactory;

            protected $table = "mm_store";
            protected $primaryKey = 'id_store';


            protected $fillable = [
            'user_id',
            'store_slug',
          

            'moderation_status',
            'store_status',
            'store_name' ,
            'store_slogan',
            'store_url',
        
            'store_business_name',
            
            'social_facebook',
            'social_twitter',
            'social_linkedin',
            'social_google',
            'social_instagram',

            'payment_status',
            'payment_url',
            'store_address',
            'store_postcode',
            'store_city',
            'store_country',

            'store_phone',
            'store_email',
            'enable_credit',
            'enable_multiple_receipt',
            'enable_sms',

         

//            'stripe_count_onboard_refresh',
//            'stripe_count_onboard_return',
//            'stripe_account_id',
//            'stripe_onboard_complete'




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
                    $model->id_store = (string) Uuid::uuid4();

                    // Generate unique slug
                    $slug = Str::slug($model->store_name) . mt_rand(100, 1000);
                    while (Store::where('store_slug', $slug)->exists()) {
                        $slug = Str::slug($model->store_name) . mt_rand(100, 1000);
                    }
                    $model->store_slug = $slug;
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


            public function getStoreUserDobAttribute($value)
            {
                return Carbon::createFromTimeStamp(strtotime($value))->format('d/m/Y');
            }

            // public function getCreatedAtAttribute($value){
            //     return \Carbon\Carbon::createFromTimeStamp(strtotime($value))->format('d/m/Y');
            // }
}
