<?php

namespace App\DTO;

use App\Models\Sender;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use App\Enum\UserRoleType;


class UserDto
{
    public function __construct(
        public string $userId,
        public string $firstName,
        public string $lastName,
        public string $email,
        public ?string $userHandle,
        public ?string $currencyId,
        public UserRoleType $userRoleType,

    )
    {
    }

    public static function fromEloquentModel(User $user): UserDto
    {
        return new self(
            $user->id_user,
            $user->first_name,
            $user->last_name,
            $user->email,
            $user->user_handle,
            $user->currency_id,
            $user->user_role_type
        );
    }


}
