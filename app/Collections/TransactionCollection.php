<?php

namespace App\Collections;

use App\DTO\CommissionDto;
use App\DTO\RateDto;

class TransactionCollection
{
    public function __construct(
        public float $amountSent,
        public float $exchangeRate,
        public float $localAmount,
        public float $totalAmount,
        public float $totalCommission,
        public float $agentCommission,
        public float $bouRate,
        public float $soldRate,
    ) {}

    public static function processTransactionData(
        RateDto $userRate,
        CommissionDto $userCommission,
        float $amountSent,
        int $conversionType
    ): self {
        $totalAmount = self::calculateTotalAmount($amountSent, $userRate->mainRate, $userCommission->value);
        $agentCommission = self::calculateAgentCommission($userCommission->agentQuota, $userCommission->value);

        return new self(
            amountSent: $amountSent,
            exchangeRate: $userRate->mainRate,
            localAmount: $totalAmount + $userCommission->value,
            totalAmount: $totalAmount,
            totalCommission: $userCommission->value,
            agentCommission: $agentCommission,
            bouRate: $userRate->bouRate,
            soldRate: $userRate->soldRate
        );
    }

    private static function calculateTotalAmount(float $amountSent, float $exchangeRate, float $commission): float
    {
        return $amountSent * $exchangeRate + $commission;
    }

    private static function calculateAgentCommission(float $agentQuota, float $commission): float
    {
        return $agentQuota / 100 * $commission;
    }
}
