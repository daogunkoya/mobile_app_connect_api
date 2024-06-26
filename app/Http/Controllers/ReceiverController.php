<?php

namespace App\Http\Controllers;

use App\DTO\ReceiverDto;
use App\Models\Receiver;
use App\Models\Sender;
use App\Models\User;
use App\DTO\UserDto;
use App\Enum\UserRoleType;
use Illuminate\Http\Request;
use App\Services\Receiver\ReceiverService;
use App\Facades\ReceiverServiceFacade;
use App\Http\Requests\Receivers\ReceiverValidation;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Http\Resources\ReceiverResource;
use App\Http\Resources\ErrorResource;
use Symfony\Component\HttpFoundation\Response;

class ReceiverController extends Controller
{


    public function __construct(public ReceiverService $receiverService)
    {
        $this->receiverService = $receiverService;
    }


    public function index(Request $request, $senderId): JsonResponse
    {

        $receiverDataList = ReceiverServiceFacade::fetchReceiver($senderId);
        //dd($receiverDataList);
        $receiverResource = ReceiverResource::collection(ReceiverDto::fromEloquentModelCollection($receiverDataList));
        return $receiverResource->response()->setStatusCode(Response::HTTP_OK);
    }


    public function store(ReceiverValidation $request,  $sender)
    {
        // Get validated data from the request
        $validatedData = $request->validated();
        $authenticatedUser = UserDto::fromEloquentModel(auth()->user());
        
        if($authenticatedUser->userRoleType == UserRoleType::AGENT){
            $sender = Sender::find($sender);
        }
        if ($sender instanceof Sender) {
            // If sender is an instance of Sender, proceed as usual
           
            $receiver = $sender->receiver()->create($validatedData);
        } else {
            $user = User::find($sender);
                    var_dump($sender);
            if ($user) {
                $userDto = UserDto::fromEloquentModel($user);
                $validatedData['sender_id'] = $userDto->userId;

                $receiver = $user->receivers()->create($validatedData);
            } else {
                return (new ErrorResource('User not found'))->response()->setStatusCode(Response::HTTP_NOT_FOUND);
            }
        }
    
        return (new ReceiverResource(
            ReceiverDto::fromEloquentModel($receiver->fresh())
        ))->response()->setStatusCode(Response::HTTP_CREATED);
    }
    



    public function update($senderId, Receiver $receiver, ReceiverValidation $request)
    {
        $receiver->update($request->validated());

        return (new ReceiverResource(
            ReceiverDto::fromEloquentModel($receiver->fresh())))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    public function show(Request $request, $senderId, $receiverId): JsonResponse
    {
        $receiver = Receiver::find($receiverId);
        return (new ReceiverResource(
            ReceiverDto::fromEloquentModel($receiver)))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    public function destroy(Request $request, $receiverId): JsonResponse
    {
        $response = $this->receiverService->deleteReceiver($receiverId);
        return response()->json(ReceiverServiceFacade::deleteReceiver($receiverId), 204);
    }

}
