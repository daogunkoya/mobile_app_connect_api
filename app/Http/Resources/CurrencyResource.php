<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CurrencyResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'currency_id' => $this->currencyId,
            'currency_country' => $this->currencyCountry,
            'currency_title' => $this->currencyTitle,
            'currency_symbol' => $this->currencySymbol,
            'currency_type' => $this->currencyType,
            'currency_default' => $this->currencyDefault,
        ];
    }
}
