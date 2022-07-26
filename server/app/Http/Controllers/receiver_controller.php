<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Receiver\receiver_service;
use App\Http\Requests\Receivers\receiver_validation;

class receiver_controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $customer_id)
    {
        //
        $service = new receiver_service();
        $list = $service->fetch_receiver($request, $customer_id);
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
        $service = new receiver_service();
       
        $receiver_id = $service->create_receiver($request->all(), $id);
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
        $service = new receiver_service();
       
        $receiver_id = $service->update_receiver($request->all(), $receiver_id);
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