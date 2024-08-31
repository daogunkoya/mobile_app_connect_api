<?php

namespace App\Filters;

use Illuminate\Http\Request;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Carbon\Carbon;
use App\Enum\UserRoleType;
use App\Filters\BaseQuery;  

class BankFilter extends BaseQuery
{
    protected $isAdmin = false;

    public function __construct(Request $request)
    {
        parent::__construct($request);
    }



    public function currencyId($currencyId)
    {
         $this->builder->when($currencyId??false, fn($builder) => $builder->where('currency_id', $currencyId));
        return $this->builder;
    }
            


    public function userId($userId)
    {
        $this->builder->when($userId ?? false, fn($builder) => $builder->where('user_id', $userId));
        return $this->builder;
    }
    

 

    public function search($value)
    {
        return $this->builder->when($value ?? false, fn($builder) => 
                $builder->where('name', 'like', '%' . $value . '%')
            
        );
    
}

}
