<?php

namespace App\Services\Sender;

use App\Models\sender;
use Illuminate\Support\Facades\DB;
use App\Services\Helper;
use App\Models\mm_log_device;
use Illuminate\Support\Facades\Http;
use App\Models\mm_user;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class SenderService
{
    //fetch customer
    public function fetchSenders($request):array
    {

        $userId = Auth::user()->id_user;
        $input = $request->all();
        $search = !empty($input['search']) && $input['search'] != "null" ? "%" . $input['search'] . "%" : '%';

        $select = [
            'id_sender as sender_id',
            'user_id',
            'sender_title',
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

        $query = Sender::where('user_id', $userId)
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



    //create new customer
    public function createSender($input, $user_id)
    {

        if (!empty($input)) {
            $senderName = $input['sender_fname'] .' '. $input['sender_lname'] ;
            $new_sender =   sender::create([
                "user_id" => $user_id,
                'store_id' => session()->get('process_store_id') ?? request()->process_store_id,
                "sender_title" => $input['sender_title'] ?? '',
                "sender_name" =>   $senderName ?? '',
                "sender_mname" => $input['sender_mname'] ?? '',
                "sender_fname" =>  $input['sender_fname'] ?? '',
                "sender_lname" =>  $input['sender_lname'] ?? '',
                "sender_dob" =>  $input['sender_dob'] ?? '',
                "sender_email" =>  $input['sender_email'] ?? '',
                "sender_phone" =>  $input['sender_phone'] ?? '',
                "sender_mobile" =>  $input['sender_mobile'] ?? '',
                "sender_address" =>  $input['sender_address'] ?? '',
                "sender_postcode" =>  $input['sender_postcode'] ?? '',
                "photo_id" => ''
            ]);

            return $new_sender->id_sender;
        }
    }


    //update customer
    public function updateSender($input, $user_id, $sender_id)
    {
         $senderfName = $input['sender_fname']??'' ;
        $senderlName =  $input['sender_lname']??'' ;

        $senderName =  $senderfName .' '.  $senderlName;

        if (!empty($input)) {
            $update_sender =   sender::where('id_sender', $sender_id)->update([
                "user_id" => $user_id,
                'store_id' => session()->get('process_store_id') ?? request()->process_store_id,
                "sender_title" => $input['sender_title'] ?? '',
                "sender_name" =>  $senderName?? '',
                "sender_mname" => $input['sender_mname'] ?? '',
                "sender_fname" =>  $input['sender_fname'] ?? '',
                "sender_lname" =>  $input['sender_lname'] ?? '',
                "sender_dob" =>  $input['sender_dob'] ?? '',
                "sender_email" =>  $input['sender_email'] ?? '',
                "sender_phone" =>  $input['sender_phone'] ?? '',
                "sender_mobile" =>  $input['sender_mobile'] ?? '',
                "sender_address" =>  $input['sender_address'] ?? '',
                "sender_postcode" =>  $input['sender_postcode'] ?? '',
                "photo_id" => ''
            ]);

            return true;
        }
    }
}
