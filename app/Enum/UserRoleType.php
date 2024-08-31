<?php

namespace App\Enum;


enum UserRoleType: int
{
    case CUSTOMER = 1;
    case AGENT = 2;
    case MANAGER = 3;
    case ADMIN = 4;


    public function fetchType(): int
    {
        return match($this)
        {
            self::CUSTOMER => 1,
            self::AGENT => 2,
            self::MANAGER => 3,
            self::ADMIN => 4,
        };
    }

    public function label(): string
    {
        return match($this)
        {
            self::CUSTOMER => 'Customer',
            self::AGENT => 'Agent',
            self::MANAGER => 'Manager',
            self::ADMIN => 'Admin',
        };
    }

    public static function getRoleTypeEnumInstance($value): self
    {
        return match(strtolower($value))
        {
            'customer' => self::CUSTOMER,
            'agent' => self::AGENT,
            'admin' => self::ADMIN,
            'manager' => self::MANAGER,
            default => throw new \InvalidArgumentException("Invalid user role value"),
        };
    }
}
