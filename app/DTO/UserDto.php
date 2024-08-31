<?php

namespace App\DTO;
use App\DTO\UserCurrencyDto;
use App\Models\Sender;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use App\Enum\UserRoleType;
use App\Enum\UserStatus;
use App\Models\OutstandingPayment;

class UserDto
{
    public function __construct(
        public string $userId,
        public string $firstName,
        public string $lastName,
        public ?string $title,
        public ?string $middleName,
        public ?string $dob,
        public string $userName,
        public string $email,
        public ?string $userHandle,
        public ?string $currencyId,
        public ?string $address,
        public ?string $postcode,
        public ?array $metaData,
        public UserStatus $userStatus,
        public UserRoleType $userRoleType,
        public UserCurrencyDto | null $userCurrency,
        public ?string $transactionCount,
        public int $senderCount,
        public int $receiverCount,
        public array $outstanding,

    )
    {
    }

    public static function fromEloquentModel(User $user): UserDto
    {
        return new self(
            $user->id_user,
            $user->first_name,
            $user->last_name,
            $user->title,
            $user->middle_name,
            $user->dob,
          "$user->first_name $user->last_name",
            $user->email,
            $user->user_handle,
            $user->currency_id,
            $user->address,
            $user->postcode,
            $user->metadata,
            $user->status,
            $user->user_role_type,
            $user->latestUserCurrency ?UserCurrencyDto::fromEloquentModel($user->latestUserCurrency):null,
            $user->transaction()->count(),
            $user->sender->count(),
            $user->receiver->count(),
            [
                'total_commission_sum' => $user->outstandingPayments()->sum('total_commission'), // Total commission sum
                'total_agent_commission_sum' => $user->outstandingCommissions()->sum('agent_commission'), // Total commission sum
                'total_business_commission_sum' => $user->outstandingCommissions()->sum('total_commission') - $user->outstandingCommissions()->sum('agent_commission') , // Total commission sum
                'amount_sent_sum' => $user->outstandingPayments()->sum('amount_sent'),          // Total amount sum
            ]


        );
    }

    public static function fromEloquentModelCollection($userList)
    {
        if ($userList instanceof LengthAwarePaginator) {
           
            $mappedItems = collect($userList->items())
                ->map(fn(User $user) => self::fromEloquentModel($user));
    
            return new LengthAwarePaginator(
                $mappedItems,
                $userList->total(),
                $userList->perPage(),
                $userList->currentPage(),
                ['path' => LengthAwarePaginator::resolveCurrentPath()]
            );
        }

        if ($userList instanceof EloquentCollection) {
            return $userList->map(fn(User $user) => self::fromEloquentModel($user));
        }
    


}

}
