<?php

namespace App\Actions;

use App\DTO\TransactionDto;
use App\DTO\UserDto;

class TransactionFulfilled
{

    /**
     * @param  transactionDto $transaction
     * @param  UserDto  $user
     */
    public function __construct(
        public transactionDto $transaction,
        public UserDto $user,
    ) {


    }
}
