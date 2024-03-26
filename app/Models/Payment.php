<?php

namespace App\Models;

use App\Enum\PaymentProvider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{

    protected $table ="mm_payment";
    protected $guarded =[];
    protected $casts = [
        'payment_gateway' => PaymentProvider::class
    ];

    public function user():belongsTo
    {
        return $this->belongsTo(User::class);
    }

}
