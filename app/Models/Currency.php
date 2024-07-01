<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;
use DateTimeInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;
use App\Scopes\StoreScope; // Import the scope
use App\Enum\CurrencyType;


class Currency extends Model
{

    use hasFactory;
            protected $table = "mm_currency";
            protected $primaryKey = 'id_currency';

            protected $fillable = [
            'store_id',
            'user_id',
            // 'currency_origin',
            // 'currency_origin_symbol',
            // 'currency_destination',
            // 'currency_destination_symbol',
            // 'currency_code',
            "currency_type",
            'currency_country',
            "currency_symbol",
            'default_currency',
            'income_category',
            'currency_status',
            'moderation_status'
            ];


                protected $keyType = 'string';

                public $incrementing = false;

                protected $casts = [
                    'currency_type' => CurrencyType::class,
                ];

            //date serialization undo
            protected function serializeDate(DateTimeInterface $date)
            {
                return $date->format('Y-m-d H:i:s');
            }


            public static function boot()
            {
                parent::boot();
                // Apply the global scope
                static::addGlobalScope(new StoreScope);

                self::creating(function ($model) {
                    $model->id_currency = (string) Uuid::uuid4();
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
