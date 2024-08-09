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
        public string $createdAt,
        public string $commissionId,
        public string $userId,
        public ?string $memberUserId,
        public ?string $currencyId,
        public string $startFrom,
        public ?string $endAt,
        public ?string $value,
        public ?string $agentQuota,
        public CurrencyDto | null $currency, 
        public UserDto | null $memberUser

    )
    {
    }

    public static function fromEloquentModel(Commission $commission): self
    {
        return new self(
            $commission->created_at,
            $commission->id_commission,
            $commission->user_id,
            $commission->member_user_id,
            $commission->currency_id,
            $commission->start_from,
            $commission->end_at,
            $commission->value,
            $commission->agent_quota,
            $commission->currency ? CurrencyDto::fromEloquentModel($commission->currency) : null, 
            $commission->member_user? UserDto::fromEloquentModel($commission->member_user) : null

        );
    }

    public static function fromEloquentCollection(LengthAwarePaginator $commissions): LengthAwarePaginator
    {

        //return $commissions->map(fn (Currency $currency) => self::fromEloquentModel($currency));
        if ($commissions instanceof LengthAwarePaginator) {
            $mappedItems = collect($commissions->items())
                ->map(fn(Commission $commission) => self::fromEloquentModel($commission));
    
            return new LengthAwarePaginator(
                $mappedItems,
                $commissions->total(),
                $commissions->perPage(),
                $commissions->currentPage(),
                ['path' => LengthAwarePaginator::resolveCurrentPath()]
            );
        }
    }



}
