<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
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
            'list_image',
            'store_user_phone',

            'store_admin_type',
            'moderation_status',
            'store_status',

            'store_user_email' ,
            'store_name' ,
            'store_user_password' ,

            'store_group_revenue_id',
            'store_group_industry_id',

            'store_business_name',
            'store_business_type_id',
            'store_business_vat' ,
            'store_business_crn' ,

            'store_user_first_name',
            'store_user_last_name',
            'store_user_address',
            'store_user_postcode',
            'store_user_city',
            'store_user_dob',

            'social_facebook',
            'social_twitter',
            'social_linkedin',
            'social_google',
            'social_instagram',

            'payment_status',
            'payment_url',

            'stripe_count_onboard_refresh',
            'stripe_count_onboard_return',
            'stripe_account_id',
            'stripe_onboard_complete'




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

                    // generate uniqu slug
                    $slug = str_slug($model->store_name) . mt_rand(100, 1000);
                    while (Store::where('store_slug', $slug)->exists()) {
                        $slug = str_slug($model->store_name) . mt_rand(100, 1000);
                    }
                    $model->store_slug = $slug;

                    //$model->record_count_update = $model->count() + 1;
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
