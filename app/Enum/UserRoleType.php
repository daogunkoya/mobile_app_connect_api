<?php

namespace App\Enum;


enum UserRoleType: int
{
    case CUSTOMER = 1;
    case AGENT = 2;
    case ADMIN = 3;


    public function fetchType(): int
    {
        return match($this)
        {
            self::CUSTOMER => 1,
            self::AGENT => 2,
            self::ADMIN => 3,
        };
    }

    public function label(): string
    {
        return match($this)
        {
            self::CUSTOMER => 'Customer',
            self::AGENT => 'Agent',
            self::ADMIN => 'Admin',
        };
    }
}
