<?php

namespace App\Repositories;

use App\Models\Receiver;
use App\Services\Helper;
use Illuminate\Support\Facades\Auth;
use App\Repositories\BankRepository;

class ReceiverRepository
{

    public function fetchReceiver($input, $sender_id): array
    {

        $user_id = Auth::id();
        $select = ['id_receiver as receiver_id',
            'created_at',
            'user_id',
            'receiver_title',
            'id_receiver as receiver_name',
            'receiver_fname', 'receiver_lname',
            'receiver_phone',
            'receiver_address',
            'transfer_type',
            'account_number',
            'bank_id'
            ];
        //$search =!empty($input['search'])? "%".$input['search']."%":'%';
        $search = !empty($input['search']) && $input['search'] != "null" ? "%" . $input['search'] . "%" : '%';


        $query = Receiver::where('sender_id', $sender_id)
            ->where('receiver_name', 'like', $search)
            ->orderBy('created_at', 'DESC');

        // Get the current page from the request or use the first page by default
        $page = $input['page'] ?? 1;

        // Number of items per page (you can customize this)
        $limit = $input['limit'] ?? 6;

        // Use the `paginate` method to get paginated results
        $receivers = $query
            ->select($select)
            ->paginate($limit, ['*'], 'page', $page);

        return [
            'receiver_count' => $query->count(),
            'receiver' => $receivers->items(), // Get the items for the current page
            'current_page' => $receivers->currentPage(),
            'last_page' => $receivers->lastPage(),
            'total' => $receivers->total(),
            'per_page' => $receivers->perPage(),
            'banks_id_list'=> BankRepository::fetchBankIdList()
        ];
    }


}
