<?php

namespace App\DTO;


use App\Models\Currency;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use App\Enum\CurrencyType;


class CurrencyDto extends BaseDto
{
    public function __construct(
        public string $currencyId,
        public string $currencyCountry,
        public string $currencySymbol,
        public string $currencyType,
 

    )
    {
    }

    public static function fromEloquentModel(Currency $currency): self
    {
        return new self(
            $currency->id_currency,
            $currency->currency_country,
            $currency->currency_symbol,
            $currency->currency_type->label(),

        );
    }

    public static function fromEloquentCollection(EloquentCollection $currencies): Collection
    {

        return $currencies->map(fn (Currency $currency) => self::fromEloquentModel($currency));
    }

   


}
