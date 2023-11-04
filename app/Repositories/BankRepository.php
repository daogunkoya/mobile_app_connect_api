<?php

namespace  App\Repositories;

use App\Models\Bank;

class BankRepository{

    public static function fetchBankIdList():array
    {
        $list_bank = optional(
            Bank::where('bank_category', 'b')->select(
                'name as value',
                'id_bank as key'
            )->get()
        )->toArray();

        $count_bank = Bank::where('bank_category', 'b')->count();

        $list_pickup = optional(
            Bank::where('bank_category', 'p')->select(
                'name as value',
                'id_bank as key'
            )->get()
        )->toArray();

        $count_pickup = Bank::where('bank_category', 'p')->count();

        $proof_id_list = optional(
            Bank::where('bank_proof_identity', '1')->select(
                'name as value',
                'id_bank as key'
            )->get()
        )->toArray();

        $option_list = optional(
            Bank::where('transfer_type', '3')->select(
                'name as value',
                'transfer_type_key as key'
            )->get()
        )->toArray();

        return [
            'bank_count'         => $count_bank,
            'bank'               => $list_bank,
            'bank_pickup'        => $count_pickup,
            'list_pickup'        => $list_pickup,
            'proof_id'           => $proof_id_list,
            'transfer_type_list' => $option_list,
        ];
    }
}
