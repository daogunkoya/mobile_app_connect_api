<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Interfaces\Auth\LoginServiceInterface;
use App\Interfaces\Auth\RegisterServiceInterface;
use App\Http\Requests\RegisterUserRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;


class AuthController extends Controller
{

    protected LoginServiceInterface $loginService;
    protected RegisterServiceInterface $registerService;


    public function __construct(LoginServiceInterface $loginService, RegisterServiceInterface $registerService)
    {
        $this->loginService = $loginService;
        $this->registerService = $registerService;
    }


    public function register(RegisterUserRequest $request): JsonResponse
    {

        //$credentials = $request->only('email', 'password');
        $input = $request->all();

        try {
            $result = $this->registerService->registerUser(
                $input['first_name'],
                $input['last_name'],
                $input['email'],
                $input['password']
            );
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        }

        return response()->json($result);
    }







    public function login(Request $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');

        try {
            $result = $this->loginService->loginUser($credentials);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        }
      
        return response()->json($result??"");
    }
}
