<?php

namespace App\DTO;

use App\Models\AcceptableIdentity;
use App\Models\Bank;
use App\Models\Commission;
use App\Models\Rate;
use App\Models\Receiver;
use App\Models\Sender;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;


class CommissionDto
{
    public function __construct(
        public string $commissionId,
        public string $userId,
        public ?string $currencyId,
        public string $startFrom,
        public ?string $endAt,
        public ?string $value,
        public ?string $agentQuota,

    )
    {
    }

    public static function fromEloquentModel(Commission $commission): self
    {
        return new self(
            $commission->id_commission,
            $commission->user_id,
            $commission->currency_id,
            $commission->start_from,
            $commission->end_at,
            $commission->value,
            $commission->agent_quota,

        );
    }



}
