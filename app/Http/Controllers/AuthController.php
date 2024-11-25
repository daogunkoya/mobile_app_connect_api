<?php

namespace App\Http\Controllers;

use App\Http\Resources\AuthResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Interfaces\Auth\LoginServiceInterface;
use App\Interfaces\Auth\RegisterServiceInterface;
use App\Http\Requests\RegisterUserRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use App\DTO\UserDto;
use App\Permissions\Abilities;

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
            $userData = $this->registerService->registerUser($request->mapToAttributes());
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        }
        $user = UserDto::fromEloquentModel($userData);
        $token = $userData->createToken('auth_token', Abilities::getAbilities($user));
//return $token;
        return (new AuthResource(compact('token', 'user')))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }



    public function login(Request $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');

        try {
            $token = $this->loginService->loginUser($credentials);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        }

      //  return response()->json($result ?? "");
      $user = UserDto::fromEloquentModel(auth()->user());
        return (new AuthResource(compact('token', 'user')))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    public function test(Request $request)
    {
        return request()->user()->toArray();
    }
}
