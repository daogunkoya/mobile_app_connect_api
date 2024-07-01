<?php

namespace App\Actions;

use App\Exceptions\RateNotSetException;
use App\Repositories\RateRepository;
use PhpParser\Node\Expr\Cast\Double;

class CalculateTransaction
{
    public function __construct(
        public float $rate,
        public float $localAmount,
        public int   $commission,
        public float $totalAmount,
        public float $amountToSend,
        public int   $conversionType,
        public int   $agentQuota,
    )
    {
    }


    public static function calculateHandle($userRate, $userCommission, $sendAmount, $conversionType):self
    {

        if (!$userRate) throw new RateNotSetException("no rate is provided");
        // Evaluate based on convert type
        $sendAmount = $conversionType == 1 ? $sendAmount : $sendAmount / $userRate;

        $agentQuota = $userCommission->agent_quota;

        $totalAmount = $sendAmount + $userCommission->value;
        $localAmount = $userRate * $sendAmount;

        return new self(
            $userRate,
            $localAmount,
            $userCommission->value,
            $totalAmount,
            $sendAmount,
            $conversionType,
            $agentQuota
        );
    }
}
