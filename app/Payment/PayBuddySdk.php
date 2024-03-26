<?php

namespace App\Payment;

use Illuminate\Support\Str;
use NumberFormatter;

final class PayBuddySdk
{
    public function charge(string $token, int $amountInCents, string $stateDescription):array
    {
        $this->validateToken($token);

        $numberFormatter = new NumberFormatter('en-US', NumberFormatter::CURRENCY);

        return [
            'id' => (string)Str::uuid(),
            'amount_in_cents' => $amountInCents,
            'localized_amount' => $numberFormatter->format($amountInCents / 100),
            'statement_description' => $stateDescription,
            'created_at' => now()->toDateTimeString(),

        ];
    }

    public static function make():PayBuddySdk
    {
        return new self();
    }

    public static function validToken():string
    {
        return (string) Str::uuid();
    }

    public static function inValidToken():string
    {
        return substr(self::validToken(), -35);
    }

    private function validateToken(string $token): void
    {
        if(! Str::isUuid($token)){
            throw new \RuntimeException('The payment token is not valid');
        }
    }
}
