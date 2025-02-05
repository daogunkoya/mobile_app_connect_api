<?php

namespace App\Filters;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\DTO\UserDto;
use App\Enum\UserRoleType;

abstract class BaseQuery
{
    protected Builder $builder;
    protected array $filters = [];
    // protected bool $isAdmin = false;

    public function __construct(
        protected Request $request
    ) {}


    public function isUserAdmin(): bool
    {
        $user = UserDto::fromEloquentModel(auth()->user());
       
        return $user->userRoleType == UserRoleType::ADMIN;  
    }

    public function filter(array $filters): Builder
    {
        foreach ($filters as $filter => $value) {
            if (method_exists($this, $filter)) {
                $this->$filter($value);
            }
        }

        return $this->builder;
    }

    public function apply(Builder $builder): Builder
    {
        $this->builder = $builder;
        foreach ($this->request->all() as $key => $value) {
            $camelKey = $this->snakeToCamel($key);
            if (method_exists($this, $camelKey)) {
                $this->$camelKey($value);
            }
        }
        return $this->builder;
    }

    private function snakeToCamel(string $value): string
    {
        return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $value))));
    }

    public function date($value)
    {
        return $this->builder->when($value ?? false, function ($builder, $search) use ($value) {
            $searchDate = $value;
            $builder->when($searchDate, function ($builder, $date) {
                return $builder->where(fn($builder) => match ($date) {
                    'today' => $builder->whereDate('created_at', today()),
                    'yesterday' => $builder->whereDate('created_at', today()->subDay()),
                    'week' => $builder->whereBetween('created_at', [now()->subDays(7)->startOfDay(), now()->endOfDay()]),
                    'month' => $builder->whereBetween('created_at', [now()->subDays(30)->startOfDay(), now()->endOfDay()]),
                    default => $builder->whereBetween('created_at', [
                        Carbon::createFromFormat('d/m/Y', $date['from'])->startOfDay(),
                        Carbon::createFromFormat('d/m/Y', $date['to'])->endOfDay(),
                    ]),
                });
            });
        });
    }
}
