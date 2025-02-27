<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Exceptions\InvalidRequestException;
use App\Http\Requests\RegisterUserRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Interfaces\Auth\RegisterServiceInterface;
use Illuminate\Validation\ValidationException;
use Laravel\Passport\PersonalAccessTokenResult;

//use Laravel\Sanctum\NewAccessToken;

class RegisterUserService implements RegisterServiceInterface
{
    //public function registerUser(array $validatedInput): PersonalAccessTokenResult
    public function registerUser(array $validatedInput): User
    {
        //var_dump($validatedInput);
        $user = User::where('email', $validatedInput['email'])->first();

        if ($user) {
            //throw new InvalidRequestException('Email address already exists');
            throw ValidationException::withMessages([
                'email | name | password' => __('auth.failed'),
            ]);
        }

        // $user = User::create([
        //     'first_name' => $first_name,
        //     'last_name' => $last_name,
        //     'email' => $email,
        //     'password' => Hash::make($password),
        //     'store_id' => '$2y$10$VpVDzy8gIINv1fWRGHpAx.7e/Y3XSruR8OtUn3qUrjM9x9VGo5rIS'
        // ]);
        $user = User::create($validatedInput);
        Auth::attempt(['email' => $validatedInput['email'], 'password'=> $validatedInput['password']]);
       // $user->auth();
        // $token = $user->createToken('auth_token')->plainTextToken;
        return $user;
//        $token = $tokenResult->accessToken;
//        return $tokenResult;$tokenResult
    }
}
