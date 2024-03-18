<?php

namespace App\Http\Controllers;

use App\Actions\CreateTransaction;
use App\Collections\TransactionCollection;
use App\DTO\CommissionDto;
use App\DTO\RateDto;
use App\DTO\ReceiverDto;
use App\DTO\TransactionDto;
use App\DTO\UserDto;
use App\Http\Resources\TransactionResource;
use App\Models\Commission;
use App\Models\Rate;
use App\Models\Receiver;
use App\Models\Transaction;
use App\Repositories\CommissionRepository;
use App\Repositories\RateRepository;
use App\Repositories\TransactionRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\Transaction\TransactionService;
use App\Http\Requests\Transactions\calulate_validation;
use App\Http\Requests\Transactions\TransactionCreateValidation;
use App\Http\Requests\Transactions\transaction_update_validation;
use Symfony\Component\HttpFoundation\Response;


class TransactionController extends Controller
{

    public function __construct(public TransactionRepository $transactionRepository,
    public CreateTransaction $createTransaction
    )
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
    public function store(TransactionCreateValidation $request, TransactionService $transaction_service)
    {
        $validated = $request->validated();

        $user = UserDto::fromEloquentModel(auth()->user());

        $userCommission = CommissionRepository::getCommissionValue(request('amountSent', $user->userId));
        $userRate = RateRepository::fetchTodaysRate($user->userId);

        // Process transaction
        $transactionCollection = TransactionCollection::processTransactionData(
            RateDto::fromEloquentModel(($userRate)),
            CommissionDto::fromEloquentModel($userCommission),
            $validated['amount_sent'],
            $validated['conversion_type']);

        $receiver = ReceiverDto::fromEloquentModel(Receiver::with('sender')->find(request('receiver_id')));
        $transaction = $this->createTransaction->handle( $transactionCollection , $receiver,  $user );

        return (new TransactionResource( TransactionDto::fromEloquentModel($transaction)))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
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
