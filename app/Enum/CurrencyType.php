<?php

namespace App\Enum;


enum CurrencyType: int
{
    case ORIGIN = 0;
    case DESTINATION = 1;



    public function fetchType(): int
    {
        return match($this)
        {
            self::ORIGIN => 0,
            self::DESTINATION => 1,

        };
    }

    public function label(): string
    {
        return match($this)
        {
            self::ORIGIN => 'Origin',
            self::DESTINATION => 'Destination',
        };
    }

    public static function getStatusEnumInstance($value): self
    {
        return match(strtolower($value))
        {
            'origin' => self::ORIGIN,
            'destination' => self::DESTINATION,
            default => throw new \InvalidArgumentException("Invalid type value"),
        };
    }

}
