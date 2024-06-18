<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HomeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'transactions' => $this->transactions,
            'senders' => $this->senders,
            'currencies' => $this->currencies,
            'rates' => $this->rate
        ];
    }
}
