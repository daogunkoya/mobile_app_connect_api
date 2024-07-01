<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\TransactionResource;
use App\Http\Resources\SenderResource;
use App\Http\Resources\CurrencyResource;
use App\Http\Resources\UserResource;
use App\DTO\TransactionDto;
use App\DTO\SenderDto;

class HomeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */

     public function __construct($resource)
     {
        parent::__construct($resource);
        
     }
    public function toArray(Request $request): array
    {
        return [
            'transactions' => new TransactionResourceCollection($this->resource['transactionDtoCollection']),
            'user' => new UserResource($this->resource['user']),
            'senders' => new SenderResourceCollection($this->resource['senderDtoCollection']),
            'currencies' => new CurrencyResourceCollection($this->resource['currencyDtoCollection']),
            'rates' => new RateReSource($this->resource['rate']),
        ];
    }
}
