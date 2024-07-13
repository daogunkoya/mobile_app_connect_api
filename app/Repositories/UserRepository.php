<?php

namespace App\Repositories;

use App\Enum\UserRoleType;
use App\Models\User;
use App\Exceptions\RateNotSetException;

class UserRepository
{

    public function __construct(
        protected CommissionRepository $commissionRepository,
        protected BankRepository       $bankRepository
    ) {
    }

    public function fetchUsers($input, $user)
    {
     
            $isAdmin = $user->userRoleType == UserRoleType::ADMIN;

            $userQuery =   User::query();

        $query = $userQuery->with(['transaction','receiver', 'sender'])
            ->filter([
                'userId' => !$isAdmin?$user->userId: null,
                'search' => $input['search'] ?? '',
                'date' => $input['date'] ?? '',
                'status' => $input['status'] ?? '',
                'type' => $input['status'] ?? ''
            ])
            ->orderBy('created_at', 'DESC');


        $page = request('page') ?? 1;
        $limit = request('limit') ?? 6;

        return [
            $query->paginate($limit, ['*'], 'page', $page),
            $query->count()
        ];
    }

    public static function selectList()
    {
        return [
            'id',
            'sender_fname',
            'sender_lname',
            'receiver_fname',
            'receiver_lname',
            'receiver_email',
            'transaction_code',
            'transaction_status',
            'total_amount',
            'created_at'
        ];
    }


   
}
