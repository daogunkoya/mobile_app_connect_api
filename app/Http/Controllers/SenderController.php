<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Sender\SenderService;
use App\Http\Requests\Sender\sender_validation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use App\Repositories\SenderRepository;
use App\Facades\SenderServiceFacade;
use App\Http\Resources\SenderResource;

class SenderController extends Controller
{

    public function __construct(public SenderService $senderService, public SenderRepository $senderRepository)
    {

    }


    public function index(Request $request): jsonResponse
    {

        $senderData = SenderServiceFacade::fetchSenders($request->all());

        return response()->json([
            'sender_count' => $senderData['sender_count'],
            'sender' => SenderResource::collection($senderData['sender']),
           // 'senders' =>$senderData['sender'],
            'current_page' => $senderData['current_page'],
            'last_page' => $senderData['last_page'],
            'total' => $senderData['total'],
            'per_page' => $senderData['per_page'],
        ]);
    }


    public function store(sender_validation $request): JsonResponse
    {

        $sender_id = SenderServiceFacade::createSender($request->all(), Auth::id());
        return response()->json(['agent_customer_id' => $sender_id], 201);
    }


    public function update(sender_validation $request, $sender_id): JsonResponse
    {

        if (!$sender_id || empty($sender_id)) {
            return response()->json(["sender_id" => $sender_id], 400);
        }

        $response = $this->senderService->updateSender($request->all(), Auth::id(), $sender_id);

        if ($response) {
            return response()->json(['agent_customer_id' => $sender_id]);
        }
        return response()->json(["sender_id" => $sender_id], 400);

    }

    public function show($senderId): JsonResponse
    {
        $sender = $this->senderService->showSender($senderId);
        return response()->json(new SenderResource($sender), 200);
    }

    public function destroy($senderId): JsonResponse
    {
        $this->senderService->deleteSender($senderId);
        return response()->json([], 204);
    }
}
