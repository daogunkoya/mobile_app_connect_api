<?php

namespace App\Http\Controllers;

use App\DTO\ReceiverDto;
use App\Models\Receiver;
use App\Models\Sender;
use Illuminate\Http\Request;
use App\Services\Receiver\ReceiverService;
use App\Facades\ReceiverServiceFacade;
use App\Http\Requests\Receivers\receiver_validation;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Http\Resources\ReceiverResource;
use Symfony\Component\HttpFoundation\Response;

class ReceiverController extends Controller
{


    public function __construct(public ReceiverService $receiverService)
    {
        $this->receiverService = $receiverService;
    }


    public function index(Request $request,  $senderId): JsonResponse
    {

        $receiverDataList = ReceiverServiceFacade::fetchReceiver($senderId);
        $receiverResource = ReceiverResource::collection(ReceiverDto::fromEloquentModelCollection($receiverDataList));
        return $receiverResource->response()->setStatusCode(Response::HTTP_OK);
    }


    public function store(receiver_validation $request, Sender $sender): JsonResponse
    {
        $sender->sender_id = $sender->id_sender;
        $receiver = $sender->receiver()->create($request->validated());
        return (new ReceiverResource($receiver))->response()->setStatusCode(Response::HTTP_CREATED);
    }


    public function update( Sender $sender, $receiverId, receiver_validation $request)
    {
        $receiver = Receiver::find($receiverId);
        $receiverUpdated = $receiver->update($request->validated());
        return (new ReceiverResource($receiver->fresh()))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function show(Request $request, $senderId, $receiverId): JsonResponse
    {
        $receiver = Receiver::find($receiverId);
        return (new ReceiverResource($receiver))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function destroy(Request $request, $receiverId): JsonResponse
    {
        $response = $this->receiverService->deleteReceiver($receiverId);
        return response()->json(ReceiverServiceFacade::deleteReceiver($receiverId), 204);
    }

}
