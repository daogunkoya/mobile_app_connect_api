<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RomanNumeralResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'integer_value' => $this->integer_value,
            'roman_numeral' => $this->roman_numeral,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
