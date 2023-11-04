<?php

namespace App\Enum\Bank;

enum TransferType: string
{
    case Bank = "bank";
    case PickUp = "pickup";
    case None = 'none';
}
