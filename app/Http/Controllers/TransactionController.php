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
use Illuminate\Support\Facades\Mail;
use App\Mail\ReportTransaction;
use PDF; // Ensure you import the PDF facade
use App\Models\UserCurrency;
use App\Enum\UserRoleType;
use App\Services\Currency\UserCurrencyService;
use Illuminate\Validation\Rule;
use App\Enum\updateTransactionStatus;
use App\Services\Log\LoggingService;




class TransactionController extends Controller
{

    public function __construct(
        public TransactionRepository $transactionRepository,
        private CreateTransaction $createTransaction,
        private UserCurrencyService $userCurrencyService,
        private PaymentGateway $paymentGateway
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
            $validated['origin_currency_id'],
            $validated['destination_currency_id'],
            $validated['amount_sent'],
            $validated['conversion_type']);

        $receiver = ReceiverDto::fromEloquentModel(
            Receiver::with('sender')
                ->find( $validated['receiver_id']));

                $this->userCurrencyService->handleUserCurrency($validated, $user, $receiver);

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
            $validated['origin_currency_id'],
            $validated['destination_currency_id'],
            $validated['send_amount'],
            $validated['conversion_type']);

        return (new TransferBreakDownResource($transactionCollection))
        ->response()
        ->setStatusCode(Response::HTTP_OK);
    }

    public function downloadReceipt(Transaction $transaction)
    {
        $transactionDto = TransactionDto::fromEloquentModel($transaction);
        $transactionData = $transactionDto->toArray();
       //return $transactionData;
        $pdf = PDF::loadView('receipts.main', $transactionData);
        return $pdf->download('receipt.pdf');
    }

    public function reportTransaction(Request $request,Transaction $transaction){
        $request->validate([
            'description' => 'required|string',
           // 'transactionId' => 'required|integer',
            'image' => 'image|mimes:jpeg,png,jpg,gif,docx|max:2048',
        ]);

        $imagePath = $request->file('image')->store('reports', 'public');

        // Send email
        Mail::to('admin@example.com')->send(new ReportTransaction(
            $request->description, 
            $imagePath, 
            Transactiondto::fromEloquentModel($transaction)));

        return response()->json(['message' => 'Report submitted successfully'], 200);

    }

    public function updateTransactionStatus(Request $request, Transaction $transaction, LoggingService $loggingService)
    {
        // Validate the request
        $validated = $request->validate([
          'status' => ['required', 'string', Rule::in(array_map(fn($status) => $status->label(), TransactionStatus::cases()))],
        ]);

        // Update the user's status
        $newStatus = TransactionStatus::getStatusEnumInstance($validated['status']);
       
        $transaction->transaction_status = $newStatus;
        $transaction->save();

        $updatedTransaction  = TransactionDto::fromEloquentModel($transaction->fresh());

        $loggingService->logActivity($transaction, "Transaction status changed to {$newStatus->label()}");

        return (new TransactionResource($updatedTransaction))->response()->setStatusCode(Response::HTTP_OK);

       
    }
}
