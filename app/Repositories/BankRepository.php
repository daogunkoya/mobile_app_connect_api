<?php

namespace  App\Repositories;

use App\Models\Bank;
use App\Models\Currency;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Filters\BankFilter;

class BankRepository{

public function __construct(protected BankFilter $bankFilter)
    {
        
    }
    
    public function fetchBanks($input): LengthAwarePaginator
    {
        
        $currencyId = $input['currency_id'] ?? Currency::whereDefaultCurrency(1)->value('id_currency');
        $bankQuery = Bank::query();;
        $bankQuery ->with(['currency'])->filter($this->bankFilter);

        $query = $bankQuery->orderBy('created_at', 'DESC')->where('bank_status', 1)->whereCurrencyId($currencyId);

        $page = $input['page'] ?? 1;
        $limit = $input['limit']?? 6;

        return $query->paginate($limit, ['*'], 'page', $page);
    }

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
