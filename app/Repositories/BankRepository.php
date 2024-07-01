<?php

namespace  App\Repositories;

use App\Models\Bank;

class BankRepository{

    public static function fetchBankList():array
    {
        $list_bank = optional(
            Bank::where('bank_category', 'b')->select(
                'name',
                'id'
            )->get()
        )->toArray();

        $count_bank = Bank::where('bank_category', 'b')->count();

        $list_pickup = optional(
            Bank::where('bank_category', 'p')->select(
                'id',
                'name',
            )->get()
        )->toArray();

        $count_pickup = Bank::where('bank_category', 'p')->count();



        return [
            'bank_count'         => $count_bank,
            'bank'               => $list_bank,
            'bank_pickup'        => $count_pickup,
            'list_pickup'        => $list_pickup,
        ];
    }

    public function fetchBanksIdentityTypesList():array{

        $banksList = BankRepository::fetchBankList();
        $acceptableIdentityList = IdentityRepository::fetchIdentityList();
        $transferTypeList = \App\Enum\Bank\TransferType::transTypeList();

        return array_merge($banksList, $acceptableIdentityList, $transferTypeList);
    }
}
