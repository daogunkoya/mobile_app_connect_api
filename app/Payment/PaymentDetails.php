<?php

namespace App\Payment;

class PaymentDetails
{
public function __construct(
    public string $token,
    public int $amountInCents,
    public string $statementDescription
)
{
}
}
