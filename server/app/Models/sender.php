<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class sender extends Model
{
    use HasFactory;

    protected $table = "laravel_agent_customers";
    protected $primaryKey = 'id_customer';


    protected $fillable = ['user_id','fname','lname',
                           'mname','name','email','mobile','phone',
                           'dob','address','postcode','title','currency_id',
                           'address_id','photo_id'

    ];



     //date serialization undo
     protected function serializeDate(DateTimeInterface $date)
     {
         return $date->format('Y-m-d H:i:s');
     }
 
     public static function boot()
     {
         parent::boot();
         self::creating(function ($model) {
             $model->id_user = (string) Uuid::generate(4);
             $model->user_status = 1;
             $model->user_email_status = 1;
         });
     }
}
