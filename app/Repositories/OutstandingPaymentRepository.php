<?php

namespace App\Repositories;

use App\models\OutstandingPayment;
use App\Models\User;
use App\Filters\OutstandingPaymentFilter;

class OutstandingPaymentRepository
{

    public function __construct(protected OutstandingPaymentFilter $outstandingPaymentFilter)
    {
        
    }

    public function fetchOutstandingPayment($input)
    {
        
      
        $outstandingPayment =   OutstandingPayment::query();
       
        $select = ['id_outstanding','user_id','sender_name','receiver_name','transaction_id','currency_id','total_amount','amount_sent', 'local_amount', 'total_commission',
        'agent_commission','exchange_rate','bou_rate', 'sold_rate','transaction_code', 'commission_paid_status',
         'transaction_paid_status', 'created_at'];

       $outstandingPayment->filter($this->outstandingPaymentFilter)->select($select);


        $outstandingPayment->orderBy('created_at', 'DESC');

        $page = $input['page'] ?? 1;
        $limit = $input['limit'] ?? 15;

        return $outstandingPayment->paginate($limit, ['*'], 'page', $page);
    }

    public function toggleCurrencyStatus($currency)
    {
        // Toggle the currency status
        $statusUpdated = $currency->update(['currency_status' => $currency->currency_status == 1 ? 0 : 1]);
    
        // If update was successful, return the fresh instance of the model
        if ($statusUpdated) {
            return $currency->fresh();
        }
    
        // If the update failed, you might want to return null or handle the error as needed
        return null;
    }
    
    public function fetchUserCurrencyId(string $userId = null): ?string
    {
        if (!empty($userId)) {
            return User::where('id_user', $userId)->value('active_currency_id');
        }
        return Currency::where('default_currency', 1)->value('id_currency');
    }

}
