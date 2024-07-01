<?php

namespace App\Services\Contracts;

interface AuthContract
{
    public function register($request);
    public function respondWithToken($token, $user_id, $user_redirect);
    public function login($request, $user_redirect);
    public function me();
    public function logout($request, $token);
    public function refresh();
    public function username();
}
