<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Currency;
use App\DTO\CurrencyDTO;

class AuthResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [



            'user' => [
                'user_id' => auth()->user()->id_user,
                'user_name' => auth()->user()->user_name,
                'user_role_type' => 1,
                'user_email' => auth()->user()->email,
                'user_phone' => "0987657",
                'user_handle' => null,
                'user_dob' => '12/12/2020',
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
                'total_order_quantity' => 0,
            ],
            'store' => [
                'store_id' => store_id(),
                'store_name' => store_name(),
                'store_url' => store_url(),
                'store_version' => 1,
            ],
            // 'user_currencies' => ['USD', 'EUR', 'GBP', 'JPY'],
            // 'available_currencies' => CurrencyDTO::fromEloquentCollection(Currency::all()),
            'access_status' => 1,
            'available_currencies' => '',
            'access_token' => $this->accessToken,
            'token_type' => 'bearer',
            'expires_in' => ''
        ];
    }
}
