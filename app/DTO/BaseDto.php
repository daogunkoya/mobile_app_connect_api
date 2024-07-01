<?php

namespace App\DTO;

use Illuminate\Contracts\Support\Arrayable;
use ReflectionClass;

abstract class BaseDto implements Arrayable
{


    public function toArrayCollection(): array
    {
        if (method_exists($this, 'items')) {
            return $this->items->map(fn($item) => $item->toArray())->toArray();
        }

        throw new \InvalidArgumentException('Unsupported input type');
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

    // public static function toArrayCollection($input): array
    // {
    //     if ($input instanceof Collection || $input instanceof EloquentCollection || $input instanceof LengthAwarePaginator) {
    //         return $input->map(fn($item) => $item->toArray())->toArray();
    //     }

    //     throw new \InvalidArgumentException('Unsupported input type');
    // }
}
