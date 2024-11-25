<?php

namespace App\Enum;

enum PaymentProvider: string
{

    case PayBuddy = 'pay_buddy';
    case InMemory = 'in_memory';
    case InPerson = 'in_person';
}
