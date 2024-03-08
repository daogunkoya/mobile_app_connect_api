<?php

namespace App\Http\Controllers;

use App\DTO\TransactionDto;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use App\Repositories\TransactionRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\Transaction\TransactionService;
use App\Http\Requests\Transactions\calulate_validation;
use App\Http\Requests\Transactions\transaction_create_validation;
use App\Http\Requests\Transactions\transaction_update_validation;
use Symfony\Component\HttpFoundation\Response;


class TransactionController extends Controller
{

    public function __construct(public TransactionRepository $transactionRepository)
    {
    }

    public function index(TransactionService $transaction_service, Request $request):JsonResponse
    {


        $transactionList = $this->transactionRepository->fetchTransaction($request->all(), auth()->user());
        $totalTransaction = $this->transactionRepository->calculateTotalAmount($request->all(), auth()->user());

        return (TransactionResource::collection(
            TransactionDto::fromEloquentModelCollection($transactionList)))
            ->additional(['total_transactions' => $totalTransaction])
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(transaction_create_validation $request, TransactionService $transaction_service)
    {
        [$message, $status] = $this->transactionRepository->storeTransaction($request->all());
        return response()->json($message, $status);
    }


    public function show(Transaction $transaction)
    {
        return (new TransactionResource( TransactionDto::fromEloquentModel($transaction)))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(transaction_update_validation $request, $transaction_id)
    {
        [$message, $status] = $this->transactionRepository->updateTransaction($request->all(), $transaction_id);
        return response()->json($message, $status);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
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
