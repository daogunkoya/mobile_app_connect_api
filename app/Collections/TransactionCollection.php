<?php

namespace App\Collections;

use App\DTO\CommissionDto;
use App\DTO\RateDto;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class TransactionCollection
{
    public function __construct(
        public string $originCurrencyId,
        public string $destinationCurrencyId,
        public float $amountSent,
        public float $beneficiaryReceive,
        public float $exchangeRate,
        public float $totalAmount,
        public float $totalCommission,
        public float $agentCommission,
        public float $localAmount,
        public ?float $bouRate,
        public ?float $soldRate,
        public string $transactionCode
    ) {
    }

    public static function processTransactionData(
        RateDto $userRate,
        CommissionDto $userCommission,
        string $originCurrencyId,
        string $destinationCurrencyId,
        float $amountSent,
        int $conversionType
    ): self {

        $localSendingAmount = $conversionType == 2 ? $amountSent : $amountSent * $userRate->mainRate;
        $sendingAmount = $conversionType == 1 ? $amountSent : $amountSent/$userRate->mainRate;

        $totalAmount = self::calculateTotalAmount($sendingAmount, $userRate->mainRate, $userCommission->value);
        $agentCommission = self::calculateAgentCommission($userCommission->agentQuota, $userCommission->value);
        

        return new self(
            originCurrencyId: $originCurrencyId,
            destinationCurrencyId: $destinationCurrencyId,
            amountSent: $sendingAmount,
            beneficiaryReceive:$localSendingAmount,
            exchangeRate: $userRate->mainRate,
            totalAmount: $totalAmount,
            totalCommission: $userCommission->value,
            agentCommission: $agentCommission,
            localAmount: $localSendingAmount ,
            bouRate: $userRate?->bouRate,
            soldRate: $userRate?->soldRate,
            transactionCode: self::generateTransactionCode()
        );
    }

    private static function calculateTotalAmount( float $amountSent, float $exchangeRate, float $commission): float
    {
        return $amountSent + $commission;
    
    }

    


    private static function calculateAgentCommission(float $agentQuota, float $commission): float
    {
        return $agentQuota / 100 * $commission;
    }


    public static function generateTransactionCode()
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
