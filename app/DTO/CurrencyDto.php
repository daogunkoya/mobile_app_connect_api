<?php

namespace App\DTO;


use App\Models\Currency;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use App\Enum\CurrencyType;
use Illuminate\Pagination\LengthAwarePaginator;
use phpseclib3\File\ASN1\Maps\Curve;

class CurrencyDto extends BaseDto
{
    public function __construct(
        public string $currencyId,
        public string $currencyCountry,
        public string $currencyTitle,
        public string $currencySymbol,
        public string $currencyType,
        public int $currencyDefault,
 

    )
    {
    }

    public static function fromEloquentModel(Currency $currency): self
    {
        return new self(
            $currency->id_currency,
            $currency->currency_country,
            $currency->currency_title,
            $currency->currency_symbol,
            $currency->currency_type->label(),
            $currency->default_currency,

        );
    }

    public static function fromEloquentCollection(LengthAwarePaginator $currencies): LengthAwarePaginator
    {

        //return $currencies->map(fn (Currency $currency) => self::fromEloquentModel($currency));
        if ($currencies instanceof LengthAwarePaginator) {
            $mappedItems = collect($currencies->items())
                ->map(fn(Currency $currency) => self::fromEloquentModel($currency));
    
            return new LengthAwarePaginator(
                $mappedItems,
                $currencies->total(),
                $currencies->perPage(),
                $currencies->currentPage(),
                ['path' => LengthAwarePaginator::resolveCurrentPath()]
            );
        }
    }

   


}
