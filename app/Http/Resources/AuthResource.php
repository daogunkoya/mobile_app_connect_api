<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'user_id' => auth()->user()->id_user,
            'user_role_type' => 1,
            'user_handle' => null,
            'user_name' => auth()->user()->user_name,
            'user_status' => 1,
            'user_image_url' => null,
            'user_access_type' => 1,
            'access_status' => 1,
            'notification_status' => 1,
            'total_order_quantity' => 0,
            'store_url' => store_url(),
            'store_name' => store_name(),
            'store_version' => 1,
            'rate' => 980,
            'total_sent' => 12,
            'count_total_sent' => 23.00,
            'total_pending' => 123,
            'total_paid' => 340,
            'count_total_paid' => 12,
            'access_token' => $this->accessToken,
            'token_type' => 'bearer',
            'expires_in' => ''
        ];
    }
}
