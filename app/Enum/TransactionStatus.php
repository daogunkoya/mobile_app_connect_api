<?php

namespace App\Enum;


enum TransactionStatus: int
{
    case DELETED = 0;
    case PENDING = 1;
    case FAILED = 2;
    case PAID = 3;


    public function fetchType(): int
    {
        return match($this)
        {
            self::DELETED => 0,
            self::PENDING => 1,
            self::FAILED => 2,
            self::PAID => 3,
        };
    }

    public function label(): string
    {
        return match($this)
        {
            self::DELETED => 'Deleted',
            self::PENDING => 'Pending',
            self::FAILED => 'Failed',
            self::PAID => 'Paid',
        };
    }


    public static function validStatuses(): array
    {
        return array_map(fn($status) => $status->label(), self::cases());
    }

    public static function getStatusEnumInstance($value): self
    {
        return match(strtolower($value))
        {
            'deleted' => self::DELETED,
            'pending' => self::PENDING,
            'paid' => self::PAID,
            'failed' => self::FAILED,
            default => throw new \InvalidArgumentException("Invalid status value"),
        };
    }

}
