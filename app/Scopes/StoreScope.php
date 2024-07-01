<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class StoreScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        // Modify the query to include the store_id condition
        $builder->where('store_id', store_id()); // Replace $store_id with the actual value
    }
}
