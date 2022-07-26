<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Agent_Customer\agent_customer_service;
use App\Http\Requests\Agents\agent_customer_validation;

class agent_customer_controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $service = new agent_customer_service();
        $list = $service->fetch_agent_customer($request);
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
    public function store(agent_customer_validation $request)
    {
        //
        $service = new agent_customer_service();
       
        $agent_customer_id = $service->create_customer($request->all(), $request->user->id_user);
        return response()->json(['agent_customer_id' => $agent_customer_id]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(agent_customer_validation $request, $customer_id)
    {
        $service = new agent_customer_service();
       
        $agent_customer_id = $service->update_customer($request->all(), $request->user->id_user, $customer_id);
        return response()->json(['agent_customer_id' => $agent_customer_id]);
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