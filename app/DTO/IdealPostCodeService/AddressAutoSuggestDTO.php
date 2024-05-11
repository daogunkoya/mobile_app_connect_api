<?php

namespace App\DTO\IdealPostCodeService;

class AddressAutoSuggestDTO
{
    public function __construct(public readonly array $data, public string $postcode)
    {
    }

    public function getReadableData(): array
    {
        return collect(data_get($this->data, 'result.hits', []))
            ->map(function ($item) {
                $addressArray = array_map('trim', explode(',', $item['suggestion']));
                [$townOrCity, $postCode] = array_reverse($addressArray);
                $addressNo = explode(' ', $item['suggestion'])[0];
                $address1 = explode(' ', $item['suggestion'])[1];
                $address2 = explode(' ', $item['suggestion'])[2];

                return [
                    'address_no' => $addressNo,
                    'address1' => $item['suggestion'],
                    'address2' => $item['suggestion'],
                    'address' => $item['suggestion'],
                    'town_or_city' => $townOrCity,
                    'city' => $postCode,
                    'udprn' => $item['udprn'],
                    'post_code' => $this->postcode,
                    'country' => 'United Kingdom',
                ];
            })
            ->toArray();
    }
}
