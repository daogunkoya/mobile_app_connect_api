<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Receiver\ReceiverService;
use App\Http\Requests\Receivers\receiver_validation;

class ReceiverController extends Controller
{

    public $receiverService;
    public function __construct(ReceiverService $receiverService)
    {
        $this->receiverService = $receiverService;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $customer_id)
    {
        //

        $list = $this->receiverService->fetchReceiver($request, $customer_id);
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
    public function store(receiver_validation $request, $id)
    {
        //


        $receiver_id = $this->receiverService->createReceiver($request->all(), $id);
        return response()->json(['receiver_id' => $receiver_id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($receiver_id)
    {

    }


    public function update(receiver_validation $request, $customer_id, $receiver_id)
    {


        $response = $this->receiverService->updateReceiver($request->all(), $receiver_id);
        return response()->json(['receiver_id' => $receiver_id]);
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
