<?php

namespace App\Models;

use App\Enum\PaymentProvider;
use App\Enum\PaymentType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{

    protected $table ="mm_payment";
    protected $guarded =[];
    protected $casts = [
        'payment_gateway' => PaymentProvider::class,
    //    'payment_type' => PaymentType::class
    ];

    public function user():belongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function paymentable()
    {
        return $this->morphTo();
    }

}
