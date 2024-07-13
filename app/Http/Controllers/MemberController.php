<?php

namespace App\Http\Controllers;

use App\Models\Sender;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repositories\UserRepository;
use App\DTO\UserDto;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;

class MemberController extends Controller
{

    public function __construct(private UserRepository $userRepository)
    {
    }

    public function index(Request $request): JsonResponse
    {
        $user = UserDto::fromEloquentModel(auth()->user());
        [
            $usersData, 
            $totalUsers
            ] = $usersData = $this->userRepository->fetchUsers($request->all(), $user);

        return  UserResource::collection(
            userDto::fromEloquentModelCollection($usersData)
        )->response()->setStatusCode(Response::HTTP_OK);
    }
}
