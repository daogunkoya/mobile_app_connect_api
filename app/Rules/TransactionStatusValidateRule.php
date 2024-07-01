<?php
namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Enum\TransactionStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\DataAwareRule;
use Closure;

class TransactionStatusValidateRule implements ValidationRule, DataAwareRule
{
    /**
     * All of the data under validation.
     *
     * @var array<string, mixed>
     */
    protected $data = [];

    protected string $enumClass;

    public function __construct(string $enumClass)
    {
        $this->enumClass = $enumClass;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            // Try casting to enum
           if(!$this->enumClass::getStatusEnumInstance($value)){
            $fail('status supplied is not valid.');
           }
           // return $enum instanceof $this->enumClass;
        } catch (\ValueError $e) {
            $fail('status supplied is not valid.');
        }
         catch (\InvalidArgumentException $e) {
            $fail('status supplied is not valid.');
        }
    }

    public function message()
    {
        return 'The :attribute is not a valid value for enum ' . $this->enumClass;
    }

    public function setData(array $data): static
    {
        $this->data = $data;
 
        return $this;
    }
}
