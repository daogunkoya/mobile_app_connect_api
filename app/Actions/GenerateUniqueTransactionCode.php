<?php

namespace App\Actions;

use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class GenerateUniqueTransactionCode
{
 public function __invoke()
    {
        $maxLength = 9;
        $transactionCode = '';
        do {
            $transactionCode = Str::random($maxLength) . Auth::id();
        } while (Transaction::where('transaction_code', $transactionCode)->exists());

        // Trim the transaction code to ensure it doesn't exceed the maximum length
        $transactionCode = substr($transactionCode, 0, $maxLength);

        return $transactionCode;

    }

}
