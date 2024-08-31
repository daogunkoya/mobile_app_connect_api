<?php

namespace App\Enum;


enum PaymentType: int
{
    case TRANSACTION = 1;
    case AGENTTRANSACTION = 2;
    case AGENTCOMMISSION = 3;
    case SUBSCRIPTION = 4;


    public function fetchType(): int
    {
        return match($this)
        {
            self::TRANSACTION => 0,
            self::AGENTTRANSACTION => 1,
            self::AGENTCOMMISSION => 2,
            self::SUBSCRIPTION => 3,
        };
    }

    public function label(): string
    {
        return match($this)
        {
            self::TRANSACTION => 'Transaction',
            self::AGENTTRANSACTION => 'Agent Transaction',
            self::AGENTCOMMISSION => 'Agent Commission',
            self::SUBSCRIPTION => 'Subscription',
        };
    }

    public static function getStatusEnumInstance($value): self
    {
        return match(strtolower($value))
        {
            'transaction' => self::TRANSACTION,
            'agenttransaction' => self::AGENTTRANSACTION,
            'agentcommission' => self::AGENTCOMMISSION,
            'subscription' => self::SUBSCRIPTION,
            default => throw new \InvalidArgumentException("Invalid status value"),
        };
    }
}
