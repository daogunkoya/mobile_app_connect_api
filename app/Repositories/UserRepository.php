<?php

namespace App\Repositories;

use App\Enum\UserRoleType;
use App\Models\User;
use App\Exceptions\RateNotSetException;
use App\Filters\UserFilter;

class UserRepository
{

    public function __construct(
        protected CommissionRepository $commissionRepository,
        protected BankRepository       $bankRepository,
        protected UserFilter           $userFilter
    ) {
    }

    public function fetchUsers($input, $user)
    {
     
            $isAdmin = $user->userRoleType == UserRoleType::ADMIN;

            $userQuery =   User::query();

        $query = $userQuery
        //->with(['transaction','receiver', 'sender'])
            ->withCount('transaction')
            ->withCount('receiver')
            ->withCount('sender')
            ->withSum(['outstandingPayments' => function ($query) {
                $query->where('transaction_paid_status', 0); // Or any condition you want
            }], 'total_commission')
            ->withSum(['outstandingPayments' => function ($query) {
                $query->where('commission_paid_status', 0); // Or any condition you want
            }], 'agent_commission')
            ->withSum('outstandingPayments', 'amount_sent')
            ->filter($this->userFilter)
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
