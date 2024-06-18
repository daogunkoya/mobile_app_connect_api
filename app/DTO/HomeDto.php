<?php

namespace App\DTO;

use App\Models\Bank;
use App\Models\Sender;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use ReflectionClass;
use App\DTO\RateDto;
use App\DTO\CurrencyDto;
use Illuminate\Pagination\LengthAwarePaginator;


class HomeDto implements Arrayable
{
    public function __construct(
        public LengthAwarePaginator $transactions,
        public LengthAwarePaginator $senders,
        public RateDto $rate,
        public Collection $currencies,

    )
    {
    }

    public static function fromEloquentModel($transactions, $senders, $rate, $currencies): self
    {
        return new self(
            $transactions,
            $senders,
            $rate,
            $currencies
        );
    }

    public function toArray(): array
    {
        $reflectionClass = new ReflectionClass($this);
        $properties = $reflectionClass->getProperties();
        $array = [];

        foreach ($properties as $property) {
            $propertyName = $property->getName();
            $propertyValue = $this->{$propertyName};

            // Check if the property is an object and has a toArray method
            if (is_object($propertyValue) && method_exists($propertyValue, 'toArray')) {
                $array[$propertyName] = $propertyValue->toArray();
            } else {
                $array[$propertyName] = $propertyValue;
            }
        }

        return $array;
    }


}
