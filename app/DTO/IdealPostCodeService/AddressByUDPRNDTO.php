<?php

namespace App\DTO\IdealPostCodeService;

class AddressByUDPRNDTO
{
    public function __construct(public readonly array $data)
    {
    }

    public function getReadableData(): array
    {
        return collect(data_get($this->data, 'result'), [])
            ->only(['postcode', 'line_1', 'line_2', 'line_3', 'uprn'])
            ->toArray();
    }
}
