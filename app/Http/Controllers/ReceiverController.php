<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Receiver\ReceiverService;
use App\Facades\ReceiverServiceFacade;
use App\Http\Requests\Receivers\receiver_validation;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Http\Resources\ReceiverResource;

class ReceiverController extends Controller
{


    public function __construct(public ReceiverService $receiverService)
    {
        $this->receiverService = $receiverService;
    }


    public function index(Request $request, $customer_id): JsonResponse
    {
        $receiverList = ReceiverServiceFacade::fetchReceiver($request->all(), $customer_id);

        return response()->json([
            'receiver_count' =>  $receiverList['receiver_count'],
            'receiver' =>  ReceiverResource::collection($receiverList['receiver']),
            'current_page' =>  $receiverList['current_page'],
            'last_page' =>  $receiverList['last_page'],
            'total' =>  $receiverList['total'],
            'per_page' =>  $receiverList['per_page'],
            'banks_id_list'=> $receiverList['banks_id_list']
        ]);
    }


    public function store(receiver_validation $request, $id): JsonResponse
    {
        $receiver_id = ReceiverServiceFacade::createReceiver($request->all(), $id);
        return response()->json(['receiver_id' => $receiver_id], 201);
    }


    public function update(receiver_validation $request, $customerId, $receiverId): JsonResponse
    {
        $response = $this->receiverService->updateReceiver($request->all(), $receiverId);
        return response()->json(['receiverId' => $receiverId]);
    }

    public function show(Request $request, $senderId, $receiverId): JsonResponse
    {
        $receiver = ReceiverServiceFacade::showReceiver($receiverId);
        return response()->json( new ReceiverResource(($receiver)));
    }

    public function destroy(Request $request, $receiverId): JsonResponse
    {
        $response = $this->receiverService->deleteReceiver($receiverId);
        return response()->json(ReceiverServiceFacade::deleteReceiver($receiverId), 204);
    }

}
