<?php

namespace App\DTO;
use App\DTO\UserCurrencyDto;
use App\DTO\UserDocumentDto;
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
        public ?string $email_verified_at,
        public ?string $phone,
        public ?string $userHandle,
        public ?string $currencyId,
        public ?string $address,
        public ?string $postcode,
        public ?array $metaData,
        public ?UserStatus $userStatus,
        public ?UserRoleType $userRoleType,
        public UserCurrencyDto | null $userCurrency,
        public array | UserDocumentDto | null $userDocument,
        public ?string $transactionCount,
        public int $senderCount,
        public int $receiverCount,
        public array $outstanding,

    )
    {
    }

    public static function fromEloquentModel(User $user): UserDto

    {
        $totalCommissionSum = $user->outstandingPayments('commission', 0)->sum('total_commission');
        $totalAgentCommission = $user->outstandingPayments('commission', 0)->sum('agent_commission');
        $totalBusinessCommission =  $totalCommissionSum - $totalAgentCommission;
        $amountSentSum = $user->outstandingPayments('transaction', 0)->sum('amount_sent');

        return new self(
            $user->id_user,
            $user->first_name,
            $user->last_name,
            $user->title,
            $user->middle_name,
            $user->dob,
          "$user->first_name $user->last_name",
            $user->email,
            $user->email_verified_at,
            $user->phone,
            $user->user_handle,
            $user->currency_id,
            $user->address,
            $user->postcode,
            $user->metadata,
            $user->status??UserStatus::ACTIVE,
            $user->user_role_type??UserRoleType::CUSTOMER,
            $user->latestUserCurrency ?UserCurrencyDto::fromEloquentModel($user->latestUserCurrency):null,
            $user->userIdentityDocument ?UserDocumentDto::fromEloquentModel($user->userIdentityDocument):null,
            $user->transaction()->count(),
            $user->sender->count(),
            $user->receiver->count(),
            [
                'total_commission_sum' => $totalCommissionSum,
                'total_agent_commission_sum' => $totalAgentCommission,
                'total_business_commission_sum' => $totalBusinessCommission,
                'amount_sent_sum' => $amountSentSum,
                'count' => $user->outstandingPayments('transaction', 0)->count() 
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
