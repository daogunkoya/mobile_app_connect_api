<?php

namespace App\DTO;

use App\Models\Bank;
use App\Models\Sender;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use ReflectionClass;
use Illuminate\Pagination\LengthAwarePaginator;


class BankDto implements Arrayable
{
    public function __construct(
        public string $id,
        public string $name,
        public string $createdAt,
        public string $bankCategory,
        public CurrencyDto | null $currency,

    )
    {
    }

    public static function fromEloquentModel(Bank $bank): BankDto
    {
        return new self(
            $bank->id,
            $bank->name,
            $bank->created_at,
            $bank->bank_category,
            $bank->currency ? CurrencyDto::fromEloquentModel($bank->currency) : null, 
        );
    }

    public static function fromEloquentCollection(LengthAwarePaginator $banks): LengthAwarePaginator
    {

       
        if ($banks instanceof LengthAwarePaginator) {
            $mappedItems = collect($banks->items())
                ->map(fn(bank $bank) => self::fromEloquentModel($bank));
    
            return new LengthAwarePaginator(
                $mappedItems,
                $banks->total(),
                $banks->perPage(),
                $banks->currentPage(),
                ['path' => LengthAwarePaginator::resolveCurrentPath()]
            );
        }

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
