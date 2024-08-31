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
use App\Enum\UserRoleType;

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
        $userRole = $this->resource['user']->userRoleType;
        return [
            'transactions' => new TransactionResourceCollection($this->resource['transactionDtoCollection']),
            'user' => new UserResource($this->resource['user']),
            'users' => $userRole == UserRoleType::ADMIN ?new UserResourceCollection($this->resource['userDtoCollection']): [],
            'senders' => $userRole == UserRoleType::AGENT ?
             new SenderResourceCollection($this->resource['senderDtoCollection']):null,
             'receivers' => $userRole == UserRoleType::CUSTOMER ? new ReceiverResourceCollection($this->resource['receiverDtoCollection']):null,
            'currencies' => new CurrencyResourceCollection($this->resource['currencyDtoCollection']),
            'rates' => new RateReSource($this->resource['rate']),
            'store' => new StoreResource($this->resource['store']),
        ];
    }
}
