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
}
