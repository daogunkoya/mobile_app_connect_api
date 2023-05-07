<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;
use DateTimeInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;


class Sender extends Model
{

            protected $table = "mm_sender";
            protected $primaryKey = 'id_sender';

            protected $fillable = [
            'store_id',
            'user_id',
            'sender_title',
            'sender_name',
            'sender_slug',
            'sender_fname',
            'sender_mname',
            'sender_lname',
            'sender_dob',
            'currency_id',
            'sender_email',
            'sender_phone',
            'sender_mobile',
            'sender_address',
            'sender_postcode' ,
            'photo_id',
            'sender_status',
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
                    $model->id_sender = (string) Uuid::uuid4();



                      // generate uniqu slug
                      $slug = Str::slug($model->sender_name . mt_rand(100,1000));
                      while(Sender::where('sender_slug',$slug)->exists() )  $slug = Str::slug($model->sender_name . mt_rand(100,1000));
                      $model->sender_slug =$slug;
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

            public function getCountSenderReceiverAttribute($sender_id){
                return receiver::where('sender_id',$sender_id)->count();
            }



}

