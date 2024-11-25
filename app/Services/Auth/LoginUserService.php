<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Interfaces\Auth\LoginServiceInterface;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\PersonalAccessTokenResult;
use Illuminate\Validation\ValidationException;
use App\Permissions\Abilities;
use App\DTO\UserDto;

class LoginUserService implements LoginServiceInterface
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<int,  mixed>
     * @param array<string,mixed> $credentials The credentials used to log in the user.
     */


    public function loginUser(array $credentials): PersonalAccessTokenResult
    {

        if (!Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        $user = Auth::user();

        if (!$user instanceof User) {
            return [];
        }
        $userDto = UserDto::fromEloquentModel($user);
       // var_dump(Abilities::getAbilities($userDto));
       
        return $user->createToken('auth_token', Abilities::getAbilities($userDto));

       // $res = ['token'=>$token, 'user' => $user];
//        $res = $this->authResponse($token, $user);
//        return $res;
    }



    public function authResponse($token, $user): array
    {

        $now = \Carbon\Carbon::now();

        // $store_data=$this->store_data($user_id);
        //  $response = array_merge($response_token, $store_data);

        return
            ['user_id' => $user['id_user'],
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
            'rate' => 980,
                'total_sent' => 12,
                'count_total_sent'=>23.00,
                'total_pending' => 123,
                'total_paid' => 340,
                'count_total_paid' => 12,

            'access_token' => $token->accessToken,
            'token_type' => 'bearer',
            'abilities' => Abilities::getAbilities($user),
            'expires_in' => $now
                ];
    }

        //fetch store related data
    // public function store_data($user_id)
    // {

    //     // $user_id = user_id()??'';
    //     // var_dump($user_id);
    //     $rate = rate_service::todays_rate();
    //     $today_rate = $rate['main_rate'] ?? 0;

    //     $total_sent = mm_transaction::where('user_id', $user_id)->where('transaction_status', 1)->sum('total_amount');
    //     $total_pending = mm_transaction::where('user_id', $user_id)->where('transaction_status', 1)->where('moderation_status', 1)->sum('total_amount');
    //     $total_paid = mm_transaction::where('user_id', $user_id)->where('transaction_status', 1)->where('moderation_status', 2)->sum('total_amount');
    //     $count_total_sent = mm_transaction::where('user_id', $user_id)->where('transaction_status', 1)->count();
    //     $count_total_pending = mm_transaction::where('user_id', $user_id)->where('transaction_status', 1)->where('moderation_status', 1)->count();
    //     $count_total_paid = mm_transaction::where('user_id', $user_id)->where('transaction_status', 1)->where('moderation_status', 2)->count();
    //     $count_sender = mm_sender::where('user_id', $user_id)->count();
    //     $count_receiver = mm_receiver::where('user_id', $user_id)->count();


    //     return [
    //         'store_name' => store_name(),
    //         'store_url' => session()->get('process_store_url'),
    //         'rate' => $today_rate,
    //         'total_pending' => number_format($total_pending, 2),
    //         'total_paid' => number_format($total_paid, 2),
    //         'total_sent' => number_format($total_sent, 2),
    //         'count_total_sent' => $count_total_sent,
    //         'count_total_pending' => $count_total_pending,
    //         'count_total_paid' => $count_total_paid,
    //         'count_total_sender' => $count_sender,
    //         'count_total_receiver' => $count_receiver,

    //     ];
    // }
}
