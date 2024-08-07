<?php

namespace App\Repositories;

use App\Enum\UserRoleType;
use App\Models\Currency;
use App\Models\Receiver;
use App\Models\Sender;
use App\Repositories\BankRepository;
use App\Services\Helper;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReceiverRepository
{


    public function fetchReceiver($senderId): LengthAwarePaginator
    {
        $receiverQuery = Receiver::query()->where('sender_id', $senderId);

        $query = $receiverQuery->with(['bank:id,name', 'identity:id,name'])
            ->filter(['search' => request('search'), 'all' => request('fetchall')])
            ->orderBy('created_at', 'DESC');

        $page = request('page') ?? 1;
        $limit = request('limit') ?? 6;

        return request('fetchall') ?
            $query->paginate(PHP_INT_MAX) :
            $query->paginate($limit, ['*'], 'page', $page);

    }


    public function fetchBanksIdentityTypesList(): array
    {

        $banksList = BankRepository::fetchBankList();
        $acceptableIdentityList = IdentityRepository::fetchIdentityList();
        $transferTypeList = \App\Enum\Bank\TransferType::transTypeList();

        return array_merge($banksList, $acceptableIdentityList, $transferTypeList);
    }

    protected function fetchAllReceivers($query): array
    {
        $select = [
            'id_receiver as receiver_id',
            // 'receiver_name',
        ];
        // Fetch all items without pagination
        $receivers = $query->select([
            'id_receiver as receiver_id',
            'receiver_fname', 'receiver_lname',
            'receiver_phone', 'account_number', 'transfer_type', 'bank_id'
            // DB::raw('CONCAT(receiver_fname, " ", receiver_lname) as name')
        ])->get();

        $receivers->makeHidden(['bank_id', 'identity', '']);

        return [
            'receiver_count' => $receivers->count(),
            'receiver' => $receivers,
        ];
    }


    public function createReceiver($input, $sender_id): string
    {

        $currency_id = $input['currency_id'] ?? Currency::where('default_currency', 1)->value('id_currency');

        if (!empty($input)) {
            $new_receiver = receiver::create([
                "user_type" => 1,
                'store_id' => session()->get('process_store_id') ?? request()->process_store_id,
                "sender_id" => $sender_id,
                "receiver_title" => $input['receiver_title'] ?? '',
                // "receiver_name" =>  $input['receiver_name'] ?? '',
                "receiver_mname" => $input['receiver_name'] ?? '',
                "receiver_fname" => $input['receiver_fname'] ?? '',
                "receiver_lname" => $input['receiver_lname'] ?? '',
                "receiver_email" => $input['receiver_email'] ?? '',
                "receiver_phone" => $input['receiver_phone'] ?? '',
                "receiver_address" => $input['receiver_address'] ?? '',
                "transfer_type" => $input['transfer_type'] ?? '',
                "account_number" => $input['account_number'] ?? '',
                "identity_type_id" => $input['identity_type_id'] ?? '',
                "currency_id" => $currency_id,
                "bank_id" => $input['bank_id'] ?? '',
                "photo_id" => ''
            ]);

            return $new_receiver->id_receiver;
        }
    }


    //updatereceiver
    public function updateReceiver($input, $receiver_id): bool
    {

        if (!empty($input)) {
            $updated_receiver = receiver::where('id_receiver', $receiver_id)->update([
                "user_id" => Auth::id(),
                "user_type" => 1,
                'store_id' => session()->get('process_store_id') ?? request()->process_store_id,
                "receiver_title" => $input['receiver_title'] ?? '',
                "receiver_mname" => $input['receiver_name'] ?? '',
                "receiver_fname" => $input['receiver_fname'] ?? '',
                "receiver_lname" => $input['receiver_lname'] ?? '',
                "receiver_email" => $input['receiver_email'] ?? '',
                "receiver_phone" => $input['receiver_phone'] ?? '',
                "receiver_address" => $input['receiver_address'] ?? '',
                "transfer_type" => $input['transfer_type'] ?? '',
                "account_number" => $input['account_number'] ?? '',
                "identity_type_id" => $input['identity_type_id'] ?? '',
                "bank_id" => $input['bank_id'] ?? ''
            ]);

            return true;
        }

        return false;
    }

    public function deleteReceiver($receiverId): bool
    {

        if (!Receiver::where('id_receiver', $receiverId)->exists()) {
            return false;
        }
        Receiver::where('id_receiver', $receiverId)->delete();
        return true;

    }

    public function showReceiver($receiverId): ?Receiver
    {

        $receiver = Receiver::where('id_receiver', $receiverId)->first();

        return $receiver ?? null;

    }


}
