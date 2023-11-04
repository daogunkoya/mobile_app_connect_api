<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Sender\SenderService;
use App\Http\Requests\Sender\sender_validation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use App\Repositories\SenderRepository;

class SenderController extends Controller
{
    public $senderService;
    public function __construct(SenderService $senderService, public SenderRepository $senderRepository)
    {
        $this->senderService = $senderService;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $list = $this->senderRepository->fetchSenders($request->all());
        return response()->json($list);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(sender_validation $request):JsonResponse
    {
        //
        $service = new SenderService();

        $sender_id = $service->createSender($request->all(), Auth::id());
        return response()->json(['agent_customer_id' => $sender_id]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(sender_validation $request, $sender_id)
    {

        if(!$sender_id || empty($sender_id)) {
            return response()->json(["sender_id"=>$sender_id], 400);
        }

       $response =  $this->senderService->updateSender($request->all(), Auth::id(), $sender_id);

        if($response){
            return response()->json(['agent_customer_id' => $sender_id]);
        }
        return response()->json(["sender_id"=>$sender_id], 400);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
