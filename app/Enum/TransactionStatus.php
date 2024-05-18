<?php

namespace App\Enum;


enum TransactionStatus: int
{
    case PENDING = 1;
    case FAILED = 2;
    case PAID = 3;


    public function fetchType(): int
    {
        return match($this)
        {
            self::PENDING => 1,
            self::FAILED => 2,
            self::PAID => 3,
        };
    }

    public function label(): string
    {
        return match($this)
        {
            self::PENDING => 'Pending',
            self::FAILED => 'Failed',
            self::PAID => 'Paid',
        };
    }

}
