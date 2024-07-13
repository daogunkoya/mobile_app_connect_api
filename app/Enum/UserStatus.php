<?php

namespace App\Enum;


enum UserStatus: int
{
    case INACTIVE = 0;
    case ACTIVE = 1;
    case SUSPENDED = 2;
    case DELETED = 3;


    public function fetchType(): int
    {
        return match($this)
        {
            self::INACTIVE => 0,
            self::ACTIVE => 1,
            self::SUSPENDED => 2,
            self::DELETED => 3,
        };
    }

    public function label(): string
    {
        return match($this)
        {
            self::INACTIVE => 'inactive',
            self::ACTIVE => 'active',
            self::SUSPENDED => 'ssuspended',
            self::DELETED => 'deleted',
        };
    }

    public static function getStatusEnumInstance($value): self
    {
        return match(strtolower($value))
        {
            'inactive' => self::INACTIVE,
            'active' => self::ACTIVE,
            'suspended' => self::SUSPENDED,
            'deleted' => self::DELETED,
            default => throw new \InvalidArgumentException("Invalid status value"),
        };
    }
}
