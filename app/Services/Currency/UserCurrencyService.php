<?php

namespace App\Services\Currency;

use App\Models\UserCurrency;
use App\Enum\UserRoleType;
use App\DTO\UserDto;
use App\DTO\ReceiverDto;
use Illuminate\Support\Facades\Auth;

class UserCurrencyService
{
    public function handleUserCurrency(array $validated, UserDto $user, ReceiverDto $receiver): UserCurrency
    {
        $userCurrency = UserCurrency::firstOrCreate(
            [
                'user_id' => $user->userId,
                'sender_id' => $user->userRoleType === UserRoleType::CUSTOMER ? $user->userId : $receiver->senderId,
                'origin_currency_id' => $validated['origin_currency_id'],
                'destination_currency_id' => $validated['destination_currency_id'],
            ],
            ['last_used_at' => now()]
        );

        if (!$userCurrency->wasRecentlyCreated) {
            $userCurrency->update(['last_used_at' => now()]);
        }

        return $userCurrency;
    }
}
