<?php

namespace App\Http\Controllers;

use App\DTO\SenderDto;
use App\Models\Sender;
use Illuminate\Http\Request;
use App\Services\Sender\SenderService;
use App\Http\Requests\Sender\sender_validation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use App\Repositories\SenderRepository;
use App\Facades\SenderServiceFacade;
use App\Http\Resources\SenderResource;
use Symfony\Component\HttpFoundation\Response;

class SenderController extends Controller
{

    public function __construct(public SenderService $senderService, public SenderRepository $senderRepository)
    {

    }

    public function index(Request $request)
    {
   //return  auth()->user()->id_user;
        $senderData = SenderServiceFacade::fetchSenders($request->all());

    return  SenderResource::collection(
            SenderDto::fromEloquentModelCollection($senderData))->response()->setStatusCode(Response::HTTP_OK);

//        return $senderResource->additional([
//            'current_page' => $senderData['current_page'] ?? 1,
//            'last_page' => $senderData->last_page ?? 1,
//            'total' => $senderData->total ?? 0,
//            'per_page' => $senderData->per_page ?? 1
//        ])->response();

    }


    public function store(sender_validation $request): JsonResponse
    {

        $sender = auth()->user()->sender()->create($request->validated());
        return (new SenderResource($sender))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    public function update(Sender $sender, sender_validation $request): JsonResponse
    {
        return $sender->update($request->validated()) ?
            response()->json([], Response::HTTP_OK)
            : response()->json(["error" => 'Something went wrong'], 400);

    }

    public function show($senderId): JsonResponse
    {
            return (new SenderResource
            (SenderDto::fromEloquentModel( Sender::find($senderId ))))
                ->response()
                ->setStatusCode(Response::HTTP_OK);
        }

    public function destroy($senderId): JsonResponse
    {
        $this->senderService->deleteSender($senderId);
        return response()->json([], 204);
    }
}
