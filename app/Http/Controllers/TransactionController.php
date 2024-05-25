<?php

namespace App\Http\Controllers;

use App\Exceptions\CommissionNotSetException;
use App\Actions\CreateTransaction;
use App\Collections\TransactionCollection;
use App\DTO\CommissionDto;
use App\DTO\RateDto;
use App\DTO\ReceiverDto;
use App\DTO\TransactionDto;
use App\DTO\UserDto;
use App\Exceptions\RateNotSetException;
use App\Http\Requests\TransactionRequest as RequestsTransactionRequest;
use App\Http\Resources\TransactionResource;
use App\Models\Commission;
use App\Models\Rate;
use App\Models\Receiver;
use App\Models\Transaction;
use App\Payment\PaymentGateway;
use App\Repositories\CommissionRepository;
use App\Repositories\RateRepository;
use App\Repositories\TransactionRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\Transaction\TransactionService;
use App\Http\Requests\Transactions\TransactionRequest;
use App\Http\Requests\Transactions\calulate_validation;
use App\Http\Requests\Transactions\TransactionCreateValidation;
use App\Http\Requests\Transactions\transaction_update_validation;
use Symfony\Component\HttpFoundation\Response;
use App\Payment\Contracts\PendingPayment;
use App\Http\Resources\TransferBreakDownResource;
use App\Enum\TransactionStatus;



class TransactionController extends Controller
{

    public function __construct(
        public TransactionRepository $transactionRepository,
        public CreateTransaction $createTransaction,
        protected PaymentGateway $paymentGateway
    )
    {
    }

    public function index(TransactionService $transaction_service, TransactionRequest $request)
    {

            
       [ $transactionList, $totalTransaction ] = $this->transactionRepository
       ->fetchTransaction($request->all(), UserDto::fromEloquentModel(auth()->user()));
       // $totalTransaction = $this->transactionRepository->calculateTotalAmount($request->all(), auth()->user());

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
        $userCommission = CommissionRepository::getCommissionValue($validated['amount_sent'], $user->userId);
        $userRate = RateRepository::fetchTodaysRate($user->userId);

        if(is_null($userCommission))throw new CommissionNotSetException('commission is not set for '. $validated['amount_sent']);
        if(is_null($userRate))throw new RateNotSetException('todays rate  is not set for.');

       // $pendingPayment = new PendingPayment($this->paymentGateway, $validated['payment_token']);

        // Process transaction
        $transactionCollection = TransactionCollection::processTransactionData(
            RateDto::fromEloquentModel(($userRate)),
            CommissionDto::fromEloquentModel($userCommission),
            $validated['amount_sent'],
            $validated['conversion_type']);

        $receiver = ReceiverDto::fromEloquentModel(
            Receiver::with('sender')
                ->find( $validated['receiver_id']));

        $transaction = $this->createTransaction->handle(
            $transactionCollection ,
            new PendingPayment($this->paymentGateway, $validated['payment_token']),
            $receiver,
            $user );

        return (new TransactionResource( $transaction))->response()->setStatusCode(Response::HTTP_OK);
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
       
        
        $validated = $request->validated();

        $user = UserDto::fromEloquentModel(auth()->user());

        $userRate = RateRepository::fetchTodaysRate($user->userId);

        $userSendAmount = $validated['conversion_type'] == 1? $validated['send_amount']
        :$validated['send_amount'] / $userRate->main_rate;

        $userCommission = CommissionRepository::getCommissionValue($userSendAmount, $user->userId);

        // Process transaction
        $transactionCollection = TransactionCollection::processTransactionData(
            RateDto::fromEloquentModel(($userRate)),
            CommissionDto::fromEloquentModel($userCommission),
            $validated['send_amount'],
            $validated['conversion_type']);

        return (new TransferBreakDownResource($transactionCollection))
        ->response()
        ->setStatusCode(Response::HTTP_OK);
    }
}
