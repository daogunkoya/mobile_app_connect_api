<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Currency;
use App\DTO\CurrencyDTO;

class AuthResource extends JsonResource
{

    public function __construct($resource)
     {
        parent::__construct($resource);
        
     }

    public function toArray(Request $request): array
    {

        $user = $this->resource['user'];

        return [



            'user' => [
                'user_id' => $user->userId,
                'user_name' => $user->userName,
                'user_role_type' => $user->userRoleType->label(),
                'user_email' => $user->email,
                'user_phone' => "0987657",
                'user_dob' => '12/12/2020',
                'user_default_currency' => $user->currencyId,
                'user_postcode' => 'Nw10 3er',
                'user_address1' => '22 wil street',
                'user_address2' => 'London',
                'user_city' => 'England',
                'user_country' => 'London',
                'user_status' => 1,
                'user_image_url' => null,
                'user_access_type' => 1,
                'rate' => 980,
                'total_sent' => 12,
                'count_total_sent' => 23.00,
                'total_pending' => 123,
                'total_paid' => 340,
                'count_total_paid' => 12,
                'notification_status' => 1,
            ],
            'store' => [
                'store_id' => store_id(),
                'store_name' => store_name(),
                'store_url' => store_url(),
            ],
            // 'user_currencies' => ['USD', 'EUR', 'GBP', 'JPY'],
            // 'available_currencies' => CurrencyDTO::fromEloquentCollection(Currency::all()),
            'access_status' => 1,
            'access_token' => $this->resource['token']->accessToken,
            'token_type' => 'bearer',
            'expires_in' => ''
        ];
    }
}
