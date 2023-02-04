<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Transaction\transaction_service;
use App\Http\Requests\Transactions\calulate_validation;
use App\Http\Requests\Transactions\transaction_create_validation;
use App\Http\Requests\Transactions\transaction_update_validation;

class transactions_controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(transaction_service $transaction_service, Request $request)
    
    {
        //
        [$content, $status] = $transaction_service->fetch_transaction(($request->all()));
        return response()->json($content, $status);
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
    public function store(transaction_create_validation $request, transaction_service $transaction_service)
    {
        [$message, $status] = $transaction_service->store_transaction($request->all());
        return response()->json($message, $status);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(transaction_update_validation $request, transaction_service $transaction_service, $transaction_id)
    {
        [$message, $status] = $transaction_service->update_transaction($request->all(), $transaction_id);
        return response()->json($message, $status);
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

    public function calculate_transaction(calulate_validation $request, transaction_service $transaction_service){

       

            $input = $request->all();
            $res = $transaction_service->show_amount_breakdown($input);

            return response()->json($res);

        

       
           

    }
}
