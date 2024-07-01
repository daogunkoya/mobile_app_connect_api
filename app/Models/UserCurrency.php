<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;
use App\Traits\HasUuid;

class UserCurrency extends Model
{
    use HasFactory, HasUuid;
    protected $table = 'mm_user_currencies';

    protected $fillable = [
        'user_id',
         'sender_id', 
         'type', 
         'origin_currency_id', 
         'destination_currency_id', 
         'last_used_at'];

        //  public static function boot()
        //  {
        //      parent::boot();
        //      self::creating(function ($model) {
        //          $model->id = (string)Uuid::uuid4();  
        //      });
        //  }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }


    public function originCurrency()
    {
        return $this->belongsTo(Currency::class, 'origin_currency_id');
    }

    public function destinationCurrency()
    {
        return $this->belongsTo(Currency::class, 'destination_currency_id');
    }
}
