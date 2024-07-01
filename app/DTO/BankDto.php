<?php

namespace App\DTO;

use App\Models\Bank;
use App\Models\Sender;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use ReflectionClass;


class BankDto implements Arrayable
{
    public function __construct(
        public string $id,
        public string $name,

    )
    {
    }

    public static function fromEloquentModel(Bank $bank): BankDto
    {
        return new self(
            $bank->id,
            $bank->name,
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
