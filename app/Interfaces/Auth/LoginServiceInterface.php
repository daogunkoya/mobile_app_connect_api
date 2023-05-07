<?php
namespace App\Interfaces\Auth;
use Laravel\Passport\PersonalAccessTokenResult;
interface LoginServiceInterface
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<int,  mixed>
     *  @param array<string,mixed> $credentials The credentials used to log in the user.
     */
    public function loginUser(array $credentials):array;
}