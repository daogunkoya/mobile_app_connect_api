<?php

namespace App\DTO;


use App\Models\Currency;
use App\DTO\CurrencyDto;
use App\Models\UserCurrency;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use App\Enum\CurrencyType;
use Illuminate\Pagination\LengthAwarePaginator;
use PhpOffice\PhpSpreadsheet\Calculation\DateTimeExcel\Current;
use phpseclib3\File\ASN1\Maps\Curve;

class UserCurrencyDto extends BaseDto
{
    public function __construct(
        public string $userId,
        public string $senderId,
        public CurrencyDto $originCurrency,
        public CurrencyDto $destinationCurrency,

    )
    {
    }

  
    public static function fromEloquentModel(UserCurrency $userCurrency): UserCurrencyDto
    {
        return new self(
            $userCurrency->user_id,
            $userCurrency->sender_id,
            CurrencyDto::fromEloquentModel($userCurrency->originCurrency),
            CurrencyDto::fromEloquentModel($userCurrency->destinationCurrency),
            // Map other fields as needed
        );
    }

    
    }

   
