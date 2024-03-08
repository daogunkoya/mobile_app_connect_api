<?php

namespace App\Repositories;

use App\DTO\SenderDto;
use App\Enum\UserRoleType;
use App\Models\Sender;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;

class SenderRepository
{
    //fetch customer
    public function fetchSenders($input): LengthAwarePaginator
    {
        $user = auth()->user();
        $senderQuery = $user->user_role_type == UserRoleType::ADMIN ?
            Sender::query() :
            $user->sender();

        $query = $senderQuery->withCount('receiver') // Eager load 'receivers' count
            ->select(self::selectSenderList())
        ->filter(['search' => request('search'), 'all' => request('fetchall')])
            ->orderBy('created_at', 'DESC');

        $page = request('page') ?? 1;
        $limit = request('limit') ?? 6;

        return request('fetchall') ?
            $query->paginate(PHP_INT_MAX) :
            $query->paginate($limit, ['*'], 'page', $page);
    }

    public static function selectSenderList():array
    {
        return request('fetchall') ?
           [
                'id_sender',
                'sender_name',
                'sender_phone'
            ]:

            [
                'id_sender',
                'user_id',
                'sender_title',
                'created_at',
                //  'sender_name',
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

        ;
    }


    public function createSender($input, $user_id): string
    {

        if (!empty($input)) {
            $new_sender = sender::create([
                "user_id" => $user_id,
                "sender_title" => $input['sender_title'] ?? '',
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

    public function updateSender($input, $user_id, $sender_id): bool
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

    public function deleteSender($senderId): bool
    {

        if (!Sender::where('id_sender', $senderId)->exists()) {
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
