<?php

namespace App\Repositories;

use App\Models\Sender;
use Illuminate\Support\Facades\Auth;

class SenderRepository
{
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
            ->where(function ($query) use ($search) {
                $query->where('sender_fname', 'like', $search)
                    ->orWhere('sender_lname', 'like', $search);
            })
            ->orderBy('created_at', 'DESC');

        // Get the current page from the request or use the first page by default
        $page = $input['page'] ?? 1;
        // Check if we want to fetch all items without pagination
        if (!empty($input['fetchall']) && $input['fetchall'] == 1) {
            return $this->fetchAllSenders($query);
        }

        // Number of items per page (you can customize this)
        $limit = $input['limit'] ?? 6;

        // Use the `paginate` method to get paginated results
        $senders = $query
            ->select($select)
            ->paginate($limit, ['*'], 'page', $page);

        return [
            'sender_count' => $senders->total(),
            'sender' => $senders->items(), // Get the items for the current page
            'current_page' => $senders->currentPage(),
            'last_page' => $senders->lastPage(),
            'total' => $senders->total(),
            'per_page' => $senders->perPage(),
        ];
    }


    protected function fetchAllSenders($query): array
    {
        $select = [
            'id_sender as sender_id',
            'sender_name',
            'sender_phone'
        ];
        // Fetch all items without pagination
        $senders = $query->select($select)->get();

        return [
            'sender_count' => $senders->count(),
            'sender' => $senders,
        ];
    }


    public function createSender($input, $user_id):string
    {

        if (!empty($input)) {
            $senderName = $input['sender_fname'] . ' ' . $input['sender_lname'];
            $new_sender = sender::create([
                "user_id" => $user_id,
                'store_id' => session()->get('process_store_id') ?? request()->process_store_id,
                "sender_title" => $input['sender_title'] ?? '',
                "sender_name" => $senderName ?? '',
                "sender_mname" => $input['sender_mname'] ?? '',
                "sender_fname" => $input['sender_fname'] ?? '',
                "sender_lname" => $input['sender_lname'] ?? '',
                "sender_dob" => $input['sender_dob'] ?? '',
                "sender_email" => $input['sender_email'] ?? '',
                "sender_phone" => $input['sender_phone'] ?? '',
                "sender_mobile" => $input['sender_mobile'] ?? '',
                "sender_address" => $input['sender_address'] ?? '',
                "sender_postcode" => $input['sender_postcode'] ?? '',
                "photo_id" => ''
            ]);

            return $new_sender->id_sender;
        }
    }

    public function updateSender($input, $user_id, $sender_id):bool
    {
        $senderfName = $input['sender_fname'] ?? '';
        $senderlName = $input['sender_lname'] ?? '';

        $senderName = $senderfName . ' ' . $senderlName;

        if (!empty($input)) {
            $update_sender = sender::where('id_sender', $sender_id)->update([
                "user_id" => $user_id,
                'store_id' => session()->get('process_store_id') ?? request()->process_store_id,
                "sender_title" => $input['sender_title'] ?? '',
                "sender_name" => $senderName ?? '',
                "sender_mname" => $input['sender_mname'] ?? '',
                "sender_fname" => $input['sender_fname'] ?? '',
                "sender_lname" => $input['sender_lname'] ?? '',
                "sender_dob" => $input['sender_dob'] ?? '',
                "sender_email" => $input['sender_email'] ?? '',
                "sender_phone" => $input['sender_phone'] ?? '',
                "sender_mobile" => $input['sender_mobile'] ?? '',
                "sender_address" => $input['sender_address'] ?? '',
                "sender_postcode" => $input['sender_postcode'] ?? '',
                "photo_id" => ''
            ]);

            return true;
        }
        return false;
    }

    public function deleteSender($senderId):bool{

        if(!Sender::where('id_sender', $senderId)->exists()){
            return false;
        }
         Sender::where('id_sender', $senderId)->delete();
        return true;

    }

    public function showSender($senderId): object
    {
        try {
            $sender = Sender::findOrFail($senderId);
            return $sender;
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // If the sender is not found, you can return null or a default instance
            return new \stdClass();
        }
    }



}
