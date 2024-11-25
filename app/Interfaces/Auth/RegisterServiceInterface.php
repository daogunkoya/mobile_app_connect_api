<?php

namespace App\Interfaces\Auth;

use Laravel\Passport\PersonalAccessTokenResult;

use App\Models\User;

//use Laravel\Sanctum\NewAccessToken;

interface RegisterServiceInterface
{
   // public function registerUser(string $first_name, string $last_name, string $email, string $password): PersonalAccessTokenResult;
    public function registerUser(array $validatedInput): User;
}
