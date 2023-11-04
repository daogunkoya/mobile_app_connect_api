<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AuthResource extends JsonResource
{
    public function authResponse($token, $user): array
    {


        return  [
            'user_id' => $user['id_user'],
            'user_role_type' => 1,
            'user_handle' => null,
            'user_name' => $user['user_name'],
            'user_status' => 1,
            'user_image_url' => null,
            'user_access_type' => 1,
            'access_status' => 1,
            'notification_status' => 1,
            'total_order_quantity' => 0,
            'store_url' => store_url(),
            'store_name' => store_name(),
            'store_version' => 1,

            'access_token' => $token['accessToken'],
            'token_type' => 'bearer',
            'expires_in' => $token['expires_at']
        ];
    }
}
