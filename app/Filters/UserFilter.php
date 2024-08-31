<?php

namespace App\Filters;

use Illuminate\Http\Request;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Carbon\Carbon;
use App\Enum\UserRoleType;
use App\Filters\BaseQuery;  
use App\Enum\UserStatus;

class UserFilter extends BaseQuery
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

    public function status($userStatus)
    {
         // ->when($filter['status'] ?? false, fn($query) => $query->where('user_status', Status::getStatusEnumInstance($filter['status'])))  
         $this->builder->when($userStatus??false, fn($builder) => $builder->where('user_status', Status::getStatusEnumInstance($userStatus)));
        return $this->builder;
    }

    public function type($userRoleType)
    {
        // Remove spaces and unwanted characters from the input
        $cleanedInput = preg_replace('/[^a-zA-Z0-9_,]/', '', $userRoleType);
        
        // Split the cleaned input by commas and filter out any empty strings
        $categories = collect(explode(',', $cleanedInput))->filter();
    
        // Map each category to its corresponding enum instance
        $listCategories = $categories->map(fn($category) => UserRoleType::getRoleTypeEnumInstance($category));
    
        // Apply the query condition if the list of categories is not empty
        $this->builder->when($listCategories->isNotEmpty(), fn($builder) => $builder->whereIn('user_role_type', $listCategories));
    
        return $this->builder;
    }
    
    
            


    public function userId($userId)
    {
        $this->builder->when($userId ?? false, fn($builder) => $builder->where('id_user', $userId));
        return $this->builder;
    }
    

 

    public function search($value)
    {
        return $this->builder->when($value ?? false, fn($builder) => 
                $builder->where('first_name', 'like', '%' . $value . '%')
                      ->orWhere('last_name', 'like', '%' . $value . '%')
            
        );
    
}

}
