<?php

namespace App\Filters;

use Illuminate\Http\Request;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Carbon\Carbon;
use App\Enum\UserRoleType;
use App\Filters\BaseQuery;  

class CurrencyFilter extends BaseQuery
{
    protected $isAdmin = false;

    public function __construct(Request $request)
    {
        parent::__construct($request);
    }



    public function currencyId($currencyId)
    {
         $this->builder->when($currencyId??false, fn($builder) => $builder->where('id_currency', $currencyId));
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
                $builder->where('sender_fname', 'like', '%' . $value . '%')
                      ->orWhere('sender_lname', 'like', '%' . $value . '%')
            
        );
    
}

}
