<?php

namespace App\Enum\Bank;

enum TransferType: string
{
    case Bank = "bank";
    case PickUp = "pickup";
    case None = 'none';

    public  static function transTypeList(): array{

        return  ['transfer_type_list' => [ self::Bank, self::PickUp] ];

}
}
