<?php

namespace App\Http\Controllers;

use App\Models\Sender;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repositories\UserRepository;
use App\DTO\UserDto;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use App\Enum\UserStatus;
use App\Models\StatusChangeLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Services\Log\LoggingService;
use App\Http\Requests\User\UserRequest;

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

    public function updateMemberStatus(Request $request, User $user, LoggingService $loggingService)
    {
        // Validate the request
        $validated = $request->validate([
          'status' => ['required', 'string', Rule::in(array_map(fn($status) => $status->label(), UserStatus::cases()))],
        ]);

        // Update the user's status
        $newStatus = UserStatus::getStatusEnumInstance($validated['status']);
        $user->status = $newStatus;
        $user->save();

        // Log the status change
       // $this->logStatusChange($user, $newStatus);

        $loggingService->logActivity($user, "User status changed to {$newStatus->label()}");

        return (new UserResource(UserDto::fromEloquentModel($user->fresh())))->response()->setStatusCode(Response::HTTP_OK);
        // return response()->json([
        //     'message' => 'User status updated successfully.',
        //     'user' => $user,
        // ], Response::HTTP_OK);
    }


    public function update(User $user, UserRequest $request): JsonResponse
    {
        return $user->update($request->mapToAttributes()) ?
            (new UserResource(UserDto::fromEloquentModel($user->fresh())))->response()->setStatusCode(Response::HTTP_OK)
           // response()->json([], Response::HTTP_OK)
            : response()->json(["error" => 'Something went wrong'], 400);

    }

    protected function logStatusChange(User $user, UserStatus $newStatus)
    {
        StatusChangeLog::create([
            'user_id' => Auth::id(),
            'loggable_type' => get_class($user),
            'loggable_id' => $user->getKey(),
            'status' => $newStatus->label(),
        ]);
    }
}
