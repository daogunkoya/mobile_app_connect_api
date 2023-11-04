<?php
namespace  App\Repositories;

use App\Models\Sender;
use Illuminate\Support\Facades\Auth;

class SenderRepository{
    //fetch customer
    public function fetchSenders($input): array
    {
        $userId = Auth::user()->id_user;
        $search = !empty($input['search']) && $input['search'] != "null" ? "%" . $input['search'] . "%" : '%';

        $select = [
            'id_sender as sender_id',
            'user_id',
            'sender_title',
            'created_at',
            'sender_name',
            'sender_mname',
            'sender_fname',
            'sender_lname',
            'sender_dob',
            'sender_email',
            'sender_phone',
            'sender_mobile',
            'sender_address',
            'sender_postcode'
        ];

        $query = Sender::withCount('receiver') // Eager load 'receivers' count
        ->where('user_id', $userId)
            ->where('sender_name', 'like', $search)
            ->orderBy('created_at', 'DESC');

        // Get the current page from the request or use the first page by default
        $page = $input['page'] ?? 1;

        // Number of items per page (you can customize this)
        $limit = $input['limit'] ?? 6;

        // Use the `paginate` method to get paginated results
        $senders = $query
            ->select($select)
            ->paginate($limit, ['*'], 'page', $page);

        return [
            'sender_count' => $query->count(),
            'sender' => $senders->items(), // Get the items for the current page
            'current_page' => $senders->currentPage(),
            'last_page' => $senders->lastPage(),
            'total' => $senders->total(),
            'per_page' => $senders->perPage(),
        ];
    }


}
