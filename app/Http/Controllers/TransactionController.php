<?php

namespace App\Http\Controllers;

use App\Repositories\TransactionRepository;
use Illuminate\Http\Request;
use App\Services\Transaction\TransactionService;
use App\Http\Requests\Transactions\calulate_validation;
use App\Http\Requests\Transactions\transaction_create_validation;
use App\Http\Requests\Transactions\transaction_update_validation;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct(public TransactionRepository $transactionRepository)
    {
    }

    public function index(TransactionService $transaction_service, Request $request)
    {
        //
        $transactionList = $this->transactionRepository->fetchTransaction(($request->all()));
        return response()->json($transactionList);
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
    public function store(transaction_create_validation $request, TransactionService $transaction_service)
    {
        [$message, $status] = $transaction_service->storeTransaction($request->all());
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
    public function update(transaction_update_validation $request, TransactionService $transaction_service, $transaction_id)
    {
        [$message, $status] = $transaction_service->updateTransaction($request->all(), $transaction_id);
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

    public function calculateTransaction(calulate_validation $request, TransactionService $transaction_service)
    {



            $input = $request->all();
            $res = $transaction_service->showAmountBreakdown($input);

            return response()->json($res);
    }
}
